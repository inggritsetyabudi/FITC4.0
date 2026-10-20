# Posyandu Pintar — Sistem Pencatatan Tumbuh Kembang Balita

**FITCOM 4.0 — Intelligent Home & Community**

> *Tumbuh mereka berubah setiap bulan. Catatannya jangan sampai tertinggal.*

Posyandu Pintar adalah aplikasi web fullstack untuk pencatatan tumbuh kembang balita dan skrining gizi berbasis aturan (*rule-based screening*) di posyandu desa. Menggantikan buku KMS fisik yang rentan tercecer dan sulit dibandingkan antar bulan.

Dibangun dengan **PHP Native (MVC)**, **MySQL**, **Bootstrap 5**, dan **Chart.js**.

---

## Fitur Utama

### Kader Posyandu
- **Dasbor tugas** — daftar balita yang *belum ditimbang bulan ini* langsung tampil di atas.
- **Pencatatan pengukuran cepat** — umur dihitung otomatis dari tanggal lahir, tanpa manual.
- **Skrining otomatis** — status gizi (*Normal / Perlu Pemantauan / Prioritas*) langsung muncul setelah input, disertai catatan penjelasan.
- **Deteksi anomali input** — peringatan bila berat/tinggi turun drastis dari bulan sebelumnya (misal salah ketik).

### Bidan Desa
- **Decision-first** — daftar balita prioritas tampil paling atas sebelum grafik analitik.
- **Analitik desa** — distribusi status gizi per dusun dan cakupan penimbangan 6 bulan.
- **Kurva pertumbuhan** — grafik garis BB/TB per balita ala buku KMS.
- **Rekap & unduh** — rekapitulasi bulanan dengan filter dusun, ekspor CSV dan PDF.

### Admin Desa
- **CRUD lengkap** — penduduk, balita, ibu hamil, lansia, posyandu, jadwal, kegiatan, user.
- **Laporan terpadu** — ringkasan sistem, unduh laporan PDF/CSV, backup data JSON.
- **Audit log** — pencatatan otomatis setiap aksi untuk transparansi.
- **Pengumuman** — notifikasi broadcast ke kader/bidan/warga.

### Publik
- Landing page edukasi + artikel kesehatan (*read-only*).

---

## Teknologi

| Bagian | Teknologi |
| :--- | :--- |
| Backend | PHP Native (arsitektur MVC tanpa framework) |
| Database | MySQL / MariaDB (PDO + prepared statements) |
| Frontend | Bootstrap 5, Chart.js, ikon Bootstrap |
| Server lokal | Laragon (Apache + MySQL) |

---

## Struktur Direktori

```
FITC4.0/
├── index.php            # Front controller & router (controller/action)
├── config/
│   ├── database.php     # Koneksi PDO (auto-create database)
│   ├── auth.php         # Session, RBAC (require_auth)
│   └── helpers.php      # base_url, hitung_umur, skrining & anomali
├── controllers/         # Auth, Beranda, Kader, Bidan, Admin, dll.
├── models/              # User, Anak, Pengukuran, Dusun, Berita, AdminModel
├── views/               # landingPage, kader, bidan, admin, shared, components
├── database/
│   └── posyandu_pintar.sql   # Schema + seed data
├── assets/              # CSS, gambar, JS komponen & mock data legacy
├── docs/                # design-system, ui-flow, mock-data, responsive
└── README.md
```

---

## Mulai Cepat

### 1. Siapkan Database
Import `database/posyandu_pintar.sql` lewat phpMyAdmin/DBeaver, atau:

```bash
mysql -u root < database/posyandu_pintar.sql
```

Database `posyandu_pintar`, 12 tabel, dan data demo akan terbuat otomatis.

### 2. Jalankan
Letakkan proyek di folder `www` Laragon dan aktifkan MySQL, lalu buka:

```
http://fitc4.0.test/
```

atau via port konfigurasi Laragon di `http://localhost/FITC4.0/`.

---

## Akun Demo

Kredensial di bawah (kata sandi semua: `123`):

| Peran | Nama Pengguna | Kredensial & Wilayah |
| :--- | :--- | :--- |
| **Kader** | `kader1` | Bu Sari — Dusun Gamping Wetan |
| **Bidan** | `bidan` | Bidan Siti — Desa Sukatani |
| **Admin** | `admin` | Pemerintah Desa Sukatani |

---

## Alur Demo Singkat

1. **Kader** masuk → pilih balita dari daftar *belum ditimbang* → input BB & TB → sistem menampilkan status skrining otomatis.
2. Ganti ke **Bidan** → daftar balita prioritas tampil di atas → cek grafik distribusi per dusun & cakupan penimbangan.
3. Buka **Rekap** → filter dusun → **Ekspor CSV / PDF**.
4. Masuk **Admin** → kelola data desa dari satu panel, cek **Audit Log**, unduh laporan.

---

## Skema Database

```
users ──┬── anak ──── pengukuran   (riwayat timbangan, kategori_status)
        └── berita                  (penulis)
warga    | ibu_hamil | lansia      (master data sasaran program)
posyandu_pos | jadwal_kegiatan      (operasional pos)
notifikasi | audit_logs | profil_desa
```

Semua status (role, kategori gizi, status kehamilan, dll.) dibatasi oleh `ENUM` sehingga nilai yang masuk selalu valid. Detail lengkap dan catatan desain ada di komentar file `database/posyandu_pintar.sql`.

---

## Kontributor
- **Rafi Ahmad Fairuz** — Developer FITCOM 4.0
- Dokumen desain UI: lihat folder `docs/`
