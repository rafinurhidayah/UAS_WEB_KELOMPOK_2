<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Constants.php';
require_once __DIR__ . '/../config/Database.php';

/**
 * Model master data: categories, facility_types, dan locations.
 */
final class MasterDataModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::connection();
    }

    // =================================================================
    // categories
    // =================================================================

    /**
     * @return array<int, array<string, mixed>>
     */
    public function allCategories(bool $onlyActive = false): array
    {
        $sql = 'SELECT id_kategori, nama_kategori, deskripsi, status_aktif
                FROM categories';

        if ($onlyActive) {
            $sql .= ' WHERE status_aktif = 1';
        }

        $sql .= ' ORDER BY nama_kategori ASC';

        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findCategory(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id_kategori, nama_kategori, deskripsi, status_aktif
             FROM categories WHERE id_kategori = :id LIMIT 1'
        );
        $statement->execute([':id' => $id]);

        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createCategory(array $data): int
    {
        $nama = trim((string) ($data['nama_kategori'] ?? ''));
        $deskripsi = trim((string) ($data['deskripsi'] ?? ''));
        $status = $this->normalizeActive($data['status_aktif'] ?? 1);

        $this->assertLength($nama, 1, 60, 'Nama kategori');
        $this->assertLength($deskripsi, 0, 255, 'Deskripsi kategori');

        $statement = $this->pdo->prepare(
            'INSERT INTO categories (nama_kategori, deskripsi, status_aktif)
             VALUES (:nama, :deskripsi, :status)'
        );

        try {
            $statement->execute([
                ':nama'      => $nama,
                ':deskripsi' => $deskripsi === '' ? null : $deskripsi,
                ':status'    => $status,
            ]);
        } catch (PDOException $exception) {
            throw $this->translateDuplicate($exception);
        }

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateCategory(int $id, array $data): bool
    {
        $sets = [];
        $params = [':id' => $id];

        if (array_key_exists('nama_kategori', $data)) {
            $nama = trim((string) $data['nama_kategori']);
            $this->assertLength($nama, 1, 60, 'Nama kategori');
            $sets[] = 'nama_kategori = :nama';
            $params[':nama'] = $nama;
        }

        if (array_key_exists('deskripsi', $data)) {
            $deskripsi = trim((string) $data['deskripsi']);
            $this->assertLength($deskripsi, 0, 255, 'Deskripsi kategori');
            $sets[] = 'deskripsi = :deskripsi';
            $params[':deskripsi'] = $deskripsi === '' ? null : $deskripsi;
        }

        if (array_key_exists('status_aktif', $data)) {
            $sets[] = 'status_aktif = :status';
            $params[':status'] = $this->normalizeActive($data['status_aktif']);
        }

        if ($sets === []) {
            return false;
        }

        $statement = $this->pdo->prepare(
            'UPDATE categories SET ' . implode(', ', $sets) . ' WHERE id_kategori = :id'
        );

        try {
            $statement->execute($params);
        } catch (PDOException $exception) {
            throw $this->translateDuplicate($exception);
        }

        return true;
    }

    public function setCategoryStatus(int $id, bool $active): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE categories SET status_aktif = :status WHERE id_kategori = :id'
        );

        return $statement->execute([
            ':status' => $active ? 1 : 0,
            ':id'     => $id,
        ]);
    }

    public function deleteCategory(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM categories WHERE id_kategori = :id');

        try {
            $statement->execute([':id' => $id]);
        } catch (PDOException $exception) {
            throw $this->translateDeleteReference($exception, 'kategori');
        }

        return $statement->rowCount() > 0;
    }

    // =================================================================
    // facility_types
    // =================================================================

    /**
     * @return array<int, array<string, mixed>>
     */
    public function allFacilityTypes(?int $idKategori = null, bool $onlyActive = false): array
    {
        $conditions = [];
        $params = [];

        if ($idKategori !== null) {
            $conditions[] = 't.id_kategori = :id_kategori';
            $params[':id_kategori'] = $idKategori;
        }

        if ($onlyActive) {
            $conditions[] = 't.status_aktif = 1';
        }

        $sql = 'SELECT t.id_jenis, t.id_kategori, t.nama_fasilitas, t.deskripsi,
                       t.icon, t.status_aktif, c.nama_kategori
                FROM facility_types t
                INNER JOIN categories c ON c.id_kategori = t.id_kategori';

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY c.nama_kategori ASC, t.nama_fasilitas ASC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findFacilityType(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT t.id_jenis, t.id_kategori, t.nama_fasilitas, t.deskripsi,
                    t.icon, t.status_aktif, c.nama_kategori
             FROM facility_types t
             INNER JOIN categories c ON c.id_kategori = t.id_kategori
             WHERE t.id_jenis = :id
             LIMIT 1'
        );
        $statement->execute([':id' => $id]);

        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createFacilityType(array $data): int
    {
        $idKategori = (int) ($data['id_kategori'] ?? 0);
        $nama = trim((string) ($data['nama_fasilitas'] ?? ''));
        $deskripsi = trim((string) ($data['deskripsi'] ?? ''));
        $icon = $this->normalizeIcon((string) ($data['icon'] ?? 'build'));
        $status = $this->normalizeActive($data['status_aktif'] ?? 1);

        if ($idKategori <= 0) {
            throw new InvalidArgumentException('Kategori wajib dipilih.');
        }

        $this->assertLength($nama, 1, 80, 'Nama fasilitas');
        $this->assertLength($deskripsi, 0, 255, 'Deskripsi fasilitas');

        $statement = $this->pdo->prepare(
            'INSERT INTO facility_types (id_kategori, nama_fasilitas, deskripsi, icon, status_aktif)
             VALUES (:id_kategori, :nama, :deskripsi, :icon, :status)'
        );

        try {
            $statement->execute([
                ':id_kategori' => $idKategori,
                ':nama'        => $nama,
                ':deskripsi'   => $deskripsi === '' ? null : $deskripsi,
                ':icon'        => $icon,
                ':status'      => $status,
            ]);
        } catch (PDOException $exception) {
            throw $this->translateDuplicate($exception);
        }

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateFacilityType(int $id, array $data): bool
    {
        $sets = [];
        $params = [':id' => $id];

        if (array_key_exists('id_kategori', $data)) {
            $idKategori = (int) $data['id_kategori'];
            if ($idKategori <= 0) {
                throw new InvalidArgumentException('Kategori wajib dipilih.');
            }
            $sets[] = 'id_kategori = :id_kategori';
            $params[':id_kategori'] = $idKategori;
        }

        if (array_key_exists('nama_fasilitas', $data)) {
            $nama = trim((string) $data['nama_fasilitas']);
            $this->assertLength($nama, 1, 80, 'Nama fasilitas');
            $sets[] = 'nama_fasilitas = :nama';
            $params[':nama'] = $nama;
        }

        if (array_key_exists('deskripsi', $data)) {
            $deskripsi = trim((string) $data['deskripsi']);
            $this->assertLength($deskripsi, 0, 255, 'Deskripsi fasilitas');
            $sets[] = 'deskripsi = :deskripsi';
            $params[':deskripsi'] = $deskripsi === '' ? null : $deskripsi;
        }

        if (array_key_exists('icon', $data)) {
            $sets[] = 'icon = :icon';
            $params[':icon'] = $this->normalizeIcon((string) $data['icon']);
        }

        if (array_key_exists('status_aktif', $data)) {
            $sets[] = 'status_aktif = :status';
            $params[':status'] = $this->normalizeActive($data['status_aktif']);
        }

        if ($sets === []) {
            return false;
        }

        $statement = $this->pdo->prepare(
            'UPDATE facility_types SET ' . implode(', ', $sets) . ' WHERE id_jenis = :id'
        );

        try {
            $statement->execute($params);
        } catch (PDOException $exception) {
            throw $this->translateDuplicate($exception);
        }

        return true;
    }

    public function setFacilityTypeStatus(int $id, bool $active): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE facility_types SET status_aktif = :status WHERE id_jenis = :id'
        );

        return $statement->execute([
            ':status' => $active ? 1 : 0,
            ':id'     => $id,
        ]);
    }

    public function deleteFacilityType(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM facility_types WHERE id_jenis = :id');

        try {
            $statement->execute([':id' => $id]);
        } catch (PDOException $exception) {
            throw $this->translateDeleteReference($exception, 'jenis fasilitas');
        }

        return $statement->rowCount() > 0;
    }

    // =================================================================
    // locations
    // =================================================================

    /**
     * @return array<int, array<string, mixed>>
     */
    public function allLocations(bool $onlyActive = false, ?string $search = null): array
    {
        $conditions = [];
        $params = [];

        if ($onlyActive) {
            $conditions[] = 'status_aktif = 1';
        }

        if ($search !== null && trim($search) !== '') {
            $searchLike = '%' . trim($search) . '%';
            $conditions[] = '(gedung LIKE :search_gedung OR lantai LIKE :search_lantai OR nama_ruangan LIKE :search_ruangan OR keterangan LIKE :search_keterangan)';
            $params[':search_gedung'] = $searchLike;
            $params[':search_lantai'] = $searchLike;
            $params[':search_ruangan'] = $searchLike;
            $params[':search_keterangan'] = $searchLike;
        }

        $sql = 'SELECT id_lokasi, gedung, lantai, nama_ruangan, keterangan, status_aktif
                FROM locations';

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY gedung ASC, lantai ASC, nama_ruangan ASC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findLocation(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id_lokasi, gedung, lantai, nama_ruangan, keterangan, status_aktif
             FROM locations WHERE id_lokasi = :id LIMIT 1'
        );
        $statement->execute([':id' => $id]);

        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createLocation(array $data): int
    {
        $gedung = trim((string) ($data['gedung'] ?? ''));
        $lantai = trim((string) ($data['lantai'] ?? ''));
        $ruangan = trim((string) ($data['nama_ruangan'] ?? ''));
        $keterangan = trim((string) ($data['keterangan'] ?? ''));
        $status = $this->normalizeActive($data['status_aktif'] ?? 1);

        $this->assertLength($gedung, 1, 80, 'Gedung');
        $this->assertLength($lantai, 1, 20, 'Lantai');
        $this->assertLength($ruangan, 1, 80, 'Nama ruangan');
        $this->assertLength($keterangan, 0, 255, 'Keterangan lokasi');

        $statement = $this->pdo->prepare(
            'INSERT INTO locations (gedung, lantai, nama_ruangan, keterangan, status_aktif)
             VALUES (:gedung, :lantai, :ruangan, :keterangan, :status)'
        );

        try {
            $statement->execute([
                ':gedung'     => $gedung,
                ':lantai'     => $lantai,
                ':ruangan'    => $ruangan,
                ':keterangan' => $keterangan === '' ? null : $keterangan,
                ':status'     => $status,
            ]);
        } catch (PDOException $exception) {
            throw $this->translateDuplicate($exception);
        }

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateLocation(int $id, array $data): bool
    {
        $sets = [];
        $params = [':id' => $id];

        if (array_key_exists('gedung', $data)) {
            $gedung = trim((string) $data['gedung']);
            $this->assertLength($gedung, 1, 80, 'Gedung');
            $sets[] = 'gedung = :gedung';
            $params[':gedung'] = $gedung;
        }

        if (array_key_exists('lantai', $data)) {
            $lantai = trim((string) $data['lantai']);
            $this->assertLength($lantai, 1, 20, 'Lantai');
            $sets[] = 'lantai = :lantai';
            $params[':lantai'] = $lantai;
        }

        if (array_key_exists('nama_ruangan', $data)) {
            $ruangan = trim((string) $data['nama_ruangan']);
            $this->assertLength($ruangan, 1, 80, 'Nama ruangan');
            $sets[] = 'nama_ruangan = :ruangan';
            $params[':ruangan'] = $ruangan;
        }

        if (array_key_exists('keterangan', $data)) {
            $keterangan = trim((string) $data['keterangan']);
            $this->assertLength($keterangan, 0, 255, 'Keterangan lokasi');
            $sets[] = 'keterangan = :keterangan';
            $params[':keterangan'] = $keterangan === '' ? null : $keterangan;
        }

        if (array_key_exists('status_aktif', $data)) {
            $sets[] = 'status_aktif = :status';
            $params[':status'] = $this->normalizeActive($data['status_aktif']);
        }

        if ($sets === []) {
            return false;
        }

        $statement = $this->pdo->prepare(
            'UPDATE locations SET ' . implode(', ', $sets) . ' WHERE id_lokasi = :id'
        );

        try {
            $statement->execute($params);
        } catch (PDOException $exception) {
            throw $this->translateDuplicate($exception);
        }

        return true;
    }

    public function setLocationStatus(int $id, bool $active): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE locations SET status_aktif = :status WHERE id_lokasi = :id'
        );

        return $statement->execute([
            ':status' => $active ? 1 : 0,
            ':id'     => $id,
        ]);
    }

    public function deleteLocation(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM locations WHERE id_lokasi = :id');

        try {
            $statement->execute([':id' => $id]);
        } catch (PDOException $exception) {
            throw $this->translateDeleteReference($exception, 'lokasi');
        }

        return $statement->rowCount() > 0;
    }

    // =================================================================
    // Helper internal
    // =================================================================

    private function normalizeActive(mixed $value): int
    {
        if (is_string($value)) {
            $value = in_array(strtolower($value), ['1', 'true', 'aktif', 'ya'], true) ? 1 : 0;
        }

        return ((int) (bool) $value) === 1 ? 1 : 0;
    }

    private function normalizeIcon(string $icon): string
    {
        $icon = trim($icon);

        if ($icon === '') {
            return 'build';
        }

        // Nama Material Symbols hanya huruf kecil, angka, dan underscore.
        if (preg_match('/^[a-z0-9_]{1,40}$/', $icon) !== 1) {
            return 'build';
        }

        return $icon;
    }

    private function assertLength(string $value, int $min, int $max, string $label): void
    {
        $length = function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);

        if ($length < $min) {
            throw new InvalidArgumentException($label . ' wajib diisi.');
        }

        if ($length > $max) {
            throw new InvalidArgumentException($label . ' maksimal ' . $max . ' karakter.');
        }
    }

    private function translateDuplicate(PDOException $exception): Throwable
    {
        if ($exception->getCode() !== '23000') {
            return $exception;
        }

        $message = $exception->getMessage();

        if (str_contains($message, 'uq_categories_nama')) {
            return new RuntimeException('Nama kategori sudah ada.', 0, $exception);
        }

        if (str_contains($message, 'uq_type_kategori_nama')) {
            return new RuntimeException('Jenis fasilitas dengan nama tersebut sudah ada pada kategori ini.', 0, $exception);
        }

        if (str_contains($message, 'uq_location')) {
            return new RuntimeException('Lokasi dengan gedung, lantai, dan ruangan tersebut sudah ada.', 0, $exception);
        }

        if (str_contains($message, 'fk_types_kategori')) {
            return new InvalidArgumentException('Kategori yang dipilih tidak valid.', 0, $exception);
        }

        return $exception;
    }

    private function translateDeleteReference(PDOException $exception, string $label): Throwable
    {
        if ($exception->getCode() === '23000') {
            return new RuntimeException(
                ucfirst($label) . ' tidak dapat dihapus karena masih dipakai data lain. Nonaktifkan saja.',
                0,
                $exception
            );
        }

        return $exception;
    }
}
