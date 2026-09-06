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
            'Kasengan',
            'Bendomungal'
        ];
    }

    public function getJadwal(): array
    {
        return [
            [
                'dusun'   => 'Gamping Wetan',
                'tempat'  => 'Balai RW 01 Dusun Gamping Wetan',
                'waktu'   => 'Senin Minggu ke-1, Pukul 08.30 – 11.00 WIB',
                'kader'   => 'Bu Sari & Tim Posyandu Mawar',
                'layanan' => 'Timbang Balita, KMS, Vitamin A, Konseling MPASI'
            ],
            [
                'dusun'   => 'Boharan',
                'tempat'  => 'Posko Kesehatan RW 02 Boharan',
                'waktu'   => 'Rabu Minggu ke-1, Pukul 08.30 – 11.00 WIB',
                'kader'   => 'Bu Rini & Tim Posyandu Melati',
                'layanan' => 'Pengukuran Antropometri, PMT Balita, Tinjauan Bidan'
            ],
            [
                'dusun'   => 'Kasengan',
                'tempat'  => 'Balai Warga RW 03 Kasengan',
                'waktu'   => 'Kamis Minggu ke-2, Pukul 09.00 – 11.30 WIB',
                'kader'   => 'Bu Nurul & Tim Posyandu Anggrek',
                'layanan' => 'Skrining Pertumbuhan, Pemberian Kapsul Vitamin, Imunisasi'
            ],
            [
                'dusun'   => 'Bendomungal',
                'tempat'  => 'Gedung Serbaguna RW 04 Bendomungal',
                'waktu'   => 'Sabtu Minggu ke-2, Pukul 08.00 – 10.30 WIB',
                'kader'   => 'Bu Khadijah & Tim Posyandu Kenanga',
                'layanan' => 'Pencatatan KMS Digital, Pembagian Telur PMT, Skrining Bidan'
            ]
        ];
    }
}
