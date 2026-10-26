<?php
class Pengukuran
{
    private PDO $db;
    private string $table = 'pengukuran';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getByAnakId(int $anakId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE anak_id = ? ORDER BY tanggal_ukur ASC");
        $stmt->execute([$anakId]);
        return $stmt->fetchAll();
    }

    public function getLastMeasurement(int $anakId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE anak_id = ? ORDER BY tanggal_ukur DESC LIMIT 1");
        $stmt->execute([$anakId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getAnakSudahUkurBulanIni(string $bulanTahun): array
    {
        $stmt = $this->db->prepare("SELECT DISTINCT anak_id FROM {$this->table} WHERE tanggal_ukur LIKE ?");
        $stmt->execute([$bulanTahun . '%']);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (anak_id, tanggal_ukur, usia_bulan, berat_kg, tinggi_cm, kategori_status, catatan, dicatat_oleh_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['anak_id'],
            $data['tanggal_ukur'],
            $data['usia_bulan'],
            $data['berat_kg'],
            $data['tinggi_cm'],
            $data['kategori_status'] ?? 'normal',
            $data['catatan'] ?? null,
            $data['dicatat_oleh_id'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getRecentMeasurements(int $limit = 10): array
    {
        $sql = "SELECT p.*, a.nama as nama_anak, a.dusun, a.jenis_kelamin, u.nama as nama_pencatat
                FROM {$this->table} p
                JOIN anak a ON p.anak_id = a.id
                LEFT JOIN users u ON p.dicatat_oleh_id = u.id
                ORDER BY p.tanggal_ukur DESC, p.id DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPrioritas(?string $dusun = null): array
    {
        $sql = "SELECT a.*, p.id as pengukuran_id, p.tanggal_ukur, p.usia_bulan, p.berat_kg, p.tinggi_cm, p.kategori_status, p.catatan
                FROM anak a
                INNER JOIN {$this->table} p ON a.id = p.anak_id
                INNER JOIN (
                    SELECT anak_id, MAX(tanggal_ukur) as max_tanggal
                    FROM {$this->table}
                    GROUP BY anak_id
                ) latest ON p.anak_id = latest.anak_id AND p.tanggal_ukur = latest.max_tanggal
                WHERE a.status = 'aktif' AND p.kategori_status IN ('pemantauan', 'prioritas', 'berisiko')";

        $params = [];
        if ($dusun && $dusun !== 'semua') {
            $sql .= " AND a.dusun = ?";
            $params[] = $dusun;
        }

        $sql .= " ORDER BY p.kategori_status DESC, a.nama ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getDistribusiStatusGizi(string $bulanTahun): array
    {
        $sql = "SELECT p.kategori_status, COUNT(*) as total
                FROM {$this->table} p
                INNER JOIN (
                    SELECT anak_id, MAX(tanggal_ukur) as max_tanggal
                    FROM {$this->table}
                    WHERE tanggal_ukur LIKE ?
                    GROUP BY anak_id
                ) latest ON p.anak_id = latest.anak_id AND p.tanggal_ukur = latest.max_tanggal
                GROUP BY p.kategori_status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$bulanTahun . '%']);
        $rows = $stmt->fetchAll();

        $result = [
            'normal'     => 0,
            'pemantauan' => 0,
            'prioritas'  => 0,
            'total'      => 0
        ];

        foreach ($rows as $row) {
            $cat = $row['kategori_status'];
            $tot = (int)$row['total'];
            if (isset($result[$cat])) {
                $result[$cat] = $tot;
            } else {
                $result['prioritas'] += $tot;
            }
            $result['total'] += $tot;
        }

        return $result;
    }

    public function getRekapBulanan(string $bulanTahun, ?string $dusun = null): array
    {
        $sql = "SELECT a.id, a.nama, a.nik, a.tanggal_lahir, a.jenis_kelamin, a.nama_ortu, a.dusun,
                       p.tanggal_ukur, p.usia_bulan, p.berat_kg, p.tinggi_cm, p.kategori_status, p.catatan
                FROM anak a
                LEFT JOIN {$this->table} p ON a.id = p.anak_id AND p.tanggal_ukur LIKE ?
                WHERE a.status = 'aktif'";

        $params = [$bulanTahun . '%'];

        if ($dusun && $dusun !== 'semua') {
            $sql .= " AND a.dusun = ?";
            $params[] = $dusun;
        }

        $sql .= " ORDER BY a.dusun ASC, a.nama ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
