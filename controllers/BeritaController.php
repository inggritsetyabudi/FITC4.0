<?php
require_once __DIR__ . '/../models/Berita.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

class BeritaController
{
    private PDO $db;
    private Berita $beritaModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->beritaModel = new Berita($db);
    }

    public function detail(): void
    {
        $id = $_GET['id'] ?? 'art_01';
        $berita = $this->beritaModel->getById($id);

        if (!$berita) {
            header('Location: ' . base_url('index.php#berita'));
            exit;
        }

        $semuaBerita = $this->beritaModel->getPublished(4);
        $beritaLainnya = array_filter($semuaBerita, fn($b) => $b['id'] != $berita['id']);

        $pageTitle = htmlspecialchars($berita['judul']) . ' — Posyandu Pintar';
        $pageDesc = htmlspecialchars($berita['ringkasan']);

        require_once __DIR__ . '/../views/shared/detailBerita.php';
    }
}
