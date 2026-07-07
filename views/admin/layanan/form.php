<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0"><?= e($title) ?></h5></div>
    <div class="card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <form method="post" action="<?= e($action) ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Layanan</label>
                    <input type="text" name="nama_layanan" class="form-control" value="<?= e(old('nama_layanan', $data['nama_layanan'] ?? '')) ?>" placeholder="Cuci Kering" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Satuan</label>
                    <select name="satuan" class="form-select" required>
                        <?php $satuanValue = old('satuan', $data['satuan'] ?? 'kg'); ?>
                        <option value="kg" <?= selected($satuanValue, 'kg') ?>>kg</option>
                        <option value="item" <?= selected($satuanValue, 'item') ?>>item</option>
                        <option value="pasang" <?= selected($satuanValue, 'pasang') ?>>pasang</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Harga</label>
                    <input type="number" step="0.01" min="0" name="harga" class="form-control" value="<?= e(old('harga', $data['harga'] ?? '')) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estimasi Hari</label>
                    <input type="number" min="1" name="estimasi_hari" class="form-control" value="<?= e(old('estimasi_hari', $data['estimasi_hari'] ?? 2)) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <?php $statusValue = old('status', $data['status'] ?? 'Aktif'); ?>
                    <select name="status" class="form-select" required>
                        <option value="Aktif" <?= selected($statusValue, 'Aktif') ?>>Aktif</option>
                        <option value="Nonaktif" <?= selected($statusValue, 'Nonaktif') ?>>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Simpan</button>
                <a class="btn btn-light" href="index.php?page=admin/layanan">Kembali</a>
            </div>
        </form>
    </div>
</div>
