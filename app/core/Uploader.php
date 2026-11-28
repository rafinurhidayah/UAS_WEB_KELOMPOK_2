<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Constants.php';

/**
 * Validasi dan penyimpanan foto bukti laporan.
 *
 * Aturan:
 * - MIME asli dideteksi dengan finfo_file (bukan dari ekstensi/nama file).
 * - Hanya JPG, PNG, dan WebP.
 * - Ukuran maksimal 5 MB.
 * - Nama file dibuat unik dan aman, disimpan di uploads/reports/.
 */
final class Uploader
{
    /**
     * Menyimpan satu file gambar dan mengembalikan path relatif
     * (contoh: uploads/reports/rpt_20261128_101500_ab12cd34.jpg).
     *
     * @param array<string, mixed> $file Satu entri dari $_FILES.
     * @throws InvalidArgumentException|RuntimeException
     */
    public static function store(array $file): string
    {
        self::assertValidUpload($file);

        $tmpPath = (string) $file['tmp_name'];
        $mime = self::detectMime($tmpPath);

        $allowed = ALLOWED_IMAGE_MIME;
        if (!isset($allowed[$mime])) {
            throw new InvalidArgumentException(
                'Format foto tidak didukung. Gunakan JPG, PNG, atau WebP.'
            );
        }

        // Pastikan file benar-benar gambar yang dapat dibaca.
        if (@getimagesize($tmpPath) === false) {
            throw new InvalidArgumentException('File yang diunggah bukan gambar yang valid.');
        }

        $extension = $allowed[$mime];
        $directory = UPLOAD_PATH;

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('Folder uploads/reports tidak dapat dibuat.');
        }

        $filename = 'rpt_' . date('Ymd_His') . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $target = $directory . '/' . $filename;

        if (!move_uploaded_file($tmpPath, $target)) {
            throw new RuntimeException('Foto gagal disimpan. Silakan coba lagi.');
        }

        @chmod($target, 0644);

        return UPLOAD_DIR . $filename;
    }

    /**
     * Menyimpan banyak foto sekaligus.
     *
     * Mendukung $_FILES['photos'] dengan name berupa array maupun
     * daftar file tunggal.
     *
     * @param array<string, mixed> $files
     * @return array<int, string> Daftar path relatif.
     */
    public static function storeMany(array $files): array
    {
        $paths = [];

        foreach (self::normalizeFiles($files) as $file) {
            $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);

            // Lewati input kosong; error lain akan divalidasi di store().
            if ($error === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $paths[] = self::store($file);
        }

        return $paths;
    }

    /**
     * Menghapus foto berdasarkan path relatif yang tersimpan di database.
     */
    public static function delete(string $relativePath): bool
    {
        $relativePath = str_replace('\\', '/', trim($relativePath));

        if (!str_starts_with($relativePath, UPLOAD_DIR)) {
            return false;
        }

        $filename = basename($relativePath);
        if ($filename === '' || $filename === '.' || $filename === '..') {
            return false;
        }

        $basePath = realpath(UPLOAD_PATH);
        $fullPath = realpath(UPLOAD_PATH . '/' . $filename);

        if ($basePath === false || $fullPath === false) {
            return false;
        }

        $normalizedBase = rtrim(str_replace('\\', '/', $basePath), '/') . '/';
        $normalizedFile = str_replace('\\', '/', $fullPath);

        if (!str_starts_with($normalizedFile, $normalizedBase)) {
            return false;
        }

        return @unlink($fullPath);
    }

    /**
     * @param array<string, mixed> $file
     */
    private static function assertValidUpload(array $file): void
    {
        if (!isset($file['tmp_name'], $file['error'])) {
            throw new InvalidArgumentException('Data file upload tidak lengkap.');
        }

        $error = (int) $file['error'];

        if ($error !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException(self::uploadErrorMessage($error));
        }

        $tmpPath = (string) $file['tmp_name'];

        if ($tmpPath === '' || !is_file($tmpPath)) {
            throw new InvalidArgumentException('File sementara upload tidak ditemukan.');
        }

        if (PHP_SAPI !== 'cli' && !is_uploaded_file($tmpPath)) {
            throw new InvalidArgumentException('File upload tidak valid.');
        }

        $clientSize = (int) ($file['size'] ?? 0);
        if ($clientSize > MAX_PHOTO_SIZE) {
            throw new InvalidArgumentException('Ukuran foto maksimal 5 MB.');
        }

        $actualSize = (int) @filesize($tmpPath);
        if ($actualSize <= 0) {
            throw new InvalidArgumentException('Foto kosong atau rusak.');
        }

        if ($actualSize > MAX_PHOTO_SIZE) {
            throw new InvalidArgumentException('Ukuran foto maksimal 5 MB.');
        }
    }

    private static function detectMime(string $path): string
    {
        if (!class_exists('finfo')) {
            throw new RuntimeException('Ekstensi PHP fileinfo tidak tersedia.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($path);

        if (!is_string($mime) || $mime === '') {
            throw new InvalidArgumentException('Tipe file tidak dapat dideteksi.');
        }

        return strtolower($mime);
    }

    /**
     * @param array<string, mixed> $files
     * @return array<int, array<string, mixed>>
     */
    private static function normalizeFiles(array $files): array
    {
        if (!isset($files['name'])) {
            return [];
        }

        if (!is_array($files['name'])) {
            return [$files];
        }

        $normalized = [];
        $count = count($files['name']);

        for ($index = 0; $index < $count; $index++) {
            $normalized[] = [
                'name'     => $files['name'][$index] ?? '',
                'type'     => $files['type'][$index] ?? '',
                'tmp_name' => $files['tmp_name'][$index] ?? '',
                'error'    => $files['error'][$index] ?? UPLOAD_ERR_NO_FILE,
                'size'     => $files['size'][$index] ?? 0,
            ];
        }

        return $normalized;
    }

    private static function uploadErrorMessage(int $error): string
    {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Ukuran foto melebihi batas server.',
            UPLOAD_ERR_PARTIAL   => 'Foto hanya terunggah sebagian. Silakan ulangi.',
            UPLOAD_ERR_NO_FILE   => 'Tidak ada foto yang diunggah.',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder sementara upload tidak tersedia.',
            UPLOAD_ERR_CANT_WRITE => 'Server gagal menulis file foto.',
            UPLOAD_ERR_EXTENSION  => 'Upload dihentikan oleh ekstensi PHP.',
            default               => 'Terjadi kesalahan saat mengunggah foto.',
        };
    }
}
