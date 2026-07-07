<?php
class Layanan
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function countAll(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM layanan WHERE status = 'Aktif'");
        $stmt->execute();
        return (int)$stmt->get_result()->fetch_assoc()['total'];
    }

    public function getAll(string $keyword = ''): array
    {
        $keyword = trim($keyword);
        if ($keyword !== '') {
            $search = '%' . $keyword . '%';
            $stmt = $this->db->prepare("SELECT * FROM layanan WHERE nama_layanan LIKE ? OR satuan LIKE ? ORDER BY id DESC");
            $stmt->bind_param('ss', $search, $search);
        } else {
            $stmt = $this->db->prepare("SELECT * FROM layanan ORDER BY id DESC");
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getActive(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM layanan WHERE status = 'Aktif' ORDER BY nama_layanan ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM layanan WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    public function create(string $nama, string $satuan, float $harga, int $estimasiHari, string $status): bool
    {
        $stmt = $this->db->prepare("INSERT INTO layanan (nama_layanan, satuan, harga, estimasi_hari, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdis', $nama, $satuan, $harga, $estimasiHari, $status);
        return $stmt->execute();
    }

    public function update(int $id, string $nama, string $satuan, float $harga, int $estimasiHari, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE layanan SET nama_layanan = ?, satuan = ?, harga = ?, estimasi_hari = ?, status = ? WHERE id = ?");
        $stmt->bind_param('ssdisi', $nama, $satuan, $harga, $estimasiHari, $status, $id);
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM layanan WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
