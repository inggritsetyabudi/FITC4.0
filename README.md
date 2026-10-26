# Posyandu Pintar

Aplikasi pencatatan data balita, pengukuran bulanan, jadwal posyandu, dan laporan desa.

Frontend menggunakan React, TypeScript, Vite, dan Tailwind CSS. API berada di folder `api/` dan menggunakan PHP dengan MySQL.

## Menjalankan frontend

```sh
npm ci
npm run dev
```

Vite berjalan di `http://localhost:5173`. Permintaan ke `/api` diteruskan ke `http://127.0.0.1:8000`.

```sh
npm run build
npm run preview
```

## Menjalankan API

Aktifkan MySQL, lalu jalankan:

```sh
php -S 127.0.0.1:8000 -t api api/index.php
```

Koneksi database menggunakan variabel lingkungan `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, dan `DB_PASS`. Nilai bawaan: `127.0.0.1:3306`, database `posyandu_pintar`, pengguna `root`, tanpa kata sandi. Tabel dan data awal dibuat saat koneksi pertama.

## Penggunaan

- **Kader:** data balita dan pencatatan pengukuran.
- **Bidan:** tinjauan balita prioritas dan rekap pengukuran.
- **Admin:** petugas, jadwal, dan laporan.

Halaman login menyediakan pilihan akun demo. Jika API tidak tersedia, aplikasi menggunakan data demo di localStorage. Sejumlah aksi pengelolaan akun masih berupa simulasi.

Hasil kalkulator KMS diperbarui setelah formulir dikirim. Perhitungannya masih berupa estimasi sederhana.

## Struktur

```text
src/pages/              Halaman aplikasi
src/components/         Layout dan komponen bersama
src/context/            State autentikasi
src/services/api.ts     Akses API dan penyimpanan lokal
src/types/              Tipe data
src/utils/              Helper dan data demo
src/assets/fonts/       Font dan ikon lokal
public/images/          Gambar yang digunakan aplikasi
api/                    Router, konfigurasi, dan model PHP
```

Lisensi Inter dan Bootstrap Icons disertakan bersama asetnya.
