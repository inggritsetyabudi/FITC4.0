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
        $totalPengukuran = (int)$this->db->query("SELECT COUNT(*) FROM pengukuran")->fetchColumn();
        $totalPrioritas = (int)$this->db->query("SELECT COUNT(*) FROM pengukuran p JOIN (SELECT anak_id, MAX(tanggal_ukur) AS max_tanggal FROM pengukuran GROUP BY anak_id) latest ON p.anak_id = latest.anak_id AND p.tanggal_ukur = latest.max_tanggal WHERE p.kategori_status IN ('pemantauan','prioritas','berisiko')")->fetchColumn();
        $totalKader = (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role = 'kader'")->fetchColumn();
        $totalBidan = (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role = 'bidan'")->fetchColumn();

        $bulan = date('Y-m');
        $stmt = $this->db->prepare("SELECT COUNT(DISTINCT anak_id) FROM pengukuran WHERE tanggal_ukur LIKE ?");
        $stmt->execute([$bulan . '%']);
        $sudahUkur = (int)$stmt->fetchColumn();

        return [
            'total_balita' => $totalBalita,
            'total_pengukuran' => $totalPengukuran,
            'total_prioritas' => $totalPrioritas,
            'total_kader' => $totalKader,
            'total_bidan' => $totalBidan,
            'sudah_ukur_bulan_ini' => $sudahUkur,
            'bulan' => $bulan
        ];
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
}
