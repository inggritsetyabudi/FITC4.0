<?php

class Pengukuran
{
    public function __construct(private PDO $db) {}

    public function getAll(?string $bulan = null, ?string $dusun = null, ?string $status = null, ?int $balitaId = null): array
    {
        $sql = "SELECT p.id, p.balita_id, p.tanggal_ukur, p.berat_kg, p.tinggi_cm, p.kategori_status, p.catatan,
                a.nama AS nama_anak, d.nama AS dusun, a.nama_ortu, a.jenis_kelamin,
                TIMESTAMPDIFF(MONTH, a.tanggal_lahir, p.tanggal_ukur) AS usia_bulan
                FROM pengukuran p
                JOIN balita a ON a.id = p.balita_id
                JOIN dusun d ON d.id = a.dusun_id
                WHERE 1=1";

        $params = [];

        if ($bulan && $bulan !== 'semua') {
            $sql .= " AND p.tanggal_ukur LIKE ?";
            $params[] = "$bulan%";
        }

        if ($dusun && $dusun !== 'semua') {
            $sql .= " AND d.nama = ?";
            $params[] = $dusun;
        }

        if ($status && $status !== 'semua') {
            $sql .= " AND p.kategori_status = ?";
            $params[] = $status;
        }

        if ($balitaId) {
            $sql .= " AND p.balita_id = ?";
            $params[] = $balitaId;
        }

        $sql .= " ORDER BY p.tanggal_ukur DESC, p.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getLastByBalitaId(int $balitaId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM pengukuran WHERE balita_id = ? ORDER BY tanggal_ukur DESC, id DESC LIMIT 1");
        $stmt->execute([$balitaId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO pengukuran (balita_id, tanggal_ukur, berat_kg, tinggi_cm, kategori_status, catatan, dicatat_oleh_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['balita_id'],
            $data['tanggal_ukur'],
            $data['berat_kg'],
            $data['tinggi_cm'],
            $data['kategori_status'],
            $data['catatan'] ?: null,
            $data['dicatat_oleh_id'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM pengukuran WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
