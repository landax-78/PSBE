<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex flex-column flex-md-row gap-2 justify-content-between align-items-md-center">
        <h5 class="mb-0">Daftar Layanan Laundry</h5>
        <div class="d-flex gap-2">
            <form class="d-flex" method="get">
                <input type="hidden" name="page" value="admin/layanan">
                <input type="search" name="q" value="<?= e($keyword) ?>" class="form-control form-control-sm" placeholder="Cari layanan...">
            </form>
            <a href="index.php?page=admin/layanan/create" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>No</th><th>Nama Layanan</th><th>Satuan</th><th>Harga</th><th>Estimasi</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php foreach ($layanan as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td class="fw-semibold"><?= e($row['nama_layanan']) ?></td>
                        <td><?= e($row['satuan']) ?></td>
                        <td><?= rupiah($row['harga']) ?></td>
                        <td><?= e($row['estimasi_hari']) ?> hari</td>
                        <td><span class="badge <?= $row['status']==='Aktif'?'text-bg-success':'text-bg-secondary' ?>"><?= e($row['status']) ?></span></td>
                        <td class="text-nowrap">
                            <a class="btn btn-warning btn-sm" href="index.php?page=admin/layanan/edit&id=<?= e($row['id']) ?>"><i class="bi bi-pencil-square"></i></a>
                            <a class="btn btn-danger btn-sm btn-delete" href="index.php?page=admin/layanan/delete&id=<?= e($row['id']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$layanan): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Data tidak ditemukan.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
