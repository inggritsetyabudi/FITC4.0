<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../models/Pengukuran.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

class AdminController
{
    private User $userModel;
    private AdminModel $adminModel;
    private Pengukuran $pengukuranModel;

    public function __construct(PDO $db)
    {
        $this->userModel = new User($db);
        $this->adminModel = new AdminModel($db);
        $this->pengukuranModel = new Pengukuran($db);
    }

    public function dashboard(): void
    {
        require_auth(['admin']);
        $stats = $this->adminModel->getMacroStats();

        $pageTitle = 'Dasbor Admin — Posyandu Pintar';
        $activePage = 'admin_dashboard';
        $topbarTitle = 'Pusat Kendali Pemantauan Balita';
        $breadcrumb = '<span>Dasbor Utama</span>';

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function kader(): void
    {
        $user = require_auth(['admin']);
        $successMsg = '';
        $errorMsg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subAction = $_POST['sub_action'] ?? 'store';
            if ($subAction === 'store') {
                $nama = trim($_POST['nama'] ?? '');
                $username = trim($_POST['username'] ?? '');
                $password = trim($_POST['password'] ?? '123');
                $role = $_POST['role'] ?? 'kader';
                $dusun = $_POST['dusun_binaan'] ?? '';
                $sapaan = trim($_POST['sapaan'] ?? $nama);
                $telepon = trim($_POST['telepon'] ?? '');

                if ($nama === '' || $username === '') {
                    $errorMsg = 'Nama lengkap dan username wajib diisi.';
                } elseif ($this->userModel->findByUsername($username)) {
                    $errorMsg = "Username '$username' sudah digunakan.";
                } else {
                    $created = $this->userModel->create([
                        'nama' => $nama,
                        'username' => $username,
                        'password' => password_hash($password, PASSWORD_DEFAULT),
                        'role' => $role,
                        'dusun_binaan' => $dusun,
                        'sapaan' => $sapaan,
                        'telepon' => $telepon
                    ]);
                    $successMsg = $created ? "Akun $role atas nama $nama berhasil dibuat." : 'Gagal membuat akun.';
                }
            } elseif ($subAction === 'reset_pass') {
                $targetId = (int)($_POST['id'] ?? 0);
                if ($targetId > 0) {
                    $this->userModel->resetPassword($targetId, '123');
                    $successMsg = 'Password berhasil direset menjadi 123.';
                }
            } elseif ($subAction === 'delete') {
                $targetId = (int)($_POST['id'] ?? 0);
                if ($targetId > 0 && $targetId !== (int)$user['id']) {
                    $this->userModel->delete($targetId);
                    $successMsg = 'Akun berhasil dihapus.';
                }
            }
        }

        $daftarKader = $this->userModel->getByRole('kader');
        $daftarBidan = $this->userModel->getByRole('bidan');
        $daftarDusun = ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];

        $pageTitle = 'Kader & Bidan — Posyandu Pintar';
        $activePage = 'admin_kader';
        $topbarTitle = 'Petugas Pemantauan Balita';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Kader &amp; Bidan</span>';

        require_once __DIR__ . '/../views/admin/kader.php';
    }

    public function laporan(): void
    {
        require_auth(['admin']);

        $bulan = trim($_GET['bulan'] ?? date('Y-m'));
        $dataRekap = $this->pengukuranModel->getRekapBulanan($bulan, null);

        $pageTitle = 'Laporan Balita — Posyandu Pintar';
        $activePage = 'admin_laporan';
        $topbarTitle = 'Laporan Pertumbuhan Balita (PDF & CSV)';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Laporan Balita</span>';

        require_once __DIR__ . '/../views/admin/laporan.php';
    }

    public function export_pdf(): void
    {
        $user = require_auth(['admin']);
        $bulan = trim($_GET['bulan'] ?? date('Y-m'));
        $dataRekap = $this->pengukuranModel->getRekapBulanan($bulan, null);

        $tipeLaporan = 'Laporan Pertumbuhan & Hasil Pengukuran Balita';
        $tanggalCetak = format_tanggal_id(date('Y-m-d'));
        $nomorSurat = '045/POSYANDU/' . date('Y');
        $namaPenandatangan = $user['nama'] ?? 'Administrator Posyandu';
        $jabatanPenandatangan = 'Administrator Posyandu Desa Sukatani';

        require __DIR__ . '/../views/shared/cetak_laporan_pdf.php';
    }

    public function export_csv(): void
    {
        require_auth(['admin']);
        $bulan = trim($_GET['bulan'] ?? date('Y-m'));
        $dataRekap = $this->pengukuranModel->getRekapBulanan($bulan, null);

        $filename = 'Data_Balita_Posyandu_' . $bulan . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['No', 'Nama Balita', 'NIK', 'Tgl Lahir', 'JK', 'Nama Orang Tua', 'Dusun', 'Tgl Ukur', 'Usia (Bulan)', 'BB (kg)', 'TB (cm)', 'Status Gizi', 'Catatan']);

        $no = 1;
        foreach ($dataRekap as $r) {
            fputcsv($output, [
                $no++, $r['nama'], $r['nik'] ? "'" . $r['nik'] : '-', $r['tanggal_lahir'], $r['jenis_kelamin'],
                $r['nama_ortu'], $r['dusun'], $r['tanggal_ukur'] ?: 'Belum Ukur', $r['usia_bulan'] ?? '-',
                $r['berat_kg'] ?? '-', $r['tinggi_cm'] ?? '-', $r['kategori_status'] ? ucfirst($r['kategori_status']) : 'Belum Ditimbang', $r['catatan'] ?? '-'
            ]);
        }
        fclose($output);
        exit;
    }

    public function jadwal(): void
    {
        require_auth(['admin']);
        $successMsg = '';
        $errorMsg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $judul = trim($_POST['judul_agenda'] ?? '');
            $dusun = $_POST['dusun'] ?? 'Gamping Wetan';
            $tgl = $_POST['tanggal_kegiatan'] ?? '';
            $mulai = $_POST['jam_mulai'] ?? '08:00:00';
            $selesai = $_POST['jam_selesai'] ?? '11:30:00';
            $lokasi = trim($_POST['lokasi'] ?? '');
            $pj = trim($_POST['penanggung_jawab'] ?? '');

            if ($judul === '' || $tgl === '' || $lokasi === '') {
                $errorMsg = 'Judul, tanggal, dan lokasi wajib diisi.';
            } else {
                $created = $this->adminModel->createJadwal([
                    'judul_agenda' => $judul,
                    'jenis_layanan' => 'Penimbangan Balita',
                    'dusun' => $dusun,
                    'tanggal_kegiatan' => $tgl,
                    'jam_mulai' => $mulai,
                    'jam_selesai' => $selesai,
                    'lokasi' => $lokasi,
                    'penanggung_jawab' => $pj ?: 'Kader Posyandu',
                    'keterangan' => 'Penimbangan dan pemantauan tumbuh kembang balita.',
                    'status' => 'rencana'
                ]);
                $successMsg = $created ? "Jadwal $judul berhasil ditambahkan." : 'Gagal menyimpan jadwal.';
            }
        }

        $daftarJadwal = $this->adminModel->getJadwal($_GET['status'] ?? 'semua');
        $daftarDusun = ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];

        $pageTitle = 'Jadwal Posyandu — Posyandu Pintar';
        $activePage = 'admin_jadwal';
        $topbarTitle = 'Jadwal Penimbangan Balita';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Jadwal Posyandu</span>';

        require_once __DIR__ . '/../views/admin/jadwal.php';
    }
}
