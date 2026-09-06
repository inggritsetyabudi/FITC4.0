<?php
require_once __DIR__ . '/../models/Anak.php';
require_once __DIR__ . '/../models/Pengukuran.php';
require_once __DIR__ . '/../models/Dusun.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

class BidanController
{
    private PDO $db;
    private Anak $anakModel;
    private Pengukuran $pengukuranModel;
    private Dusun $dusunModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->anakModel = new Anak($db);
        $this->pengukuranModel = new Pengukuran($db);
        $this->dusunModel = new Dusun($db);
    }

    public function dashboard(): void
    {
        $user = require_auth(['bidan', 'admin']);

        $bulanTahunIni = '2026-08';
        $bulanNama = 'Agustus 2026';

        $totalBalita = $this->anakModel->countTotal();
        $statGizi = $this->pengukuranModel->getDistribusiStatusGizi($bulanTahunIni);
        $balitaPrioritas = $this->pengukuranModel->getPrioritas();

        $dusunList = $this->dusunModel->getAll();
        $dusunStats = [];
        foreach ($dusunList as $d) {
            $anakDusun = $this->anakModel->getAllWithLastMeasurement($d);
            $dusunStats[$d] = [
                'normal'     => count(array_filter($anakDusun, fn($a) => $a['status_terakhir'] === 'normal' || empty($a['status_terakhir']))),
                'pemantauan' => count(array_filter($anakDusun, fn($a) => $a['status_terakhir'] === 'pemantauan')),
                'prioritas'  => count(array_filter($anakDusun, fn($a) => in_array($a['status_terakhir'], ['prioritas', 'berisiko']))),
            ];
        }

        $bulanLabels = ['Mar 2026', 'Apr 2026', 'Mei 2026', 'Jun 2026', 'Jul 2026', 'Agu 2026'];
        $cakupanData = [12, 14, 13, 15, 14, 15];

        $pageTitle = 'Dasbor Bidan Desa — Posyandu Pintar';
        $activePage = 'bidan_dashboard';
        $topbarTitle = 'Pemantauan & Evaluasi Gizi Balita Desa';
        $breadcrumb = '<span>Dasbor Bidan</span>';

        require_once __DIR__ . '/../views/bidan/dashboard.php';
    }

    public function prioritas(): void
    {
        $user = require_auth(['bidan', 'admin', 'kader']);

        $filterDusun = trim($_GET['dusun'] ?? '');
        $balitaPrioritas = $this->pengukuranModel->getPrioritas($filterDusun);
        $daftarDusun = $this->dusunModel->getAll();

        $pageTitle = 'Daftar Balita Prioritas Penanganan — Posyandu Pintar';
        $activePage = 'prioritas';
        $topbarTitle = 'Balita Butuh Intervensi & Konseling Bidan';
        $breadcrumb = '<a href="' . base_url('index.php?controller=bidan&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Prioritas</span>';

        require_once __DIR__ . '/../views/bidan/prioritas.php';
    }

    public function rekap(): void
    {
        $user = require_auth(['bidan', 'admin', 'kader']);

        $bulan = trim($_GET['bulan'] ?? '2026-08');
        $filterDusun = trim($_GET['dusun'] ?? '');

        $dataRekap = $this->pengukuranModel->getRekapBulanan($bulan, $filterDusun);
        $daftarDusun = $this->dusunModel->getAll();

        $pageTitle = 'Rekapitulasi Layanan Posyandu — Posyandu Pintar';
        $activePage = 'rekap';
        $topbarTitle = 'Rekap Timbang & Pertumbuhan Balita';
        $breadcrumb = '<a href="' . base_url('index.php?controller=bidan&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Rekap Data</span>';

        require_once __DIR__ . '/../views/bidan/rekap.php';
    }

    public function export(): void
    {
        $user = require_auth(['bidan', 'admin', 'kader']);

        $bulan = trim($_GET['bulan'] ?? '2026-08');
        $filterDusun = trim($_GET['dusun'] ?? '');

        $dataRekap = $this->pengukuranModel->getRekapBulanan($bulan, $filterDusun);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Rekap_Posyandu_' . $bulan . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['No', 'Nama Balita', 'NIK', 'Tgl Lahir', 'JK', 'Nama Orang Tua', 'Dusun', 'Tgl Ukur', 'Usia (Bulan)', 'BB (kg)', 'TB (cm)', 'Status Gizi', 'Catatan']);

        $no = 1;
        foreach ($dataRekap as $r) {
            fputcsv($output, [
                $no++,
                $r['nama'],
                $r['nik'] ? "'" . $r['nik'] : '-',
                $r['tanggal_lahir'],
                $r['jenis_kelamin'],
                $r['nama_ortu'],
                $r['dusun'],
                $r['tanggal_ukur'] ?: 'Belum Ukur',
                $r['usia_bulan'] ?? '-',
                $r['berat_kg'] ?? '-',
                $r['tinggi_cm'] ?? '-',
                $r['kategori_status'] ? ucfirst($r['kategori_status']) : 'Belum Ditimbang',
                $r['catatan'] ?? '-'
            ]);
        }
        fclose($output);
        exit;
    }

    public function export_pdf(): void
    {
        $user = require_auth(['bidan', 'admin']);
        $bulan = $_GET['bulan'] ?? '2026-08';
        $filterDusun = $_GET['dusun'] ?? '';
        $dataRekap = $this->pengukuranModel->getRekapBulanan($bulan, $filterDusun ?: null);

        $tipeLaporan = 'Laporan Hasil Penimbangan & Evaluasi Pertumbuhan Balita Desa';
        $tanggalCetak = format_tanggal_id(date('Y-m-d'));
        $nomorSurat = '028/BIDAN/POSYANDU/' . date('Y');
        $namaPenandatangan = $user['nama'] ?? 'Bidan Siti, S.Tr.Keb';
        $jabatanPenandatangan = 'Bidan Desa Sukatani';

        require __DIR__ . '/../views/shared/cetak_laporan_pdf.php';
    }
}
