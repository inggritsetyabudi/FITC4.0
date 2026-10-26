<?php

class Berita
{
    public function __construct(private PDO $db) {}

    public function getPublished(?string $tipe = null): array
    {
        $sql = "SELECT b.id, b.judul, b.kategori, b.tipe, b.ringkasan, b.isi, b.gambar, b.published_at, u.nama AS penulis
                FROM berita b
                LEFT JOIN users u ON u.id = b.penulis_id
                WHERE b.status = 'published'";
        if ($tipe) {
            $sql .= " AND b.tipe = ?";
        }
        $sql .= " ORDER BY b.published_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($tipe ? [$tipe] : []);
        return $stmt->fetchAll();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT b.id, b.judul, b.kategori, b.tipe, b.ringkasan, b.isi, b.gambar, b.status, b.published_at, u.nama AS penulis
                FROM berita b
                LEFT JOIN users u ON u.id = b.penulis_id
                ORDER BY b.id DESC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT b.id, b.judul, b.kategori, b.tipe, b.ringkasan, b.isi, b.gambar, b.status, b.published_at, u.nama AS penulis
                FROM berita b
                LEFT JOIN users u ON u.id = b.penulis_id
                WHERE b.id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO berita (judul, kategori, tipe, ringkasan, isi, gambar, status, published_at, penulis_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['judul'],
            $data['kategori'],
            $data['tipe'],
            $data['ringkasan'],
            $data['isi'],
            $data['gambar'] ?: null,
            $data['status'],
            $data['published_at'],
            $data['penulis_id'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE berita SET judul = ?, kategori = ?, tipe = ?, ringkasan = ?, isi = ?, gambar = ?, status = ?, published_at = ? WHERE id = ?");
        $stmt->execute([
            $data['judul'],
            $data['kategori'],
            $data['tipe'],
            $data['ringkasan'],
            $data['isi'],
            $data['gambar'] ?: null,
            $data['status'],
            $data['published_at'],
            $id,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM berita WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
