<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Constants.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/UserModel.php';

/**
 * Controller autentikasi.
 *
 * Aturan bisnis:
 * - Registrasi mandiri HANYA untuk Mahasiswa (id_role = 1).
 * - Petugas/Admin hanya dapat dibuat oleh Admin (belum ada UI di fase ini).
 * - Login tunggal tanpa pilihan role; role ditentukan dari database.
 */
final class AuthController
{
    private UserModel $users;

    public function __construct(?UserModel $users = null)
    {
        $this->users = $users ?? new UserModel();
    }

    /**
     * Registrasi akun Mahasiswa.
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helper::redirect('/pages/auth/register.php');
        }

        if (!Helper::verifyCsrf($_POST['csrf_token'] ?? null)) {
            Helper::flash('error', 'Sesi formulir kedaluwarsa. Muat ulang halaman lalu coba lagi.');
            Helper::redirect('/pages/auth/register.php');
        }

        // Terima nama field baru maupun lama (kompatibilitas form Fase 1).
        $nama = trim((string) ($_POST['nama'] ?? $_POST['fullName'] ?? ''));
        $nimNip = trim((string) ($_POST['nim_nip'] ?? $_POST['nim'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $noHp = trim((string) ($_POST['no_hp'] ?? $_POST['phone'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $confirmPassword = (string) ($_POST['confirmPassword'] ?? '');

        $errors = [];

        if ($nama === '') {
            $errors[] = 'Nama lengkap wajib diisi.';
        } elseif ((function_exists('mb_strlen') ? mb_strlen($nama) : strlen($nama)) > 100) {
            $errors[] = 'Nama lengkap maksimal 100 karakter.';
        }

        if ($nimNip === '') {
            $errors[] = 'NIM wajib diisi.';
        } elseif (preg_match('/^[0-9]{8,14}$/', $nimNip) !== 1) {
            $errors[] = 'NIM harus berupa 8–14 digit angka.';
        }

        if ($email === '') {
            $errors[] = 'Email wajib diisi.';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Format email tidak valid.';
        }

        if ($noHp === '') {
            $errors[] = 'Nomor WhatsApp/telepon wajib diisi.';
        } elseif (strlen($noHp) > 20) {
            $errors[] = 'Nomor WhatsApp/telepon maksimal 20 karakter.';
        }

        if ($password === '') {
            $errors[] = 'Kata sandi wajib diisi.';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Kata sandi minimal 8 karakter.';
        }

        if ($confirmPassword === '') {
            $errors[] = 'Konfirmasi kata sandi wajib diisi.';
        } elseif ($password !== $confirmPassword) {
            $errors[] = 'Konfirmasi kata sandi tidak sama.';
        }

        if (!isset($_POST['agreement'])) {
            $errors[] = 'Anda harus menyatakan bahwa data yang diisi benar.';
        }

        if ($errors === []) {
            if ($this->users->emailExists($email)) {
                $errors[] = 'Email sudah terdaftar. Gunakan email lain atau masuk ke akun Anda.';
            }

            if ($this->users->nimNipExists($nimNip)) {
                $errors[] = 'NIM sudah terdaftar. Gunakan NIM lain atau masuk ke akun Anda.';
            }
        }

        if ($errors !== []) {
            Helper::flash('error', implode(' ', $errors));
            Helper::redirect('/pages/auth/register.php');
        }

        try {
            // id_role dan status_akun di-hardcode; input dari request diabaikan.
            $this->users->create([
                'id_role'     => 1,
                'nama'        => $nama,
                'email'       => $email,
                'password'    => $password,
                'nim_nip'     => $nimNip,
                'no_hp'       => $noHp,
                'status_akun' => STATUS_AKUN_AKTIF,
            ]);
        } catch (PDOException $exception) {
            error_log('[REGISTER] ' . $exception->getMessage());
            Helper::flash('error', 'Registrasi gagal karena gangguan sistem. Silakan coba lagi.');
            Helper::redirect('/pages/auth/register.php');
        } catch (InvalidArgumentException | RuntimeException $exception) {
            Helper::flash('error', $exception->getMessage());
            Helper::redirect('/pages/auth/register.php');
        } catch (Throwable $exception) {
            error_log('[REGISTER] ' . $exception->getMessage());
            Helper::flash('error', 'Registrasi gagal. Silakan coba lagi.');
            Helper::redirect('/pages/auth/register.php');
        }

        Helper::flash(
            'success',
            'Registrasi berhasil. Silakan masuk dengan email dan kata sandi Anda.'
        );
        Helper::redirect('/pages/auth/login.php');
    }

    /**
     * Login tunggal untuk semua role.
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helper::redirect('/pages/auth/login.php');
        }

        if (!Helper::verifyCsrf($_POST['csrf_token'] ?? null)) {
            Helper::flash('error', 'Sesi formulir kedaluwarsa. Muat ulang halaman lalu coba lagi.');
            Helper::redirect('/pages/auth/login.php');
        }

        $email = strtolower(trim((string) ($_POST['email'] ?? $_POST['account'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            Helper::flash('error', 'Email dan kata sandi wajib diisi.');
            Helper::redirect('/pages/auth/login.php');
        }

        // Auth::attempt() memakai password_verify() dan menolak akun nonaktif.
        if (!Auth::attempt($email, $password)) {
            Helper::flash('error', 'Email atau kata sandi salah, atau akun Anda tidak aktif.');
            Helper::redirect('/pages/auth/login.php');
        }

        $user = Auth::user();

        if ($user === null) {
            Helper::flash('error', 'Sesi login gagal dibuat. Silakan coba lagi.');
            Helper::redirect('/pages/auth/login.php');
        }

        $role = (string) $user['nama_role'];

        if ($role === ROLE_MAHASISWA) {
            Helper::redirect('/pages/mahasiswa/dashboard.php');
        }

        $dashboard = ROLE_HOME[$role] ?? null;

        if ($dashboard !== null && file_exists(APP_ROOT . $dashboard)) {
            Helper::redirect($dashboard);
        }

        // Dashboard Petugas/Admin belum dibuat pada fase ini.
        Helper::flash(
            'info',
            'Login berhasil sebagai ' . $role
            . '. Dashboard ' . $role
            . ' belum tersedia, sementara Anda diarahkan ke beranda.'
        );
        Helper::redirect('/index.php');
    }

    /**
     * Logout total: hapus data sesi, cookie sesi, lalu hancurkan sesi.
     */
    public function logout(): void
    {
        Auth::logout();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        Helper::redirect('/pages/auth/login.php');
    }
}
