-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 20, 2026 at 05:21 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `posyandu_pintar`
--

-- --------------------------------------------------------

--
-- Table structure for table `anak`
--

CREATE TABLE `anak` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` char(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ortu` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dusun` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kader_id` int UNSIGNED DEFAULT NULL COMMENT 'kader pembina, FK -> users',
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anak`
--

INSERT INTO `anak` (`id`, `nama`, `nik`, `tanggal_lahir`, `jenis_kelamin`, `nama_ortu`, `dusun`, `kader_id`, `status`) VALUES
(1, 'Dinda Aulia', '3509124401010001', '2024-07-15', 'P', 'Rina Wulandari', 'Gamping Wetan', 3, 'aktif'),
(2, 'Farel Aditya', '3509124402010002', '2023-03-10', 'L', 'Bagus Prayogo', 'Gamping Wetan', 3, 'aktif'),
(3, 'Aisyah Putri', '3509124403010003', '2024-01-22', 'P', 'Ahmad Fauzi', 'Boharan', 4, 'aktif'),
(4, 'Rafif Rahman', '3509124404010004', '2023-11-05', 'L', 'Yuni Astuti', 'Kemangsen', NULL, 'aktif'),
(5, 'Kirana Ayu', '3509124405010005', '2024-09-30', 'P', 'Sugeng Riyadi', 'Bendomungal', NULL, 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int UNSIGNED NOT NULL,
  `user_nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'create / update / delete / export',
  `target_modul` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'warga / anak / pengukuran / ...',
  `detail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int UNSIGNED NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mis. PANDUAN POSYANDU, GIZI BALITA',
  `ringkasan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'path asset hasil upload_asset()',
  `waktu_baca` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '4 menit baca',
  `status` enum('published','draft') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `published_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int UNSIGNED DEFAULT NULL COMMENT 'FK -> users'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `kategori`, `ringkasan`, `isi`, `gambar`, `waktu_baca`, `status`, `published_at`, `created_by`) VALUES
(1, 'Kenapa penimbangan balita perlu dilakukan rutin setiap bulan?', 'PANDUAN POSYANDU', 'Penimbangan rutin adalah kunci utama mendeteksi perlambatan pertumbuhan sebelum anak mengalami risiko gizi kurang atau stunting.', '<p>Pertumbuhan balita pada 1.000 Hari Pertama Kehidupan berlangsung sangat pesat. Kenaikan berat yang mendatar atau turun 1-2 bulan berturut-turut adalah tanda awal gangguan gizi yang harus ditindaklanjuti kader dan bidan desa.</p>', 'assets/images/hero_timbang.jpg', '5 menit baca', 'published', '2026-08-24 09:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ibu_hamil`
--

CREATE TABLE `ibu_hamil` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` char(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_suami` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dusun` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usia_kehamilan_minggu` smallint UNSIGNED NOT NULL DEFAULT '12',
  `hpl` date NOT NULL COMMENT 'hari perkiraan lahir',
  `status_kehamilan` enum('sehat','resiko_tinggi','kek') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sehat',
  `catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ibu_hamil`
--

INSERT INTO `ibu_hamil` (`id`, `nama`, `nik`, `nama_suami`, `dusun`, `usia_kehamilan_minggu`, `hpl`, `status_kehamilan`, `catatan`, `status`) VALUES
(1, 'Nurhasanah', '3509124407010007', 'Joko Santoso', 'Boharan', 28, '2026-12-20', 'sehat', NULL, 'aktif'),
(2, 'Wulan Sari', '3509124407110010', 'Agus Setiawan', 'Bendomungal', 20, '2027-02-14', 'sehat', 'PMT pemulihan dikirim bulan ini', 'aktif'),
(3, 'Endang Kusmiati', '3509124407120011', 'Slamet Riyadi', 'Kemangsen', 32, '2026-11-30', 'resiko_tinggi', 'Tensi 140/90, kontrol ke Puskesmas', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_kegiatan`
--

CREATE TABLE `jadwal_kegiatan` (
  `id` int UNSIGNED NOT NULL,
  `judul_agenda` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_layanan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Penimbangan & Imunisasi dst.',
  `dusun` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_kegiatan` date NOT NULL,
  `jam_mulai` time NOT NULL DEFAULT '08:00:00',
  `jam_selesai` time NOT NULL DEFAULT '11:30:00',
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penanggung_jawab` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('rencana','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'rencana'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_kegiatan`
--

INSERT INTO `jadwal_kegiatan` (`id`, `judul_agenda`, `jenis_layanan`, `dusun`, `tanggal_kegiatan`, `jam_mulai`, `jam_selesai`, `lokasi`, `penanggung_jawab`, `keterangan`, `status`) VALUES
(1, 'Posyandu Bulan Oktober 2026', 'Penimbangan & Imunisasi', 'Gamping Wetan', '2026-10-10', '08:00:00', '11:30:00', 'Balai RT 01 Gamping Wetan', 'Bu Sari Rahayu', 'Target: semua balita dusun ditimbang', 'rencana'),
(2, 'Posbindu Lansia September', 'Posbindu Lansia', 'Kemangsen', '2026-09-20', '07:30:00', '10:00:00', 'Musala Al-Ikhlas Kemangsen', 'Bu Tini Wahyuni', 'Cek tensi & gula darah 12 lansia', 'selesai');

-- --------------------------------------------------------

--
-- Table structure for table `lansia`
--

CREATE TABLE `lansia` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` char(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `dusun` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tensi_terakhir` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '120/80' COMMENT 'tekanan darah, mis. 120/80',
  `gula_darah` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '110 mg/dL',
  `keluhan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_kesehatan` enum('sehat','pemantauan','resiko_tinggi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sehat',
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lansia`
--

INSERT INTO `lansia` (`id`, `nama`, `nik`, `tanggal_lahir`, `jenis_kelamin`, `dusun`, `tensi_terakhir`, `gula_darah`, `keluhan`, `status_kesehatan`, `status`) VALUES
(1, 'Marni Suwarno', '3509124408010008', '1961-06-11', 'P', 'Kemangsen', '130/85', '105 mg/dL', 'Sesak saat naik tangga', 'pemantauan', 'aktif'),
(2, 'Sutarman', '3509124408110012', '1958-01-20', 'L', 'Gamping Wetan', '120/80', '95 mg/dL', NULL, 'sehat', 'aktif'),
(3, 'Wagimin', '3509124408210013', '1956-10-02', 'L', 'Boharan', '150/95', '165 mg/dL', 'Riwayat hipertensi', 'resiko_tinggi', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int UNSIGNED NOT NULL,
  `judul` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` enum('semua','kader','bidan','warga') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'semua',
  `dibuat_oleh` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin Desa',
  `tgl_kirim` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('terkirim','draft') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'terkirim'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `judul`, `pesan`, `target`, `dibuat_oleh`, `tgl_kirim`, `status`) VALUES
(1, 'Posyandu Melati 10 Oktober', 'Bapak/Ibu diharapkan hadir membawa buku KIA anak pada kegiatan penimbangan bulan ini.', 'semua', 'Admin Desa', '2026-10-21 00:13:18', 'terkirim');

-- --------------------------------------------------------

--
-- Table structure for table `pengukuran`
--

CREATE TABLE `pengukuran` (
  `id` int UNSIGNED NOT NULL,
  `anak_id` int UNSIGNED NOT NULL,
  `tanggal_ukur` date NOT NULL,
  `usia_bulan` smallint UNSIGNED NOT NULL COMMENT 'umur saat diukur (hitung_umur)',
  `berat_kg` decimal(5,2) NOT NULL,
  `tinggi_cm` decimal(5,1) NOT NULL,
  `kategori_status` enum('normal','pemantauan','prioritas','berisiko') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dicatat_oleh_id` int UNSIGNED DEFAULT NULL COMMENT 'kader/bidan pencatat, FK -> users'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengukuran`
--

INSERT INTO `pengukuran` (`id`, `anak_id`, `tanggal_ukur`, `usia_bulan`, `berat_kg`, `tinggi_cm`, `kategori_status`, `catatan`, `dicatat_oleh_id`) VALUES
(1, 1, '2026-08-10', 25, 10.70, 82.5, 'normal', NULL, 3),
(2, 1, '2026-09-10', 26, 10.80, 83.1, 'pemantauan', 'Berat naik tipis, pantau asupan harian', 3),
(3, 2, '2026-09-10', 42, 12.10, 90.0, 'normal', NULL, 3),
(4, 3, '2026-09-12', 32, 9.00, 85.0, 'normal', NULL, 4),
(5, 4, '2026-09-15', 34, 10.20, 88.0, 'normal', NULL, 4),
(6, 5, '2026-09-16', 24, 8.90, 78.2, 'prioritas', 'BB stagnan 2 bulan, dirujuk bidan', 4);

-- --------------------------------------------------------

--
-- Table structure for table `posyandu_pos`
--

CREATE TABLE `posyandu_pos` (
  `id` int UNSIGNED NOT NULL,
  `nama_pos` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dusun` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jadwal_rutin` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'teks bebas, mis. Setiap tanggal 10',
  `kader_utama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posyandu_pos`
--

INSERT INTO `posyandu_pos` (`id`, `nama_pos`, `dusun`, `lokasi_alamat`, `jadwal_rutin`, `kader_utama`, `telepon`, `status`) VALUES
(1, 'Pos Melati', 'Gamping Wetan', 'Balai RT 01 Gamping Wetan', 'Setiap tanggal 10', 'Bu Sari Rahayu', '0812-3456-7802', 'aktif'),
(2, 'Pos Anggrek', 'Boharan', 'Balai Dusun Boharan', 'Setiap tanggal 12', 'Bu Dewi Lestari', '0812-3456-7803', 'aktif'),
(3, 'Pos Kenanga', 'Kemangsen', 'Musala Al-Ikhlas Kemangsen', 'Setiap tanggal 15', 'Bu Tini Wahyuni', '0812-3456-7804', 'aktif'),
(4, 'Pos Teratai', 'Bendomungal', 'Gedong Serbaguna Bendomungal', 'Setiap tanggal 16', 'Bu Yuni Astuti', '0812-3456-7805', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `profil_desa`
--

CREATE TABLE `profil_desa` (
  `id` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `nama_desa` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kecamatan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kabupaten` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kepala_desa` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_kantor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_pos` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visi_misi` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profil_desa`
--

INSERT INTO `profil_desa` (`id`, `nama_desa`, `kecamatan`, `kabupaten`, `kepala_desa`, `alamat_kantor`, `telepon`, `email`, `kode_pos`, `visi_misi`) VALUES
(1, 'Desa Sukatani', 'Kecamatan Balongbendo', 'Kabupaten Sidoarjo', 'Bambang Sudiro, S.Sos', 'Jl. Raya Sukatani No. 12, Balongbendo, Sidoarjo', '031-8971234', 'pemdes@sukatani.desa.id', '61263', 'Mewujudkan masyarakat Desa Sukatani yang sehat, cerdas, berdaya saing melalui layanan Posyandu digital terintegrasi.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'password_hash() PHP',
  `role` enum('kader','bidan','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kader',
  `dusun_binaan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'khusus role kader',
  `sapaan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bu / Ibu / Pak, dipakai di dashboard',
  `telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `dusun_binaan`, `sapaan`, `telepon`, `created_at`) VALUES
(1, 'Admin Desa Sukatani', 'admin', '$2y$10$UlStY4XONSKLv1i9.vd4cO2eAJg21N1t1JWGPTdeOW0af6dG4tL0a', 'admin', NULL, 'Admin', '031-8971234', '2026-10-20 17:13:18'),
(2, 'Siti Nurhaliza, S.Tr.Keb', 'bidan', '$2y$10$UlStY4XONSKLv1i9.vd4cO2eAJg21N1t1JWGPTdeOW0af6dG4tL0a', 'bidan', NULL, 'Ibu', '0812-3456-7801', '2026-10-20 17:13:18'),
(3, 'Sari Rahayu', 'kader1', '$2y$10$UlStY4XONSKLv1i9.vd4cO2eAJg21N1t1JWGPTdeOW0af6dG4tL0a', 'kader', 'Gamping Wetan', 'Bu', '0812-3456-7802', '2026-10-20 17:13:18'),
(4, 'Dewi Lestari', 'kader2', '$2y$10$UlStY4XONSKLv1i9.vd4cO2eAJg21N1t1JWGPTdeOW0af6dG4tL0a', 'kader', 'Boharan', 'Bu', '0812-3456-7803', '2026-10-20 17:13:18');

-- --------------------------------------------------------

--
-- Table structure for table `warga`
--

CREATE TABLE `warga` (
  `id` int UNSIGNED NOT NULL,
  `nik` char(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_kk` char(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `dusun` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rt_rw` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '001/001',
  `telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` enum('balita','ibu_hamil','lansia','umum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'umum',
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warga`
--

INSERT INTO `warga` (`id`, `nik`, `no_kk`, `nama`, `tanggal_lahir`, `jenis_kelamin`, `dusun`, `rt_rw`, `telepon`, `kategori`, `status`) VALUES
(1, '3509124401010001', '3509124401010001', 'Dinda Aulia', '2024-07-15', 'P', 'Gamping Wetan', '001/002', NULL, 'balita', 'aktif'),
(2, '3509124402010002', '3509124402010001', 'Farel Aditya', '2023-03-10', 'L', 'Gamping Wetan', '001/002', NULL, 'balita', 'aktif'),
(3, '3509124406010006', '3509124406010002', 'Rina Wulandari', '1996-04-18', 'P', 'Gamping Wetan', '001/002', '0812-3456-7810', 'umum', 'aktif'),
(4, '3509124407010007', '3509124407010002', 'Nurhasanah', '1998-02-06', 'P', 'Boharan', '002/001', '0812-3456-7811', 'ibu_hamil', 'aktif'),
(5, '3509124408010008', '3509124408010002', 'Marni Suwarno', '1961-06-11', 'P', 'Kemangsen', '003/001', '0812-3456-7812', 'lansia', 'aktif'),
(6, '3509124409010009', '3509124409010002', 'Sugeng Riyadi', '1990-09-30', 'L', 'Bendomungal', '004/001', '0812-3456-7813', 'umum', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anak`
--
ALTER TABLE `anak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_anak_dusun` (`dusun`),
  ADD KEY `idx_anak_kader` (`kader_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_created` (`created_at`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_berita_published` (`status`,`published_at`),
  ADD KEY `fk_berita_penulis` (`created_by`);

--
-- Indexes for table `ibu_hamil`
--
ALTER TABLE `ibu_hamil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bumil_dusun` (`dusun`),
  ADD KEY `idx_bumil_status` (`status_kehamilan`);

--
-- Indexes for table `jadwal_kegiatan`
--
ALTER TABLE `jadwal_kegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jadwal_tanggal` (`tanggal_kegiatan`),
  ADD KEY `idx_jadwal_status` (`status`);

--
-- Indexes for table `lansia`
--
ALTER TABLE `lansia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lansia_dusun` (`dusun`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengukuran`
--
ALTER TABLE `pengukuran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pengukuran_anak_tanggal` (`anak_id`,`tanggal_ukur`),
  ADD KEY `idx_pengukuran_tanggal` (`tanggal_ukur`),
  ADD KEY `idx_pengukuran_kategori` (`kategori_status`),
  ADD KEY `fk_pengukuran_pencatat` (`dicatat_oleh_id`);

--
-- Indexes for table `posyandu_pos`
--
ALTER TABLE `posyandu_pos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pos_dusun` (`dusun`);

--
-- Indexes for table `profil_desa`
--
ALTER TABLE `profil_desa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_username` (`username`);

--
-- Indexes for table `warga`
--
ALTER TABLE `warga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_warga_nik` (`nik`),
  ADD KEY `idx_warga_dusun` (`dusun`),
  ADD KEY `idx_warga_kategori` (`kategori`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anak`
--
ALTER TABLE `anak`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ibu_hamil`
--
ALTER TABLE `ibu_hamil`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jadwal_kegiatan`
--
ALTER TABLE `jadwal_kegiatan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lansia`
--
ALTER TABLE `lansia`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengukuran`
--
ALTER TABLE `pengukuran`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `posyandu_pos`
--
ALTER TABLE `posyandu_pos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `warga`
--
ALTER TABLE `warga`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anak`
--
ALTER TABLE `anak`
  ADD CONSTRAINT `fk_anak_kader` FOREIGN KEY (`kader_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `berita`
--
ALTER TABLE `berita`
  ADD CONSTRAINT `fk_berita_penulis` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pengukuran`
--
ALTER TABLE `pengukuran`
  ADD CONSTRAINT `fk_pengukuran_anak` FOREIGN KEY (`anak_id`) REFERENCES `anak` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengukuran_pencatat` FOREIGN KEY (`dicatat_oleh_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
