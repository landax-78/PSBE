<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-shield-lock"></i></div>
            <div>
                <div class="stat-number"><?= e((string)$totalAdmin) ?></div>
                <div class="stat-label">Total Admin</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-person-heart"></i></div>
            <div>
                <div class="stat-number"><?= e((string)$totalPelanggan) ?></div>
                <div class="stat-label">Total Pelanggan</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between gap-2 align-items-md-center">
        <div>
            <h5 class="mb-1">Kelola Hak Akses User</h5>
            <small class="text-muted">Admin dapat menaikkan pelanggan menjadi admin atau mengembalikan admin menjadi pelanggan jika profil pelanggan tersedia.</small>
        </div>
        <a href="index.php?page=admin/pelanggan/create" class="btn btn-primary btn-sm"><i class="bi bi-person-plus"></i> Tambah Pelanggan</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Nama Pelanggan</th>
                        <th>Role Saat Ini</th>
                        <th width="260">Ubah Role</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $i => $row): ?>
                    <?php $isSelf = (int)$row['id'] === (int)($_SESSION['user']['id'] ?? 0); ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td class="fw-semibold">
                            <?= e($row['username']) ?>
                            <?php if ($isSelf): ?>
                                <span class="badge text-bg-info ms-1">Akun Anda</span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($row['email']) ?></td>
                        <td>
                            <?= e($row['nama'] ?: '-') ?>
                            <?php if (!$row['pelanggan_id']): ?>
                                <div class="small text-muted">Tidak memiliki profil pelanggan</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= $row['role'] === 'admin' ? 'text-bg-primary' : 'text-bg-secondary' ?> text-capitalize">
                                <?= e($row['role']) ?>
                            </span>
                        </td>
                        <td>
                            <form method="post" action="index.php?page=admin/users/role" class="d-flex gap-2 align-items-center" onsubmit="return confirm('Yakin ingin mengubah role user ini?')">
                                <input type="hidden" name="id" value="<?= e((string)$row['id']) ?>">
                                <select name="role" class="form-select form-select-sm" <?= $isSelf ? 'disabled' : '' ?>>
                                    <option value="pelanggan" <?= selected($row['role'], 'pelanggan') ?>>Pelanggan</option>
                                    <option value="admin" <?= selected($row['role'], 'admin') ?>>Admin</option>
                                </select>
                                <button class="btn btn-success btn-sm" type="submit" <?= $isSelf ? 'disabled' : '' ?>>Simpan</button>
                            </form>
                            <?php if ($isSelf): ?>
                                <div class="small text-muted mt-1">Role akun sendiri tidak bisa diubah.</div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$users): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada user.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
