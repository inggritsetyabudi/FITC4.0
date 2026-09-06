<?php
require_once __DIR__ . '/../models/Anak.php';
require_once __DIR__ . '/../models/Pengukuran.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

class PengukuranController
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

        $dusunKader = $user['dusun_binaan'] ?? '';
        $daftarBalita = $this->anakModel->getAllWithLastMeasurement($dusunKader && $user['role'] === 'kader' ? $dusunKader : null);
        if (empty($daftarBalita) && $dusunKader) {
            $daftarBalita = $this->anakModel->getAllWithLastMeasurement();
        }

        $riwayatUkur = $this->pengukuranModel->getRecentMeasurements(15);

        $selectedAnakId = (int)($_GET['anak_id'] ?? 0);
        $selectedAnak = $selectedAnakId > 0 ? $this->anakModel->getById($selectedAnakId) : null;

        $successMsg = $_GET['success'] ?? '';
        $errorMsg = $_GET['error'] ?? '';

        $pageTitle = 'Pencatatan Penimbangan — Posyandu Pintar';
        $activePage = 'pengukuran';
        $topbarTitle = 'Input Timbangan & Pengukuran Balita';
        $breadcrumb = '<a href="' . base_url('index.php?controller=kader&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Pencatatan</span>';

        require_once __DIR__ . '/../views/kader/pengukuran.php';
    }

    public function store(): void
    {
        $user = require_auth(['kader', 'admin', 'bidan']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $anakId = (int)($_POST['anak_id'] ?? 0);
            $tanggalUkur = trim($_POST['tanggal_ukur'] ?? date('Y-m-d'));
            $beratKg = (float)($_POST['berat_kg'] ?? 0);
            $tinggiCm = (float)($_POST['tinggi_cm'] ?? 0);
            $catatan = trim($_POST['catatan'] ?? '');

            if ($anakId <= 0 || $beratKg <= 0 || $tinggiCm <= 0) {
                header('Location: ' . base_url('index.php?controller=pengukuran&action=index&error=' . urlencode('Pilih balita dan masukkan angka berat serta tinggi badan yang valid.')));
                exit;
            }

            $anak = $this->anakModel->getById($anakId);
            if (!$anak) {
                header('Location: ' . base_url('index.php?controller=pengukuran&action=index&error=' . urlencode('Data balita tidak ditemukan.')));
                exit;
            }

            $umur = hitung_umur($anak['tanggal_lahir'], $tanggalUkur);
            $usiaBulan = $umur['totalBulan'];

            $bbIdealMin = ($usiaBulan * 0.2) + 7.5;
            $bbIdealMax = ($usiaBulan * 0.3) + 11.0;
            if ($usiaBulan < 12) {
                $bbIdealMin = 3.2 + ($usiaBulan * 0.5);
                $bbIdealMax = 4.2 + ($usiaBulan * 0.7);
            }

            $kategoriStatus = 'normal';
            if ($beratKg < ($bbIdealMin * 0.85)) {
                $kategoriStatus = 'prioritas';
            } elseif ($beratKg < $bbIdealMin || $beratKg > ($bbIdealMax * 1.2)) {
                $kategoriStatus = 'pemantauan';
            }

            try {
                $this->pengukuranModel->insert([
                    'anak_id'         => $anakId,
                    'tanggal_ukur'    => $tanggalUkur,
                    'usia_bulan'      => $usiaBulan,
                    'berat_kg'        => $beratKg,
                    'tinggi_cm'       => $tinggiCm,
                    'kategori_status' => $kategoriStatus,
                    'catatan'         => $catatan ?: null,
                    'dicatat_oleh_id' => $user['id']
                ]);

                header('Location: ' . base_url('index.php?controller=pengukuran&action=index&success=' . urlencode("Pengukuran untuk {$anak['nama']} berhasil dicatat! Status: " . ucfirst($kategoriStatus))));
                exit;
            } catch (Exception $e) {
                header('Location: ' . base_url('index.php?controller=pengukuran&action=index&error=' . urlencode('Gagal mencatat pengukuran: ' . $e->getMessage())));
                exit;
            }
        }

        $this->index();
    }

    public function delete(): void
    {
        $user = require_auth(['kader', 'admin']);
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($id > 0) {
            $this->pengukuranModel->delete($id);
            header('Location: ' . base_url('index.php?controller=pengukuran&action=index&success=' . urlencode('Rekaman pengukuran berhasil dihapus.')));
            exit;
        }

        header('Location: ' . base_url('index.php?controller=pengukuran&action=index'));
        exit;
    }
}
