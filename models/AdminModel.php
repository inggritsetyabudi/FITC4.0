<?php
class AdminModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getMacroStats(): array
    {
        $totalBalita = (int)$this->db->query("SELECT COUNT(*) FROM anak WHERE status = 'aktif'")->fetchColumn();
        $totalBumil = (int)$this->db->query("SELECT COUNT(*) FROM ibu_hamil WHERE status = 'aktif'")->fetchColumn();
        $totalLansia = (int)$this->db->query("SELECT COUNT(*) FROM lansia WHERE status = 'aktif'")->fetchColumn();
        $totalWarga = (int)$this->db->query("SELECT COUNT(*) FROM warga WHERE status = 'aktif'")->fetchColumn();
        $totalKader = (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role = 'kader'")->fetchColumn();
        $totalBidan = (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role = 'bidan'")->fetchColumn();
        $totalPos = (int)$this->db->query("SELECT COUNT(*) FROM posyandu_pos WHERE status = 'aktif'")->fetchColumn();
        $totalPengukuran = (int)$this->db->query("SELECT COUNT(*) FROM pengukuran")->fetchColumn();
        $totalKegiatan = (int)$this->db->query("SELECT COUNT(*) FROM jadwal_kegiatan WHERE status = 'rencana'")->fetchColumn();

        $dusunList = ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];
        $dusunBreakdown = [];
        foreach ($dusunList as $d) {
            $stmtB = $this->db->prepare("SELECT COUNT(*) FROM anak WHERE dusun = ? AND status = 'aktif'");
            $stmtB->execute([$d]);
            $balitaCount = (int)$stmtB->fetchColumn();

            $stmtH = $this->db->prepare("SELECT COUNT(*) FROM ibu_hamil WHERE dusun = ? AND status = 'aktif'");
            $stmtH->execute([$d]);
            $bumilCount = (int)$stmtH->fetchColumn();

            $stmtL = $this->db->prepare("SELECT COUNT(*) FROM lansia WHERE dusun = ? AND status = 'aktif'");
            $stmtL->execute([$d]);
            $lansiaCount = (int)$stmtL->fetchColumn();

            $dusunBreakdown[$d] = [
                'balita' => $balitaCount,
                'bumil'  => $bumilCount,
                'lansia' => $lansiaCount,
                'total'  => $balitaCount + $bumilCount + $lansiaCount
            ];
        }

        return [
            'total_balita'     => $totalBalita,
            'total_bumil'      => $totalBumil,
            'total_lansia'     => $totalLansia,
            'total_warga'      => $totalWarga,
            'total_kader'      => $totalKader,
            'total_bidan'      => $totalBidan,
            'total_pos'        => $totalPos,
            'total_pengukuran' => $totalPengukuran,
            'total_kegiatan'   => $totalKegiatan,
            'dusun_breakdown'  => $dusunBreakdown
        ];
    }

    public function getWarga(?string $dusun = null, ?string $kategori = null, ?string $q = null): array
    {
        $sql = "SELECT * FROM warga WHERE 1=1";
        $params = [];

        if ($dusun && $dusun !== 'semua') {
            $sql .= " AND dusun = ?";
            $params[] = $dusun;
        }

        if ($kategori && $kategori !== 'semua') {
            $sql .= " AND kategori = ?";
            $params[] = $kategori;
        }

        if ($q && trim($q) !== '') {
            $sql .= " AND (nama LIKE ? OR nik LIKE ? OR no_kk LIKE ?)";
            $term = '%' . trim($q) . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $sql .= " ORDER BY nama ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function createWarga(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO warga (nik, no_kk, nama, tanggal_lahir, jenis_kelamin, dusun, rt_rw, telepon, kategori, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nik'],
            $data['no_kk'] ?? null,
            $data['nama'],
            $data['tanggal_lahir'],
            $data['jenis_kelamin'],
            $data['dusun'],
            $data['rt_rw'] ?? '001/001',
            $data['telepon'] ?? null,
            $data['kategori'] ?? 'umum',
            $data['status'] ?? 'aktif'
        ]);
    }

    public function updateWarga(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE warga SET nik = ?, no_kk = ?, nama = ?, tanggal_lahir = ?, jenis_kelamin = ?, dusun = ?, rt_rw = ?, telepon = ?, kategori = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['nik'],
            $data['no_kk'] ?? null,
            $data['nama'],
            $data['tanggal_lahir'],
            $data['jenis_kelamin'],
            $data['dusun'],
            $data['rt_rw'] ?? '001/001',
            $data['telepon'] ?? null,
            $data['kategori'] ?? 'umum',
            $data['status'] ?? 'aktif',
            $id
        ]);
    }

    public function toggleStatusWarga(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE warga SET status = IF(status = 'aktif', 'nonaktif', 'aktif') WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getIbuHamil(?string $dusun = null, ?string $status = null): array
    {
        $sql = "SELECT * FROM ibu_hamil WHERE 1=1";
        $params = [];

        if ($dusun && $dusun !== 'semua') {
            $sql .= " AND dusun = ?";
            $params[] = $dusun;
        }

        if ($status && $status !== 'semua') {
            $sql .= " AND status_kehamilan = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY hpl ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function createIbuHamil(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO ibu_hamil (nama, nik, nama_suami, dusun, usia_kehamilan_minggu, hpl, status_kehamilan, catatan, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nama'],
            $data['nik'],
            $data['nama_suami'],
            $data['dusun'],
            (int)($data['usia_kehamilan_minggu'] ?? 12),
            $data['hpl'],
            $data['status_kehamilan'] ?? 'sehat',
            $data['catatan'] ?? null,
            $data['status'] ?? 'aktif'
        ]);
    }

    public function updateIbuHamil(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE ibu_hamil SET nama = ?, nik = ?, nama_suami = ?, dusun = ?, usia_kehamilan_minggu = ?, hpl = ?, status_kehamilan = ?, catatan = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['nama'],
            $data['nik'],
            $data['nama_suami'],
            $data['dusun'],
            (int)($data['usia_kehamilan_minggu'] ?? 12),
            $data['hpl'],
            $data['status_kehamilan'] ?? 'sehat',
            $data['catatan'] ?? null,
            $data['status'] ?? 'aktif',
            $id
        ]);
    }

    public function getLansia(?string $dusun = null, ?string $status = null): array
    {
        $sql = "SELECT * FROM lansia WHERE 1=1";
        $params = [];

        if ($dusun && $dusun !== 'semua') {
            $sql .= " AND dusun = ?";
            $params[] = $dusun;
        }

        if ($status && $status !== 'semua') {
            $sql .= " AND status_kesehatan = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY nama ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function createLansia(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO lansia (nama, nik, tanggal_lahir, jenis_kelamin, dusun, tensi_terakhir, gula_darah, keluhan, status_kesehatan, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nama'],
            $data['nik'],
            $data['tanggal_lahir'],
            $data['jenis_kelamin'],
            $data['dusun'],
            $data['tensi_terakhir'] ?? '120/80',
            $data['gula_darah'] ?? '110 mg/dL',
            $data['keluhan'] ?? null,
            $data['status_kesehatan'] ?? 'sehat',
            $data['status'] ?? 'aktif'
        ]);
    }

    public function updateLansia(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE lansia SET nama = ?, nik = ?, tanggal_lahir = ?, jenis_kelamin = ?, dusun = ?, tensi_terakhir = ?, gula_darah = ?, keluhan = ?, status_kesehatan = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['nama'],
            $data['nik'],
            $data['tanggal_lahir'],
            $data['jenis_kelamin'],
            $data['dusun'],
            $data['tensi_terakhir'] ?? '120/80',
            $data['gula_darah'] ?? '110 mg/dL',
            $data['keluhan'] ?? null,
            $data['status_kesehatan'] ?? 'sehat',
            $data['status'] ?? 'aktif',
            $id
        ]);
    }

    public function getPosyandu(): array
    {
        $stmt = $this->db->query("SELECT * FROM posyandu_pos ORDER BY dusun ASC, nama_pos ASC");
        return $stmt->fetchAll();
    }

    public function createPosyandu(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO posyandu_pos (nama_pos, dusun, lokasi_alamat, jadwal_rutin, kader_utama, telepon, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nama_pos'],
            $data['dusun'],
            $data['lokasi_alamat'],
            $data['jadwal_rutin'],
            $data['kader_utama'],
            $data['telepon'] ?? null,
            $data['status'] ?? 'aktif'
        ]);
    }

    public function updatePosyandu(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE posyandu_pos SET nama_pos = ?, dusun = ?, lokasi_alamat = ?, jadwal_rutin = ?, kader_utama = ?, telepon = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['nama_pos'],
            $data['dusun'],
            $data['lokasi_alamat'],
            $data['jadwal_rutin'],
            $data['kader_utama'],
            $data['telepon'] ?? null,
            $data['status'] ?? 'aktif',
            $id
        ]);
    }

    public function getJadwal(?string $status = null): array
    {
        $sql = "SELECT * FROM jadwal_kegiatan WHERE 1=1";
        $params = [];
        if ($status && $status !== 'semua') {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        $sql .= " ORDER BY tanggal_kegiatan ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function createJadwal(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO jadwal_kegiatan (judul_agenda, jenis_layanan, dusun, tanggal_kegiatan, jam_mulai, jam_selesai, lokasi, penanggung_jawab, keterangan, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['judul_agenda'],
            $data['jenis_layanan'],
            $data['dusun'],
            $data['tanggal_kegiatan'],
            $data['jam_mulai'] ?? '08:00:00',
            $data['jam_selesai'] ?? '11:30:00',
            $data['lokasi'],
            $data['penanggung_jawab'],
            $data['keterangan'] ?? null,
            $data['status'] ?? 'rencana'
        ]);
    }

    public function updateJadwal(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE jadwal_kegiatan SET judul_agenda = ?, jenis_layanan = ?, dusun = ?, tanggal_kegiatan = ?, jam_mulai = ?, jam_selesai = ?, lokasi = ?, penanggung_jawab = ?, keterangan = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['judul_agenda'],
            $data['jenis_layanan'],
            $data['dusun'],
            $data['tanggal_kegiatan'],
            $data['jam_mulai'] ?? '08:00:00',
            $data['jam_selesai'] ?? '11:30:00',
            $data['lokasi'],
            $data['penanggung_jawab'],
            $data['keterangan'] ?? null,
            $data['status'] ?? 'rencana',
            $id
        ]);
    }

    public function getPemeriksaanRekap(?string $dusun = null, ?string $status = null): array
    {
        $sql = "
            SELECT p.*, a.nama AS nama_anak, a.nik, a.dusun, a.nama_ortu, u.nama AS nama_petugas
            FROM pengukuran p
            JOIN anak a ON a.id = p.anak_id
            LEFT JOIN users u ON u.id = p.dicatat_oleh_id
            WHERE 1=1
        ";
        $params = [];

        if ($dusun && $dusun !== 'semua') {
            $sql .= " AND a.dusun = ?";
            $params[] = $dusun;
        }

        if ($status && $status !== 'semua') {
            $sql .= " AND p.kategori_status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY p.tanggal_ukur DESC LIMIT 100";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getNotifikasi(): array
    {
        $stmt = $this->db->query("SELECT * FROM notifikasi ORDER BY tgl_kirim DESC");
        return $stmt->fetchAll();
    }

    public function createNotifikasi(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO notifikasi (judul, pesan, target, dibuat_oleh, tgl_kirim, status) VALUES (?, ?, ?, ?, NOW(), ?)");
        return $stmt->execute([
            $data['judul'],
            $data['pesan'],
            $data['target'] ?? 'semua',
            $data['dibuat_oleh'] ?? 'Admin Desa',
            $data['status'] ?? 'terkirim'
        ]);
    }

    public function getAuditLogs(int $limit = 50): array
    {
        $stmt = $this->db->prepare("SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function log(string $userNama, string $role, string $action, string $targetModul, string $detail): bool
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $stmt = $this->db->prepare("INSERT INTO audit_logs (user_nama, role, action, target_modul, detail, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$userNama, $role, $action, $targetModul, $detail, $ip]);
    }

    public function getProfilDesa(): array
    {
        $stmt = $this->db->query("SELECT * FROM profil_desa LIMIT 1");
        $row = $stmt->fetch();
        if (!$row) {
            return [
                'nama_desa'     => 'Desa Sukatani',
                'kecamatan'     => 'Kecamatan Balongbendo',
                'kabupaten'     => 'Kabupaten Sidoarjo',
                'kepala_desa'   => 'Bambang Sudiro, S.Sos',
                'alamat_kantor' => 'Jl. Raya Sukatani No. 12, Balongbendo, Sidoarjo',
                'telepon'       => '031-8971234',
                'email'         => 'pemdes@sukatani.desa.id',
                'kode_pos'      => '61263',
                'visi_misi'     => 'Mewujudkan masyarakat Desa Sukatani yang sehat, cerdas, berdaya saing melalui layanan Posyandu digital terintegrasi.'
            ];
        }
        return $row;
    }

    public function updateProfilDesa(array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE profil_desa SET nama_desa = ?, kecamatan = ?, kabupaten = ?, kepala_desa = ?, alamat_kantor = ?, telepon = ?, email = ?, kode_pos = ?, visi_misi = ? WHERE id = 1");
        return $stmt->execute([
            $data['nama_desa'],
            $data['kecamatan'],
            $data['kabupaten'],
            $data['kepala_desa'],
            $data['alamat_kantor'],
            $data['telepon'],
            $data['email'],
            $data['kode_pos'],
            $data['visi_misi']
        ]);
    }

    public function getBackupJson(): string
    {
        $data = [
            'meta' => [
                'aplikasi'   => 'Posyandu Pintar (FITC4.0)',
                'desa'       => 'Desa Sukatani',
                'exported_at'=> date('Y-m-d H:i:s'),
                'operator'   => 'Administrator Desa'
            ],
            'warga'           => $this->db->query("SELECT * FROM warga")->fetchAll(),
            'anak'            => $this->db->query("SELECT * FROM anak")->fetchAll(),
            'ibu_hamil'       => $this->db->query("SELECT * FROM ibu_hamil")->fetchAll(),
            'lansia'          => $this->db->query("SELECT * FROM lansia")->fetchAll(),
            'pengukuran'      => $this->db->query("SELECT * FROM pengukuran")->fetchAll(),
            'posyandu_pos'    => $this->db->query("SELECT * FROM posyandu_pos")->fetchAll(),
            'jadwal_kegiatan' => $this->db->query("SELECT * FROM jadwal_kegiatan")->fetchAll(),
            'users'           => $this->db->query("SELECT id, nama, username, role, dusun_binaan, sapaan, telepon, created_at FROM users")->fetchAll(),
            'profil_desa'     => $this->getProfilDesa()
        ];
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
