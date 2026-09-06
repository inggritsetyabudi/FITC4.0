<?php
require_once __DIR__ . '/../models/Anak.php';
require_once __DIR__ . '/../models/Pengukuran.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

class BalitaController
{
    private PDO $db;
    private Anak $anakModel;
    private Pengukuran $pengukuranModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->anakModel = new Anak($db);
        $this->pengukuranModel = new Pengukuran($db);
    }

    public function index(): void
    {
        $user = require_auth(['kader', 'admin', 'bidan']);

        $filterDusun = trim($_GET['dusun'] ?? '');
        $searchQuery = trim($_GET['q'] ?? '');

        $daftarBalita = $this->anakModel->getAllWithLastMeasurement($filterDusun, $searchQuery);
        $daftarDusun = $this->anakModel->getDaftarDusun();

        $successMsg = $_GET['success'] ?? '';
        $errorMsg = $_GET['error'] ?? '';

        $pageTitle = 'Data Balita — Posyandu Pintar';
        $activePage = 'balita';
        $topbarTitle = 'Data Balita Desa';
        $breadcrumb = '<a href="' . base_url('index.php?controller=kader&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Data Balita</span>';

        require_once __DIR__ . '/../views/kader/balita.php';
    }

    public function store(): void
    {
        $user = require_auth(['kader', 'admin', 'bidan']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $nik = trim($_POST['nik'] ?? '');
            $tanggalLahir = trim($_POST['tanggal_lahir'] ?? '');
            $jenisKelamin = trim($_POST['jenis_kelamin'] ?? 'L');
            $namaOrtu = trim($_POST['nama_ortu'] ?? '');
            $dusun = trim($_POST['dusun'] ?? 'Gamping Wetan');

            if ($nama === '' || $tanggalLahir === '' || $namaOrtu === '') {
                header('Location: ' . base_url('index.php?controller=balita&action=index&error=' . urlencode('Nama balita, tanggal lahir, dan nama orang tua wajib diisi.')));
                exit;
            }

            try {
                $this->anakModel->insert([
                    'nama'          => $nama,
                    'nik'           => $nik,
                    'tanggal_lahir' => $tanggalLahir,
                    'jenis_kelamin' => $jenisKelamin,
                    'nama_ortu'     => $namaOrtu,
                    'dusun'         => $dusun,
                    'kader_id'      => $user['id']
                ]);

                header('Location: ' . base_url('index.php?controller=balita&action=index&success=' . urlencode("Data balita '$nama' berhasil ditambahkan!")));
                exit;
            } catch (Exception $e) {
                header('Location: ' . base_url('index.php?controller=balita&action=index&error=' . urlencode('Gagal menambahkan balita: ' . $e->getMessage())));
                exit;
            }
        }

        $this->index();
    }

    public function update(): void
    {
        $user = require_auth(['kader', 'admin', 'bidan']);
        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $nik = trim($_POST['nik'] ?? '');
            $tanggalLahir = trim($_POST['tanggal_lahir'] ?? '');
            $jenisKelamin = trim($_POST['jenis_kelamin'] ?? 'L');
            $namaOrtu = trim($_POST['nama_ortu'] ?? '');
            $dusun = trim($_POST['dusun'] ?? '');

            if ($nama && $tanggalLahir && $namaOrtu && $dusun) {
                $this->anakModel->update($id, [
                    'nama'          => $nama,
                    'nik'           => $nik,
                    'tanggal_lahir' => $tanggalLahir,
                    'jenis_kelamin' => $jenisKelamin,
                    'nama_ortu'     => $namaOrtu,
                    'dusun'         => $dusun
                ]);
                header('Location: ' . base_url('index.php?controller=balita&action=index&success=' . urlencode("Data balita '$nama' berhasil diperbarui!")));
                exit;
            }
        }

        header('Location: ' . base_url('index.php?controller=balita&action=index'));
        exit;
    }

    public function delete(): void
    {
        $user = require_auth(['kader', 'admin']);
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($id > 0) {
            $this->anakModel->delete($id);
            header('Location: ' . base_url('index.php?controller=balita&action=index&success=' . urlencode('Data balita berhasil dinonaktifkan.')));
            exit;
        }

        header('Location: ' . base_url('index.php?controller=balita&action=index'));
        exit;
    }

    public function detail(): void
    {
        $user = require_auth(['kader', 'bidan', 'admin']);
        $id = (int)($_GET['id'] ?? 0);

        $anak = $this->anakModel->getById($id);
        if (!$anak) {
            header('Location: ' . base_url('index.php?controller=balita&action=index'));
            exit;
        }

        $riwayatUkur = $this->pengukuranModel->getByAnakId($id);
        $umurSekarang = hitung_umur($anak['tanggal_lahir']);
        $terakhir = end($riwayatUkur) ?: null;

        $pageTitle = 'Rekam KMS: ' . htmlspecialchars($anak['nama']) . ' — Posyandu Pintar';
        $activePage = 'balita';
        $topbarTitle = 'Buku KMS Digital Balita';
        $breadcrumb = '<a href="' . base_url('index.php?controller=balita&action=index') . '" class="text-decoration-none text-forest">Data Balita</a> <i class="bi bi-chevron-right fs-xs"></i> <span>' . htmlspecialchars($anak['nama']) . '</span>';

        require_once __DIR__ . '/../views/shared/detailBalita.php';
    }
}
