<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div><div class="stat-number"><?= e($totalPelanggan) ?></div><div class="stat-label">Pelanggan</div></div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-bag-check"></i></div>
            <div><div class="stat-number"><?= e($totalLayanan) ?></div><div class="stat-label">Layanan Aktif</div></div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-receipt"></i></div>
            <div><div class="stat-number"><?= e($totalTransaksi) ?></div><div class="stat-label">Total Transaksi</div></div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            <div><div class="stat-number small-text"><?= rupiah($pendapatanBulanIni) ?></div><div class="stat-label">Pendapatan Bulan Ini</div></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Transaksi Terbaru</h5>
                <a href="index.php?page=admin/transaksi/create" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr><th>Kode</th><th>Pelanggan</th><th>Status</th><th>Total</th><th>Tanggal</th></tr></thead>
                        <tbody>
                        <?php foreach ($latest as $row): ?>
                            <tr>
                                <td><a href="index.php?page=admin/transaksi/detail&id=<?= e($row['id']) ?>" class="fw-semibold text-decoration-none"><?= e($row['kode_transaksi']) ?></a></td>
                                <td><?= e($row['nama_pelanggan']) ?></td>
                                <td><span class="badge status-badge status-<?= strtolower($row['status']) ?>"><?= e($row['status']) ?></span></td>
                                <td><?= rupiah($row['total_harga']) ?></td>
                                <td><?= e($row['tanggal_masuk']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$latest): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="mb-3">Ringkasan Operasional</h5>
                <div class="info-box mb-3">
                    <span class="text-muted">Transaksi Aktif</span>
                    <strong><?= e($transaksiAktif) ?></strong>
                </div>
                <p class="text-muted mb-0">Status aktif adalah transaksi yang belum berstatus <strong>Diambil</strong>. Admin dapat memperbarui status cucian melalui menu Transaksi.</p>
            </div>
        </div>
    </div>
</div>
