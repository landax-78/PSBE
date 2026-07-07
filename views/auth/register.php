<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - CleanWash</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="login-body">
<div class="container py-4">
    <div class="row min-vh-100 align-items-center justify-content-center">
        <div class="col-lg-10">
            <div class="login-card row g-0 overflow-hidden">
                <div class="col-lg-5 login-hero d-none d-lg-flex flex-column justify-content-between">
                    <div>
                        <div class="brand-icon big mb-3"><i class="bi bi-person-plus"></i></div>
                        <h1>Daftar</h1>
                        <p>Buat akun pelanggan untuk melihat status cucian, riwayat transaksi, dan mengubah profil secara mandiri.</p>
                    </div>
                    <div class="small opacity-75">CleanWash Laundry</div>
                </div>
                <div class="col-lg-7 bg-white p-4 p-md-5">
                    <h3 class="fw-bold mb-1">Buat Akun Pelanggan</h3>
                    <p class="text-muted mb-4">Isi data dengan benar untuk membuat akun baru.</p>
                    <?php flash(); ?>
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= e($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="index.php?page=register" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" value="<?= e(old('nama')) ?>" placeholder="Contoh: Rina Amelia" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="no_hp" class="form-control" value="<?= e(old('no_hp')) ?>" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="<?= e(old('username')) ?>" placeholder="Minimal 4 karakter" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= e(old('email')) ?>" placeholder="nama@email.com" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap" required><?= e(old('alamat')) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirm" class="form-control" placeholder="Ulangi password" required>
                            </div>
                        </div>

                        <button class="btn btn-primary btn-lg w-100" type="submit">
                            <i class="bi bi-person-check me-2"></i>Daftar Sekarang
                        </button>
                    </form>

                    <div class="auth-switch mt-4 text-center">
                        Sudah punya akun?
                        <a href="index.php?page=login" class="fw-semibold text-decoration-none">Login di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
