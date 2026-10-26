<?php

class Balita
{
    public function __construct(private PDO $db) {}

    public function getAll(?string $dusun = null, ?string $search = null): array
    {
        $sql = "SELECT a.id, a.nama, a.nik, a.tanggal_lahir, a.jenis_kelamin, a.nama_ortu, a.status,
                d.nama AS dusun,
                (SELECT p.kategori_status FROM pengukuran p WHERE p.balita_id = a.id ORDER BY p.tanggal_ukur DESC, p.id DESC LIMIT 1) AS status_terakhir,
                (SELECT p.berat_kg FROM pengukuran p WHERE p.balita_id = a.id ORDER BY p.tanggal_ukur DESC, p.id DESC LIMIT 1) AS berat_terakhir,
                (SELECT p.tinggi_cm FROM pengukuran p WHERE p.balita_id = a.id ORDER BY p.tanggal_ukur DESC, p.id DESC LIMIT 1) AS tinggi_terakhir,
                (SELECT p.tanggal_ukur FROM pengukuran p WHERE p.balita_id = a.id ORDER BY p.tanggal_ukur DESC, p.id DESC LIMIT 1) AS tanggal_ukur_terakhir
                FROM balita a
                JOIN dusun d ON d.id = a.dusun_id
                WHERE a.status = 'aktif'";

        $params = [];

        if ($dusun && $dusun !== 'semua') {
            $sql .= " AND d.nama = ?";
            $params[] = $dusun;
        }

        if ($search) {
            $q = $search . '%';
            $sql .= " AND (a.nama LIKE ? OR a.nik LIKE ? OR a.nama_ortu LIKE ?)";
            $params[] = $q;
            $params[] = $q;
            $params[] = $q;
        }

        $sql .= " ORDER BY a.nama ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT a.id, a.nama, a.nik, a.tanggal_lahir, a.jenis_kelamin, a.nama_ortu, a.status,
                d.nama AS dusun,
                (SELECT p.kategori_status FROM pengukuran p WHERE p.balita_id = a.id ORDER BY p.tanggal_ukur DESC, p.id DESC LIMIT 1) AS status_terakhir,
                (SELECT p.berat_kg FROM pengukuran p WHERE p.balita_id = a.id ORDER BY p.tanggal_ukur DESC, p.id DESC LIMIT 1) AS berat_terakhir,
                (SELECT p.tinggi_cm FROM pengukuran p WHERE p.balita_id = a.id ORDER BY p.tanggal_ukur DESC, p.id DESC LIMIT 1) AS tinggi_terakhir,
                (SELECT p.tanggal_ukur FROM pengukuran p WHERE p.balita_id = a.id ORDER BY p.tanggal_ukur DESC, p.id DESC LIMIT 1) AS tanggal_ukur_terakhir
                FROM balita a
                JOIN dusun d ON d.id = a.dusun_id
                WHERE a.id = ? LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO balita (nama, nik, tanggal_lahir, jenis_kelamin, nama_ortu, dusun_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nama'],
            $data['nik'] ?: null,
            $data['tanggal_lahir'],
            $data['jenis_kelamin'],
            $data['nama_ortu'],
            $data['dusun_id'],
            $data['status'] ?? 'aktif',
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE balita SET nama = ?, nik = ?, tanggal_lahir = ?, jenis_kelamin = ?, nama_ortu = ?, dusun_id = ?, status = ? WHERE id = ?");
        $stmt->execute([
            $data['nama'],
            $data['nik'] ?: null,
            $data['tanggal_lahir'],
            $data['jenis_kelamin'],
            $data['nama_ortu'],
            $data['dusun_id'],
            $data['status'] ?? 'aktif',
            $id,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM balita WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
