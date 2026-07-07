<?php
class Pelanggan
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function countAll(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM pelanggan");
        $stmt->execute();
        return (int)$stmt->get_result()->fetch_assoc()['total'];
    }

    public function getAll(string $keyword = ''): array
    {
        $keyword = trim($keyword);
        if ($keyword !== '') {
            $search = '%' . $keyword . '%';
            $sql = "SELECT p.*, u.username, u.email
                    FROM pelanggan p
                    JOIN users u ON p.user_id = u.id
                    WHERE p.nama LIKE ? OR p.no_hp LIKE ? OR u.email LIKE ?
                    ORDER BY p.id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('sss', $search, $search, $search);
        } else {
            $sql = "SELECT p.*, u.username, u.email
                    FROM pelanggan p
                    JOIN users u ON p.user_id = u.id
                    ORDER BY p.id DESC";
            $stmt = $this->db->prepare($sql);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT p.*, u.username, u.email, u.id AS user_id
                FROM pelanggan p
                JOIN users u ON p.user_id = u.id
                WHERE p.id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    public function getByUserId(int $userId): ?array
    {
        $sql = "SELECT p.*, u.username, u.email, u.id AS user_id
                FROM pelanggan p
                JOIN users u ON p.user_id = u.id
                WHERE p.user_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    public function create(int $userId, string $nama, string $noHp, string $alamat): bool
    {
        $stmt = $this->db->prepare("INSERT INTO pelanggan (user_id, nama, no_hp, alamat) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('isss', $userId, $nama, $noHp, $alamat);
        return $stmt->execute();
    }

    public function update(int $id, string $nama, string $noHp, string $alamat): bool
    {
        $stmt = $this->db->prepare("UPDATE pelanggan SET nama = ?, no_hp = ?, alamat = ? WHERE id = ?");
        $stmt->bind_param('sssi', $nama, $noHp, $alamat, $id);
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $row = $this->getById($id);
        if (!$row) {
            return false;
        }
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare("DELETE FROM pelanggan WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            $userId = (int)$row['user_id'];
            $stmtUser = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $stmtUser->bind_param('i', $userId);
            $stmtUser->execute();

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
