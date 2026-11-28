<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Constants.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Helper.php';

/**
 * Manajemen sesi dan autentikasi berbasis role (RBAC).
 *
 * Peran yang dipakai adalah nilai roles.nama_role:
 * Mahasiswa, Petugas, Admin.
 */
final class Auth
{
    private static ?array $user = null;

    private static bool $loaded = false;

    /**
     * Memulai sesi dengan konfigurasi cookie yang aman.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        if (session_status() === PHP_SESSION_DISABLED) {
            throw new RuntimeException('Sesi PHP dinonaktifkan pada server ini.');
        }

        if (headers_sent()) {
            // Jangan memaksa start bila output sudah terkirim.
            return;
        }

        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.cookie_httponly', '1');

        $secure = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off');

        session_name(SESSION_NAME);
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();
    }

    /**
     * Verifikasi kredensial lalu membuat sesi login.
     */
    public static function attempt(string $email, string $password): bool
    {
        self::start();

        $email = trim($email);
        if ($email === '' || $password === '') {
            return false;
        }

        $statement = Database::connection()->prepare(
            'SELECT u.id_user, u.id_role, u.nama, u.email, u.password,
                    u.nim_nip, u.no_hp, u.status_akun, u.created_at,
                    r.nama_role
             FROM users u
             INNER JOIN roles r ON r.id_role = u.id_role
             WHERE u.email = :email
             LIMIT 1'
        );
        $statement->execute([':email' => $email]);

        $user = $statement->fetch();
        if (!is_array($user)) {
            return false;
        }

        if (($user['status_akun'] ?? '') !== STATUS_AKUN_AKTIF) {
            return false;
        }

        if (!password_verify($password, (string) $user['password'])) {
            return false;
        }

        // Upgrade hash lama secara transparan.
        if (password_needs_rehash((string) $user['password'], PASSWORD_DEFAULT)) {
            $rehash = Database::connection()->prepare(
                'UPDATE users SET password = :password WHERE id_user = :id'
            );
            $rehash->execute([
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':id'       => (int) $user['id_user'],
            ]);
        }

        unset($user['password']);
        self::login($user);

        return true;
    }

    /**
     * Menyimpan user ke sesi (setelah kredensial valid).
     */
    public static function login(array $user): void
    {
        self::start();

        unset($user['password']);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        $_SESSION[AUTH_SESSION_KEY] = (int) $user['id_user'];
        $_SESSION['_auth_login_at'] = time();

        // Data sesi ringkas yang diminta modul autentikasi.
        $_SESSION['id_user'] = (int) $user['id_user'];
        $_SESSION['id_role'] = (int) ($user['id_role'] ?? 0);
        $_SESSION['nama_role'] = (string) ($user['nama_role'] ?? '');
        $_SESSION['nama'] = (string) ($user['nama'] ?? '');
        $_SESSION['email'] = (string) ($user['email'] ?? '');
        $_SESSION['nim_nip'] = (string) ($user['nim_nip'] ?? '');

        // Rotasi token CSRF setelah login.
        unset($_SESSION[CSRF_SESSION_KEY]);

        self::$user = $user;
        self::$loaded = true;
    }

    public static function logout(): void
    {
        self::start();

        unset(
            $_SESSION[AUTH_SESSION_KEY],
            $_SESSION['_auth_login_at'],
            $_SESSION['id_user'],
            $_SESSION['id_role'],
            $_SESSION['nama_role'],
            $_SESSION['nama'],
            $_SESSION['email'],
            $_SESSION['nim_nip']
        );

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        self::$user = null;
        self::$loaded = false;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    /**
     * Data user aktif (selalu dicek ulang ke database).
     *
     * @return array<string, mixed>|null
     */
    public static function user(): ?array
    {
        self::start();

        if (self::$loaded) {
            return self::$user;
        }

        self::$loaded = true;

        $id = $_SESSION[AUTH_SESSION_KEY] ?? null;
        if (!is_int($id) && !(is_string($id) && ctype_digit($id))) {
            self::$user = null;
            return null;
        }

        $statement = Database::connection()->prepare(
            'SELECT u.id_user, u.id_role, u.nama, u.email, u.nim_nip,
                    u.no_hp, u.status_akun, u.created_at, r.nama_role
             FROM users u
             INNER JOIN roles r ON r.id_role = u.id_role
             WHERE u.id_user = :id
             LIMIT 1'
        );
        $statement->execute([':id' => (int) $id]);

        $user = $statement->fetch();

        if (!is_array($user) || ($user['status_akun'] ?? '') !== STATUS_AKUN_AKTIF) {
            unset(
                $_SESSION[AUTH_SESSION_KEY],
                $_SESSION['_auth_login_at'],
                $_SESSION['id_user'],
                $_SESSION['id_role'],
                $_SESSION['nama_role'],
                $_SESSION['nama'],
                $_SESSION['email'],
                $_SESSION['nim_nip']
            );
            self::$user = null;
            return null;
        }

        self::$user = $user;

        return self::$user;
    }

    public static function id(): ?int
    {
        $user = self::user();

        return $user === null ? null : (int) $user['id_user'];
    }

    public static function role(): ?string
    {
        $user = self::user();

        return $user === null ? null : (string) $user['nama_role'];
    }

    /**
     * Cek apakah user aktif memiliki salah satu role yang diberikan.
     */
    public static function hasRole(string ...$roles): bool
    {
        $current = self::role();

        return $current !== null && in_array($current, $roles, true);
    }

    /**
     * Menghasilkan tag <script> berisi identitas user aktif untuk layout.js.
     *
     * Cetak hasilnya SEBELUM <script src=".../layout.js"> agar
     * window.CURRENT_USER sudah tersedia saat topbar/sidebar dirender.
     *
     * Aman dipanggil saat belum login (mengembalikan string kosong) dan
     * tidak pernah melakukan redirect.
     */
    public static function userScript(): string
    {
        $user = self::user();

        if ($user === null) {
            return '';
        }

        $payload = [
            'id_user'   => (int) ($user['id_user'] ?? 0),
            'nama'      => (string) ($user['nama'] ?? ''),
            'nim_nip'   => (string) ($user['nim_nip'] ?? ''),
            'email'     => (string) ($user['email'] ?? ''),
            'nama_role' => (string) ($user['nama_role'] ?? ''),
        ];

        $json = json_encode(
            $payload,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
        );

        if ($json === false) {
            return '';
        }

        return '<script>window.CURRENT_USER = ' . $json . ';</script>';
    }

    /**
     * Middleware RBAC untuk halaman.
     *
     * - Belum login  : redirect ke halaman login.
     * - Role ditolak : redirect ke dashboard role-nya (atau beranda).
     *
     * @param array<int, string> $allowedRoles Role yang diizinkan; kosong = semua user login.
     * @return array<string, mixed> User aktif.
     */
    public static function guard(array $allowedRoles = []): array
    {
        $user = self::user();

        if ($user === null) {
            Helper::flash('error', 'Silakan masuk terlebih dahulu untuk mengakses halaman tersebut.');
            Helper::redirect('/pages/auth/login.php');
        }

        if ($allowedRoles !== [] && !in_array((string) $user['nama_role'], $allowedRoles, true)) {
            Helper::flash('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');

            $home = ROLE_HOME[(string) $user['nama_role']] ?? '/index.php';

            // Dashboard Petugas/Admin belum tersedia pada fase ini. Jangan
            // redirect ke halaman yang belum ada (menghindari 404).
            if ($home !== '/index.php' && !is_file(APP_ROOT . $home)) {
                $home = '/index.php';
            }

            Helper::redirect($home);
        }

        return $user;
    }
}
