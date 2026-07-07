-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2026 at 10:47 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `layanan_id` int(11) NOT NULL,
  `berat` decimal(8,2) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id`, `transaksi_id`, `layanan_id`, `berat`, `harga`, `subtotal`, `created_at`) VALUES
(1, 1, 1, 5.00, 7000.00, 35000.00, '2026-07-06 08:42:34'),
(2, 2, 2, 5.00, 10000.00, 50000.00, '2026-07-06 08:42:34'),
(3, 3, 4, 3.00, 15000.00, 45000.00, '2026-07-06 08:42:34'),
(4, 4, 3, 6.00, 5000.00, 30000.00, '2026-07-06 08:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id` int(11) NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `satuan` varchar(20) NOT NULL DEFAULT 'kg',
  `harga` decimal(12,2) NOT NULL,
  `estimasi_hari` int(11) NOT NULL DEFAULT 2,
  `status` enum('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`id`, `nama_layanan`, `satuan`, `harga`, `estimasi_hari`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Cuci Kering', 'kg', 7000.00, 2, 'Aktif', '2026-07-06 08:42:22', '2026-07-06 08:42:22'),
(2, 'Cuci Setrika', 'kg', 10000.00, 2, 'Aktif', '2026-07-06 08:42:22', '2026-07-06 08:42:22'),
(3, 'Setrika Saja', 'kg', 5000.00, 1, 'Aktif', '2026-07-06 08:42:22', '2026-07-06 08:42:22'),
(4, 'Laundry Express', 'kg', 15000.00, 1, 'Aktif', '2026-07-06 08:42:22', '2026-07-06 08:42:22'),
(5, 'Cuci Bed Cover', 'item', 25000.00, 3, 'Aktif', '2026-07-06 08:42:22', '2026-07-06 08:42:22');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `user_id`, `nama`, `no_hp`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 2, 'Budi Santoso', '081234567890', 'Jl. Melati No. 10', '2026-07-06 08:42:15', '2026-07-06 08:42:15'),
(2, 3, 'Siti Aminah', '082233445566', 'Jl. Kenanga No. 15', '2026-07-06 08:42:15', '2026-07-06 08:42:15'),
(3, 4, 'Andi Pratama', '083344556677', 'Jl. Mawar No. 21', '2026-07-06 08:42:15', '2026-07-06 08:42:15'),
(4, 5, 'ISMAIL WAHYU GINANJAR', '08895813706', 'Jl. Siluk', '2026-07-06 08:44:16', '2026-07-06 08:44:16');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `kode_transaksi` varchar(30) NOT NULL,
  `pelanggan_id` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `tanggal_selesai_est` date NOT NULL,
  `status` enum('Menunggu','Dicuci','Dikeringkan','Disetrika','Selesai','Diambil') NOT NULL DEFAULT 'Menunggu',
  `total_harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `kode_transaksi`, `pelanggan_id`, `tanggal_masuk`, `tanggal_selesai_est`, `status`, `total_harga`, `created_at`, `updated_at`) VALUES
(1, 'CW-20260701-0001', 1, '2026-07-01', '2026-07-03', 'Selesai', 35000.00, '2026-07-06 08:42:28', '2026-07-06 08:42:28'),
(2, 'CW-20260702-0001', 2, '2026-07-02', '2026-07-04', 'Dicuci', 50000.00, '2026-07-06 08:42:28', '2026-07-06 08:42:28'),
(3, 'CW-20260703-0001', 3, '2026-07-03', '2026-07-04', 'Dikeringkan', 45000.00, '2026-07-06 08:42:28', '2026-07-06 08:42:28'),
(4, 'CW-20260704-0001', 1, '2026-07-04', '2026-07-06', 'Menunggu', 30000.00, '2026-07-06 08:42:28', '2026-07-06 08:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pelanggan') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@cleanwash.test', '$2y$12$Pkshh5eiDpQEsRzZ5oiHWukFLlReOEKhrNP5fMue8U.d/0YOibxeW', 'admin', '2026-07-06 08:42:05', '2026-07-06 08:42:05'),
(2, 'budi', 'budi@example.com', '$2y$12$Pkshh5eiDpQEsRzZ5oiHWukFLlReOEKhrNP5fMue8U.d/0YOibxeW', 'pelanggan', '2026-07-06 08:42:05', '2026-07-06 08:42:05'),
(3, 'siti', 'siti@example.com', '$2y$12$Pkshh5eiDpQEsRzZ5oiHWukFLlReOEKhrNP5fMue8U.d/0YOibxeW', 'pelanggan', '2026-07-06 08:42:05', '2026-07-06 08:42:05'),
(4, 'andi', 'andi@example.com', '$2y$12$Pkshh5eiDpQEsRzZ5oiHWukFLlReOEKhrNP5fMue8U.d/0YOibxeW', 'pelanggan', '2026-07-06 08:42:05', '2026-07-06 08:42:05'),
(5, 'wahyu', 'wahyu@gmail.com', '$2y$10$0Ui52.Fl.Ktbj7tukjLM9.I8n2YR7nwtKSD3ZDh8BI4sSrXPtB2qi', 'admin', '2026-07-06 08:44:16', '2026-07-06 08:46:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detail_transaksi` (`transaksi_id`),
  ADD KEY `fk_detail_layanan` (`layanan_id`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  ADD KEY `fk_transaksi_pelanggan` (`pelanggan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `fk_detail_layanan` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD CONSTRAINT `fk_pelanggan_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_transaksi_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
