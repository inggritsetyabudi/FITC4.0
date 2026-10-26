<?php
class Anak
{
    private PDO $db;
    private string $table = 'anak';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllWithLastMeasurement(?string $dusun = null, ?string $search = null): array
    {
        $sql = "SELECT a.*, 
                (SELECT p.kategori_status FROM pengukuran p WHERE p.anak_id = a.id ORDER BY p.tanggal_ukur DESC LIMIT 1) as status_terakhir,
                (SELECT p.berat_kg FROM pengukuran p WHERE p.anak_id = a.id ORDER BY p.tanggal_ukur DESC LIMIT 1) as berat_terakhir,
                (SELECT p.tinggi_cm FROM pengukuran p WHERE p.anak_id = a.id ORDER BY p.tanggal_ukur DESC LIMIT 1) as tinggi_terakhir,
                (SELECT p.tanggal_ukur FROM pengukuran p WHERE p.anak_id = a.id ORDER BY p.tanggal_ukur DESC LIMIT 1) as tanggal_ukur_terakhir
                FROM {$this->table} a 
                WHERE a.status = 'aktif'";

        $params = [];

        if ($dusun && $dusun !== 'semua') {
            $sql .= " AND a.dusun = ?";
            $params[] = $dusun;
        }

        if ($search) {
            $sql .= " AND (a.nama LIKE ? OR a.nik LIKE ? OR a.nama_ortu LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $sql .= " ORDER BY a.nama ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nama, nik, tanggal_lahir, jenis_kelamin, nama_ortu, dusun, kader_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'aktif')");
        $stmt->execute([
            $data['nama'],
            $data['nik'] ?: null,
            $data['tanggal_lahir'],
            $data['jenis_kelamin'],
            $data['nama_ortu'],
            $data['dusun'],
            $data['kader_id'] ?? 1
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET nama = ?, nik = ?, tanggal_lahir = ?, jenis_kelamin = ?, nama_ortu = ?, dusun = ? WHERE id = ?");
        return $stmt->execute([
            $data['nama'],
            $data['nik'] ?: null,
            $data['tanggal_lahir'],
            $data['jenis_kelamin'],
            $data['nama_ortu'],
            $data['dusun'],
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = 'nonaktif' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countTotal(?string $dusun = null): int
    {
        if ($dusun && $dusun !== 'semua') {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE status = 'aktif' AND dusun = ?");
            $stmt->execute([$dusun]);
            return (int)$stmt->fetchColumn();
        }
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table} WHERE status = 'aktif'");
        return (int)$stmt->fetchColumn();
    }

    public function getDaftarDusun(): array
    {
        $stmt = $this->db->query("SELECT DISTINCT dusun FROM {$this->table} WHERE status = 'aktif' ORDER BY dusun ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
