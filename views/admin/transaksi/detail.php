<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Invoice <?= e($data['kode_transaksi']) ?></h5>
                <button class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i class="bi bi-printer"></i> Cetak</button>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="text-muted small">Pelanggan</div>
                        <div class="fw-bold"><?= e($data['nama_pelanggan']) ?></div>
                        <div><?= e($data['no_hp']) ?></div>
                        <div><?= e($data['alamat']) ?></div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div>Tanggal Masuk: <strong><?= e($data['tanggal_masuk']) ?></strong></div>
                        <div>Estimasi Selesai: <strong><?= e($data['tanggal_selesai_est']) ?></strong></div>
                        <div>Status: <span class="badge status-badge status-<?= strtolower($data['status']) ?>"><?= e($data['status']) ?></span></div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light"><tr><th>No</th><th>Layanan</th><th>Berat/Jumlah</th><th>Harga</th><th>Subtotal</th></tr></thead>
                        <tbody>
                        <?php foreach ($detail as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= e($row['nama_layanan']) ?></td>
                                <td><?= e($row['berat']) ?> <?= e($row['satuan']) ?></td>
                                <td><?= rupiah($row['harga']) ?></td>
                                <td><?= rupiah($row['subtotal']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot><tr><th colspan="4" class="text-end">Total</th><th><?= rupiah($data['total_harga']) ?></th></tr></tfoot>
                    </table>
                </div>
                <div class="d-flex gap-2 no-print">
                    <a class="btn btn-warning" href="index.php?page=admin/transaksi/edit&id=<?= e($data['id']) ?>"><i class="bi bi-pencil-square"></i> Edit</a>
                    <a class="btn btn-light" href="index.php?page=admin/transaksi">Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 no-print">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Ubah Status Laundry</h5></div>
            <div class="card-body">
                <form method="post" action="index.php?page=admin/transaksi/status">
                    <input type="hidden" name="id" value="<?= e($data['id']) ?>">
                    <input type="hidden" name="back" value="admin/transaksi/detail&id=<?= e($data['id']) ?>">
                    <select name="status" class="form-select mb-3">
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= e($status) ?>" <?= selected($data['status'], $status) ?>><?= e($status) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-primary w-100" type="submit">Simpan Status</button>
                </form>
            </div>
        </div>
    </div>
</div>
