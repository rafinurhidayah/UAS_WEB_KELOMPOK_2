<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Constants.php';
require_once __DIR__ . '/../config/Database.php';

/**
 * Model barang fisik (facility_items).
 *
 * Katalog publik dibaca dari view v_fasilitas; data admin dibaca dengan
 * join lengkap. Penghapusan menggunakan soft delete (status_aktif = 0).
 */
final class FacilityItemModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::connection();
    }

    /**
     * Katalog publik dari view v_fasilitas.
     *
     * Filter: kategori, status (kondisi), search, limit, offset.
     *
     * @param array<string, mixed> $filters
     * @return array<int, array<string, mixed>>
     */
    public function catalog(array $filters = []): array
    {
        $conditions = [];
        $params = [];

        if (!empty($filters['kategori'])) {
            $conditions[] = 'category = :kategori';
            $params[':kategori'] = (string) $filters['kategori'];
        }

        if (!empty($filters['status'])) {
            $status = (string) $filters['status'];
            if (!in_array($status, KONDISI_LIST, true)) {
                throw new InvalidArgumentException('Kondisi fasilitas tidak valid.');
            }
            $conditions[] = 'status = :status';
            $params[':status'] = $status;
        }

        if (!empty($filters['search'])) {
            $searchLike = '%' . trim((string) $filters['search']) . '%';
            $conditions[] = '(name LIKE :search_name OR code LIKE :search_code OR building LIKE :search_building)';
            $params[':search_name'] = $searchLike;
            $params[':search_code'] = $searchLike;
            $params[':search_building'] = $searchLike;
        }

        $sql = 'SELECT id_barang, code, name, category, icon, status, building, `desc`, tag
                FROM v_fasilitas';

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY name ASC, id_barang ASC';
        $sql .= ' LIMIT ' . $this->normalizeLimit((int) ($filters['limit'] ?? 100));
        $sql .= ' OFFSET ' . max(0, (int) ($filters['offset'] ?? 0));

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findFromCatalog(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id_barang, code, name, category, icon, status, building, `desc`, tag
             FROM v_fasilitas
             WHERE id_barang = :id
             LIMIT 1'
        );
        $statement->execute([':id' => $id]);

        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    /**
     * Daftar lengkap untuk admin (termasuk yang nonaktif).
     *
     * Filter: search, id_jenis, id_kategori, id_lokasi, kondisi,
     * status_aktif, limit, offset.
     *
     * @param array<string, mixed> $filters
     * @return array<int, array<string, mixed>>
     */
    public function all(array $filters = []): array
    {
        $conditions = [];
        $params = [];

        if (!empty($filters['search'])) {
            $searchLike = '%' . trim((string) $filters['search']) . '%';
            $conditions[] = '(i.kode_barang LIKE :search_kode OR i.nama_label LIKE :search_label OR t.nama_fasilitas LIKE :search_jenis OR l.gedung LIKE :search_gedung OR l.nama_ruangan LIKE :search_ruangan)';
            $params[':search_kode'] = $searchLike;
            $params[':search_label'] = $searchLike;
            $params[':search_jenis'] = $searchLike;
            $params[':search_gedung'] = $searchLike;
            $params[':search_ruangan'] = $searchLike;
        }

        if (!empty($filters['id_jenis'])) {
            $conditions[] = 'i.id_jenis = :id_jenis';
            $params[':id_jenis'] = (int) $filters['id_jenis'];
        }

        if (!empty($filters['id_kategori'])) {
            $conditions[] = 't.id_kategori = :id_kategori';
            $params[':id_kategori'] = (int) $filters['id_kategori'];
        }

        if (!empty($filters['id_lokasi'])) {
            $conditions[] = 'i.id_lokasi = :id_lokasi';
            $params[':id_lokasi'] = (int) $filters['id_lokasi'];
        }

        if (!empty($filters['kondisi'])) {
            $kondisi = (string) $filters['kondisi'];
            if (!in_array($kondisi, KONDISI_LIST, true)) {
                throw new InvalidArgumentException('Kondisi fasilitas tidak valid.');
            }
            $conditions[] = 'i.kondisi = :kondisi';
            $params[':kondisi'] = $kondisi;
        }

        if (isset($filters['status_aktif']) && $filters['status_aktif'] !== '') {
            $conditions[] = 'i.status_aktif = :status_aktif';
            $params[':status_aktif'] = ((int) (bool) $filters['status_aktif']) === 1 ? 1 : 0;
        }

        $sql = $this->baseSelect();

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY i.created_at DESC, i.id_barang DESC';
        $sql .= ' LIMIT ' . $this->normalizeLimit((int) ($filters['limit'] ?? 100));
        $sql .= ' OFFSET ' . max(0, (int) ($filters['offset'] ?? 0));

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            $this->baseSelect() . ' WHERE i.id_barang = :id LIMIT 1'
        );
        $statement->execute([':id' => $id]);

        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByKode(string $kodeBarang): ?array
    {
        $kodeBarang = trim($kodeBarang);
        if ($kodeBarang === '') {
            return null;
        }

        $statement = $this->pdo->prepare(
            $this->baseSelect() . ' WHERE i.kode_barang = :kode LIMIT 1'
        );
        $statement->execute([':kode' => $kodeBarang]);

        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    /**
     * Pilihan barang aktif untuk dropdown form laporan.
     *
     * @return array<int, array<string, mixed>>
     */
    public function options(): array
    {
        $statement = $this->pdo->query(
            'SELECT i.id_barang, i.kode_barang, i.nama_label, i.kondisi,
                    i.id_jenis, i.id_lokasi,
                    t.nama_fasilitas, t.icon,
                    l.gedung, l.lantai, l.nama_ruangan
             FROM facility_items i
             INNER JOIN facility_types t ON t.id_jenis = i.id_jenis
             INNER JOIN locations l ON l.id_lokasi = i.id_lokasi
             WHERE i.status_aktif = 1
             ORDER BY t.nama_fasilitas ASC, i.nama_label ASC'
        );

        return $statement->fetchAll();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        $idJenis = (int) ($data['id_jenis'] ?? 0);
        $idLokasi = (int) ($data['id_lokasi'] ?? 0);
        $kodeBarang = trim((string) ($data['kode_barang'] ?? ''));
        $namaLabel = trim((string) ($data['nama_label'] ?? ''));
        $kondisi = (string) ($data['kondisi'] ?? KONDISI_BAIK);
        $createdBy = (int) ($data['created_by'] ?? 0);
        $status = array_key_exists('status_aktif', $data)
            ? (((int) (bool) $data['status_aktif']) === 1 ? 1 : 0)
            : 1;

        if ($idJenis <= 0 || $idLokasi <= 0 || $createdBy <= 0) {
            throw new InvalidArgumentException('Jenis, lokasi, dan pembuat barang wajib diisi.');
        }

        if ($kodeBarang === '' || $namaLabel === '') {
            throw new InvalidArgumentException('Kode barang dan nama label wajib diisi.');
        }

        if (strlen($kodeBarang) > 20) {
            throw new InvalidArgumentException('Kode barang maksimal 20 karakter.');
        }

        if (strlen($namaLabel) > 120) {
            throw new InvalidArgumentException('Nama label maksimal 120 karakter.');
        }

        if (!in_array($kondisi, KONDISI_LIST, true)) {
            throw new InvalidArgumentException('Kondisi fasilitas tidak valid.');
        }

        $statement = $this->pdo->prepare(
            'INSERT INTO facility_items
                (id_jenis, id_lokasi, kode_barang, nama_label, kondisi, status_aktif, created_by)
             VALUES
                (:id_jenis, :id_lokasi, :kode_barang, :nama_label, :kondisi, :status_aktif, :created_by)'
        );

        try {
            $statement->execute([
                ':id_jenis'    => $idJenis,
                ':id_lokasi'   => $idLokasi,
                ':kode_barang' => $kodeBarang,
                ':nama_label'  => $namaLabel,
                ':kondisi'     => $kondisi,
                ':status_aktif' => $status,
                ':created_by'  => $createdBy,
            ]);
        } catch (PDOException $exception) {
            throw $this->translateException($exception);
        }

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool
    {
        $sets = [];
        $params = [':id' => $id];

        if (array_key_exists('id_jenis', $data)) {
            $sets[] = 'id_jenis = :id_jenis';
            $params[':id_jenis'] = (int) $data['id_jenis'];
        }

        if (array_key_exists('id_lokasi', $data)) {
            $sets[] = 'id_lokasi = :id_lokasi';
            $params[':id_lokasi'] = (int) $data['id_lokasi'];
        }

        if (array_key_exists('kode_barang', $data)) {
            $kode = trim((string) $data['kode_barang']);
            if ($kode === '' || strlen($kode) > 20) {
                throw new InvalidArgumentException('Kode barang wajib diisi dan maksimal 20 karakter.');
            }
            $sets[] = 'kode_barang = :kode_barang';
            $params[':kode_barang'] = $kode;
        }

        if (array_key_exists('nama_label', $data)) {
            $label = trim((string) $data['nama_label']);
            if ($label === '' || strlen($label) > 120) {
                throw new InvalidArgumentException('Nama label wajib diisi dan maksimal 120 karakter.');
            }
            $sets[] = 'nama_label = :nama_label';
            $params[':nama_label'] = $label;
        }

        if (array_key_exists('kondisi', $data)) {
            $kondisi = (string) $data['kondisi'];
            if (!in_array($kondisi, KONDISI_LIST, true)) {
                throw new InvalidArgumentException('Kondisi fasilitas tidak valid.');
            }
            $sets[] = 'kondisi = :kondisi';
            $params[':kondisi'] = $kondisi;
        }

        if (array_key_exists('status_aktif', $data)) {
            $sets[] = 'status_aktif = :status_aktif';
            $params[':status_aktif'] = ((int) (bool) $data['status_aktif']) === 1 ? 1 : 0;
        }

        if ($sets === []) {
            return false;
        }

        $statement = $this->pdo->prepare(
            'UPDATE facility_items SET ' . implode(', ', $sets) . ' WHERE id_barang = :id'
        );

        try {
            $statement->execute($params);
        } catch (PDOException $exception) {
            throw $this->translateException($exception);
        }

        return true;
    }

    public function updateKondisi(int $id, string $kondisi): bool
    {
        if (!in_array($kondisi, KONDISI_LIST, true)) {
            throw new InvalidArgumentException('Kondisi fasilitas tidak valid.');
        }

        $statement = $this->pdo->prepare(
            'UPDATE facility_items SET kondisi = :kondisi WHERE id_barang = :id'
        );

        return $statement->execute([':kondisi' => $kondisi, ':id' => $id]);
    }

    public function setStatus(int $id, bool $active): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE facility_items SET status_aktif = :status WHERE id_barang = :id'
        );

        return $statement->execute([
            ':status' => $active ? 1 : 0,
            ':id'     => $id,
        ]);
    }

    /**
     * Soft delete: barang tidak dihapus permanen.
     */
    public function softDelete(int $id): bool
    {
        return $this->setStatus($id, false);
    }

    public function restore(int $id): bool
    {
        return $this->setStatus($id, true);
    }

    public function kodeExists(string $kodeBarang, ?int $exceptId = null): bool
    {
        $kodeBarang = trim($kodeBarang);
        if ($kodeBarang === '') {
            return false;
        }

        $sql = 'SELECT 1 FROM facility_items WHERE kode_barang = :kode';
        $params = [':kode' => $kodeBarang];

        if ($exceptId !== null) {
            $sql .= ' AND id_barang <> :id';
            $params[':id'] = $exceptId;
        }

        $sql .= ' LIMIT 1';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchColumn() !== false;
    }

    public function countActive(): int
    {
        $statement = $this->pdo->query(
            'SELECT COUNT(*) FROM facility_items WHERE status_aktif = 1'
        );

        return (int) $statement->fetchColumn();
    }

    private function baseSelect(): string
    {
        return 'SELECT i.id_barang, i.id_jenis, i.id_lokasi, i.kode_barang, i.nama_label,
                       i.kondisi, i.status_aktif, i.created_by, i.created_at,
                       t.nama_fasilitas, t.icon, t.id_kategori,
                       c.nama_kategori,
                       l.gedung, l.lantai, l.nama_ruangan,
                       u.nama AS nama_pembuat
                FROM facility_items i
                INNER JOIN facility_types t ON t.id_jenis = i.id_jenis
                INNER JOIN categories c ON c.id_kategori = t.id_kategori
                INNER JOIN locations l ON l.id_lokasi = i.id_lokasi
                INNER JOIN users u ON u.id_user = i.created_by';
    }

    private function normalizeLimit(int $limit): int
    {
        if ($limit < 1) {
            return 100;
        }

        return min($limit, 500);
    }

    private function translateException(PDOException $exception): Throwable
    {
        if ($exception->getCode() !== '23000') {
            return $exception;
        }

        $message = $exception->getMessage();

        if (str_contains($message, 'uq_items_kode')) {
            return new RuntimeException('Kode barang sudah digunakan.', 0, $exception);
        }

        if (str_contains($message, 'fk_items_jenis')) {
            return new InvalidArgumentException('Jenis fasilitas tidak valid.', 0, $exception);
        }

        if (str_contains($message, 'fk_items_lokasi')) {
            return new InvalidArgumentException('Lokasi tidak valid.', 0, $exception);
        }

        if (str_contains($message, 'fk_items_creator')) {
            return new InvalidArgumentException('Pembuat barang tidak valid.', 0, $exception);
        }

        return $exception;
    }
}
