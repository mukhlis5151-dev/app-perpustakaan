# Skenario Demo App Perpustakaan

Dokumen ini berisi urutan langkah demo aplikasi, dari login sampai demo API di Postman. Perkiraan durasi: 10 sampai 12 menit.

## Persiapan Sebelum Demo

Lakukan semuanya sebelum presentasi dimulai:

1. Nyalakan **MySQL** (Laragon: Start All).
2. Reset data demo: `php artisan migrate:fresh --seed`
3. Nyalakan dua server di dua terminal:
   - Terminal 1: `php artisan serve --port=8000`
   - Terminal 2: `php artisan serve --port=8011`
4. Buka browser (disarankan jendela incognito) di `http://127.0.0.1:8000/login`.
5. Buka Postman, collection `app-perpustakaan`. Di tab **Variables**, pastikan `base_url` = `http://127.0.0.1:8000/api` dan `user_id` = `1` (id admin setelah seeding).
6. Data awal setelah seeding: **20 buku, 15 anggota, 10 peminjaman, 5 kategori, 3 user**.

---

## Langkah 1: Login

- **Buka:** `http://127.0.0.1:8000/login`
- **Aksi:** isi Email `admin@pens.ac.id`, Password `password`, klik **Login**.
- **Hasil yang terlihat:** masuk ke halaman Dashboard; navbar menampilkan "Bambang Sutrisno (Admin)".
- **Poin penjelasan:** akses halaman dilindungi middleware `auth`; menu Kategori hanya muncul untuk role admin.

## Langkah 2: Tampilkan Dashboard

- **Buka:** `http://127.0.0.1:8000/`
- **Hasil yang terlihat:** tiga kartu: Total Buku **20**, Total Anggota **15**, Peminjaman Aktif **N** (angka acak hasil seeding; catat angka ini).
- **Poin penjelasan:** angka ini tidak dihitung langsung di controller. `DashboardController` memanggil `GET /api/stats` lewat Laravel HTTP Client ke server kedua (port 8011).

## Langkah 3: Tambah Kategori

- **Buka:** menu **Kategori** → **+ Tambah Kategori**.
- **Aksi:** isi Nama Kategori `Pemrograman`, Deskripsi `Buku seputar pemrograman dan pengembangan perangkat lunak.`, lalu simpan.
- **Hasil yang terlihat:** kembali ke daftar kategori; "Pemrograman" muncul sebagai kategori ke-6.
- **Poin penjelasan:** validasi di controller (`nama_kategori` wajib, maksimal 100 karakter).

## Langkah 4: Tambah Buku

- **Buka:** menu **Buku** → **+ Tambah Buku**.
- **Aksi:** isi
  - Judul: `Pemrograman Web dengan Laravel`
  - Penulis: `Tim Dosen Informatika`
  - Penerbit: `Penerbit Kampus`
  - Tahun terbit: `2026`
  - ISBN: `9786020000001`
  - Stok: `3`
  - Kategori: `Pemrograman`
  
  Lalu simpan.
- **Hasil yang terlihat:** buku baru tersimpan. Buku ini berada di **halaman 3** daftar buku (total 21 buku, 10 per halaman); klik angka **3** di pagination untuk menunjukkannya.
- **Poin penjelasan:** validasi lewat `StoreBookRequest` (Form Request); pagination memakai view kustom.

## Langkah 5: Tambah Anggota

- **Buka:** menu **Anggota** → **+ Tambah Anggota**.
- **Aksi:** isi
  - Nama: `Rina Aprilia`
  - NIM: `3124500101`
  - Email: `rina.aprilia@pens.ac.id`
  - Nomor telepon: `081234567890`
  - Alamat: `Surabaya`
  - Status: `aktif`
  
  Lalu simpan.
- **Hasil yang terlihat:** anggota baru tersimpan (total 16 anggota; muncul di halaman 2).
- **Poin penjelasan:** NIM dan email harus unik (aturan `unique` di validasi).

## Langkah 6: Buat Peminjaman

- **Buka:** menu **Peminjaman** → **+ Tambah Peminjaman**.
- **Aksi:** isi
  - Anggota: `Rina Aprilia (3124500101)`
  - Tanggal pinjam: hari ini
  - Tanggal kembali: 7 hari setelah hari ini (contoh: pinjam 10 November 2026, kembali 17 November 2026)
  - Buku: centang `Pemrograman Web dengan Laravel` dan satu buku lain dari daftar
  
  Lalu klik **Simpan**.
- **Hasil yang terlihat:** pesan "Transaksi peminjaman berhasil dibuat." di halaman daftar peminjaman.
- **Poin penjelasan:** petugas pencatat diambil otomatis dari akun yang login (Bambang Sutrisno). Satu transaksi bisa memuat beberapa buku (tabel `loan_items`).

## Langkah 7: Lihat Daftar Peminjaman Aktif

- **Buka:** menu **Peminjaman**.
- **Aksi:** karena sudah ada 11 transaksi dan satu halaman memuat 10, klik halaman **2** di pagination. Transaksi baru (ID 11) berstatus **Dipinjam**.
- **Cek tambahan:** kembali ke **Dashboard**. Peminjaman Aktif sekarang **N + 1**.
- **Poin penjelasan:** peminjaman aktif adalah transaksi dengan status "Dipinjam".

## Langkah 8: Proses Pengembalian

- **Buka:** halaman 2 daftar peminjaman → pada transaksi baru, klik **Edit**.
- **Aksi:** ubah **Status** menjadi **Dikembalikan**, klik **Perbarui**.
- **Hasil yang terlihat:** pesan "Transaksi peminjaman berhasil diperbarui."; status transaksi menjadi "Dikembalikan".
- **Cek tambahan:** Dashboard kembali menampilkan Peminjaman Aktif **N**.

## Langkah 9: Tampilkan Laporan

- **Buka:** menu **Laporan** (`/loans/report`).
- **Hasil yang terlihat:** tabel transaksi dengan kolom Anggota, Petugas, Buku, Tanggal, dan Status. Ada navigasi halaman ("Halaman 1 dari 2"); klik **Selanjutnya** untuk melihat transaksi Rina Aprilia berstatus "Dikembalikan".
- **Poin penjelasan:** halaman ini juga membaca data dari API (`GET /api/loans`), bukan query database langsung. Pagination dibangun dari `meta.current_page` dan `meta.last_page` pada respons API.

## Langkah 10: Demo API di Postman

Buka collection `app-perpustakaan`, jalankan request berikut satu per satu (tombol **Send**):

| Request | Yang ditunjukkan |
|---|---|
| `01 - List Books` | status 200, struktur `data`, `links`, `meta`; setiap buku memuat objek `category` |
| `04 - Create Book` | status **201** dan buku baru muncul |
| `05 - Create Book Invalid` | status **422** dengan pesan validasi per field |
| `08 - Book Not Found` | status **404** untuk id yang tidak ada |
| `11 - Create Loan` | status **201**, status transaksi `"dipinjam"` |
| `12 - Kembalikan Loan` | status 200, status berubah `"dikembalikan"` |
| `13 - Stats` | `total_buku` **22** dan `total_anggota` **16** |

Angka pada `13 - Stats`: 20 buku seeding + 1 dari demo web + 1 dari `04 - Create Book` di Postman = 22 buku; 15 anggota + 1 = 16.

- **Poin penjelasan:** data yang dibuat lewat web langsung terlihat di API, karena keduanya memakai database yang sama.

---

## Rencana Cadangan

| Masalah saat demo | Penanganan |
|---|---|
| Dashboard atau Laporan menampilkan 0 / kosong | server port 8011 belum jalan. Nyalakan `php artisan serve --port=8011`, lalu refresh |
| Postman `11 - Create Loan` gagal 422 | variabel `user_id` kosong. Isi `1` di tab Variables collection |
| Postman Tes `02 - Filter Judul` gagal | normal setelah `migrate:fresh --seed` (tes ini mencari judul "uji"). Lewati request ini |
| Perlu mengulang demo dari awal | `php artisan migrate:fresh --seed`, lalu login ulang |
