# Sistem Informasi Laundry CleanWash

Project UAS Praktikum Pemrograman Web Back-End menggunakan PHP Native 8+, OOP, MySQL, mysqli Prepared Statement, Session Login, Bootstrap 5.

## Folder
Letakkan folder ini di:

```text
C:\xampp\htdocs\Laundry
```

## Database
Nama database: `db_laundry`

Database tidak dibuat otomatis. Jalankan SQL secara manual lewat XAMPP Shell/CMD.

Buka XAMPP Shell:

```cmd
mysql -u root
```

Lalu paste script SQL yang ada pada file:

```text
Laundry/database/laundry.sql
```

Atau import langsung:

```cmd
mysql -u root < C:\xampp\htdocs\Laundry\database\laundry.sql
```

## Jalankan Aplikasi

```text
http://localhost/Laundry
```

## Akun Awal

Admin awal:

```text
username: admin
password: password123
```

Pelanggan dapat membuat akun sendiri melalui menu daftar/register. Admin juga dapat mengubah role user melalui menu Kelola User.

## Fitur

- Login, logout, session, role admin/pelanggan
- Register akun pelanggan
- Admin dashboard
- CRUD pelanggan
- CRUD layanan
- CRUD transaksi dan detail transaksi
- Update status laundry
- Laporan transaksi
- Kelola user: ubah pelanggan menjadi admin atau admin menjadi pelanggan jika valid
- Pelanggan dashboard, status laundry, riwayat, edit profil

## Keamanan

- Prepared Statement mysqli
- Password hash menggunakan `password_hash()`
- Verifikasi password menggunakan `password_verify()`
- Validasi data kosong, email unik, angka harga/berat, dan hak akses role
