<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Anak.php';
require_once __DIR__ . '/../models/Pengukuran.php';
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

class AdminController
{
    private PDO $db;
    private User $userModel;
    private Anak $anakModel;
    private Pengukuran $pengukuranModel;
    private AdminModel $adminModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->anakModel = new Anak($db);
        $this->pengukuranModel = new Pengukuran($db);
        $this->adminModel = new AdminModel($db);
    }

    public function dashboard(): void
    {
        $user = require_auth(['admin']);
        $stats = $this->adminModel->getMacroStats();
        $auditLogs = $this->adminModel->getAuditLogs(6);
        $jadwalTerdekat = $this->adminModel->getJadwal('rencana');
        $profilDesa = $this->adminModel->getProfilDesa();

        $pageTitle = 'Dasbor Pemerintah Desa — Posyandu Pintar';
        $activePage = 'admin_dashboard';
        $topbarTitle = 'Pusat Kendali Layanan Posyandu Terpadu';
        $breadcrumb = '<span>Dasbor Utama</span>';

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function penduduk(): void
    {
        $user = require_auth(['admin']);
        $successMsg = '';
        $errorMsg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subAction = $_POST['sub_action'] ?? 'store';
            if ($subAction === 'store') {
                $nik = trim($_POST['nik'] ?? '');
                $nama = trim($_POST['nama'] ?? '');
                $tanggalLahir = $_POST['tanggal_lahir'] ?? '';
                $jk = $_POST['jenis_kelamin'] ?? 'L';
                $dusun = $_POST['dusun'] ?? 'Gamping Wetan';

                if ($nik === '' || $nama === '' || $tanggalLahir === '') {
                    $errorMsg = 'NIK, Nama Warga, dan Tanggal Lahir wajib diisi.';
                } else {
                    $created = $this->adminModel->createWarga([
                        'nik'           => $nik,
                        'no_kk'         => trim($_POST['no_kk'] ?? ''),
                        'nama'          => $nama,
                        'tanggal_lahir' => $tanggalLahir,
                        'jenis_kelamin' => $jk,
                        'dusun'         => $dusun,
                        'rt_rw'         => trim($_POST['rt_rw'] ?? '001/001'),
                        'telepon'       => trim($_POST['telepon'] ?? ''),
                        'kategori'      => $_POST['kategori'] ?? 'umum',
                        'status'        => 'aktif'
                    ]);
                    if ($created) {
                        $this->adminModel->log($user['nama'], 'admin', 'TAMBAH', 'Data Warga', "Menambahkan data warga baru: $nama ($nik)");
                        $successMsg = "Data warga $nama berhasil disimpan.";
                    } else {
                        $errorMsg = 'Gagal menyimpan data warga. Pastikan NIK belum terdaftar.';
                    }
                }
            } elseif ($subAction === 'toggle') {
                $wargaId = (int)($_POST['id'] ?? 0);
                if ($wargaId > 0) {
                    $this->adminModel->toggleStatusWarga($wargaId);
                    $this->adminModel->log($user['nama'], 'admin', 'UPDATE', 'Data Warga', "Mengubah status aktif/nonaktif warga ID: $wargaId");
                    $successMsg = 'Status warga berhasil diperbarui.';
                }
            }
        }

        $filterDusun = $_GET['dusun'] ?? 'semua';
        $filterKategori = $_GET['kategori'] ?? 'semua';
        $searchQuery = $_GET['q'] ?? '';

        $daftarWarga = $this->adminModel->getWarga($filterDusun, $filterKategori, $searchQuery);
        $daftarDusun = ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];

        $pageTitle = 'Data Kependudukan — Posyandu Pintar';
        $activePage = 'admin_penduduk';
        $topbarTitle = 'Master Data Penduduk & Sasaran Posyandu';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Data Warga</span>';

        require_once __DIR__ . '/../views/admin/penduduk.php';
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
                    $errorMsg = 'Nama lengkap dan nama pengguna wajib diisi.';
                } else {
                    $existing = $this->userModel->findByUsername($username);
                    if ($existing) {
                        $errorMsg = "Nama pengguna '$username' sudah digunakan petugas lain.";
                    } else {
                        $created = $this->userModel->create([
                            'nama'         => $nama,
                            'username'     => $username,
                            'password'     => password_hash($password, PASSWORD_DEFAULT),
                            'role'         => $role,
                            'dusun_binaan' => $dusun,
                            'sapaan'       => $sapaan,
                            'telepon'      => $telepon
                        ]);
                        if ($created) {
                            $this->adminModel->log($user['nama'], 'admin', 'TAMBAH', 'Manajemen Petugas', "Mendaftarkan akun petugas baru: $nama ($username) sebagai $role");
                            $successMsg = "Akun $role atas nama $nama berhasil didaftarkan.";
                        } else {
                            $errorMsg = 'Gagal mendaftarkan akun petugas baru.';
                        }
                    }
                }
            } elseif ($subAction === 'reset_pass') {
                $targetId = (int)($_POST['id'] ?? 0);
                if ($targetId > 0) {
                    $this->userModel->resetPassword($targetId, '123');
                    $this->adminModel->log($user['nama'], 'admin', 'RESET_PASS', 'Manajemen Petugas', "Mereset kata sandi petugas ID $targetId ke '123'");
                    $successMsg = "Kata sandi petugas berhasil direset ke nilai awal ('123').";
                }
            } elseif ($subAction === 'delete') {
                $targetId = (int)($_POST['id'] ?? 0);
                if ($targetId > 0 && $targetId !== (int)$user['id']) {
                    $this->userModel->delete($targetId);
                    $this->adminModel->log($user['nama'], 'admin', 'HAPUS', 'Manajemen Petugas', "Menghapus akun petugas ID: $targetId");
                    $successMsg = 'Akun petugas berhasil dihapus dari sistem.';
                }
            }
        }

        $daftarKader = $this->userModel->getByRole('kader');
        $daftarBidan = $this->userModel->getByRole('bidan');
        $daftarDusun = ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];

        $pageTitle = 'Kelola Kader & Bidan — Posyandu Pintar';
        $activePage = 'admin_kader';
        $topbarTitle = 'Manajemen Petugas Posyandu Desa';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Kader &amp; Bidan</span>';

        require_once __DIR__ . '/../views/admin/kader.php';
    }

    public function posyandu(): void
    {
        $user = require_auth(['admin']);
        $successMsg = '';
        $errorMsg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $namaPos = trim($_POST['nama_pos'] ?? '');
            $dusun = $_POST['dusun'] ?? 'Gamping Wetan';
            $alamat = trim($_POST['lokasi_alamat'] ?? '');
            $jadwal = trim($_POST['jadwal_rutin'] ?? '');
            $kader = trim($_POST['kader_utama'] ?? '');
            $telp = trim($_POST['telepon'] ?? '');

            if ($namaPos === '' || $alamat === '') {
                $errorMsg = 'Nama posyandu dan alamat lokasi wajib diisi.';
            } else {
                $created = $this->adminModel->createPosyandu([
                    'nama_pos'      => $namaPos,
                    'dusun'         => $dusun,
                    'lokasi_alamat' => $alamat,
                    'jadwal_rutin'  => $jadwal ?: 'Minggu ke-1 setiap bulan',
                    'kader_utama'   => $kader ?: 'Kader Posyandu',
                    'telepon'       => $telp,
                    'status'        => 'aktif'
                ]);
                if ($created) {
                    $this->adminModel->log($user['nama'], 'admin', 'TAMBAH', 'Pos Posyandu', "Menambahkan pos posyandu baru: $namaPos ($dusun)");
                    $successMsg = "Pos Posyandu $namaPos berhasil disimpan.";
                }
            }
        }

        $daftarPos = $this->adminModel->getPosyandu();
        $daftarDusun = ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];

        $pageTitle = 'Daftar Pos Posyandu — Posyandu Pintar';
        $activePage = 'admin_posyandu';
        $topbarTitle = 'Manajemen Pos Layanan Posyandu Wilayah Desa';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Pos Posyandu</span>';

        require_once __DIR__ . '/../views/admin/posyandu.php';
    }

    public function ibu_hamil(): void
    {
        $user = require_auth(['admin']);
        $successMsg = '';
        $errorMsg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $nik = trim($_POST['nik'] ?? '');
            $suami = trim($_POST['nama_suami'] ?? '');
            $dusun = $_POST['dusun'] ?? 'Gamping Wetan';
            $usia = (int)($_POST['usia_kehamilan_minggu'] ?? 12);
            $hpl = $_POST['hpl'] ?? '';
            $statusKehamilan = $_POST['status_kehamilan'] ?? 'sehat';
            $catatan = trim($_POST['catatan'] ?? '');

            if ($nama === '' || $nik === '' || $hpl === '') {
                $errorMsg = 'Nama Ibu Hamil, NIK, dan Hari Perkiraan Lahir (HPL) wajib diisi.';
            } else {
                $created = $this->adminModel->createIbuHamil([
                    'nama'                  => $nama,
                    'nik'                   => $nik,
                    'nama_suami'            => $suami,
                    'dusun'                 => $dusun,
                    'usia_kehamilan_minggu' => $usia,
                    'hpl'                   => $hpl,
                    'status_kehamilan'      => $statusKehamilan,
                    'catatan'               => $catatan,
                    'status'                => 'aktif'
                ]);
                if ($created) {
                    $this->adminModel->log($user['nama'], 'admin', 'TAMBAH', 'Ibu Hamil', "Mendaftarkan ibu hamil baru: $nama ($dusun)");
                    $successMsg = "Data ibu hamil $nama berhasil didaftarkan.";
                }
            }
        }

        $filterDusun = $_GET['dusun'] ?? 'semua';
        $filterStatus = $_GET['status'] ?? 'semua';
        $daftarBumil = $this->adminModel->getIbuHamil($filterDusun, $filterStatus);
        $daftarDusun = ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];

        $pageTitle = 'Data Ibu Hamil — Posyandu Pintar';
        $activePage = 'admin_ibu_hamil';
        $topbarTitle = 'Pemantauan &amp; Skrining Risiko Ibu Hamil Desa';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Ibu Hamil</span>';

        require_once __DIR__ . '/../views/admin/ibu_hamil.php';
    }

    public function lansia(): void
    {
        $user = require_auth(['admin']);
        $successMsg = '';
        $errorMsg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $nik = trim($_POST['nik'] ?? '');
            $tgl = $_POST['tanggal_lahir'] ?? '';
            $jk = $_POST['jenis_kelamin'] ?? 'L';
            $dusun = $_POST['dusun'] ?? 'Gamping Wetan';
            $tensi = trim($_POST['tensi_terakhir'] ?? '120/80');
            $gula = trim($_POST['gula_darah'] ?? '110 mg/dL');
            $keluhan = trim($_POST['keluhan'] ?? '');
            $statusKesehatan = $_POST['status_kesehatan'] ?? 'sehat';

            if ($nama === '' || $nik === '' || $tgl === '') {
                $errorMsg = 'Nama Lansia, NIK, dan Tanggal Lahir wajib diisi.';
            } else {
                $created = $this->adminModel->createLansia([
                    'nama'             => $nama,
                    'nik'              => $nik,
                    'tanggal_lahir'    => $tgl,
                    'jenis_kelamin'    => $jk,
                    'dusun'            => $dusun,
                    'tensi_terakhir'   => $tensi,
                    'gula_darah'       => $gula,
                    'keluhan'          => $keluhan,
                    'status_kesehatan' => $statusKesehatan,
                    'status'           => 'aktif'
                ]);
                if ($created) {
                    $this->adminModel->log($user['nama'], 'admin', 'TAMBAH', 'Lansia', "Mendaftarkan peserta lansia baru: $nama ($dusun)");
                    $successMsg = "Data peserta lansia $nama berhasil disimpan.";
                }
            }
        }

        $filterDusun = $_GET['dusun'] ?? 'semua';
        $filterStatus = $_GET['status'] ?? 'semua';
        $daftarLansia = $this->adminModel->getLansia($filterDusun, $filterStatus);
        $daftarDusun = ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];

        $pageTitle = 'Data Peserta Lansia — Posyandu Pintar';
        $activePage = 'admin_lansia';
        $topbarTitle = 'Kesehatan &amp; Skrining Penyakit Tidak Menular Lansia';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Peserta Lansia</span>';

        require_once __DIR__ . '/../views/admin/lansia.php';
    }

    public function pemeriksaan(): void
    {
        $user = require_auth(['admin']);
        $filterDusun = $_GET['dusun'] ?? 'semua';
        $filterStatus = $_GET['status'] ?? 'semua';

        $daftarPemeriksaan = $this->adminModel->getPemeriksaanRekap($filterDusun, $filterStatus);
        $daftarDusun = ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];

        $pageTitle = 'Audit Rekap Pemeriksaan — Posyandu Pintar';
        $activePage = 'admin_pemeriksaan';
        $topbarTitle = 'Audit &amp; Log Rekap Hasil Pemeriksaan Lapangan';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Hasil Pemeriksaan</span>';

        require_once __DIR__ . '/../views/admin/pemeriksaan.php';
    }

    public function jadwal(): void
    {
        $user = require_auth(['admin']);
        $successMsg = '';
        $errorMsg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $judul = trim($_POST['judul_agenda'] ?? '');
            $jenis = trim($_POST['jenis_layanan'] ?? 'Penimbangan Rutin');
            $dusun = $_POST['dusun'] ?? 'Gamping Wetan';
            $tgl = $_POST['tanggal_kegiatan'] ?? '';
            $mulai = $_POST['jam_mulai'] ?? '08:00:00';
            $selesai = $_POST['jam_selesai'] ?? '11:30:00';
            $lokasi = trim($_POST['lokasi'] ?? '');
            $pj = trim($_POST['penanggung_jawab'] ?? '');
            $ket = trim($_POST['keterangan'] ?? '');

            if ($judul === '' || $tgl === '' || $lokasi === '') {
                $errorMsg = 'Judul agenda, tanggal, dan lokasi kegiatan wajib diisi.';
            } else {
                $created = $this->adminModel->createJadwal([
                    'judul_agenda'     => $judul,
                    'jenis_layanan'    => $jenis,
                    'dusun'            => $dusun,
                    'tanggal_kegiatan' => $tgl,
                    'jam_mulai'        => $mulai,
                    'jam_selesai'      => $selesai,
                    'lokasi'           => $lokasi,
                    'penanggung_jawab' => $pj ?: 'Kader Posyandu',
                    'keterangan'       => $ket,
                    'status'           => 'rencana'
                ]);
                if ($created) {
                    $this->adminModel->log($user['nama'], 'admin', 'TAMBAH', 'Jadwal', "Menambahkan jadwal agenda baru: $judul pada $tgl ($dusun)");
                    $successMsg = "Jadwal kegiatan $judul berhasil diagendakan.";
                }
            }
        }

        $filterStatus = $_GET['status'] ?? 'semua';
        $daftarJadwal = $this->adminModel->getJadwal($filterStatus);
        $daftarDusun = ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];

        $pageTitle = 'Jadwal Posyandu — Posyandu Pintar';
        $activePage = 'admin_jadwal';
        $topbarTitle = 'Manajemen Kalender &amp; Agenda Layanan Posyandu';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Jadwal Kegiatan</span>';

        require_once __DIR__ . '/../views/admin/jadwal.php';
    }

    public function kegiatan(): void
    {
        $user = require_auth(['admin']);
        $daftarJadwal = $this->adminModel->getJadwal();

        $pageTitle = 'Dokumentasi &amp; Kegiatan — Posyandu Pintar';
        $activePage = 'admin_kegiatan';
        $topbarTitle = 'Dokumentasi Pelaksanaan Program Kesehatan Desa';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Kegiatan</span>';

        require_once __DIR__ . '/../views/admin/kegiatan.php';
    }

    public function laporan(): void
    {
        $user = require_auth(['admin']);
        $stats = $this->adminModel->getMacroStats();
        $profilDesa = $this->adminModel->getProfilDesa();

        $pageTitle = 'Laporan &amp; Statistik — Posyandu Pintar';
        $activePage = 'admin_laporan';
        $topbarTitle = 'Pusat Unduh Laporan Eksekutif Desa (PDF &amp; CSV)';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Laporan Terpadu</span>';

        require_once __DIR__ . '/../views/admin/laporan.php';
    }

    public function notifikasi(): void
    {
        $user = require_auth(['admin']);
        $successMsg = '';
        $errorMsg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $judul = trim($_POST['judul'] ?? '');
            $pesan = trim($_POST['pesan'] ?? '');
            $target = $_POST['target'] ?? 'semua';

            if ($judul === '' || $pesan === '') {
                $errorMsg = 'Judul pengumuman dan isi pesan wajib diisi.';
            } else {
                $created = $this->adminModel->createNotifikasi([
                    'judul'      => $judul,
                    'pesan'      => $pesan,
                    'target'     => $target,
                    'dibuat_oleh'=> $user['nama'],
                    'status'     => 'terkirim'
                ]);
                if ($created) {
                    $this->adminModel->log($user['nama'], 'admin', 'KIRIM_NOTIF', 'Notifikasi', "Mengirimkan siaran pengumuman: $judul (Target: $target)");
                    $successMsg = 'Pengumuman siaran berhasil diterbitkan ke seluruh penerima.';
                }
            }
        }

        $daftarNotif = $this->adminModel->getNotifikasi();

        $pageTitle = 'Pengumuman &amp; Notifikasi — Posyandu Pintar';
        $activePage = 'admin_notifikasi';
        $topbarTitle = 'Pusat Informasi &amp; Siaran Pengumuman Posyandu';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Pengumuman</span>';

        require_once __DIR__ . '/../views/admin/notifikasi.php';
    }

    public function users(): void
    {
        $user = require_auth(['admin']);
        $successMsg = '';
        $errorMsg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subAction = $_POST['sub_action'] ?? '';
            if ($subAction === 'create') {
                $nama = trim($_POST['nama'] ?? '');
                $username = trim($_POST['username'] ?? '');
                $password = trim($_POST['password'] ?? '123');
                $role = $_POST['role'] ?? 'kader';
                $dusun = $_POST['dusun_binaan'] ?? '';
                $telp = trim($_POST['telepon'] ?? '');

                if ($nama === '' || $username === '') {
                    $errorMsg = 'Nama dan username wajib diisi.';
                } else {
                    $created = $this->userModel->create([
                        'nama'         => $nama,
                        'username'     => $username,
                        'password'     => password_hash($password, PASSWORD_DEFAULT),
                        'role'         => $role,
                        'dusun_binaan' => $dusun,
                        'sapaan'       => $nama,
                        'telepon'      => $telp
                    ]);
                    if ($created) {
                        $this->adminModel->log($user['nama'], 'admin', 'TAMBAH', 'User', "Membuat akun baru: $username ($role)");
                        $successMsg = "Akun $username berhasil dibuat.";
                    }
                }
            } elseif ($subAction === 'reset') {
                $targetId = (int)($_POST['id'] ?? 0);
                if ($targetId > 0) {
                    $this->userModel->resetPassword($targetId, '123');
                    $this->adminModel->log($user['nama'], 'admin', 'RESET_PASS', 'User', "Reset password user ID $targetId");
                    $successMsg = 'Password berhasil direset ke nilai awal (123).';
                }
            } elseif ($subAction === 'delete') {
                $targetId = (int)($_POST['id'] ?? 0);
                if ($targetId > 0 && $targetId !== (int)$user['id']) {
                    $this->userModel->delete($targetId);
                    $this->adminModel->log($user['nama'], 'admin', 'HAPUS', 'User', "Menghapus akun user ID $targetId");
                    $successMsg = 'Akun berhasil dihapus.';
                }
            }
        }

        $daftarUsers = $this->userModel->getAll();
        $daftarDusun = ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];

        $pageTitle = 'Manajemen Pengguna — Posyandu Pintar';
        $activePage = 'admin_users';
        $topbarTitle = 'Pengelolaan Akun Petugas &amp; Hak Akses';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Akun Petugas</span>';

        require_once __DIR__ . '/../views/admin/users.php';
    }

    public function profil_desa(): void
    {
        $user = require_auth(['admin']);
        $successMsg = '';
        $errorMsg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updated = $this->adminModel->updateProfilDesa([
                'nama_desa'     => trim($_POST['nama_desa'] ?? 'Desa Sukatani'),
                'kecamatan'     => trim($_POST['kecamatan'] ?? 'Kecamatan Balongbendo'),
                'kabupaten'     => trim($_POST['kabupaten'] ?? 'Kabupaten Sidoarjo'),
                'kepala_desa'   => trim($_POST['kepala_desa'] ?? ''),
                'alamat_kantor' => trim($_POST['alamat_kantor'] ?? ''),
                'telepon'       => trim($_POST['telepon'] ?? ''),
                'email'         => trim($_POST['email'] ?? ''),
                'kode_pos'      => trim($_POST['kode_pos'] ?? '61263'),
                'visi_misi'     => trim($_POST['visi_misi'] ?? '')
            ]);
            if ($updated) {
                $this->adminModel->log($user['nama'], 'admin', 'UPDATE', 'Profil Desa', 'Memperbarui informasi identitas Desa Sukatani');
                $successMsg = 'Profil informasi desa berhasil diperbarui.';
            }
        }

        $profil = $this->adminModel->getProfilDesa();

        $pageTitle = 'Profil Desa — Posyandu Pintar';
        $activePage = 'admin_profil_desa';
        $topbarTitle = 'Identitas Desa &amp; Struktur Layanan Posyandu';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Profil Desa</span>';

        require_once __DIR__ . '/../views/admin/profil_desa.php';
    }

    public function audit_log(): void
    {
        $user = require_auth(['admin']);
        $logs = $this->adminModel->getAuditLogs(100);

        $pageTitle = 'Audit Log Aktivitas — Posyandu Pintar';
        $activePage = 'admin_audit_log';
        $topbarTitle = 'Riwayat Rekam Jejak Aktivitas Pengguna';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Audit Log</span>';

        require_once __DIR__ . '/../views/admin/audit_log.php';
    }

    public function backup(): void
    {
        $user = require_auth(['admin']);
        $downloadType = $_GET['download'] ?? '';

        if ($downloadType === 'json') {
            $json = $this->adminModel->getBackupJson();
            $filename = 'Backup_Posyandu_' . date('Ymd_His') . '.json';
            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            echo $json;
            exit;
        }

        $pageTitle = 'Backup &amp; Arsip Data — Posyandu Pintar';
        $activePage = 'admin_backup';
        $topbarTitle = 'Cadangan Basis Data &amp; Pemulihan Sistem';
        $breadcrumb = '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Backup Data</span>';

        require_once __DIR__ . '/../views/admin/backup.php';
    }

    public function export_pdf(): void
    {
        $user = require_auth(['admin']);
        $stats = $this->adminModel->getMacroStats();
        $profil = $this->adminModel->getProfilDesa();
        $daftarWarga = $this->adminModel->getWarga();
        $daftarBumil = $this->adminModel->getIbuHamil();
        $daftarLansia = $this->adminModel->getLansia();

        $tipeLaporan = 'Laporan Eksekutif Kependudukan &amp; Posyandu Terpadu';
        $tanggalCetak = format_tanggal_id(date('Y-m-d'));
        $nomorSurat = '045/POSYANDU/' . date('Y');
        $namaPenandatangan = $profil['kepala_desa'] ?? 'Bambang Sudiro, S.Sos';
        $jabatanPenandatangan = 'Kepala Desa Sukatani';

        require __DIR__ . '/../views/shared/cetak_laporan_pdf.php';
    }

    public function export_csv(): void
    {
        require_auth(['admin']);
        $warga = $this->adminModel->getWarga();
        $filename = 'Data_Penduduk_Posyandu_' . date('Ymd') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['No', 'NIK', 'No KK', 'Nama Lengkap', 'Tanggal Lahir', 'JK', 'Dusun', 'RT/RW', 'Telepon', 'Kategori Sasaran', 'Status']);

        $no = 1;
        foreach ($warga as $w) {
            fputcsv($output, [
                $no++,
                "'" . $w['nik'],
                "'" . $w['no_kk'],
                $w['nama'],
                $w['tanggal_lahir'],
                $w['jenis_kelamin'],
                $w['dusun'],
                $w['rt_rw'],
                $w['telepon'],
                strtoupper($w['kategori']),
                strtoupper($w['status'])
            ]);
        }
        fclose($output);
        exit;
    }
}
