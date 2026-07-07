<?php
class User
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function findByUsernameOrEmail(string $identity): ?array
    {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss', $identity, $identity);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        return $user ?: null;
    }

    public function emailExists(string $email, ?int $ignoreUserId = null): bool
    {
        if ($ignoreUserId) {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1");
            $stmt->bind_param('si', $email, $ignoreUserId);
        } else {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param('s', $email);
        }
        $stmt->execute();
        return (bool)$stmt->get_result()->fetch_assoc();
    }

    public function usernameExists(string $username, ?int $ignoreUserId = null): bool
    {
        if ($ignoreUserId) {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ? AND id != ? LIMIT 1");
            $stmt->bind_param('si', $username, $ignoreUserId);
        } else {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param('s', $username);
        }
        $stmt->execute();
        return (bool)$stmt->get_result()->fetch_assoc();
    }

    public function create(string $username, string $email, string $password, string $role): int
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $username, $email, $hash, $role);
        $stmt->execute();
        return $this->db->insert_id;
    }

    public function update(int $id, string $username, string $email, ?string $password = null): bool
    {
        if ($password !== null && $password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param('sssi', $username, $email, $hash, $id);
        } else {
            $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->bind_param('ssi', $username, $email, $id);
        }
        return $stmt->execute();
    }


    public function getAllWithPelanggan(): array
    {
        $sql = "SELECT u.id, u.username, u.email, u.role, u.created_at,
                       p.id AS pelanggan_id, p.nama, p.no_hp, p.alamat
                FROM users u
                LEFT JOIN pelanggan p ON p.user_id = u.id
                ORDER BY FIELD(u.role, 'admin', 'pelanggan'), u.username ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countByRole(string $role): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM users WHERE role = ?");
        $stmt->bind_param('s', $role);
        $stmt->execute();
        return (int)($stmt->get_result()->fetch_assoc()['total'] ?? 0);
    }

    public function hasPelangganProfile(int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM pelanggan WHERE user_id = ? LIMIT 1");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return (bool)$stmt->get_result()->fetch_assoc();
    }

    public function updateRole(int $id, string $role): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param('si', $role, $id);
        return $stmt->execute();
    }
}
