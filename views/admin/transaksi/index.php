<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex flex-column flex-md-row gap-2 justify-content-between align-items-md-center">
        <h5 class="mb-0">Daftar Transaksi</h5>
        <div class="d-flex gap-2">
            <form class="d-flex" method="get">
                <input type="hidden" name="page" value="admin/transaksi">
                <input type="search" name="q" value="<?= e($keyword) ?>" class="form-control form-control-sm" placeholder="Cari kode/nama/status...">
            </form>
            <a href="index.php?page=admin/transaksi/create" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Kode</th><th>Pelanggan</th><th>Tgl Masuk</th><th>Estimasi</th><th>Status</th><th>Total</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php foreach ($transaksi as $row): ?>
                    <tr>
                        <td class="fw-semibold"><a class="text-decoration-none" href="index.php?page=admin/transaksi/detail&id=<?= e($row['id']) ?>"><?= e($row['kode_transaksi']) ?></a></td>
                        <td><?= e($row['nama_pelanggan']) ?></td>
                        <td><?= e($row['tanggal_masuk']) ?></td>
                        <td><?= e($row['tanggal_selesai_est']) ?></td>
                        <td>
                            <form method="post" action="index.php?page=admin/transaksi/status" class="d-flex gap-1 status-form">
                                <input type="hidden" name="id" value="<?= e($row['id']) ?>">
                                <input type="hidden" name="back" value="admin/transaksi">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?= e($status) ?>" <?= selected($row['status'], $status) ?>><?= e($status) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td><?= rupiah($row['total_harga']) ?></td>
                        <td class="text-nowrap">
                            <a class="btn btn-info btn-sm text-white" href="index.php?page=admin/transaksi/detail&id=<?= e($row['id']) ?>"><i class="bi bi-eye"></i></a>
                            <a class="btn btn-warning btn-sm" href="index.php?page=admin/transaksi/edit&id=<?= e($row['id']) ?>"><i class="bi bi-pencil-square"></i></a>
                            <a class="btn btn-danger btn-sm btn-delete" href="index.php?page=admin/transaksi/delete&id=<?= e($row['id']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$transaksi): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Data transaksi tidak ditemukan.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
