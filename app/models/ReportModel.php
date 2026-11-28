<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Constants.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../core/Helper.php';

/**
 * Model laporan kerusakan.
 *
 * Pembacaan daftar/detail memakai view v_laporan_detail dan statistik
 * memakai v_statistik_status / v_statistik_kategori. Penulisan laporan
 * baru dan perubahan status selalu dibungkus PDO transaction.
 */
final class ReportModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::connection();
    }

    /**
     * Daftar laporan dengan filter dan pagination.
     *
     * Filter yang didukung:
     * status_laporan, prioritas, kategori, id_pelapor, id_petugas,
     * id_barang, id_lokasi, search, tanggal_awal, tanggal_akhir,
     * sort (terbaru|terlama|prioritas|status), limit, offset.
     *
     * @param array<string, mixed> $filters
     * @return array<int, array<string, mixed>>
     */
    public function all(array $filters = []): array
    {
        $params = [];
        $where = $this->buildWhere($filters, $params);

        $limit = $this->normalizeLimit((int) ($filters['limit'] ?? 100));
        $offset = max(0, (int) ($filters['offset'] ?? 0));
        $order = $this->resolveOrder((string) ($filters['sort'] ?? 'terbaru'));

        $sql = $this->baseSelect()
            . $where
            . ' ORDER BY ' . $order
            . ' LIMIT ' . $limit . ' OFFSET ' . $offset;

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function countAll(array $filters = []): int
    {
        $params = [];
        $where = $this->buildWhere($filters, $params);

        $sql = 'SELECT COUNT(*)
                FROM v_laporan_detail v
                INNER JOIN reports r ON r.id_laporan = v.id_laporan'
            . $where;

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return (int) $statement->fetchColumn();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            $this->baseSelect() . ' WHERE v.id_laporan = :id LIMIT 1'
        );
        $statement->execute([':id' => $id]);

        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByKode(string $kodeLaporan): ?array
    {
        $kodeLaporan = trim($kodeLaporan);
        if ($kodeLaporan === '') {
            return null;
        }

        $statement = $this->pdo->prepare(
            $this->baseSelect() . ' WHERE v.kode_laporan = :kode LIMIT 1'
        );
        $statement->execute([':kode' => $kodeLaporan]);

        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function photos(int $idLaporan): array
    {
        $statement = $this->pdo->prepare(
            'SELECT id_foto, id_laporan, path_file, uploaded_at
             FROM report_photos
             WHERE id_laporan = :id
             ORDER BY id_foto ASC'
        );
        $statement->execute([':id' => $idLaporan]);

        return $statement->fetchAll();
    }

    /**
     * Riwayat status beserta nama user yang mengubah.
     *
     * @return array<int, array<string, mixed>>
     */
    public function history(int $idLaporan): array
    {
        $statement = $this->pdo->prepare(
            'SELECT h.id_riwayat, h.id_laporan, h.id_user,
                    h.status_sebelumnya, h.status_baru, h.catatan, h.created_at,
                    u.nama AS nama_user, r.nama_role
             FROM report_status_history h
             INNER JOIN users u ON u.id_user = h.id_user
             INNER JOIN roles r ON r.id_role = u.id_role
             WHERE h.id_laporan = :id
             ORDER BY h.created_at ASC, h.id_riwayat ASC'
        );
        $statement->execute([':id' => $idLaporan]);

        return $statement->fetchAll();
    }

    /**
     * Membuat laporan baru + foto + log status awal dalam satu transaction.
     *
     * @param array<string, mixed> $data       id_pelapor, id_barang, id_lokasi,
     *                                          deskripsi, prioritas, id_petugas (opsional)
     * @param array<int, string|array<string, mixed>> $photoPaths Path relatif dari Uploader.
     * @return int id_laporan baru.
     */
    public function create(array $data, array $photoPaths = []): int
    {
        $idPelapor = (int) ($data['id_pelapor'] ?? 0);
        $idBarang = (int) ($data['id_barang'] ?? 0);
        $idLokasi = (int) ($data['id_lokasi'] ?? 0);
        $deskripsi = trim((string) ($data['deskripsi'] ?? ''));
        $prioritas = (string) ($data['prioritas'] ?? PRIORITAS_SEDANG);
        $idPetugas = isset($data['id_petugas']) && $data['id_petugas'] !== ''
            ? (int) $data['id_petugas']
            : null;

        if ($idPelapor <= 0 || $idBarang <= 0 || $idLokasi <= 0 || $deskripsi === '') {
            throw new InvalidArgumentException(
                'Pelapor, barang, lokasi, dan deskripsi laporan wajib diisi.'
            );
        }

        if (!in_array($prioritas, PRIORITAS_LIST, true)) {
            throw new InvalidArgumentException('Prioritas laporan tidak valid.');
        }

        if ($idPetugas !== null) {
            $this->assertPetugas($idPetugas);
        }

        $photos = [];
        foreach ($photoPaths as $path) {
            if (is_array($path)) {
                $path = $path['path_file'] ?? '';
            }

            $path = trim((string) $path);
            if ($path !== '') {
                $photos[] = $path;
            }
        }

        $maxAttempts = 5;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                $this->pdo->beginTransaction();

                $kodeLaporan = Helper::generateKodeLaporan($this->pdo);

                $insertReport = $this->pdo->prepare(
                    'INSERT INTO reports
                        (kode_laporan, id_pelapor, id_barang, id_lokasi,
                         deskripsi, prioritas, status_laporan, id_petugas)
                     VALUES
                        (:kode_laporan, :id_pelapor, :id_barang, :id_lokasi,
                         :deskripsi, :prioritas, :status_laporan, :id_petugas)'
                );

                $insertReport->bindValue(':kode_laporan', $kodeLaporan);
                $insertReport->bindValue(':id_pelapor', $idPelapor, PDO::PARAM_INT);
                $insertReport->bindValue(':id_barang', $idBarang, PDO::PARAM_INT);
                $insertReport->bindValue(':id_lokasi', $idLokasi, PDO::PARAM_INT);
                $insertReport->bindValue(':deskripsi', $deskripsi);
                $insertReport->bindValue(':prioritas', $prioritas);
                $insertReport->bindValue(':status_laporan', STATUS_LAPORAN_MENUNGGU);

                if ($idPetugas === null) {
                    $insertReport->bindValue(':id_petugas', null, PDO::PARAM_NULL);
                } else {
                    $insertReport->bindValue(':id_petugas', $idPetugas, PDO::PARAM_INT);
                }

                $insertReport->execute();
                $idLaporan = (int) $this->pdo->lastInsertId();

                if ($photos !== []) {
                    $insertPhoto = $this->pdo->prepare(
                        'INSERT INTO report_photos (id_laporan, path_file)
                         VALUES (:id_laporan, :path_file)'
                    );

                    foreach ($photos as $path) {
                        $insertPhoto->execute([
                            ':id_laporan' => $idLaporan,
                            ':path_file'  => $path,
                        ]);
                    }
                }

                $insertHistory = $this->pdo->prepare(
                    'INSERT INTO report_status_history
                        (id_laporan, id_user, status_sebelumnya, status_baru, catatan)
                     VALUES
                        (:id_laporan, :id_user, :status_sebelumnya, :status_baru, :catatan)'
                );

                $insertHistory->bindValue(':id_laporan', $idLaporan, PDO::PARAM_INT);
                $insertHistory->bindValue(':id_user', $idPelapor, PDO::PARAM_INT);
                $insertHistory->bindValue(':status_sebelumnya', null, PDO::PARAM_NULL);
                $insertHistory->bindValue(':status_baru', STATUS_LAPORAN_MENUNGGU);
                $insertHistory->bindValue(':catatan', 'Laporan baru dibuat oleh pelapor.');
                $insertHistory->execute();

                $this->pdo->commit();

                return $idLaporan;
            } catch (PDOException $exception) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }

                $isDuplicateCode = $exception->getCode() === '23000'
                    && str_contains($exception->getMessage(), 'uq_reports_kode');

                if ($isDuplicateCode && $attempt < $maxAttempts) {
                    continue;
                }

                throw $exception;
            } catch (Throwable $exception) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }

                throw $exception;
            }
        }

        throw new RuntimeException('Gagal membuat kode laporan unik. Silakan coba lagi.');
    }

    /**
     * Memperbarui status laporan + log riwayat dalam satu transaction.
     *
     * @param int         $idLaporan   ID laporan.
     * @param string      $statusBaru  Salah satu STATUS_LAPORAN_LIST.
     * @param int         $idUser      User yang melakukan perubahan.
     * @param string|null $catatan     Catatan perubahan status.
     * @param int|null    $idPetugas   Tetapkan/ganti petugas (opsional).
     */
    public function updateStatus(
        int $idLaporan,
        string $statusBaru,
        int $idUser,
        ?string $catatan = null,
        ?int $idPetugas = null
    ): bool {
        if (!in_array($statusBaru, STATUS_LAPORAN_LIST, true)) {
            throw new InvalidArgumentException('Status laporan tidak valid.');
        }

        if ($idUser <= 0) {
            throw new InvalidArgumentException('User pengubah status tidak valid.');
        }

        if ($idPetugas !== null) {
            $this->assertPetugas($idPetugas);
        }

        $catatan = $catatan === null ? null : trim($catatan);
        if ($catatan === '') {
            $catatan = null;
        }

        $this->pdo->beginTransaction();

        try {
            $lock = $this->pdo->prepare(
                'SELECT status_laporan, id_petugas
                 FROM reports
                 WHERE id_laporan = :id
                 FOR UPDATE'
            );
            $lock->execute([':id' => $idLaporan]);

            $current = $lock->fetch();

            if (!is_array($current)) {
                throw new RuntimeException('Laporan tidak ditemukan.');
            }

            $statusSebelumnya = (string) $current['status_laporan'];

            $petugasBaru = $idPetugas;
            if ($petugasBaru === null && $current['id_petugas'] !== null) {
                $petugasBaru = (int) $current['id_petugas'];
            }

            $selesaiAtSql = $statusBaru === STATUS_LAPORAN_SELESAI
                ? 'COALESCE(selesai_at, NOW())'
                : 'NULL';

            $update = $this->pdo->prepare(
                'UPDATE reports
                 SET status_laporan = :status_laporan,
                     id_petugas = :id_petugas,
                     selesai_at = ' . $selesaiAtSql . '
                 WHERE id_laporan = :id'
            );

            $update->bindValue(':status_laporan', $statusBaru);
            $update->bindValue(':id', $idLaporan, PDO::PARAM_INT);

            if ($petugasBaru === null) {
                $update->bindValue(':id_petugas', null, PDO::PARAM_NULL);
            } else {
                $update->bindValue(':id_petugas', $petugasBaru, PDO::PARAM_INT);
            }

            $update->execute();

            $history = $this->pdo->prepare(
                'INSERT INTO report_status_history
                    (id_laporan, id_user, status_sebelumnya, status_baru, catatan)
                 VALUES
                    (:id_laporan, :id_user, :status_sebelumnya, :status_baru, :catatan)'
            );

            $history->bindValue(':id_laporan', $idLaporan, PDO::PARAM_INT);
            $history->bindValue(':id_user', $idUser, PDO::PARAM_INT);
            $history->bindValue(':status_sebelumnya', $statusSebelumnya);
            $history->bindValue(':status_baru', $statusBaru);
            $history->bindValue(':catatan', $catatan, $catatan === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
            $history->execute();

            $this->pdo->commit();

            return true;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    /**
     * Menetapkan atau melepas petugas tanpa mengubah status.
     */
    public function assignPetugas(int $idLaporan, ?int $idPetugas): bool
    {
        if ($idPetugas !== null) {
            $this->assertPetugas($idPetugas);
        }

        $exists = $this->pdo->prepare('SELECT 1 FROM reports WHERE id_laporan = :id LIMIT 1');
        $exists->execute([':id' => $idLaporan]);

        if ($exists->fetchColumn() === false) {
            throw new RuntimeException('Laporan tidak ditemukan.');
        }

        $statement = $this->pdo->prepare(
            'UPDATE reports SET id_petugas = :id_petugas WHERE id_laporan = :id'
        );

        if ($idPetugas === null) {
            $statement->bindValue(':id_petugas', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':id_petugas', $idPetugas, PDO::PARAM_INT);
        }
        $statement->bindValue(':id', $idLaporan, PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * Statistik per status dari view v_statistik_status.
     * Semua status selalu dikembalikan, termasuk yang jumlahnya 0.
     *
     * @return array<string, int>
     */
    public function statsByStatus(): array
    {
        $rows = $this->pdo->query(
            'SELECT status_laporan, jumlah FROM v_statistik_status'
        )->fetchAll();

        $result = array_fill_keys(STATUS_LAPORAN_LIST, 0);

        foreach ($rows as $row) {
            $status = (string) $row['status_laporan'];
            if (array_key_exists($status, $result)) {
                $result[$status] = (int) $row['jumlah'];
            }
        }

        return $result;
    }

    /**
     * Statistik per kategori dari view v_statistik_kategori.
     *
     * @return array<int, array{nama_kategori: string, jumlah: int}>
     */
    public function statsByCategory(): array
    {
        $rows = $this->pdo->query(
            'SELECT nama_kategori, jumlah
             FROM v_statistik_kategori
             ORDER BY jumlah DESC, nama_kategori ASC'
        )->fetchAll();

        return array_map(
            static fn (array $row): array => [
                'nama_kategori' => (string) $row['nama_kategori'],
                'jumlah'        => (int) $row['jumlah'],
            ],
            $rows
        );
    }

    /**
     * Ringkasan angka untuk kartu dashboard.
     *
     * @return array{total: int, menunggu: int, diverifikasi: int, diproses: int, selesai: int, ditolak: int, per_status: array<string, int>}
     */
    public function statsSummary(): array
    {
        $perStatus = $this->statsByStatus();

        return [
            'total'        => array_sum($perStatus),
            'menunggu'     => $perStatus[STATUS_LAPORAN_MENUNGGU],
            'diverifikasi' => $perStatus[STATUS_LAPORAN_DIVERIFIKASI],
            'diproses'     => $perStatus[STATUS_LAPORAN_DIPROSES],
            'selesai'      => $perStatus[STATUS_LAPORAN_SELESAI],
            'ditolak'      => $perStatus[STATUS_LAPORAN_DITOLAK],
            'per_status'   => $perStatus,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function latest(int $limit = 5): array
    {
        return $this->all([
            'sort'  => 'terbaru',
            'limit' => $limit,
        ]);
    }

    // =================================================================
    // Internal
    // =================================================================

    private function baseSelect(): string
    {
        return 'SELECT v.id_laporan, v.kode_laporan, v.status_laporan, v.prioritas,
                       v.deskripsi, v.created_at, v.updated_at, v.selesai_at,
                       v.nama_pelapor, v.nim_pelapor, v.nama_petugas,
                       v.kode_barang, v.nama_label, v.kondisi,
                       v.nama_fasilitas, v.nama_kategori,
                       v.gedung, v.lantai, v.nama_ruangan,
                       r.id_pelapor, r.id_petugas, r.id_barang, r.id_lokasi
                FROM v_laporan_detail v
                INNER JOIN reports r ON r.id_laporan = v.id_laporan';
    }

    /**
     * @param array<string, mixed> $filters
     * @param array<string, mixed> $params
     */
    private function buildWhere(array $filters, array &$params): string
    {
        $conditions = [];

        if (!empty($filters['status_laporan'])) {
            $status = (string) $filters['status_laporan'];
            if (!in_array($status, STATUS_LAPORAN_LIST, true)) {
                throw new InvalidArgumentException('Status laporan tidak valid.');
            }
            $conditions[] = 'v.status_laporan = :status_laporan';
            $params[':status_laporan'] = $status;
        }

        if (!empty($filters['prioritas'])) {
            $prioritas = (string) $filters['prioritas'];
            if (!in_array($prioritas, PRIORITAS_LIST, true)) {
                throw new InvalidArgumentException('Prioritas laporan tidak valid.');
            }
            $conditions[] = 'v.prioritas = :prioritas';
            $params[':prioritas'] = $prioritas;
        }

        if (!empty($filters['kategori'])) {
            $conditions[] = 'v.nama_kategori = :kategori';
            $params[':kategori'] = (string) $filters['kategori'];
        }

        if (!empty($filters['id_pelapor'])) {
            $conditions[] = 'r.id_pelapor = :id_pelapor';
            $params[':id_pelapor'] = (int) $filters['id_pelapor'];
        }

        if (!empty($filters['id_petugas'])) {
            $conditions[] = 'r.id_petugas = :id_petugas';
            $params[':id_petugas'] = (int) $filters['id_petugas'];
        }

        if (!empty($filters['id_barang'])) {
            $conditions[] = 'r.id_barang = :id_barang';
            $params[':id_barang'] = (int) $filters['id_barang'];
        }

        if (!empty($filters['id_lokasi'])) {
            $conditions[] = 'r.id_lokasi = :id_lokasi';
            $params[':id_lokasi'] = (int) $filters['id_lokasi'];
        }

        if (!empty($filters['search'])) {
            $searchLike = '%' . trim((string) $filters['search']) . '%';
            $conditions[] = '(v.kode_laporan LIKE :search_kode
                              OR v.deskripsi LIKE :search_deskripsi
                              OR v.nama_label LIKE :search_label
                              OR v.nama_pelapor LIKE :search_pelapor
                              OR v.gedung LIKE :search_gedung
                              OR v.nama_ruangan LIKE :search_ruangan)';
            $params[':search_kode'] = $searchLike;
            $params[':search_deskripsi'] = $searchLike;
            $params[':search_label'] = $searchLike;
            $params[':search_pelapor'] = $searchLike;
            $params[':search_gedung'] = $searchLike;
            $params[':search_ruangan'] = $searchLike;
        }

        if (!empty($filters['tanggal_awal'])) {
            $tanggal = $this->normalizeDate((string) $filters['tanggal_awal']);
            $conditions[] = 'DATE(v.created_at) >= :tanggal_awal';
            $params[':tanggal_awal'] = $tanggal;
        }

        if (!empty($filters['tanggal_akhir'])) {
            $tanggal = $this->normalizeDate((string) $filters['tanggal_akhir']);
            $conditions[] = 'DATE(v.created_at) <= :tanggal_akhir';
            $params[':tanggal_akhir'] = $tanggal;
        }

        return $conditions === [] ? '' : ' WHERE ' . implode(' AND ', $conditions);
    }

    private function resolveOrder(string $sort): string
    {
        return match ($sort) {
            'terlama' => 'v.created_at ASC, v.id_laporan ASC',
            'prioritas' => "FIELD(v.prioritas, '"
                . PRIORITAS_TINGGI . "', '"
                . PRIORITAS_SEDANG . "', '"
                . PRIORITAS_RENDAH . "') ASC, v.created_at DESC",
            'status' => "FIELD(v.status_laporan, '"
                . STATUS_LAPORAN_MENUNGGU . "', '"
                . STATUS_LAPORAN_DIVERIFIKASI . "', '"
                . STATUS_LAPORAN_DIPROSES . "', '"
                . STATUS_LAPORAN_SELESAI . "', '"
                . STATUS_LAPORAN_DITOLAK . "') ASC, v.created_at DESC",
            default => 'v.created_at DESC, v.id_laporan DESC',
        };
    }

    private function normalizeLimit(int $limit): int
    {
        if ($limit < 1) {
            return 100;
        }

        return min($limit, 500);
    }

    private function normalizeDate(string $date): string
    {
        $date = trim($date);

        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date, $matches) !== 1) {
            throw new InvalidArgumentException('Format tanggal harus YYYY-MM-DD.');
        }

        if (!checkdate((int) $matches[2], (int) $matches[3], (int) $matches[1])) {
            throw new InvalidArgumentException('Tanggal tidak valid.');
        }

        return $date;
    }

    private function assertPetugas(int $idPetugas): void
    {
        $statement = $this->pdo->prepare(
            'SELECT 1
             FROM users u
             INNER JOIN roles r ON r.id_role = u.id_role
             WHERE u.id_user = :id
               AND r.nama_role = :role
               AND u.status_akun = :status
             LIMIT 1'
        );
        $statement->execute([
            ':id'     => $idPetugas,
            ':role'   => ROLE_PETUGAS,
            ':status' => STATUS_AKUN_AKTIF,
        ]);

        if ($statement->fetchColumn() === false) {
            throw new InvalidArgumentException('Petugas tidak valid atau akunnya tidak aktif.');
        }
    }
}
