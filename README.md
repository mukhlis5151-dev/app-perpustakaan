# App Perpustakaan

Aplikasi web **Perpustakaan Digital Kampus** untuk mengelola kategori, buku, anggota, dan transaksi peminjaman. Dibuat dengan Laravel sebagai proyek praktikum (Pertemuan 1 sampai 10).

## Fitur

- Autentikasi (login dan logout) dengan dua role: **admin** dan **petugas**
- CRUD **buku**, **anggota**, dan **peminjaman** (satu transaksi dapat memuat beberapa buku)
- CRUD **kategori** (khusus admin)
- **Dashboard** statistik dan halaman **Laporan Peminjaman** yang mengambil data dari REST API milik aplikasi sendiri (`GET /api/stats` dan `GET /api/loans`)
- **REST API** berformat JSON untuk buku, anggota, peminjaman, dan statistik
- Seeder dan Factory untuk data demo bergaya Indonesia

## Teknologi

| Komponen | Versi |
|---|---|
| PHP | 8.3 atau lebih baru |
| Laravel | 13 |
| Database | MySQL |
| Dependency manager | Composer |
| Lingkungan lokal | Laragon (Windows) |

Node.js dan npm **tidak diperlukan**: tampilan memakai CSS sederhana di dalam layout Blade.

## Instalasi

Pastikan PHP, Composer, dan MySQL sudah tersedia (di Laragon: tekan **Start All**).

**1. Clone repository**

```bash
git clone https://github.com/mukhlis5151-dev/app-perpustakaan.git
cd app-perpustakaan
```

**2. Pasang dependency**

```bash
composer install
```

**3. Salin file environment dan buat application key**

```bash
copy .env.example .env
php artisan key:generate
```

Di macOS/Linux, ganti `copy` dengan `cp`.

**4. Buat database MySQL**

Buat database kosong bernama `db_perpustakaan` lewat HeidiSQL/phpMyAdmin, atau lewat terminal:

```bash
mysql -u root -e "CREATE DATABASE db_perpustakaan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**5. Atur `.env`**

Buka `.env`. Ubah blok database (di `.env.example` default-nya SQLite) menjadi:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan dua variabel ini juga ada di `.env`:

| Variabel | Nilai | Fungsi |
|---|---|---|
| `INTERNAL_API_URL` | `http://127.0.0.1:8011` | alamat server kedua yang dipanggil Dashboard dan Laporan untuk mengambil data dari API |
| `APP_FAKER_LOCALE` | `id_ID` | membuat data seeder bergaya Indonesia |

**6. Buat tabel dan isi data demo**

```bash
php artisan migrate:fresh --seed
```

Hasilnya: 3 user, 5 kategori, 20 buku, 15 anggota, dan 10 transaksi peminjaman. Jalankan perintah ini **sebelum** membuka aplikasi di browser.

## Menjalankan Aplikasi

Aplikasi membutuhkan **dua server sekaligus**. Buka dua terminal di folder project:

**Terminal 1, untuk browser:**

```bash
php artisan serve --port=8000
```

**Terminal 2, khusus panggilan API internal:**

```bash
php artisan serve --port=8011
```

Lalu buka `http://127.0.0.1:8000`.

**Kenapa dua server?** `php artisan serve` hanya memproses satu request pada satu waktu. Kalau Dashboard memanggil `/api/stats` ke port yang sama, request kedua itu menunggu request pertama selesai, sementara request pertama menunggu balasan request kedua (deadlock). Server kedua di port 8011 menghindarinya. Kalau server kedua mati, halaman Dashboard dan Laporan tetap tampil, tetapi berisi angka 0 dan tabel kosong.

## Akun Demo

Dibuat otomatis oleh `UserSeeder`:

| Nama | Email | Password | Role |
|---|---|---|---|
| Bambang Sutrisno | `admin@pens.ac.id` | `password` | admin |
| Siti Rahmawati | `petugas1@pens.ac.id` | `password` | petugas |
| Ahmad Fauzi | `petugas2@pens.ac.id` | `password` | petugas |

Menu **Kategori** hanya tampil untuk role admin.

## Endpoint REST API

Base URL: `http://127.0.0.1:8000/api`. Semua balasan berformat JSON. API ini belum memakai autentikasi (hanya untuk keperluan pembelajaran).

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/books` | daftar buku (10 per halaman; filter `?judul=` dan `?category_id=`) |
| GET | `/books/{id}` | detail satu buku |
| POST | `/books` | tambah buku |
| PUT | `/books/{id}` | ubah buku |
| DELETE | `/books/{id}` | hapus buku |
| GET | `/members` | daftar anggota (10 per halaman) |
| GET | `/members/{id}` | detail satu anggota |
| GET | `/loans` | daftar peminjaman beserta anggota, petugas, dan buku |
| POST | `/loans` | buat transaksi peminjaman (`member_id`, `user_id`, `tanggal_pinjam`, `tanggal_kembali`, `book_ids[]`) |
| PUT | `/loans/{id}/kembalikan` | tandai peminjaman sebagai dikembalikan |
| GET | `/stats` | total buku, total anggota, dan peminjaman aktif |

Contoh:

```bash
curl http://127.0.0.1:8000/api/stats
curl "http://127.0.0.1:8000/api/books?judul=laskar"
```

## Pemecahan Masalah

| Gejala | Penyebab dan solusi |
|---|---|
| Dashboard menampilkan angka 0 | server port 8011 belum dijalankan, atau `INTERNAL_API_URL` salah. Jalankan `php artisan config:clear` setelah mengubah `.env` |
| Error `could not find driver` atau `Connection refused` | MySQL belum menyala, atau pengaturan `DB_*` di `.env` belum benar |
| Nama pada data demo bergaya Barat | `APP_FAKER_LOCALE` belum `id_ID` saat seeding. Ubah `.env`, lalu ulangi `php artisan migrate:fresh --seed` |
| Halaman error `View ... not found` setelah mengubah file | jalankan `php artisan view:clear` |

## Perbedaan Model, View, dan Controller

Model bertanggung jawab mengelola data dan aturan bisnis yang berkaitan dengan database. View bertanggung jawab menampilkan halaman yang dilihat oleh pengguna. Controller menerima request pengguna, memprosesnya melalui Model, kemudian mengirimkan hasilnya kepada View.
