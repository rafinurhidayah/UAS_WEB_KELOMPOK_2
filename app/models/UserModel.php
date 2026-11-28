<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Constants.php';
require_once __DIR__ . '/../config/Database.php';

/**
 * Model tabel users (join roles).
 *
 * Password selalu disimpan sebagai hash password_hash(); kolom password
 * tidak pernah dikembalikan oleh method pembacaan agar tidak bocor ke view/JSON.
 */
final class UserModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::connection();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(
        ?string $role = null,
        ?string $status = null,
        ?string $search = null,
        int $limit = 100,
        int $offset = 0
    ): array {
        $conditions = [];
        $params = [];

        if ($role !== null && $role !== '') {
            $conditions[] = 'r.nama_role = :role';
            $params[':role'] = $role;
        }

        if ($status !== null && $status !== '') {
            if (!in_array($status, STATUS_AKUN_LIST, true)) {
                throw new InvalidArgumentException('Status akun tidak valid.');
            }
            $conditions[] = 'u.status_akun = :status';
            $params[':status'] = $status;
        }

        if ($search !== null && trim($search) !== '') {
            $searchLike = '%' . trim($search) . '%';
            $conditions[] = '(u.nama LIKE :search_nama OR u.email LIKE :search_email OR u.nim_nip LIKE :search_nim)';
            $params[':search_nama'] = $searchLike;
            $params[':search_email'] = $searchLike;
            $params[':search_nim'] = $searchLike;
        }

        $sql = $this->baseSelect();
        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY u.created_at DESC, u.id_user DESC';
        $sql .= ' LIMIT ' . $this->normalizeLimit($limit) . ' OFFSET ' . max(0, $offset);

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    /**
     * Mengambil data user lengkap (tanpa kolom password) berdasarkan id.
     *
     * @return array<string, mixed>|null
     */
    public function findById(int $idUser): ?array
    {
        if ($idUser <= 0) {
            return null;
        }

        $statement = $this->pdo->prepare(
            $this->baseSelect() . ' WHERE u.id_user = :id LIMIT 1'
        );
        $statement->execute([':id' => $idUser]);

        $user = $statement->fetch();

        return is_array($user) ? $user : null;
    }

    /**
     * Alias lama untuk kompatibilitas.
     *
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        return $this->findById($id);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByEmail(string $email): ?array
    {
        return $this->findByColumn('u.email', $this->normalizeEmail($email));
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByNimNip(string $nimNip): ?array
    {
        return $this->findByColumn('u.nim_nip', trim($nimNip));
    }

    public function emailExists(string $email, ?int $exceptId = null): bool
    {
        return $this->columnExists('email', $this->normalizeEmail($email), $exceptId);
    }

    public function nimNipExists(string $nimNip, ?int $exceptId = null): bool
    {
        return $this->columnExists('nim_nip', trim($nimNip), $exceptId);
    }

    /**
     * Membuat user baru. Password pada $data berupa teks asli dan akan di-hash.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        $nama = trim((string) ($data['nama'] ?? ''));
        $email = $this->normalizeEmail((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $nimNip = trim((string) ($data['nim_nip'] ?? ''));
        $idRole = (int) ($data['id_role'] ?? 0);
        $noHp = trim((string) ($data['no_hp'] ?? ''));
        $status = (string) ($data['status_akun'] ?? STATUS_AKUN_AKTIF);

        if ($nama === '' || $email === '' || $password === '' || $nimNip === '' || $idRole <= 0) {
            throw new InvalidArgumentException('Nama, email, password, NIM/NIP, dan role wajib diisi.');
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Format email tidak valid.');
        }

        if (strlen($password) < 8) {
            throw new InvalidArgumentException('Password minimal 8 karakter.');
        }

        if (!in_array($status, STATUS_AKUN_LIST, true)) {
            throw new InvalidArgumentException('Status akun tidak valid.');
        }

        $statement = $this->pdo->prepare(
            'INSERT INTO users (id_role, nama, email, password, nim_nip, no_hp, status_akun)
             VALUES (:id_role, :nama, :email, :password, :nim_nip, :no_hp, :status_akun)'
        );

        try {
            $statement->execute([
                ':id_role'    => $idRole,
                ':nama'       => $nama,
                ':email'      => $email,
                ':password'   => password_hash($password, PASSWORD_DEFAULT),
                ':nim_nip'    => $nimNip,
                ':no_hp'      => $noHp === '' ? null : $noHp,
                ':status_akun' => $status,
            ]);
        } catch (PDOException $exception) {
            throw $this->translateDuplicate($exception);
        }

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Memperbarui data profil yang boleh diubah sendiri oleh pengguna.
     * NIM/NIP dan role tidak termasuk agar tidak dapat diubah dari form profil.
     *
     * @param array<string, mixed> $data
     */
    public function updateProfile(int $idUser, array $data): bool
    {
        if ($idUser <= 0) {
            throw new InvalidArgumentException('User tidak valid.');
        }

        $nama = trim((string) ($data['nama'] ?? ''));
        $email = $this->normalizeEmail((string) ($data['email'] ?? ''));
        $noHp = trim((string) ($data['no_hp'] ?? ''));

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

        if ($this->emailExists($email, $idUser)) {
            throw new RuntimeException('Email sudah digunakan oleh akun lain.');
        }

        $statement = $this->pdo->prepare(
            'UPDATE users
             SET nama = :nama, email = :email, no_hp = :no_hp
             WHERE id_user = :id'
        );

        try {
            $statement->execute([
                ':nama'  => $nama,
                ':email' => $email,
                ':no_hp' => $noHp === '' ? null : $noHp,
                ':id'    => $idUser,
            ]);
        } catch (PDOException $exception) {
            throw $this->translateDuplicate($exception);
        }

        return true;
    }

    /**
     * Memperbarui data user. Password hanya diubah bila dikirim tidak kosong.
     *
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool
    {
        $sets = [];
        $params = [':id' => $id];

        if (array_key_exists('id_role', $data)) {
            $idRole = (int) $data['id_role'];
            if ($idRole <= 0) {
                throw new InvalidArgumentException('Role tidak valid.');
            }
            $sets[] = 'id_role = :id_role';
            $params[':id_role'] = $idRole;
        }

        if (array_key_exists('nama', $data)) {
            $nama = trim((string) $data['nama']);
            if ($nama === '') {
                throw new InvalidArgumentException('Nama wajib diisi.');
            }
            $sets[] = 'nama = :nama';
            $params[':nama'] = $nama;
        }

        if (array_key_exists('email', $data)) {
            $email = $this->normalizeEmail((string) $data['email']);
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                throw new InvalidArgumentException('Format email tidak valid.');
            }
            $sets[] = 'email = :email';
            $params[':email'] = $email;
        }

        if (array_key_exists('nim_nip', $data)) {
            $nimNip = trim((string) $data['nim_nip']);
            if ($nimNip === '') {
                throw new InvalidArgumentException('NIM/NIP wajib diisi.');
            }
            $sets[] = 'nim_nip = :nim_nip';
            $params[':nim_nip'] = $nimNip;
        }

        if (array_key_exists('no_hp', $data)) {
            $noHp = trim((string) $data['no_hp']);
            $sets[] = 'no_hp = :no_hp';
            $params[':no_hp'] = $noHp === '' ? null : $noHp;
        }

        if (array_key_exists('status_akun', $data)) {
            $status = (string) $data['status_akun'];
            if (!in_array($status, STATUS_AKUN_LIST, true)) {
                throw new InvalidArgumentException('Status akun tidak valid.');
            }
            $sets[] = 'status_akun = :status_akun';
            $params[':status_akun'] = $status;
        }

        if (array_key_exists('password', $data)) {
            $password = (string) $data['password'];
            if ($password !== '') {
                if (strlen($password) < 8) {
                    throw new InvalidArgumentException('Password minimal 8 karakter.');
                }
                $sets[] = 'password = :password';
                $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
            }
        }

        if ($sets === []) {
            return false;
        }

        $statement = $this->pdo->prepare(
            'UPDATE users SET ' . implode(', ', $sets) . ' WHERE id_user = :id'
        );

        try {
            $statement->execute($params);
        } catch (PDOException $exception) {
            throw $this->translateDuplicate($exception);
        }

        return true;
    }

    /**
     * Mengambil hash kata sandi untuk keperluan verifikasi.
     * Hanya dipakai internal controller; jangan dikirim ke view/JSON.
     */
    public function getPasswordHash(int $idUser): ?string
    {
        if ($idUser <= 0) {
            return null;
        }

        $statement = $this->pdo->prepare(
            'SELECT password FROM users WHERE id_user = :id LIMIT 1'
        );
        $statement->execute([':id' => $idUser]);

        $hash = $statement->fetchColumn();

        return is_string($hash) && $hash !== '' ? $hash : null;
    }

    /**
     * Menyimpan hash kata sandi baru.
     *
     * Parameter wajib berupa hasil password_hash(), bukan teks asli.
     */
    public function updatePassword(int $idUser, string $newPasswordHash): bool
    {
        if ($idUser <= 0) {
            throw new InvalidArgumentException('User tidak valid.');
        }

        $info = password_get_info($newPasswordHash);
        if (($info['algoName'] ?? 'unknown') === 'unknown') {
            throw new InvalidArgumentException('Hash kata sandi tidak valid.');
        }

        $statement = $this->pdo->prepare(
            'UPDATE users SET password = :password WHERE id_user = :id'
        );

        return $statement->execute([
            ':password' => $newPasswordHash,
            ':id'       => $idUser,
        ]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        if (!in_array($status, STATUS_AKUN_LIST, true)) {
            throw new InvalidArgumentException('Status akun tidak valid.');
        }

        $statement = $this->pdo->prepare(
            'UPDATE users SET status_akun = :status WHERE id_user = :id'
        );

        return $statement->execute([':status' => $status, ':id' => $id]);
    }

    /**
     * Hapus permanen. Akan gagal bila user sudah punya relasi laporan/barang
     * (FK ON DELETE RESTRICT); gunakan updateStatus('nonaktif') sebagai alternatif.
     */
    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM users WHERE id_user = :id');

        try {
            $statement->execute([':id' => $id]);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                throw new RuntimeException(
                    'User tidak dapat dihapus karena masih memiliki data terkait. Nonaktifkan akun saja.',
                    0,
                    $exception
                );
            }
            throw $exception;
        }

        return $statement->rowCount() > 0;
    }

    /**
     * @return array<int, array{id_role: int, nama_role: string}>
     */
    public function roles(): array
    {
        $statement = $this->pdo->query('SELECT id_role, nama_role FROM roles ORDER BY id_role ASC');

        return $statement->fetchAll();
    }

    private function baseSelect(): string
    {
        return 'SELECT u.id_user, u.id_role, u.nama, u.email, u.nim_nip, u.no_hp,
                       u.status_akun, u.created_at, r.nama_role
                FROM users u
                INNER JOIN roles r ON r.id_role = u.id_role';
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findByColumn(string $column, string $value): ?array
    {
        if ($value === '') {
            return null;
        }

        $statement = $this->pdo->prepare(
            $this->baseSelect() . ' WHERE ' . $column . ' = :value LIMIT 1'
        );
        $statement->execute([':value' => $value]);

        $user = $statement->fetch();

        return is_array($user) ? $user : null;
    }

    private function columnExists(string $column, string $value, ?int $exceptId): bool
    {
        if ($value === '') {
            return false;
        }

        $sql = 'SELECT 1 FROM users WHERE ' . $column . ' = :value';
        $params = [':value' => $value];

        if ($exceptId !== null) {
            $sql .= ' AND id_user <> :id';
            $params[':id'] = $exceptId;
        }

        $sql .= ' LIMIT 1';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchColumn() !== false;
    }

    private function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    private function normalizeLimit(int $limit): int
    {
        if ($limit < 1) {
            return 1;
        }

        return min($limit, 500);
    }

    private function translateDuplicate(PDOException $exception): Throwable
    {
        if ($exception->getCode() !== '23000') {
            return $exception;
        }

        $message = $exception->getMessage();

        if (str_contains($message, 'uq_users_email')) {
            return new RuntimeException('Email sudah terdaftar.', 0, $exception);
        }

        if (str_contains($message, 'uq_users_nim_nip')) {
            return new RuntimeException('NIM/NIP sudah terdaftar.', 0, $exception);
        }

        if (str_contains($message, 'fk_users_role')) {
            return new InvalidArgumentException('Role yang dipilih tidak valid.', 0, $exception);
        }

        return $exception;
    }
}
