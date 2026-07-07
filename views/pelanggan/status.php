<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Status Laundry Aktif</h5></div>
    <div class="card-body">
        <?php if (!$transaksi): ?>
            <div class="alert alert-info mb-0">Tidak ada cucian aktif saat ini.</div>
        <?php endif; ?>
        <div class="row g-3">
            <?php foreach ($transaksi as $row): ?>
                <?php $currentIndex = array_search($row['status'], $statuses, true); ?>
                <div class="col-lg-6">
                    <div class="card border clean-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= e($row['kode_transaksi']) ?></h6>
                                <span class="badge status-badge status-<?= strtolower($row['status']) ?>"><?= e($row['status']) ?></span>
                            </div>
                            <p class="text-muted small mb-3">Masuk: <?= e($row['tanggal_masuk']) ?> | Estimasi selesai: <?= e($row['tanggal_selesai_est']) ?></p>
                            <div class="progress-steps">
                                <?php foreach ($statuses as $idx => $status): ?>
                                    <div class="step <?= $idx <= $currentIndex ? 'done' : '' ?>">
                                        <span><?= $idx + 1 ?></span>
                                        <small><?= e($status) ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-3 fw-bold">Total: <?= rupiah($row['total_harga']) ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
