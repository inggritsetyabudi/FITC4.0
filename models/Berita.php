<?php
class Berita
{
    private PDO $db;
    private string $table = 'berita';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getPublished(int $limit = 6): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'published' ORDER BY published_at DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        if (count($rows) < $limit) {
            $static = array_values($this->getStaticArticles());
            foreach ($static as $s) {
                if (count($rows) >= $limit) break;
                $rows[] = $s;
            }
        }

        return $rows;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY published_at DESC");
        return $stmt->fetchAll();
    }

    private function getStaticArticles(): array
    {
        return [
            'art_01' => [
                'id'           => 'art_01',
                'judul'        => 'Kenapa penimbangan balita perlu dilakukan secara rutin setiap bulan?',
                'kategori'     => 'PANDUAN POSYANDU',
                'ringkasan'    => 'Penimbangan rutin adalah kunci utama mendeteksi perlambatan pertumbuhan sebelum anak mengalami risiko gizi kurang atau stunting. Catatan grafik yang berkesinambungan memberi kepastian bagi orang tua.',
                'gambar'       => 'assets/images/hero_timbang.jpg',
                'waktu_baca'   => '5 menit baca',
                'published_at' => '2026-08-24',
                'penulis'      => 'Bidan Siti Nurhaliza, S.Tr.Keb',
                'isi'          => '<p>Pertumbuhan balita pada 1.000 Hari Pertama Kehidupan (HPK) berlangsung sangat pesat. Perubahan berat dan tinggi badan yang tidak naik atau bahkan turun dalam 1-2 bulan berturut-turut merupakan tanda awal adanya gangguan gizi atau infeksi pada anak.</p><h4 class="fw-bold mt-4 mb-2 text-forest">1. Deteksi Dini Sebelum Terjadi Masalah Gizi Lebih Lanjut</h4><p>Dengan menimbang secara teratur setiap bulan di Posyandu, kurva pertumbuhan pada KMS digital dapat segera terbaca. Jika garis pertumbuhan mendatar atau menurun, kader dan bidan desa dapat langsung memberikan tindak lanjut tanpa menunggu kondisi anak tampak mengkhawatirkan.</p><h4 class="fw-bold mt-4 mb-2 text-forest">2. Evaluasi Kecukupan Makanan Pendamping ASI (MPASI)</h4><p>Kader dan tenaga kesehatan dapat melihat langsung apakah porsi makan dan asupan nutrisi harian balita sudah mencukupi kebutuhan tahapan usianya, serta memberikan bimbingan menu bergizi seimbang.</p><h4 class="fw-bold mt-4 mb-2 text-forest">3. Pemberian Kapsul Vitamin A & Imunisasi Lengkap</h4><p>Selain penimbangan berat dan tinggi badan, posyandu menyediakan imunisasi dasar lengkap dan pembagian vitamin A secara gratis pada bulan Februari dan Agustus.</p>'
            ],
            'art_02' => [
                'id'           => 'art_02',
                'judul'        => 'Protein hewani dan perannya dalam masa pertumbuhan',
                'kategori'     => 'GIZI BALITA',
                'ringkasan'    => 'Sumber protein hewani seperti telur, ikan, dan hati ayam mengandung asam amino esensial lengkap untuk mendukung pembentukan sel dan tinggi badan optimal anak.',
                'gambar'       => 'assets/images/berita_pmt_gizi.jpg',
                'waktu_baca'   => '4 menit baca',
                'published_at' => '2026-08-19',
                'penulis'      => 'Tim Gizi Posyandu Sukatani',
                'isi'          => '<p>Kementerian Kesehatan menegaskan bahwa pemenuhan protein hewani setiap hari merupakan langkah paling efektif dalam mencegah stunting dan mendukung perkembangan kognitif balita.</p><h4 class="fw-bold mt-4 mb-2 text-forest">Keunggulan Protein Hewani:</h4><ul class="ps-3" style="line-height: 1.8;"><li><strong>Asam Amino Lengkap:</strong> Struktur protein hewani lebih mudah diserap dan digunakan oleh tubuh anak untuk membangun jaringan otot dan organ.</li><li><strong>Kaya Zat Besi & Zink:</strong> Mencegah anemia yang dapat menurunkan konsentrasi dan imunitas balita terhadap penyakit menular.</li><li><strong>Bahan Mudah Didapat:</strong> Telur ayam, ikan lele atau bandeng lokal, dan hati ayam sangat terjangkau namun memiliki nilai gizi yang luar biasa.</li></ul><p>Pastikan setiap porsi makan anak mengandung sedikitnya satu jenis protein hewani yang diolah secara higienis dan variatif.</p>'
            ],
            'art_03' => [
                'id'           => 'art_03',
                'judul'        => 'Daftar persiapan sederhana orang tua sebelum berangkat ke Posyandu',
                'kategori'     => 'TIPS KELUARGA',
                'ringkasan'    => 'Membawa buku KIA, memastikan balita sudah cukup istirahat, dan menyiapkan pakaian santai memudahkan proses penimbangan yang cepat dan nyaman.',
                'gambar'       => 'assets/images/berita_kunjungan_bidan.jpg',
                'waktu_baca'   => '3 menit baca',
                'published_at' => '2026-08-12',
                'penulis'      => 'Bu Sari (Kader Posyandu)',
                'isi'          => '<p>Agar hari buka Posyandu berjalan lancar dan balita tidak rewel saat ditimbang, ada beberapa tips mudah yang dapat disiapkan orang tua dari rumah:</p><h4 class="fw-bold mt-4 mb-2 text-forest">Langkah Persiapan:</h4><ol class="ps-3" style="line-height: 1.8;"><li><strong>Bawa Buku KIA / KMS:</strong> Buku KIA menjadi panduan utama kader untuk mencatat data penimbangan dan riwayat imunisasi anak.</li><li><strong>Gunakan Pakaian Sederhana:</strong> Pakaikan baju yang ringan dan mudah dilepas saat penimbangan berat badan agar hasil timbangan akurat.</li><li><strong>Beri Makan dan Istirahat Cukup:</strong> Balita yang kenyang dan cukup tidur biasanya lebih tenang saat pemeriksaan fisik dilakukan.</li><li><strong>Catat Keluhan atau Pertanyaan:</strong> Jangan ragu menanyakan perkembangan makan anak atau jadwal imunisasi kepada bidan desa saat sesi konsultasi.</li></ol>'
            ]
        ];
    }

    public function getById(string|int $id): ?array
    {
        $static = $this->getStaticArticles();
        if (isset($static[$id])) {
            return $static[$id];
        }

        if (is_numeric($id)) {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
            $stmt->execute([(int)$id]);
            $row = $stmt->fetch();
            if ($row) return $row;
        }

        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE status = 'published' ORDER BY published_at DESC LIMIT 1");
        $fallback = $stmt->fetch();
        return $fallback ?: ($static['art_01'] ?? null);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (judul, kategori, ringkasan, isi, gambar, status, published_at, created_by) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");
        $stmt->execute([
            $data['judul'],
            $data['kategori'],
            $data['ringkasan'],
            $data['isi'],
            $data['gambar'] ?? null,
            $data['status'] ?? 'published',
            $data['created_by'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET judul = ?, kategori = ?, ringkasan = ?, isi = ?, gambar = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['judul'],
            $data['kategori'],
            $data['ringkasan'],
            $data['isi'],
            $data['gambar'] ?? null,
            $data['status'] ?? 'published',
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
