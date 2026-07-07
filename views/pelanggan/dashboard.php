<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-person-check"></i></div>
            <div><div class="stat-number small-text"><?= e($pelanggan['nama'] ?? '-') ?></div><div class="stat-label">Pelanggan</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-receipt"></i></div>
            <div><div class="stat-number"><?= e($totalTransaksi) ?></div><div class="stat-label">Total Transaksi</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-number"><?= e($transaksiAktif) ?></div><div class="stat-label">Laundry Aktif</div></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Transaksi Terbaru Saya</h5>
        <a href="index.php?page=pelanggan/riwayat" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Kode</th><th>Tanggal</th><th>Estimasi</th><th>Status</th><th>Total</th></tr></thead>
                <tbody>
                <?php foreach ($transaksi as $row): ?>
                    <tr>
                        <td class="fw-semibold"><?= e($row['kode_transaksi']) ?></td>
                        <td><?= e($row['tanggal_masuk']) ?></td>
                        <td><?= e($row['tanggal_selesai_est']) ?></td>
                        <td><span class="badge status-badge status-<?= strtolower($row['status']) ?>"><?= e($row['status']) ?></span></td>
                        <td><?= rupiah($row['total_harga']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$transaksi): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi laundry.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
