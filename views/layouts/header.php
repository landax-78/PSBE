<?php $user = $_SESSION['user'] ?? null; ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'CleanWash') ?> - Sistem Informasi Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="app-wrapper">
    <aside class="sidebar">
        <div class="brand mb-4">
            <div class="brand-icon"><i class="bi bi-droplet-half"></i></div>
            <div>
                <h5 class="mb-0">CleanWash</h5>
                <small>Sistem Laundry</small>
            </div>
        </div>

        <?php if (($user['role'] ?? '') === 'admin'): ?>
            <a class="nav-link <?= current_page()==='admin/dashboard'?'active':'' ?>" href="index.php?page=admin/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a class="nav-link <?= str_starts_with(current_page(),'admin/pelanggan')?'active':'' ?>" href="index.php?page=admin/pelanggan"><i class="bi bi-people"></i> Pelanggan</a>
            <a class="nav-link <?= str_starts_with(current_page(),'admin/layanan')?'active':'' ?>" href="index.php?page=admin/layanan"><i class="bi bi-bag-check"></i> Layanan</a>
            <a class="nav-link <?= str_starts_with(current_page(),'admin/transaksi')?'active':'' ?>" href="index.php?page=admin/transaksi"><i class="bi bi-receipt"></i> Transaksi</a>
            <a class="nav-link <?= str_starts_with(current_page(),'admin/laporan')?'active':'' ?>" href="index.php?page=admin/laporan"><i class="bi bi-file-earmark-bar-graph"></i> Laporan</a>
            <a class="nav-link <?= str_starts_with(current_page(),'admin/users')?'active':'' ?>" href="index.php?page=admin/users"><i class="bi bi-person-gear"></i> Kelola User</a>
        <?php else: ?>
            <a class="nav-link <?= current_page()==='pelanggan/dashboard'?'active':'' ?>" href="index.php?page=pelanggan/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a class="nav-link <?= current_page()==='pelanggan/status'?'active':'' ?>" href="index.php?page=pelanggan/status"><i class="bi bi-hourglass-split"></i> Status Laundry</a>
            <a class="nav-link <?= current_page()==='pelanggan/riwayat'?'active':'' ?>" href="index.php?page=pelanggan/riwayat"><i class="bi bi-clock-history"></i> Riwayat</a>
            <a class="nav-link <?= current_page()==='pelanggan/profil'?'active':'' ?>" href="index.php?page=pelanggan/profil"><i class="bi bi-person-circle"></i> Profil</a>
        <?php endif; ?>

        <div class="sidebar-footer">
            <a class="nav-link logout" href="index.php?page=logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </aside>

    <main class="main-content">
        <nav class="topbar mb-4">
            <div>
                <h4 class="page-title mb-0"><?= e($title ?? 'CleanWash') ?></h4>
                <small class="text-muted">Kelola data laundry secara cepat, aman, dan rapi.</small>
            </div>
            <div class="user-chip">
                <i class="bi bi-person-circle"></i>
                <span><?= e($user['username'] ?? 'User') ?></span>
                <span class="badge text-bg-primary text-capitalize"><?= e($user['role'] ?? '') ?></span>
            </div>
        </nav>
        <?php flash(); ?>
