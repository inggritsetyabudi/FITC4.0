<?php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'posyandu_pintar');
define('DB_USER', 'root');
define('DB_PASS', '');

function get_db(): PDO {
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
            

            return get_db();
        } catch (Exception $inner) {
            die("<div style='font-family:sans-serif;padding:20px;color:#721c24;background:#f8d7da;border:1px solid #f5c6cb;border-radius:8px;'>
                <h3>Gagal Terhubung ke Database MySQL</h3>
                <p>Pastikan MySQL di Laragon sudah aktif.</p>
                <small>Detail error: " . htmlspecialchars($e->getMessage()) . "</small>
            </div>");
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
        `sapaan` VARCHAR(80) NULL,
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


    $pdo->exec("CREATE TABLE IF NOT EXISTS `site_settings` (
        `setting_key` VARCHAR(100) PRIMARY KEY,
        `setting_value` TEXT NOT NULL,
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");


    $userCount = $pdo->query("SELECT COUNT(*) FROM `users`")->fetchColumn();
    if ($userCount == 0) {
        $hash123 = password_hash('123', PASSWORD_BCRYPT);
        $insertUser = $pdo->prepare("INSERT INTO `users` (nama, username, password, role, dusun_binaan, sapaan, telepon) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertUser->execute(['Sari Rahmawati', 'kader', $hash123, 'kader', 'Gamping Wetan', 'Bu Sari', '0812-3456-7890']);
        $insertUser->execute(['Rini Astuti', 'kader_melati', $hash123, 'kader', 'Boharan', 'Bu Rini', '0813-9876-5432']);
        $insertUser->execute(['Bidan Siti Nurhaliza, S.Tr.Keb', 'bidan', $hash123, 'bidan', 'Semua Dusun (Desa Sukatani)', 'Bidan Siti', '0811-2233-4455']);
        $insertUser->execute(['Administrator Desa Sukatani', 'admin', $hash123, 'admin', 'Pemerintah Desa', 'Pak Admin', '0819-0011-2233']);
    }


    $anakCount = $pdo->query("SELECT COUNT(*) FROM `anak`")->fetchColumn();
    if ($anakCount < 16) {
        $balitaList = [
            [1, 'Dinda Aulia', '3515085408240001', '2024-05-18', 'P', 'Siti Aminah', 'Gamping Wetan'],
            [2, 'Raka Pratama', '3515081209240002', '2024-09-12', 'L', 'Ahmad Fauzi', 'Boharan'],
            [3, 'Nabila Putri', '3515086006250003', '2025-06-20', 'P', 'Dewi Lestari', 'Kasengan'],
            [4, 'Arga Ramadhan', '3515081008230004', '2023-08-10', 'L', 'Hendra Setiawan', 'Bendomungal'],
            [5, 'Farel Aditya', '3515082502250005', '2025-02-25', 'L', 'Sri Rahayu', 'Gamping Wetan'],
            [6, 'Alya Safitri', '3515084812250006', '2025-12-08', 'P', 'Maya Indah', 'Boharan'],
            [7, 'Rizky Mahendra', '3515081502240007', '2024-02-15', 'L', 'Bambang Irawan', 'Gamping Wetan'],
            [8, 'Zahra Khairunnisa', '3515086508250008', '2025-08-25', 'P', 'Rahmat Hidayat', 'Bendomungal'],
            [9, 'Muhammad Alif', '3515080512240009', '2024-12-05', 'L', 'Nurul Huda', 'Kasengan'],
            [10, 'Cantika Dewi', '3515084202230010', '2023-02-14', 'P', 'Anita Sulistyo', 'Gamping Wetan'],
            [11, 'Bilal Pratama', '3515081802260011', '2026-02-18', 'L', 'Eko Wahyudi', 'Boharan'],
            [12, 'Kayla Anindita', '3515085004250012', '2025-04-10', 'P', 'Wati Marlina', 'Bendomungal'],
            [13, 'Yusuf Maulana', '3515082007240013', '2024-07-20', 'L', 'Hasan Basri', 'Kasengan'],
            [14, 'Aqila Syakira', '3515086209250014', '2025-09-22', 'P', 'Fitri Handayani', 'Gamping Wetan'],
            [15, 'Rayhan Fauzan', '3515081403240015', '2024-03-14', 'L', 'Budi Santoso', 'Boharan'],
            [16, 'Siti Nuraini', '3515085511250016', '2025-11-15', 'P', 'Khadijah', 'Bendomungal']
        ];

        $insAnak = $pdo->prepare("INSERT INTO `anak` (id, nama, nik, tanggal_lahir, jenis_kelamin, nama_ortu, dusun, kader_id, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, 1, 'aktif')
            ON DUPLICATE KEY UPDATE nama = VALUES(nama), nik = VALUES(nik), tanggal_lahir = VALUES(tanggal_lahir),
            jenis_kelamin = VALUES(jenis_kelamin), nama_ortu = VALUES(nama_ortu), dusun = VALUES(dusun)");

        foreach ($balitaList as $b) {
            $insAnak->execute($b);
        }
    }


    $pengukuranCount = $pdo->query("SELECT COUNT(*) FROM `pengukuran`")->fetchColumn();
    if ($pengukuranCount < 10) {
        $samplePengukuran = [
            [1, '2026-03-18', 22, 9.8, 80.2, 'normal', 'Nafsu makan baik', 1],
            [1, '2026-04-18', 23, 10.1, 81.0, 'normal', 'Pertumbuhan optimal', 1],
            [1, '2026-05-18', 24, 10.3, 81.7, 'normal', 'Lancar makan sayur', 1],
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

        $insUkur = $pdo->prepare("INSERT INTO `pengukuran` 
            (anak_id, tanggal_ukur, usia_bulan, berat_kg, tinggi_cm, kategori_status, catatan, dicatat_oleh_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

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
                '<p>Pemerintah Desa bersama Tim Pelaksana Posyandu mengumumkan pelaksanaan penimbangan serentak serta pembagian kapsul Vitamin A merah dan biru untuk seluruh balita pada pekan kedua bulan September 2026.</p><h5>Jadwal Pelaksanaan per Dusun:</h5><ul><li><strong>Dusun Gamping Wetan:</strong> 12 September 2026 di Balai RW 02</li><li><strong>Dusun Boharan:</strong> 13 September 2026 di Pos Sehat Melati</li><li><strong>Dusun Kasengan:</strong> 15 September 2026 di Balai Pertemuan RW 01</li><li><strong>Dusun Bendomungal:</strong> 18 September 2026 di Balai Dusun Harapan</li></ul><p>Orang tua diimbau membawa buku KIA/KMS balita dan memastikan anak dalam kondisi sehat saat hadir.</p>',
                'assets/images/berita_vitamin_a.jpg',
                '2026-08-28 08:00:00',
                3
            ],
            [
                'Program PMT Pangan Lokal: Manfaat Telur & Ikan untuk Balita',
                'GIZI & KESEHATAN',
                'Pemberian Makanan Tambahan berbasis pangan lokal terbukti efektif mendukung kenaikan berat badan dan mencegah stunting.',
                '<p>Pemberian Makanan Tambahan (PMT) berbasis pangan lokal seperti telur ayam kampung, tahu, tempe, dan ikan air tawar kaya akan protein hewani serta zink yang penting untuk pertumbuhan tulang dan sel saraf anak.</p><p>Kader posyandu terus mendampingi para ibu dalam mengolah menu MPASI bergizi seimbang dari bahan pangan lokal yang murah dan mudah diperoleh di pekarangan desa.</p>',
                'assets/images/berita_pmt_gizi.jpg',
                '2026-08-25 09:00:00',
                3
            ],
            [
                'Kunjungan Bidan Desa & Evaluasi Tumbuh Kembang Balita di Dusun Sukamaju',
                'KEGIATAN POSYANDU',
                'Bidan Siti melakukan kunjungan lapangan langsung untuk meninjau balita yang memerlukan pemantauan ketat dan memberikan konseling gizi keluarga.',
                '<p>Pada hari Kamis lalu, Bidan Desa Siti Nurhaliza bersama kader Posyandu melaksanakan kunjungan rumah langsung untuk memeriksa 3 balita yang terdeteksi mengalami tren kenaikan berat badan tidak adekuat dalam penimbangan terakhir.</p><p>Kunjungan ini bertujuan untuk mengidentifikasi faktor penyebab perlambatan pertumbuhan, seperti riwayat batuk/demam berulang, higienitas air bersih, serta asupan porsi makanan pendamping ASI.</p><h5>Hasil Konseling Keluarga:</h5><p>Orang tua balita menyambut baik evaluasi tatap muka ini dan mendapatkan panduan porsi makan tinggi kalori serta jadwal kontrol penimbangan ulang di posyandu berikutnya.</p>',
                'assets/images/berita_kunjungan_bidan.jpg',
                '2026-08-20 10:30:00',
                3
            ]
        ];

        $insBerita = $pdo->prepare("INSERT INTO `berita` 
            (judul, kategori, ringkasan, isi, gambar, status, published_at, created_by)
            VALUES (?, ?, ?, ?, ?, 'published', ?, ?)");

        foreach ($beritaList as $b) {
            $insBerita->execute($b);
        }
    }
}
