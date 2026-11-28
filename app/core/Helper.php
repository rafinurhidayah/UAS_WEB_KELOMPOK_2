<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Constants.php';
require_once __DIR__ . '/../config/Database.php';

/**
 * Utilitas umum: escape output, generator kode laporan,
 * CSRF token, flash message, dan redirect.
 */
final class Helper
{
    /**
     * Escape output untuk mencegah XSS.
     */
    public static function e(mixed $value): string
    {
        return htmlspecialchars(
            (string) ($value ?? ''),
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }

    /**
     * Membuat kode laporan harian: LPR-YYYYMMDD-0001.
     *
     * Harus dipanggil dengan PDO yang sama ketika berada di dalam
     * transaction (ReportModel) agar urutan nomor konsisten.
     */
    public static function generateKodeLaporan(?PDO $pdo = null): string
    {
        $pdo ??= Database::connection();

        $prefix = REPORT_CODE_PREFIX . '-' . date('Ymd') . '-';

        $statement = $pdo->prepare(
            'SELECT kode_laporan
             FROM reports
             WHERE kode_laporan LIKE :prefix
             ORDER BY kode_laporan DESC
             LIMIT 1'
        );
        $statement->execute([':prefix' => $prefix . '%']);

        $lastCode = $statement->fetchColumn();
        $sequence = 1;

        if (is_string($lastCode) && $lastCode !== '') {
            $tail = substr($lastCode, strlen($prefix));
            if ($tail !== '' && ctype_digit($tail)) {
                $sequence = (int) $tail + 1;
            }
        }

        return $prefix . str_pad(
            (string) $sequence,
            REPORT_CODE_SEQUENCE_LENGTH,
            '0',
            STR_PAD_LEFT
        );
    }

    // -----------------------------------------------------------------
    // CSRF
    // -----------------------------------------------------------------

    public static function csrfToken(): string
    {
        self::ensureSession();

        if (
            !isset($_SESSION[CSRF_SESSION_KEY])
            || !is_string($_SESSION[CSRF_SESSION_KEY])
            || $_SESSION[CSRF_SESSION_KEY] === ''
        ) {
            $_SESSION[CSRF_SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[CSRF_SESSION_KEY];
    }

    public static function csrfField(): string
    {
        return '<input type="hidden" name="csrf_token" value="'
            . self::e(self::csrfToken())
            . '">';
    }

    public static function verifyCsrf(?string $token): bool
    {
        self::ensureSession();

        $sessionToken = $_SESSION[CSRF_SESSION_KEY] ?? null;

        return is_string($sessionToken)
            && is_string($token)
            && $token !== ''
            && hash_equals($sessionToken, $token);
    }

    /**
     * Validasi CSRF atau lempar exception.
     *
     * @throws RuntimeException
     */
    public static function requireCsrf(?string $token): void
    {
        if (!self::verifyCsrf($token)) {
            throw new RuntimeException('Token CSRF tidak valid atau sudah kedaluwarsa.');
        }
    }

    // -----------------------------------------------------------------
    // Flash message
    // -----------------------------------------------------------------

    public static function flash(string $key, string $message, string $type = 'info'): void
    {
        self::ensureSession();

        $_SESSION[FLASH_SESSION_KEY][$key] = [
            'message' => $message,
            'type'    => $type,
        ];
    }

    /**
     * Mengambil sekaligus menghapus satu flash message.
     *
     * @return array{message: string, type: string}|null
     */
    public static function getFlash(string $key): ?array
    {
        self::ensureSession();

        if (!isset($_SESSION[FLASH_SESSION_KEY][$key])) {
            return null;
        }

        $flash = $_SESSION[FLASH_SESSION_KEY][$key];
        unset($_SESSION[FLASH_SESSION_KEY][$key]);

        return is_array($flash) ? $flash : null;
    }

    public static function hasFlash(string $key): bool
    {
        self::ensureSession();

        return isset($_SESSION[FLASH_SESSION_KEY][$key]);
    }

    /**
     * Mengambil seluruh flash message lalu mengosongkan antreannya.
     *
     * @return array<string, array{message: string, type: string}>
     */
    public static function allFlash(): array
    {
        self::ensureSession();

        $flashes = $_SESSION[FLASH_SESSION_KEY] ?? [];
        unset($_SESSION[FLASH_SESSION_KEY]);

        return is_array($flashes) ? $flashes : [];
    }

    // -----------------------------------------------------------------
    // URL & redirect
    // -----------------------------------------------------------------

    public static function url(string $path = ''): string
    {
        return BASE_URL . ($path === '' ? '' : '/' . ltrim($path, '/'));
    }

    /**
     * Redirect ke path internal (relatif terhadap BASE_URL) atau URL absolut.
     */
    public static function redirect(string $path, int $statusCode = 302): void
    {
        $location = preg_match('~^https?://~i', $path) === 1
            ? $path
            : self::url($path);

        header('Location: ' . $location, true, $statusCode);
        exit;
    }

    private static function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        // Jangan memicu warning bila output sudah terkirim.
        if (headers_sent()) {
            return;
        }

        // Gunakan bootstrap sesi Auth (cookie HttpOnly/SameSite) bila tersedia.
        if (class_exists('Auth', false)) {
            Auth::start();
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
