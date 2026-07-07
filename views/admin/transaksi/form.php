<?php
$postDetails = [];
if (is_post() && isset($_POST['layanan_id'])) {
    foreach ($_POST['layanan_id'] as $idx => $layananId) {
        $postDetails[] = ['layanan_id' => $layananId, 'berat' => $_POST['berat'][$idx] ?? ''];
    }
}
$rows = $postDetails ?: ($detail ?: [['layanan_id' => '', 'berat' => '']]);
?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0"><?= e($title) ?></h5></div>
    <div class="card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <form method="post" action="<?= e($action) ?>">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Pelanggan</label>
                    <?php $pelangganValue = old('pelanggan_id', $data['pelanggan_id'] ?? ''); ?>
                    <select name="pelanggan_id" class="form-select" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php foreach ($pelanggan as $p): ?>
                            <option value="<?= e($p['id']) ?>" <?= selected($pelangganValue, $p['id']) ?>><?= e($p['nama']) ?> - <?= e($p['no_hp']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control" value="<?= e(old('tanggal_masuk', $data['tanggal_masuk'] ?? date('Y-m-d'))) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estimasi Selesai</label>
                    <input type="date" name="tanggal_selesai_est" class="form-control" value="<?= e(old('tanggal_selesai_est', $data['tanggal_selesai_est'] ?? date('Y-m-d', strtotime('+2 day')))) ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <?php $statusValue = old('status', $data['status'] ?? 'Menunggu'); ?>
                    <select name="status" class="form-select" required>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= e($status) ?>" <?= selected($statusValue, $status) ?>><?= e($status) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Detail Layanan</h6>
                <button type="button" class="btn btn-outline-primary btn-sm" id="add-row"><i class="bi bi-plus-lg"></i> Tambah Baris</button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="detail-table">
                    <thead class="table-light"><tr><th style="width:55%">Layanan</th><th>Berat/Jumlah</th><th style="width:90px">Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td>
                                <select name="layanan_id[]" class="form-select layanan-select" required>
                                    <option value="">-- Pilih Layanan --</option>
                                    <?php foreach ($layanan as $l): ?>
                                        <option value="<?= e($l['id']) ?>" data-harga="<?= e($l['harga']) ?>" <?= selected($row['layanan_id'] ?? '', $l['id']) ?>><?= e($l['nama_layanan']) ?> - <?= rupiah($l['harga']) ?>/<?= e($l['satuan']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" step="0.01" min="0.1" name="berat[]" class="form-control berat-input" value="<?= e($row['berat'] ?? '') ?>" required></td>
                            <td><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="bi bi-x-lg"></i></button></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="alert alert-info">Total dihitung otomatis di backend berdasarkan harga layanan aktif agar data tidak bisa dimanipulasi dari browser.</div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Simpan Transaksi</button>
                <a class="btn btn-light" href="index.php?page=admin/transaksi">Kembali</a>
            </div>
        </form>
    </div>
</div>
