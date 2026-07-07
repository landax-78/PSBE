<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0"><?= e($title) ?></h5></div>
    <div class="card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <form method="post" action="<?= e($action) ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Pelanggan</label>
                    <input type="text" name="nama" class="form-control" value="<?= e(old('nama', $data['nama'] ?? '')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= e(old('no_hp', $data['no_hp'] ?? '')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= e(old('username', $data['username'] ?? '')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= e(old('email', $data['email'] ?? '')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password <?= $data ? '<small class="text-muted">(kosongkan jika tidak diganti)</small>' : '<small class="text-muted">(kosong = password123)</small>' ?></label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" required><?= e(old('alamat', $data['alamat'] ?? '')) ?></textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Simpan</button>
                <a class="btn btn-light" href="index.php?page=admin/pelanggan">Kembali</a>
            </div>
        </form>
    </div>
</div>
