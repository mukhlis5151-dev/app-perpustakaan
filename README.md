# App Perpustakaan

App Perpustakaan adalah aplikasi berbasis Laravel 12 yang digunakan untuk membantu pengelolaan perpustakaan digital kampus. Aplikasi ini akan digunakan untuk mengelola data buku, anggota, dan transaksi peminjaman.

## Tujuan Aplikasi

Aplikasi ini dibuat untuk mempermudah petugas atau admin dalam mengelola data perpustakaan secara terstruktur melalui aplikasi web.

## Cara Menjalankan Project Secara Lokal

1. Pastikan PHP, Composer, MySQL, dan Laragon sudah tersedia.
2. Jalankan Apache dan MySQL melalui Laragon.
3. Buka terminal pada folder project.
4. Jalankan perintah:

## Perbedaan Model, View, dan Controller

Model bertanggung jawab mengelola data dan aturan bisnis yang berkaitan dengan database. View bertanggung jawab menampilkan halaman yang dilihat oleh pengguna. Controller menerima request pengguna, memprosesnya melalui Model, kemudian mengirimkan hasilnya kepada View.

   ```bash
   php artisan serve