import type { Balita, Pengukuran, User, Berita, Jadwal } from '../types';

export const INITIAL_USERS: User[] = [
  {
    id: 1,
    nama: 'Sari Rahmawati',
    username: 'kader',
    role: 'kader',
    dusun_binaan: 'Gamping Wetan',
    telepon: '0812-3456-7890'
  },
  {
    id: 2,
    nama: 'Rina Wulandari',
    username: 'kader2',
    role: 'kader',
    dusun_binaan: 'Boharan',
    telepon: '0812-3456-7891'
  },
  {
    id: 3,
    nama: 'Ayu Lestari',
    username: 'bidan',
    role: 'bidan',
    dusun_binaan: null,
    telepon: '0812-3456-7892'
  },
  {
    id: 4,
    nama: 'Administrator Desa Sukatani',
    username: 'admin',
    role: 'admin',
    dusun_binaan: null,
    telepon: '0819-0011-2233'
  }
];

export const INITIAL_BALITA: Balita[] = [
  {
    id: 1,
    nama: 'Dinda Aulia',
    nik: '3515085408240001',
    tanggal_lahir: '2024-05-18',
    jenis_kelamin: 'P',
    nama_ortu: 'Siti Aminah',
    dusun: 'Gamping Wetan',
    status: 'aktif',
    status_terakhir: 'pemantauan',
    berat_terakhir: 10.8,
    tinggi_terakhir: 83.1,
    tanggal_ukur_terakhir: '2026-08-18'
  },
  {
    id: 2,
    nama: 'Farel Aditya',
    nik: '3515085408240002',
    tanggal_lahir: '2023-11-15',
    jenis_kelamin: 'L',
    nama_ortu: 'Rina Wulandari',
    dusun: 'Boharan',
    status: 'aktif',
    status_terakhir: 'prioritas',
    berat_terakhir: 11.1,
    tinggi_terakhir: 86.4,
    tanggal_ukur_terakhir: '2026-08-17'
  },
  {
    id: 3,
    nama: 'Naila Putri',
    nik: '3515085408240003',
    tanggal_lahir: '2024-02-19',
    jenis_kelamin: 'P',
    nama_ortu: 'Dewi Lestari',
    dusun: 'Kemangsen',
    status: 'aktif',
    status_terakhir: 'normal',
    berat_terakhir: 11.5,
    tinggi_terakhir: 85.8,
    tanggal_ukur_terakhir: '2026-08-19'
  },
  {
    id: 4,
    nama: 'Raka Pratama',
    nik: '3515085408240004',
    tanggal_lahir: '2024-07-16',
    jenis_kelamin: 'L',
    nama_ortu: 'Andi Saputra',
    dusun: 'Bendomungal',
    status: 'aktif',
    status_terakhir: 'normal',
    berat_terakhir: 9.7,
    tinggi_terakhir: 79.8,
    tanggal_ukur_terakhir: '2026-08-16'
  },
  {
    id: 5,
    nama: 'Almira Safitri',
    nik: '3515085408240005',
    tanggal_lahir: '2023-08-12',
    jenis_kelamin: 'P',
    nama_ortu: 'Siti Nurhayati',
    dusun: 'Gamping Wetan',
    status: 'aktif',
    status_terakhir: 'pemantauan',
    berat_terakhir: 12.0,
    tinggi_terakhir: 88.0,
    tanggal_ukur_terakhir: '2026-08-12'
  }
];

export const INITIAL_PENGUKURAN: Pengukuran[] = [
  {
    id: 1,
    anak_id: 1,
    nama_anak: 'Dinda Aulia',
    dusun: 'Gamping Wetan',
    jenis_kelamin: 'P',
    nama_ortu: 'Siti Aminah',
    tanggal_ukur: '2026-04-18',
    usia_bulan: 23,
    berat_kg: 9.8,
    tinggi_cm: 80.5,
    kategori_status: 'normal',
    catatan: 'Pertumbuhan baik'
  },
  {
    id: 2,
    anak_id: 1,
    nama_anak: 'Dinda Aulia',
    dusun: 'Gamping Wetan',
    jenis_kelamin: 'P',
    nama_ortu: 'Siti Aminah',
    tanggal_ukur: '2026-05-18',
    usia_bulan: 24,
    berat_kg: 10.1,
    tinggi_cm: 81.3,
    kategori_status: 'normal',
    catatan: 'Nafsu makan bagus'
  },
  {
    id: 3,
    anak_id: 1,
    nama_anak: 'Dinda Aulia',
    dusun: 'Gamping Wetan',
    jenis_kelamin: 'P',
    nama_ortu: 'Siti Aminah',
    tanggal_ukur: '2026-06-18',
    usia_bulan: 25,
    berat_kg: 10.3,
    tinggi_cm: 82.0,
    kategori_status: 'normal',
    catatan: 'Kondisi stabil'
  },
  {
    id: 4,
    anak_id: 1,
    nama_anak: 'Dinda Aulia',
    dusun: 'Gamping Wetan',
    jenis_kelamin: 'P',
    nama_ortu: 'Siti Aminah',
    tanggal_ukur: '2026-07-18',
    usia_bulan: 26,
    berat_kg: 11.5,
    tinggi_cm: 82.8,
    kategori_status: 'normal',
    catatan: 'Pertumbuhan sesuai pemantauan'
  },
  {
    id: 5,
    anak_id: 1,
    nama_anak: 'Dinda Aulia',
    dusun: 'Gamping Wetan',
    jenis_kelamin: 'P',
    nama_ortu: 'Siti Aminah',
    tanggal_ukur: '2026-08-18',
    usia_bulan: 27,
    berat_kg: 10.8,
    tinggi_cm: 83.1,
    kategori_status: 'pemantauan',
    catatan: 'Nafsu makan agak berkurang sejak 2 minggu lalu.'
  },
  {
    id: 6,
    anak_id: 2,
    nama_anak: 'Farel Aditya',
    dusun: 'Boharan',
    jenis_kelamin: 'L',
    nama_ortu: 'Rina Wulandari',
    tanggal_ukur: '2026-08-17',
    usia_bulan: 33,
    berat_kg: 11.1,
    tinggi_cm: 86.4,
    kategori_status: 'prioritas',
    catatan: 'Kenaikan berat badan belum sesuai target.'
  },
  {
    id: 7,
    anak_id: 3,
    nama_anak: 'Naila Putri',
    dusun: 'Kemangsen',
    jenis_kelamin: 'P',
    nama_ortu: 'Dewi Lestari',
    tanggal_ukur: '2026-08-19',
    usia_bulan: 30,
    berat_kg: 11.5,
    tinggi_cm: 85.8,
    kategori_status: 'normal',
    catatan: 'Pertumbuhan sesuai pemantauan.'
  },
  {
    id: 8,
    anak_id: 4,
    nama_anak: 'Raka Pratama',
    dusun: 'Bendomungal',
    jenis_kelamin: 'L',
    nama_ortu: 'Andi Saputra',
    tanggal_ukur: '2026-08-16',
    usia_bulan: 25,
    berat_kg: 9.7,
    tinggi_cm: 79.8,
    kategori_status: 'normal',
    catatan: 'Aktif dan nafsu makan baik.'
  },
  {
    id: 9,
    anak_id: 5,
    nama_anak: 'Almira Safitri',
    dusun: 'Gamping Wetan',
    jenis_kelamin: 'P',
    nama_ortu: 'Siti Nurhayati',
    tanggal_ukur: '2026-08-12',
    usia_bulan: 36,
    berat_kg: 12.0,
    tinggi_cm: 88.0,
    kategori_status: 'pemantauan',
    catatan: 'Perlu pemantauan asupan makan.'
  }
];

export const INITIAL_BERITA: Berita[] = [
  {
    id: 1,
    judul: 'Vitamin A untuk Tumbuh Kembang Balita',
    kategori: 'PANDUAN POSYANDU',
    ringkasan: 'Vitamin A merupakan salah satu nutrisi penting untuk mendukung kesehatan mata, imunitas, dan proses tumbuh kembang balita.',
    isi: '<p>Vitamin A merupakan salah satu nutrisi penting untuk mendukung kesehatan mata, imunitas, dan proses tumbuh kembang balita. Kapsul vitamin A dibagikan gratis setiap bulan Februari dan Agustus di seluruh posyandu desa.</p>',
    gambar: '/images/desain-vitamin-a.png',
    published_at: '2026-08-10 08:00:00',
    penulis: 'Bidan Desa Sukatani',
    waktu_baca: '4 menit baca'
  },
  {
    id: 2,
    judul: 'Kunjungan Bidan ke Posyandu',
    kategori: 'KEGIATAN',
    ringkasan: 'Kegiatan kunjungan bidan dan pemantauan balita.',
    isi: '<p>Bidan Desa bersama Kader melaksanakan pemantauan langsung terhadap balita dengan perhatian khusus dan memberikan konseling langsung kepada orang tua.</p>',
    gambar: '/images/desain-kunjungan-bidan.png',
    published_at: '2026-08-05 09:00:00',
    penulis: 'Bidan Ayu Lestari',
    waktu_baca: '3 menit baca'
  },
  {
    id: 3,
    judul: 'PMT dan Gizi Seimbang',
    kategori: 'GIZI BALITA',
    ringkasan: 'Contoh pemberian makanan tambahan yang seimbang.',
    isi: '<p>Pemberian Makanan Tambahan (PMT) kaya protein hewani seperti telur, ayam, dan ikan sangat dianjurkan untuk mendukung kenaikan berat badan balita secara adekuat.</p>',
    gambar: '/images/desain-pmt-gizi.png',
    published_at: '2026-07-29 10:30:00',
    penulis: 'Tim Kesehatan Desa',
    waktu_baca: '3 menit baca'
  }
];

export const INITIAL_JADWAL: Jadwal[] = [
  {
    id: 1,
    nama_kegiatan: 'Penimbangan Balita Bulanan',
    dusun: 'Gamping Wetan',
    lokasi: 'Balai Dusun Gamping Wetan',
    tanggal: '2026-09-12',
    waktu_mulai: '08:00',
    waktu_selesai: '11:30',
    status: 'rencana',
    petugas: 'Bu Sari'
  },
  {
    id: 2,
    nama_kegiatan: 'Posyandu Melati',
    dusun: 'Kemangsen',
    lokasi: 'Balai Desa Kemangsen',
    tanggal: '2026-09-19',
    waktu_mulai: '08:00',
    waktu_selesai: '11:00',
    status: 'selesai',
    petugas: 'Bu Rina'
  }
];
