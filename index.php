<?php
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Auth.php';
require_once __DIR__ . '/models/Pelanggan.php';
require_once __DIR__ . '/models/Layanan.php';
require_once __DIR__ . '/models/Transaksi.php';
require_once __DIR__ . '/controllers/BaseController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/PelangganController.php';
require_once __DIR__ . '/controllers/LayananController.php';
require_once __DIR__ . '/controllers/TransaksiController.php';
require_once __DIR__ . '/controllers/LaporanController.php';
require_once __DIR__ . '/controllers/CustomerController.php';
require_once __DIR__ . '/controllers/UserController.php';

try {
    $db = (new Database())->getConnection();
    $userModel = new User($db);
    $auth = new Auth($userModel);
    $page = $_GET['page'] ?? 'login';

    match ($page) {
        'login' => (new AuthController($db, $auth))->login(),
        'register' => (new AuthController($db, $auth))->register(),
        'logout' => (new AuthController($db, $auth))->logout(),

        'admin/dashboard' => (new AdminController($db, $auth))->dashboard(),
        'admin/pelanggan' => (new PelangganController($db, $auth))->index(),
        'admin/pelanggan/create' => (new PelangganController($db, $auth))->create(),
        'admin/pelanggan/edit' => (new PelangganController($db, $auth))->edit(),
        'admin/pelanggan/delete' => (new PelangganController($db, $auth))->delete(),
        'admin/layanan' => (new LayananController($db, $auth))->index(),
        'admin/layanan/create' => (new LayananController($db, $auth))->create(),
        'admin/layanan/edit' => (new LayananController($db, $auth))->edit(),
        'admin/layanan/delete' => (new LayananController($db, $auth))->delete(),
        'admin/transaksi' => (new TransaksiController($db, $auth))->index(),
        'admin/transaksi/create' => (new TransaksiController($db, $auth))->create(),
        'admin/transaksi/edit' => (new TransaksiController($db, $auth))->edit(),
        'admin/transaksi/detail' => (new TransaksiController($db, $auth))->detail(),
        'admin/transaksi/status' => (new TransaksiController($db, $auth))->updateStatus(),
        'admin/transaksi/delete' => (new TransaksiController($db, $auth))->delete(),
        'admin/laporan' => (new LaporanController($db, $auth))->index(),
        'admin/users' => (new UserController($db, $auth))->index(),
        'admin/users/role' => (new UserController($db, $auth))->updateRole(),

        'pelanggan/dashboard' => (new CustomerController($db, $auth))->dashboard(),
        'pelanggan/status' => (new CustomerController($db, $auth))->status(),
        'pelanggan/riwayat' => (new CustomerController($db, $auth))->riwayat(),
        'pelanggan/profil' => (new CustomerController($db, $auth))->profil(),

        default => redirect($auth->check() ? ($_SESSION['user']['role'] === 'admin' ? 'admin/dashboard' : 'pelanggan/dashboard') : 'login'),
    };
} catch (Throwable $e) {
    http_response_code(500);
    $message = e($e->getMessage());
    ?>
    <!doctype html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kesalahan Aplikasi - CleanWash</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="fw-bold text-danger mb-3">Aplikasi belum bisa dijalankan</h3>
                            <p class="mb-3"><?= $message ?></p>
                            <div class="alert alert-info">
                                <strong>Solusi cepat:</strong> pastikan MySQL XAMPP aktif dan database <code>db_laundry</code> sudah dibuat dengan menjalankan script <code>database/laundry.sql</code> melalui CMD/Shell XAMPP.
                            </div>
                            <a href="README.md" class="btn btn-primary">Lihat README</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}
