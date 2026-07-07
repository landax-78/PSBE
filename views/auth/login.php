<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - CleanWash</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="login-body">
<div class="container">
    <div class="row min-vh-100 align-items-center justify-content-center">
        <div class="col-lg-10">
            <div class="login-card row g-0 overflow-hidden">
                <div class="col-lg-6 login-hero d-none d-lg-flex flex-column justify-content-between">
                    <div>
                        <div class="brand-icon big mb-3"><i class="bi bi-droplet-half"></i></div>
                        <h1>CleanWash</h1>
                        <p>Sistem Informasi Laundry berbasis web untuk mengelola pelanggan, layanan, transaksi, status cucian, dan laporan.</p>
                    </div>
                    <div class="small opacity-75">PHP Native OOP + MySQL + Bootstrap 5</div>
                </div>
                <div class="col-lg-6 bg-white p-4 p-md-5">
                    <h3 class="fw-bold mb-1">Masuk Akun</h3>
                    <p class="text-muted mb-4">Masuk menggunakan username atau email yang sudah terdaftar.</p>
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
                    <form method="post" action="index.php?page=login" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label">Username / Email</label>
                            <input type="text" name="identity" class="form-control form-control-lg" value="<?= e(old('identity')) ?>" placeholder="Masukkan username atau email" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Masukkan password" required>
                        </div>
                        <button class="btn btn-primary btn-lg w-100" type="submit"><i class="bi bi-box-arrow-in-right me-2"></i>Login</button>
                    </form>

                    <div class="auth-switch mt-4 text-center">
                        Belum punya akun pelanggan?
                        <a href="index.php?page=register" class="fw-semibold text-decoration-none">Daftar sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
