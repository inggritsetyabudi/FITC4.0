<?php
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'posyandu_pintar');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');

function get_db(): ?PDO {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);

        inisialisasi_database_jika_perlu($pdo);
        return $pdo;
    } catch (PDOException $e) {
        try {
            $rootDsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
            $rootPdo = new PDO($rootDsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $rootPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            inisialisasi_database_jika_perlu($pdo);
            return $pdo;
        } catch (Exception $inner) {
            return null;
        }
    }
}

function inisialisasi_database_jika_perlu(PDO $pdo): void {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `nama` VARCHAR(150) NOT NULL,
        `username` VARCHAR(80) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `role` ENUM('kader','bidan','admin') NOT NULL DEFAULT 'kader',
        `dusun_binaan` VARCHAR(100) NULL,
        `telepon` VARCHAR(30) NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `anak` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `nama` VARCHAR(150) NOT NULL,
        `nik` VARCHAR(16) NULL,
        `tanggal_lahir` DATE NOT NULL,
        `jenis_kelamin` ENUM('L','P') NOT NULL,
        `nama_ortu` VARCHAR(150) NOT NULL,
        `dusun` VARCHAR(100) NOT NULL,
        `kader_id` INT UNSIGNED NOT NULL DEFAULT 1,
        `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `pengukuran` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `anak_id` INT UNSIGNED NOT NULL,
        `tanggal_ukur` DATE NOT NULL,
        `usia_bulan` SMALLINT UNSIGNED NOT NULL,
        `berat_kg` DECIMAL(5,2) NOT NULL,
        `tinggi_cm` DECIMAL(5,2) NOT NULL,
        `kategori_status` ENUM('normal','pemantauan','prioritas','berisiko') NOT NULL DEFAULT 'normal',
        `catatan` TEXT NULL,
        `dicatat_oleh_id` INT UNSIGNED NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX (`anak_id`),
        INDEX (`tanggal_ukur`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `berita` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `judul` VARCHAR(200) NOT NULL,
        `kategori` VARCHAR(80) NOT NULL,
        `ringkasan` TEXT NOT NULL,
        `isi` LONGTEXT NOT NULL,
        `gambar` VARCHAR(255) NULL,
        `status` ENUM('draft','published') NOT NULL DEFAULT 'published',
        `published_at` DATETIME NULL,
        `created_by` INT UNSIGNED NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `jadwal` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `nama_kegiatan` VARCHAR(150) NOT NULL,
        `dusun` VARCHAR(100) NOT NULL,
        `lokasi` VARCHAR(150) NOT NULL,
        `tanggal` DATE NOT NULL,
        `waktu_mulai` VARCHAR(10) NOT NULL,
        `waktu_selesai` VARCHAR(10) NOT NULL,
        `status` ENUM('rencana','selesai') NOT NULL DEFAULT 'rencana',
        `petugas` VARCHAR(150) NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $userCount = $pdo->query("SELECT COUNT(*) FROM `users`")->fetchColumn();
    if ($userCount == 0) {
        $hash123 = password_hash('123', PASSWORD_BCRYPT);
        $insertUser = $pdo->prepare("INSERT INTO `users` (nama, username, password, role, dusun_binaan, telepon) VALUES (?, ?, ?, ?, ?, ?)");
        $defaultUsers = [
            ['Sari Rahmawati', 'kader', $hash123, 'kader', 'Gamping Wetan', '0812-3456-7890'],
            ['Siti Nurhaliza, S.Tr.Keb', 'bidan', $hash123, 'bidan', NULL, '0813-9876-5432'],
            ['Bambang Hermawan', 'admin', $hash123, 'admin', NULL, '0857-1122-3344']
        ];
        foreach ($defaultUsers as $u) {
            $insertUser->execute($u);
        }
    }

    $anakCount = $pdo->query("SELECT COUNT(*) FROM `anak`")->fetchColumn();
    if ($anakCount == 0) {
        $sampleAnak = [
            ['Dinda Aulia', '3515085408240001', '2024-05-18', 'P', 'Siti Aminah / Herman Susanto', 'Gamping Wetan', 1],
            ['Muhammad Farhan', '3515081210240002', '2024-10-12', 'L', 'Dewi Lestari / Ahmad Fauzi', 'Gamping Wetan', 1],
            ['Aisyah Putri', '3515086506250003', '2025-06-25', 'P', 'Rina Wati / Budi Santoso', 'Boharan', 1],
            ['Rizky Pratama', '3515080308230004', '2023-08-03', 'L', 'Nurul Hidayah / Agus Supriyanto', 'Boharan', 1],
            ['Farel Aditya', '3515081502250005', '2025-02-15', 'L', 'Indah Permata / Doni Wahyudi', 'Kasengan', 1],
            ['Nabila Zahra', '3515084911240006', '2024-11-09', 'P', 'Yuni Astuti / Hendra Gunawan', 'Kasengan', 1],
            ['Alvaro Keenan', '3515082102240007', '2024-02-21', 'L', 'Mega Suryani / Rendy Pratama', 'Bendomungal', 1],
            ['Kirana Larasati', '3515086808250008', '2025-08-28', 'P', 'Anisa Rahma / Fajar Nugroho', 'Bendomungal', 1]
        ];

        $insAnak = $pdo->prepare("INSERT INTO `anak` (nama, nik, tanggal_lahir, jenis_kelamin, nama_ortu, dusun, kader_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'aktif')");
        foreach ($sampleAnak as $a) {
            $insAnak->execute($a);
        }

        $samplePengukuran = [
            [1, '2026-05-18', 24, 10.2, 81.5, 'normal', 'Nafsu makan bagus, gemar sayur', 1],
            [1, '2026-06-18', 25, 10.4, 82.2, 'normal', 'Imunisasi lanjutan', 1],
            [1, '2026-07-18', 26, 10.6, 82.9, 'normal', 'Kondisi stabil', 1],
            [1, '2026-08-18', 27, 10.8, 83.1, 'pemantauan', 'Perlu evaluasi porsi makan', 1],
            [5, '2026-05-10', 15, 8.8, 74.0, 'normal', 'Tumbuh baik', 1],
            [5, '2026-06-10', 16, 8.9, 74.8, 'pemantauan', 'Kenaikan BB tidak adekuat', 1],
            [5, '2026-07-10', 17, 8.9, 75.1, 'pemantauan', 'BB stagnan 1 bulan', 1],
            [5, '2026-08-10', 18, 8.6, 75.4, 'prioritas', 'BB turun 0.3 kg, prioritas tinjauan bidan', 1],
            [2, '2026-06-20', 21, 9.9, 79.8, 'normal', 'Tumbuh aktif', 1],
            [2, '2026-07-20', 22, 9.8, 80.5, 'pemantauan', 'BB turun 0.1 kg', 1],
            [3, '2026-08-14', 14, 9.4, 77.2, 'normal', 'MPASI lancar dan aktif', 1],
            [4, '2026-08-12', 36, 14.2, 95.0, 'normal', 'Motorik kasar berkembang sangat baik', 1],
            [7, '2026-08-11', 30, 13.1, 90.8, 'normal', 'Sehat dan ceria', 1],
            [8, '2026-08-08', 12, 8.0, 72.0, 'pemantauan', 'Berat badan stagnan 1 bulan', 1]
        ];

        $insUkur = $pdo->prepare("INSERT INTO `pengukuran` (anak_id, tanggal_ukur, usia_bulan, berat_kg, tinggi_cm, kategori_status, catatan, dicatat_oleh_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($samplePengukuran as $p) {
            $insUkur->execute($p);
        }
    }

    $beritaCount = $pdo->query("SELECT COUNT(*) FROM `berita`")->fetchColumn();
    if ($beritaCount == 0) {
        $beritaList = [
            [
                'Jadwal Penimbangan Serentak & Pembagian Vitamin A',
                'KABAR DESA',
                'Pemerintah Desa bersama Bidan Desa dan Kader mengumumkan jadwal posyandu serentak di 4 dusun pada pekan kedua September.',
                '<p>Pemerintah Desa bersama Tim Pelaksana Posyandu mengumumkan pelaksanaan penimbangan serentak serta pembagian kapsul Vitamin A merah dan biru untuk seluruh balita pada pekan kedua bulan September 2026.</p>',
                '/images/berita_vitamin_a.jpg',
                '2026-08-28 08:00:00'
            ],
            [
                'Program PMT Pangan Lokal: Manfaat Telur & Ikan untuk Balita',
                'GIZI & KESEHATAN',
                'Pemberian Makanan Tambahan berbasis pangan lokal terbukti efektif mendukung kenaikan berat badan dan mencegah stunting.',
                '<p>Pemberian Makanan Tambahan (PMT) berbasis pangan lokal seperti telur ayam kampung, tahu, tempe, dan ikan air tawar kaya akan protein hewani serta zink yang penting untuk pertumbuhan tulang dan sel saraf anak.</p>',
                '/images/berita_pmt_gizi.jpg',
                '2026-08-25 09:00:00'
            ],
            [
                'Kunjungan Bidan Desa & Evaluasi Tumbuh Kembang Balita di Dusun Sukamaju',
                'KEGIATAN POSYANDU',
                'Bidan Siti melakukan kunjungan lapangan langsung untuk meninjau balita yang memerlukan pemantauan ketat dan memberikan konseling gizi keluarga.',
                '<p>Pada hari Kamis lalu, Bidan Desa Siti Nurhaliza bersama kader Posyandu melaksanakan kunjungan rumah langsung untuk memeriksa balita yang terdeteksi mengalami tren kenaikan berat badan tidak adekuat.</p>',
                '/images/berita_kunjungan_bidan.jpg',
                '2026-08-20 10:30:00'
            ]
        ];

        $insBerita = $pdo->prepare("INSERT INTO `berita` (judul, kategori, ringkasan, isi, gambar, status, published_at, created_by) VALUES (?, ?, ?, ?, ?, 'published', ?, 2)");
        foreach ($beritaList as $b) {
            $insBerita->execute($b);
        }
    }

    $jadwalCount = $pdo->query("SELECT COUNT(*) FROM `jadwal`")->fetchColumn();
    if ($jadwalCount == 0) {
        $sampleJadwal = [
            ['Penimbangan Bulanan & Vitamin A', 'Gamping Wetan', 'Balai RW 02', '2026-09-12', '08:00', '11:30', 'rencana', 'Bu Sari & Bidan Siti'],
            ['Posyandu Balita & Konseling Gizi', 'Boharan', 'Pos Sehat Melati', '2026-09-13', '08:30', '11:30', 'rencana', 'Kader Melati & Bidan Siti'],
            ['Pemeriksaan Tumbuh Kembang', 'Kasengan', 'Balai Pertemuan RW 01', '2026-09-15', '08:00', '11:00', 'rencana', 'Kader Kasengan'],
            ['Penimbangan Terpadu & PMT', 'Bendomungal', 'Balai Dusun Harapan', '2026-09-18', '08:30', '11:30', 'rencana', 'Kader Harapan & Bidan Siti']
        ];
        $insJadwal = $pdo->prepare("INSERT INTO `jadwal` (nama_kegiatan, dusun, lokasi, tanggal, waktu_mulai, waktu_selesai, status, petugas) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($sampleJadwal as $j) {
            $insJadwal->execute($j);
        }
    }
}
