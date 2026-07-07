# Ringkasan Kesesuaian Ketentuan UAS

| Ketentuan | Implementasi |
|---|---|
| PHP Native 8+ | Ya, tanpa framework |
| MySQL | Ya, database `db_laundry` |
| OOP | Ya, model dan controller berbasis class |
| Prepared Statement | Ya, semua query model memakai `prepare()` dan `bind_param()` jika ada parameter |
| Session Login | Ya, class `Auth` menggunakan `$_SESSION` |
| Role Admin dan Pelanggan | Ya, pengecekan role di controller |
| CRUD Pelanggan | Ya |
| CRUD Layanan | Ya |
| CRUD Transaksi | Ya, termasuk detail transaksi multi-layanan |
| Update Status Laundry | Ya |
| Laporan | Ya, filter periode dan cetak |
| Dashboard Admin | Ya |
| Dashboard Pelanggan | Ya |
| Status Laundry Pelanggan | Ya |
| Riwayat Laundry Pelanggan | Ya |
| Edit Profil Pelanggan | Ya |
| Validasi Data Kosong | Ya |
| Validasi Login Salah | Ya |
| Registrasi Pelanggan | Ya |
| Email Unik | Ya |
| Harga Angka | Ya |
| Berat Angka | Ya |
| Anti SQL Injection | Ya, prepared statement |
| Tampilan Bootstrap 5 | Ya, modern dan responsif |

## Akun Awal

Akun admin awal: `admin` dengan password `password123`. Pelanggan dapat membuat akun sendiri lewat halaman registrasi.


## Cara Menjalankan

1. Letakkan folder `Laundry` ke `C:\xampp\htdocs\Laundry`.
2. Jalankan Apache dan MySQL.
3. Import database lewat CMD XAMPP dengan perintah: `mysql -u root < C:\xampp\htdocs\Laundry\database\laundry.sql`.
4. Buka `http://localhost/Laundry`.
