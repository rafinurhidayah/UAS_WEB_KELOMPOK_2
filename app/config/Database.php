<?php
declare(strict_types=1);

/**
 * Koneksi database PDO (Singleton).
 *
 * Default mengikuti Laragon:
 *   host 127.0.0.1, user root, password kosong,
 *   database pelaporan_fasilitas.
 *
 * Nilai dapat dioverride lewat environment variable DB_HOST, DB_PORT,
 * DB_NAME, DB_USER, dan DB_PASS tanpa mengubah kode.
 */
final class Database
{
    private static ?Database $instance = null;

    private PDO $pdo;

    private function __construct()
    {
        $host = self::env('DB_HOST', '127.0.0.1');
        $port = self::env('DB_PORT', '3306');
        $name = self::env('DB_NAME', 'pelaporan_fasilitas');
        $user = self::env('DB_USER', 'root');
        $pass = self::env('DB_PASS', '');

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $host,
            $port,
            $name
        );

        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_STRINGIFY_FETCHES  => false,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException(
                'Koneksi database gagal: ' . $exception->getMessage(),
                (int) $exception->getCode(),
                $exception
            );
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function connection(): PDO
    {
        return self::getInstance()->pdo;
    }

    /**
     * Alias singkat untuk koneksi PDO.
     */
    public static function pdo(): PDO
    {
        return self::connection();
    }

    private static function env(string $key, string $default): string
    {
        $value = getenv($key);
        if ($value === false || $value === '') {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? null;
        }

        return ($value === null || $value === '') ? $default : (string) $value;
    }

    private function __clone()
    {
    }

    public function __wakeup(): void
    {
        throw new RuntimeException('Singleton Database tidak boleh di-unserialize.');
    }
}
