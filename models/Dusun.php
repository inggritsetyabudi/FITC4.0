<?php
class Dusun
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        return [
            'Gamping Wetan',
            'Boharan',
            'Kemangsen',
            'Bendomungal'
        ];
    }

    public function getJadwal(): array
    {
        try {
            $stmt = $this->db->query("SELECT * FROM jadwal_kegiatan ORDER BY tanggal_kegiatan ASC");
            $rows = $stmt->fetchAll();
            if (!empty($rows)) {
                return $rows;
            }
        } catch (PDOException $e) {
        }
        return [];
    }

    public function getPosyanduPos(): array
    {
        try {
            $stmt = $this->db->query("SELECT * FROM posyandu_pos WHERE status = 'aktif' ORDER BY id ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}
