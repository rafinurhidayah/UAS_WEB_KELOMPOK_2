<?php
declare(strict_types=1);

/**
 * Konstanta global aplikasi SIPERUSAK.
 *
 * Seluruh nilai ENUM di file ini WAJIB sama persis dengan
 * database/schema.sql agar tidak terjadi mismatch saat query.
 */

if (!defined('APP_NAME')) {
    define('APP_NAME', 'SIPERUSAK');
}

if (!defined('APP_TIMEZONE')) {
    define('APP_TIMEZONE', 'Asia/Jakarta');
}

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__, 2));
}

// ---------------------------------------------------------------------
// Base URL
// ---------------------------------------------------------------------
// Prioritas: environment APP_URL, lalu deteksi otomatis dari DOCUMENT_ROOT.
// Path proyek yang mengandung spasi (mis. "projek uas web") di-encode
// menjadi %20 agar aman dipakai pada header Location.
if (!defined('BASE_URL')) {
    $appUrl = getenv('APP_URL');
    if ($appUrl === false || $appUrl === '') {
        $appUrl = $_ENV['APP_URL'] ?? $_SERVER['APP_URL'] ?? '';
    }

    if (is_string($appUrl) && $appUrl !== '') {
        define('BASE_URL', rtrim($appUrl, '/'));
    } else {
        $scheme = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
            ? 'https'
            : 'http';
        $host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');

        $basePath = '';
        $documentRoot = isset($_SERVER['DOCUMENT_ROOT'])
            ? realpath((string) $_SERVER['DOCUMENT_ROOT'])
            : false;
        $appRoot = realpath(APP_ROOT);

        if ($documentRoot !== false && $appRoot !== false) {
            $normalizedDoc = str_replace('\\', '/', $documentRoot);
            $normalizedApp = str_replace('\\', '/', $appRoot);

            if (str_starts_with($normalizedApp, $normalizedDoc)) {
                $basePath = substr($normalizedApp, strlen($normalizedDoc));
            }
        }

        $basePath = rtrim(str_replace('\\', '/', $basePath), '/');
        if ($basePath !== '') {
            $segments = array_map(
                static fn (string $segment): string => rawurlencode($segment),
                explode('/', ltrim($basePath, '/'))
            );
            $basePath = '/' . implode('/', $segments);
        }

        define('BASE_URL', $scheme . '://' . $host . $basePath);
    }
}

// ---------------------------------------------------------------------
// Upload foto laporan
// ---------------------------------------------------------------------
if (!defined('UPLOAD_DIR')) {
    define('UPLOAD_DIR', 'uploads/reports/');
}

if (!defined('UPLOAD_PATH')) {
    define('UPLOAD_PATH', APP_ROOT . '/uploads/reports');
}

if (!defined('MAX_PHOTO_SIZE')) {
    define('MAX_PHOTO_SIZE', 5 * 1024 * 1024); // 5 MB
}

if (!defined('ALLOWED_IMAGE_MIME')) {
    define('ALLOWED_IMAGE_MIME', [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ]);
}

if (!defined('ALLOWED_IMAGE_EXTENSIONS')) {
    define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);
}

// ---------------------------------------------------------------------
// Sesi & keamanan
// ---------------------------------------------------------------------
if (!defined('SESSION_NAME')) {
    define('SESSION_NAME', 'SIPERUSAK_SESSION');
}

if (!defined('AUTH_SESSION_KEY')) {
    define('AUTH_SESSION_KEY', 'auth_user_id');
}

if (!defined('CSRF_SESSION_KEY')) {
    define('CSRF_SESSION_KEY', '_csrf_token');
}

if (!defined('FLASH_SESSION_KEY')) {
    define('FLASH_SESSION_KEY', '_flash');
}

// ---------------------------------------------------------------------
// Role (nilai kolom roles.nama_role)
// ---------------------------------------------------------------------
if (!defined('ROLE_MAHASISWA')) {
    define('ROLE_MAHASISWA', 'Mahasiswa');
}

if (!defined('ROLE_PETUGAS')) {
    define('ROLE_PETUGAS', 'Petugas');
}

if (!defined('ROLE_ADMIN')) {
    define('ROLE_ADMIN', 'Admin');
}

if (!defined('ROLE_LIST')) {
    define('ROLE_LIST', [ROLE_MAHASISWA, ROLE_PETUGAS, ROLE_ADMIN]);
}

if (!defined('ROLE_HOME')) {
    define('ROLE_HOME', [
        ROLE_MAHASISWA => '/pages/mahasiswa/dashboard.php',
        ROLE_PETUGAS   => '/pages/petugas/dashboard.php',
        ROLE_ADMIN     => '/pages/admin/dashboard.php',
    ]);
}

// ---------------------------------------------------------------------
// ENUM users.status_akun
// ---------------------------------------------------------------------
if (!defined('STATUS_AKUN_AKTIF')) {
    define('STATUS_AKUN_AKTIF', 'aktif');
}

if (!defined('STATUS_AKUN_NONAKTIF')) {
    define('STATUS_AKUN_NONAKTIF', 'nonaktif');
}

if (!defined('STATUS_AKUN_LIST')) {
    define('STATUS_AKUN_LIST', [STATUS_AKUN_AKTIF, STATUS_AKUN_NONAKTIF]);
}

// ---------------------------------------------------------------------
// ENUM facility_items.kondisi
// ---------------------------------------------------------------------
if (!defined('KONDISI_BAIK')) {
    define('KONDISI_BAIK', 'baik');
}

if (!defined('KONDISI_RINGAN')) {
    define('KONDISI_RINGAN', 'ringan');
}

if (!defined('KONDISI_BERAT')) {
    define('KONDISI_BERAT', 'berat');
}

if (!defined('KONDISI_LIST')) {
    define('KONDISI_LIST', [KONDISI_BAIK, KONDISI_RINGAN, KONDISI_BERAT]);
}

// ---------------------------------------------------------------------
// ENUM reports.prioritas
// ---------------------------------------------------------------------
if (!defined('PRIORITAS_RENDAH')) {
    define('PRIORITAS_RENDAH', 'rendah');
}

if (!defined('PRIORITAS_SEDANG')) {
    define('PRIORITAS_SEDANG', 'sedang');
}

if (!defined('PRIORITAS_TINGGI')) {
    define('PRIORITAS_TINGGI', 'tinggi');
}

if (!defined('PRIORITAS_LIST')) {
    define('PRIORITAS_LIST', [PRIORITAS_RENDAH, PRIORITAS_SEDANG, PRIORITAS_TINGGI]);
}

// ---------------------------------------------------------------------
// ENUM reports.status_laporan
// ---------------------------------------------------------------------
if (!defined('STATUS_LAPORAN_MENUNGGU')) {
    define('STATUS_LAPORAN_MENUNGGU', 'Menunggu');
}

if (!defined('STATUS_LAPORAN_DIVERIFIKASI')) {
    define('STATUS_LAPORAN_DIVERIFIKASI', 'Diverifikasi');
}

if (!defined('STATUS_LAPORAN_DIPROSES')) {
    define('STATUS_LAPORAN_DIPROSES', 'Diproses');
}

if (!defined('STATUS_LAPORAN_SELESAI')) {
    define('STATUS_LAPORAN_SELESAI', 'Selesai');
}

if (!defined('STATUS_LAPORAN_DITOLAK')) {
    define('STATUS_LAPORAN_DITOLAK', 'Ditolak');
}

if (!defined('STATUS_LAPORAN_LIST')) {
    define('STATUS_LAPORAN_LIST', [
        STATUS_LAPORAN_MENUNGGU,
        STATUS_LAPORAN_DIVERIFIKASI,
        STATUS_LAPORAN_DIPROSES,
        STATUS_LAPORAN_SELESAI,
        STATUS_LAPORAN_DITOLAK,
    ]);
}

// Alur status yang disarankan (referensi controller Fase 3).
if (!defined('STATUS_LAPORAN_FLOW')) {
    define('STATUS_LAPORAN_FLOW', [
        STATUS_LAPORAN_MENUNGGU     => [STATUS_LAPORAN_DIVERIFIKASI, STATUS_LAPORAN_DITOLAK],
        STATUS_LAPORAN_DIVERIFIKASI => [STATUS_LAPORAN_DIPROSES, STATUS_LAPORAN_DITOLAK],
        STATUS_LAPORAN_DIPROSES     => [STATUS_LAPORAN_SELESAI, STATUS_LAPORAN_DITOLAK],
        STATUS_LAPORAN_SELESAI      => [],
        STATUS_LAPORAN_DITOLAK      => [],
    ]);
}

// ---------------------------------------------------------------------
// Generator kode laporan: LPR-YYYYMMDD-0001
// ---------------------------------------------------------------------
if (!defined('REPORT_CODE_PREFIX')) {
    define('REPORT_CODE_PREFIX', 'LPR');
}

if (!defined('REPORT_CODE_SEQUENCE_LENGTH')) {
    define('REPORT_CODE_SEQUENCE_LENGTH', 4);
}

// Terapkan timezone agar tanggal kode laporan konsisten (WIB).
date_default_timezone_set(APP_TIMEZONE);
