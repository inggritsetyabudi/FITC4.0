CREATE DATABASE IF NOT EXISTS posyandu_pintar
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE posyandu_pintar;

-- ---------------------------------------------------------------------------
-- Tabel
-- ---------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS dusun (
  id    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama  VARCHAR(100) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_dusun_nama (nama)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS users (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama        VARCHAR(150) NOT NULL,
  username    VARCHAR(50)  NOT NULL,
  password    VARCHAR(255) NOT NULL,                       -- hash password_hash() PHP
  role        ENUM('kader','bidan','admin') NOT NULL DEFAULT 'kader',
  dusun_id    INT UNSIGNED NULL,                           -- dusun binaan (khusus kader)
  telepon     VARCHAR(30)  NULL,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username),
  KEY idx_users_role (role),
  CONSTRAINT fk_users_dusun FOREIGN KEY (dusun_id)
    REFERENCES dusun (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS balita (
  id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama             VARCHAR(150) NOT NULL,
  nik              VARCHAR(16)  NULL,                      -- NULL jika tidak diisi
  tanggal_lahir    DATE NOT NULL,
  jenis_kelamin    ENUM('L','P') NOT NULL,
  nama_ortu        VARCHAR(150) NOT NULL,
  dusun_id         INT UNSIGNED NOT NULL,
  status           ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  dicatat_oleh_id  INT UNSIGNED NULL,
  created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_balita_nik (nik),
  KEY idx_balita_dusun (dusun_id),
  KEY idx_balita_nama (nama),
  CONSTRAINT fk_balita_dusun FOREIGN KEY (dusun_id)
    REFERENCES dusun (id) ON UPDATE CASCADE,
  CONSTRAINT fk_balita_user FOREIGN KEY (dicatat_oleh_id)
    REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pengukuran (
  id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  balita_id        INT UNSIGNED NOT NULL,
  tanggal_ukur     DATE NOT NULL,
  berat_kg         DECIMAL(5,2) NOT NULL,
  tinggi_cm        DECIMAL(5,1) NOT NULL,
  kategori_status  ENUM('normal','pemantauan','prioritas','berisiko') NOT NULL DEFAULT 'normal',
  catatan          TEXT NULL,
  dicatat_oleh_id  INT UNSIGNED NULL,
  created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  -- satu pengukuran per balita per hari (memicu error 1062 -> "duplikat" di API)
  UNIQUE KEY uq_pengukuran_balita_tanggal (balita_id, tanggal_ukur),
  KEY idx_pengukuran_tanggal (tanggal_ukur),
  KEY idx_pengukuran_status (kategori_status),
  -- batas sama dengan form: berat 1-40 kg, tinggi 30-140 cm (error 3819 di API)
  CONSTRAINT chk_pengukuran_berat  CHECK (berat_kg  BETWEEN 1 AND 40),
  CONSTRAINT chk_pengukuran_tinggi CHECK (tinggi_cm BETWEEN 30 AND 140),
  CONSTRAINT fk_pengukuran_balita FOREIGN KEY (balita_id)
    REFERENCES balita (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_pengukuran_user FOREIGN KEY (dicatat_oleh_id)
    REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS berita (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  judul         VARCHAR(255) NOT NULL,
  kategori      VARCHAR(100) NOT NULL DEFAULT 'KABAR DESA',
  tipe          ENUM('berita','blog') NOT NULL DEFAULT 'berita',
  ringkasan     TEXT NOT NULL,
  isi           MEDIUMTEXT NOT NULL,
  gambar        VARCHAR(255) NULL,
  status        ENUM('draft','published') NOT NULL DEFAULT 'draft',
  published_at  DATETIME NULL,
  penulis_id    INT UNSIGNED NULL,
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_berita_publik (status, tipe, published_at),
  CONSTRAINT fk_berita_user FOREIGN KEY (penulis_id)
    REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS jadwal (
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama_kegiatan  VARCHAR(200) NOT NULL,
  dusun_id       INT UNSIGNED NOT NULL,
  lokasi         VARCHAR(200) NOT NULL DEFAULT '',
  tanggal        DATE NOT NULL,
  waktu_mulai    TIME NOT NULL DEFAULT '08:00:00',
  waktu_selesai  TIME NOT NULL DEFAULT '11:00:00',
  status         ENUM('rencana','selesai') NOT NULL DEFAULT 'rencana',
  petugas        VARCHAR(150) NULL,
  created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_jadwal_tanggal (tanggal),
  -- jam selesai harus setelah jam mulai (error 3819 di API)
  CONSTRAINT chk_jadwal_waktu CHECK (waktu_selesai > waktu_mulai),
  CONSTRAINT fk_jadwal_dusun FOREIGN KEY (dusun_id)
    REFERENCES dusun (id) ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------------
-- Data awal
-- ---------------------------------------------------------------------------

-- Nama dusun harus persis sama dengan DUSUN_LIST di src/utils/constants.ts
INSERT INTO dusun (id, nama) VALUES
  (1, 'Gamping Wetan'),
  (2, 'Gamping Tengah'),
  (3, 'Boharan'),
  (4, 'Kasengan'),
  (5, 'Bendomungal');

INSERT INTO users (id, nama, username, password, role, dusun_id, telepon) VALUES
  (1, 'Administrator Desa', 'admin',         '$2y$10$9qFMXPeuClse1alpuJUKheHINhYz4UrBrvFSoO90R232Sc.tHoL2i', 'admin', NULL, '081200000001'),
  (2, 'Bidan Siti Nurhaliza', 'bidan',       '$2y$10$e9zpmN28CddPcL7FciIFkO.FxgmVPuYIxAWDvRPsrn9x5FDsS0sly', 'bidan', NULL, '081200000002'),
  (3, 'Sari Rahayu',      'kader.gamping',   '$2y$10$/OShZE7.zxDZBmDdAcxpp.aAxQ9oVYkFAzMyD0cjLw97TkxflPy/q', 'kader', 1,    '081200000003'),
  (4, 'Wulan Prastiwi',   'kader.boharan',   '$2y$10$/OShZE7.zxDZBmDdAcxpp.aAxQ9oVYkFAzMyD0cjLw97TkxflPy/q', 'kader', 3,    '081200000004');

-- NIK di bawah hanya contoh fiktif
INSERT INTO balita (id, nama, nik, tanggal_lahir, jenis_kelamin, nama_ortu, dusun_id, status, dicatat_oleh_id) VALUES
  (1, 'Dinda Aulia',   '3404000000000001', '2024-03-12', 'P', 'Siti Aminah',     1, 'aktif', 3),
  (2, 'Raka Pratama',  '3404000000000002', '2023-11-05', 'L', 'Herman Susanto',  2, 'aktif', 3),
  (3, 'Alya Putri',    NULL,               '2025-01-20', 'P', 'Lestari',         3, 'aktif', 4),
  (4, 'Bima Saputra',  '3404000000000004', '2024-08-02', 'L', 'Wahyu Hidayat',   4, 'aktif', 4),
  (5, 'Nayla Kirana',  NULL,               '2023-06-15', 'P', 'Dewi Anggraini',  5, 'aktif', 3),
  (6, 'Fajar Ramadhan','3404000000000006', '2025-04-10', 'L', 'Budi Santoso',    1, 'aktif', 3);

-- kategori_status mengikuti aturan evaluasi_status_pengukuran() di api/config/helpers.php:
--   pengukuran pertama = normal; selisih BB < -0,3 = prioritas; selisih <= 0 = pemantauan; selain itu normal
INSERT INTO pengukuran (balita_id, tanggal_ukur, berat_kg, tinggi_cm, kategori_status, catatan, dicatat_oleh_id) VALUES
  (1, '2026-06-10', 11.20, 84.0, 'normal',     NULL, 3),
  (1, '2026-07-10', 11.50, 85.0, 'normal',     NULL, 3),
  (1, '2026-08-12', 11.90, 86.0, 'normal',     NULL, 3),

  (2, '2026-06-12', 12.40, 88.0, 'normal',     NULL, 3),
  (2, '2026-07-14', 12.20, 88.8, 'pemantauan', 'Nafsu makan menurun sepekan terakhir.', 3),
  (2, '2026-08-11', 11.70, 89.5, 'prioritas',  'BB turun dua bulan berturut-turut, perlu konseling gizi bidan.', 3),

  (3, '2026-06-15',  8.80, 72.0, 'normal',     NULL, 4),
  (3, '2026-07-15',  9.00, 73.0, 'normal',     NULL, 4),
  (3, '2026-08-13',  9.00, 73.8, 'pemantauan', 'BB tidak naik bulan ini.', 4),

  (4, '2026-07-08',  9.60, 75.5, 'normal',     NULL, 4),
  (4, '2026-08-12',  9.90, 76.4, 'normal',     NULL, 4),

  (5, '2026-06-20', 13.50, 94.0, 'normal',     NULL, 3),
  (5, '2026-07-18', 13.90, 95.0, 'normal',     NULL, 3),
  (5, '2026-08-20', 14.20, 96.0, 'normal',     NULL, 3),

  (6, '2026-08-14',  6.90, 64.0, 'normal',     NULL, 3);

-- Gambar ada di public/images/ (dilayani Vite). Teks berikut contoh, silakan ganti.
INSERT INTO berita (id, judul, kategori, tipe, ringkasan, isi, gambar, status, published_at, penulis_id) VALUES
  (1, 'Vitamin A dan dukungannya untuk tumbuh kembang', 'GIZI BALITA', 'blog',
   'Vitamin A dibagikan pada bulan Februari dan Agustus untuk balita usia 6-59 bulan.',
   'Vitamin A membantu menjaga kesehatan mata dan daya tahan tubuh anak.\n\nKapsul vitamin A diberikan gratis di Posyandu pada bulan Februari (biru untuk bayi 6-11 bulan) dan Agustus (merah untuk balita 12-59 bulan).\n\nBawa Buku KIA saat datang agar pemberian vitamin A dapat dicatat.',
   '/images/berita_vitamin_a.jpg', 'published', '2026-08-10 08:00:00', 2),
  (2, 'Kunjungan Bidan ke Posyandu', 'KEGIATAN', 'berita',
   'Bidan desa meninjau pelaksanaan penimbangan dan memberi konseling kepada orang tua balita.',
   'Bidan desa mengunjungi Posyandu untuk memantau penimbangan balita bulan Agustus.\n\nKunjungan ini juga dimanfaatkan untuk konseling gizi dan menindaklanjuti balita yang berat badannya belum naik.',
   '/images/berita_kunjungan_bidan.jpg', 'published', '2026-08-05 09:00:00', 2),
  (3, 'PMT dan Gizi Seimbang', 'GIZI BALITA', 'berita',
   'Pemberian makanan tambahan berbahan lokal untuk mendukung gizi balita.',
   'Posyandu membagikan makanan tambahan berbahan pangan lokal yang kaya protein hewani.\n\nOrang tua juga diberi contoh menu sederhana yang dapat dibuat di rumah.',
   '/images/berita_pmt_gizi.jpg', 'published', '2026-07-29 09:00:00', 2),
  (4, 'Protein hewani dan perannya dalam masa pertumbuhan', 'GIZI BALITA', 'blog',
   'Telur, ikan, dan ayam membantu mendukung pertumbuhan anak.',
   'Protein hewani mengandung zat gizi penting untuk pertumbuhan anak.\n\nTelur, ikan, hati ayam, dan daging dapat diberikan sesuai usia dan tekstur yang aman bagi anak.',
   '/images/panduan-penimbangan.png', 'published', '2026-08-19 08:00:00', 2);

INSERT INTO jadwal (nama_kegiatan, dusun_id, lokasi, tanggal, waktu_mulai, waktu_selesai, status, petugas) VALUES
  ('Penimbangan Balita Bulanan', 1, 'Balai Dusun Gamping Wetan',  '2026-09-08', '08:00:00', '11:00:00', 'selesai', 'Sari Rahayu'),
  ('Penimbangan Balita Bulanan', 3, 'Balai Dusun Boharan',        '2026-09-10', '08:00:00', '11:00:00', 'selesai', 'Wulan Prastiwi'),
  ('Penimbangan Balita Bulanan', 1, 'Balai Dusun Gamping Wetan',  '2026-11-10', '08:00:00', '11:30:00', 'rencana', 'Sari Rahayu'),
  ('Penimbangan Balita Bulanan', 2, 'Balai Dusun Gamping Tengah', '2026-11-11', '08:00:00', '11:00:00', 'rencana', 'Bidan Siti Nurhaliza'),
  ('Penimbangan Balita Bulanan', 3, 'Balai Dusun Boharan',        '2026-11-12', '08:00:00', '11:00:00', 'rencana', 'Wulan Prastiwi'),
  ('Penimbangan Balita Bulanan', 4, 'Balai Dusun Kasengan',       '2026-11-13', '08:00:00', '11:00:00', 'rencana', 'Bidan Siti Nurhaliza'),
  ('Penimbangan & Vitamin A',    5, 'Balai Dusun Bendomungal',    '2026-11-14', '08:00:00', '11:00:00', 'rencana', 'Bidan Siti Nurhaliza');
