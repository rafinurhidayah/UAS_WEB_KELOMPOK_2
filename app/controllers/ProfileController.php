<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Constants.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../core/Helper.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/UserModel.php';

/**
 * Handler perubahan profil Mahasiswa.
 *
 * Menangani dua aksi POST:
 * - update_profile  : nama, email, no_hp (NIM/NIP read-only)
 * - change_password : verifikasi kata sandi lama lalu simpan hash baru
 */
final class ProfileController
{
    private UserModel $users;

    public function __construct(?UserModel $users = null)
    {
        $this->users = $users ?? new UserModel();
    }

    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helper::redirect('/pages/mahasiswa/profil.php');
        }

        $user = Auth::guard([ROLE_MAHASISWA]);

        if (!Helper::verifyCsrf($_POST['csrf_token'] ?? null)) {
            Helper::flash('error', 'Sesi formulir kedaluwarsa. Muat ulang halaman lalu coba lagi.');
            Helper::redirect('/pages/mahasiswa/profil.php');
        }

        $action = (string) ($_POST['action'] ?? '');

        try {
            match ($action) {
                'update_profile'  => $this->updateProfile($user),
                'change_password' => $this->changePassword($user),
                default           => throw new InvalidArgumentException('Aksi profil tidak dikenal.'),
            };
        } catch (PDOException $exception) {
            error_log('[PROFILE] ' . $exception->getMessage());
            Helper::flash('error', 'Perubahan gagal karena gangguan sistem. Silakan coba lagi.');
        } catch (InvalidArgumentException | RuntimeException $exception) {
            Helper::flash('error', $exception->getMessage());
        } catch (Throwable $exception) {
            error_log('[PROFILE] ' . $exception->getMessage());
            Helper::flash('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }

        Helper::redirect('/pages/mahasiswa/profil.php');
    }

    /**
     * @param array<string, mixed> $user
     */
    private function updateProfile(array $user): void
    {
        $idUser = (int) ($user['id_user'] ?? 0);
        $nama = trim((string) ($_POST['nama'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $noHp = trim((string) ($_POST['no_hp'] ?? ''));

        if ($nama === '') {
            throw new InvalidArgumentException('Nama lengkap wajib diisi.');
        }

        $namaLength = function_exists('mb_strlen') ? mb_strlen($nama) : strlen($nama);
        if ($namaLength > 100) {
            throw new InvalidArgumentException('Nama lengkap maksimal 100 karakter.');
        }

        if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Format email tidak valid.');
        }

        if (
            $noHp !== ''
            && (strlen($noHp) > 20 || preg_match('/^[0-9+\-\s()]+$/', $noHp) !== 1)
        ) {
            throw new InvalidArgumentException('Nomor telepon tidak valid (maksimal 20 karakter).');
        }

        $this->users->updateProfile($idUser, [
            'nama'  => $nama,
            'email' => $email,
            'no_hp' => $noHp,
        ]);

        // Sinkronkan sesi agar layout/topbar langsung memakai data terbaru.
        $_SESSION['nama'] = $nama;
        $_SESSION['email'] = $email;

        Helper::flash('success', 'Profil berhasil diperbarui.');
    }

    /**
     * @param array<string, mixed> $user
     */
    private function changePassword(array $user): void
    {
        $idUser = (int) ($user['id_user'] ?? 0);
        $oldPassword = (string) ($_POST['old_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        if ($oldPassword === '' || $newPassword === '' || $confirmPassword === '') {
            throw new InvalidArgumentException('Semua kolom kata sandi wajib diisi.');
        }

        if (strlen($newPassword) < 8) {
            throw new InvalidArgumentException('Kata sandi baru minimal 8 karakter.');
        }

        if ($newPassword !== $confirmPassword) {
            throw new InvalidArgumentException('Konfirmasi kata sandi baru tidak sama.');
        }

        $currentHash = $this->users->getPasswordHash($idUser);

        if ($currentHash === null || !password_verify($oldPassword, $currentHash)) {
            throw new InvalidArgumentException('Kata sandi lama tidak sesuai.');
        }

        $this->users->updatePassword(
            $idUser,
            password_hash($newPassword, PASSWORD_DEFAULT)
        );

        // Rotasi id sesi setelah kredensial berubah.
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        Helper::flash('success', 'Kata sandi berhasil diperbarui.');
    }
}
