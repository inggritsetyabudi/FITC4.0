<?php
require_once __DIR__ . '/../models/Anak.php';
require_once __DIR__ . '/../models/Pengukuran.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

class KaderController
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

    public function dashboard(): void
    {
        $user = require_auth(['kader', 'admin', 'bidan']);
        $dusunKader = $user['dusun_binaan'] ?? '';

        $semuaAnak = $this->anakModel->getAllWithLastMeasurement($dusunKader && $user['role'] === 'kader' ? $dusunKader : null);
        if (empty($semuaAnak) && $dusunKader) {
            $semuaAnak = $this->anakModel->getAllWithLastMeasurement();
        }

        $totalAnak = count($semuaAnak);
        $bulanTahunIni = '2026-08';
        $bulanNama = 'Agustus 2026';

        $anakSudahUkurIds = $this->pengukuranModel->getAnakSudahUkurBulanIni($bulanTahunIni);

        $daftarBelumDitimbang = [];
        $daftarPerluPerhatian = [];

        foreach ($semuaAnak as $anak) {
            $lastUkur = $this->pengukuranModel->getLastMeasurement($anak['id']);
            $sudahBulanIni = in_array($anak['id'], $anakSudahUkurIds);

            if (!$sudahBulanIni) {
                $daftarBelumDitimbang[] = [
                    'anak'     => $anak,
                    'terakhir' => $lastUkur
                ];
            }

            if ($lastUkur && in_array($lastUkur['kategori_status'], ['pemantauan', 'prioritas', 'berisiko'])) {
                $daftarPerluPerhatian[] = [
                    'anak'       => $anak,
                    'pengukuran' => $lastUkur
                ];
            }
        }

        $totalSudahUkur = $totalAnak - count($daftarBelumDitimbang);
        $persenSelesai = $totalAnak > 0 ? round(($totalSudahUkur / $totalAnak) * 100) : 0;

        $pageTitle = 'Dasbor Kader — Posyandu Pintar';
        $activePage = 'kader_dashboard';
        $topbarTitle = 'Dasbor Pelayanan Balita';
        $breadcrumb = '<span>Dasbor</span>';

        require_once __DIR__ . '/../views/kader/dashboard.php';
    }
}
