<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Riwayat Laundry Saya</h5></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Kode</th><th>Tanggal Masuk</th><th>Estimasi</th><th>Status</th><th>Total</th></tr></thead>
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
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat transaksi.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
