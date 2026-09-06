<?php
require_once __DIR__ . '/../models/Berita.php';
require_once __DIR__ . '/../models/Dusun.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

class BerandaController
{
    private PDO $db;
    private Berita $beritaModel;
    private Dusun $dusunModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->beritaModel = new Berita($db);
        $this->dusunModel = new Dusun($db);
    }

    public function index(): void
    {
        $daftarBerita = $this->beritaModel->getPublished(6);
        $jadwalDusun = $this->dusunModel->getJadwal();
        $pageTitle = 'Posyandu Pintar — Layanan Tumbuh Kembang & Skrining Balita Desa';
        $pageDesc = 'Sistem digital pencatatan tumbuh kembang balita dan pemantauan pertumbuhan untuk Posyandu desa.';

        require_once __DIR__ . '/../views/landingPage/beranda.php';
    }
}
