<?php

class Jadwal
{
    public function __construct(private PDO $db) {}

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT j.id, j.nama_kegiatan, d.nama AS dusun, j.lokasi, j.tanggal,
                TIME_FORMAT(j.waktu_mulai, '%H:%i') AS waktu_mulai,
                TIME_FORMAT(j.waktu_selesai, '%H:%i') AS waktu_selesai,
                j.status, j.petugas
                FROM jadwal j
                JOIN dusun d ON d.id = j.dusun_id
                ORDER BY j.tanggal ASC");
        return $stmt->fetchAll();
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO jadwal (nama_kegiatan, dusun_id, lokasi, tanggal, waktu_mulai, waktu_selesai, status, petugas) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nama_kegiatan'],
            $data['dusun_id'],
            $data['lokasi'],
            $data['tanggal'],
            $data['waktu_mulai'],
            $data['waktu_selesai'],
            $data['status'] ?? 'rencana',
            $data['petugas'] ?: null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE jadwal SET nama_kegiatan = ?, dusun_id = ?, lokasi = ?, tanggal = ?, waktu_mulai = ?, waktu_selesai = ?, status = ?, petugas = ? WHERE id = ?");
        $stmt->execute([
            $data['nama_kegiatan'],
            $data['dusun_id'],
            $data['lokasi'],
            $data['tanggal'],
            $data['waktu_mulai'],
            $data['waktu_selesai'],
            $data['status'] ?? 'rencana',
            $data['petugas'] ?: null,
            $id,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM jadwal WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
