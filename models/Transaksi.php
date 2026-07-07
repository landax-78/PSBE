<?php
class Transaksi
{
    private mysqli $db;
    public const STATUSES = ['Menunggu', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function countAll(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM transaksi");
        $stmt->execute();
        return (int)$stmt->get_result()->fetch_assoc()['total'];
    }

    public function countActive(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM transaksi WHERE status != 'Diambil'");
        $stmt->execute();
        return (int)$stmt->get_result()->fetch_assoc()['total'];
    }

    public function totalRevenueThisMonth(): float
    {
        $sql = "SELECT COALESCE(SUM(total_harga), 0) AS total
                FROM transaksi
                WHERE MONTH(tanggal_masuk) = MONTH(CURRENT_DATE())
                AND YEAR(tanggal_masuk) = YEAR(CURRENT_DATE())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return (float)$stmt->get_result()->fetch_assoc()['total'];
    }

    public function generateKode(): string
    {
        $prefix = 'CW-' . date('Ymd') . '-';
        $like = $prefix . '%';
        $stmt = $this->db->prepare("SELECT kode_transaksi FROM transaksi WHERE kode_transaksi LIKE ? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param('s', $like);
        $stmt->execute();
        $last = $stmt->get_result()->fetch_assoc();
        $number = 1;
        if ($last) {
            $parts = explode('-', $last['kode_transaksi']);
            $number = ((int)end($parts)) + 1;
        }
        return $prefix . str_pad((string)$number, 4, '0', STR_PAD_LEFT);
    }

    public function getAll(string $keyword = ''): array
    {
        $keyword = trim($keyword);
        if ($keyword !== '') {
            $search = '%' . $keyword . '%';
            $sql = "SELECT t.*, p.nama AS nama_pelanggan, p.no_hp
                    FROM transaksi t
                    JOIN pelanggan p ON t.pelanggan_id = p.id
                    WHERE t.kode_transaksi LIKE ? OR p.nama LIKE ? OR t.status LIKE ?
                    ORDER BY t.id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('sss', $search, $search, $search);
        } else {
            $sql = "SELECT t.*, p.nama AS nama_pelanggan, p.no_hp
                    FROM transaksi t
                    JOIN pelanggan p ON t.pelanggan_id = p.id
                    ORDER BY t.id DESC";
            $stmt = $this->db->prepare($sql);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLatest(int $limit = 5): array
    {
        $stmt = $this->db->prepare("SELECT t.*, p.nama AS nama_pelanggan
                                    FROM transaksi t
                                    JOIN pelanggan p ON t.pelanggan_id = p.id
                                    ORDER BY t.id DESC LIMIT ?");
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT t.*, p.nama AS nama_pelanggan, p.no_hp, p.alamat, u.email
                FROM transaksi t
                JOIN pelanggan p ON t.pelanggan_id = p.id
                JOIN users u ON p.user_id = u.id
                WHERE t.id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    public function getDetail(int $transaksiId): array
    {
        $sql = "SELECT dt.*, l.nama_layanan, l.satuan
                FROM detail_transaksi dt
                JOIN layanan l ON dt.layanan_id = l.id
                WHERE dt.transaksi_id = ?
                ORDER BY dt.id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $transaksiId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getByPelangganId(int $pelangganId): array
    {
        $sql = "SELECT * FROM transaksi WHERE pelanggan_id = ? ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $pelangganId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function create(int $pelangganId, string $tanggalMasuk, string $tanggalSelesaiEst, string $status, array $details): int
    {
        $kode = $this->generateKode();
        return $this->saveTransaction(null, $kode, $pelangganId, $tanggalMasuk, $tanggalSelesaiEst, $status, $details);
    }

    public function update(int $id, int $pelangganId, string $tanggalMasuk, string $tanggalSelesaiEst, string $status, array $details): int
    {
        $current = $this->getById($id);
        if (!$current) {
            throw new RuntimeException('Transaksi tidak ditemukan.');
        }
        return $this->saveTransaction($id, $current['kode_transaksi'], $pelangganId, $tanggalMasuk, $tanggalSelesaiEst, $status, $details);
    }

    private function saveTransaction(?int $id, string $kode, int $pelangganId, string $tanggalMasuk, string $tanggalSelesaiEst, string $status, array $details): int
    {
        if (!in_array($status, self::STATUSES, true)) {
            throw new InvalidArgumentException('Status transaksi tidak valid.');
        }
        if (count($details) === 0) {
            throw new InvalidArgumentException('Detail layanan minimal 1 baris.');
        }

        $this->db->begin_transaction();
        try {
            $total = 0;
            $cleanDetails = [];

            foreach ($details as $detail) {
                $layananId = (int)($detail['layanan_id'] ?? 0);
                $berat = (float)($detail['berat'] ?? 0);
                if ($layananId <= 0 || $berat <= 0) {
                    throw new InvalidArgumentException('Layanan dan berat/jumlah wajib valid.');
                }

                $stmtLayanan = $this->db->prepare("SELECT harga FROM layanan WHERE id = ? AND status = 'Aktif' LIMIT 1");
                $stmtLayanan->bind_param('i', $layananId);
                $stmtLayanan->execute();
                $layanan = $stmtLayanan->get_result()->fetch_assoc();
                if (!$layanan) {
                    throw new InvalidArgumentException('Layanan tidak ditemukan atau tidak aktif.');
                }

                $harga = (float)$layanan['harga'];
                $subtotal = $harga * $berat;
                $total += $subtotal;
                $cleanDetails[] = [
                    'layanan_id' => $layananId,
                    'berat' => $berat,
                    'harga' => $harga,
                    'subtotal' => $subtotal
                ];
            }

            if ($id === null) {
                $stmt = $this->db->prepare("INSERT INTO transaksi (kode_transaksi, pelanggan_id, tanggal_masuk, tanggal_selesai_est, status, total_harga) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sisssd', $kode, $pelangganId, $tanggalMasuk, $tanggalSelesaiEst, $status, $total);
                $stmt->execute();
                $transaksiId = $this->db->insert_id;
            } else {
                $stmt = $this->db->prepare("UPDATE transaksi SET pelanggan_id = ?, tanggal_masuk = ?, tanggal_selesai_est = ?, status = ?, total_harga = ? WHERE id = ?");
                $stmt->bind_param('isssdi', $pelangganId, $tanggalMasuk, $tanggalSelesaiEst, $status, $total, $id);
                $stmt->execute();
                $transaksiId = $id;

                $stmtDelete = $this->db->prepare("DELETE FROM detail_transaksi WHERE transaksi_id = ?");
                $stmtDelete->bind_param('i', $transaksiId);
                $stmtDelete->execute();
            }

            $stmtDetail = $this->db->prepare("INSERT INTO detail_transaksi (transaksi_id, layanan_id, berat, harga, subtotal) VALUES (?, ?, ?, ?, ?)");
            foreach ($cleanDetails as $detail) {
                $stmtDetail->bind_param('iiddd', $transaksiId, $detail['layanan_id'], $detail['berat'], $detail['harga'], $detail['subtotal']);
                $stmtDetail->execute();
            }

            $this->db->commit();
            return $transaksiId;
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function updateStatus(int $id, string $status): bool
    {
        if (!in_array($status, self::STATUSES, true)) {
            throw new InvalidArgumentException('Status tidak valid.');
        }
        $stmt = $this->db->prepare("UPDATE transaksi SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM transaksi WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function report(string $startDate, string $endDate): array
    {
        $sql = "SELECT t.*, p.nama AS nama_pelanggan
                FROM transaksi t
                JOIN pelanggan p ON t.pelanggan_id = p.id
                WHERE t.tanggal_masuk BETWEEN ? AND ?
                ORDER BY t.tanggal_masuk DESC, t.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function totalReport(string $startDate, string $endDate): float
    {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(total_harga), 0) AS total FROM transaksi WHERE tanggal_masuk BETWEEN ? AND ?");
        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        return (float)$stmt->get_result()->fetch_assoc()['total'];
    }
}
