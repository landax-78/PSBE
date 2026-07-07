<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center no-print">
        <h5 class="mb-0">Laporan Transaksi</h5>
        <form class="row g-2 align-items-end" method="get">
            <input type="hidden" name="page" value="admin/laporan">
            <div class="col-auto">
                <label class="form-label small">Dari</label>
                <input type="date" name="start" value="<?= e($start) ?>" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <label class="form-label small">Sampai</label>
                <input type="date" name="end" value="<?= e($end) ?>" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i> Filter</button>
                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="window.print()"><i class="bi bi-printer"></i> Cetak</button>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="text-center mb-4 print-only">
            <h4>Laporan Transaksi Laundry CleanWash</h4>
            <p>Periode <?= e($start) ?> s/d <?= e($end) ?></p>
        </div>
        <div class="alert alert-primary d-flex justify-content-between align-items-center">
            <span>Total Pendapatan Periode Ini</span>
            <strong><?= rupiah($total) ?></strong>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light"><tr><th>No</th><th>Kode</th><th>Pelanggan</th><th>Tanggal</th><th>Status</th><th>Total</th></tr></thead>
                <tbody>
                <?php foreach ($laporan as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= e($row['kode_transaksi']) ?></td>
                        <td><?= e($row['nama_pelanggan']) ?></td>
                        <td><?= e($row['tanggal_masuk']) ?></td>
                        <td><?= e($row['status']) ?></td>
                        <td><?= rupiah($row['total_harga']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$laporan): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data pada periode ini.</td></tr>
                <?php endif; ?>
                </tbody>
                <tfoot><tr><th colspan="5" class="text-end">Total</th><th><?= rupiah($total) ?></th></tr></tfoot>
            </table>
        </div>
    </div>
</div>
