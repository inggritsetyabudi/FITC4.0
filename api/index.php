<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/models/Anak.php';
require_once __DIR__ . '/models/Pengukuran.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Berita.php';

session_start();

$db = get_db();

function json_res($data, int $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function get_json_input(): array {
    $raw = file_get_contents('php://input');
    if (!$raw) return $_POST;
    $parsed = json_decode($raw, true);
    return is_array($parsed) ? $parsed : $_POST;
}

if (!$db) {
    if (isset($_GET['check_db'])) {
        json_res(['success' => false, 'db_connected' => false, 'message' => 'Database MySQL tidak terhubung']);
    }
}

$uri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($uri, PHP_URL_PATH) ?: '';
$path = preg_replace('#^.*?api/?#', '', $path);
$endpoint = $_GET['endpoint'] ?? trim($path, '/');
$method = $_SERVER['REQUEST_METHOD'];
$input = get_json_input();

switch ($endpoint) {
    case '':
    case 'health':
        json_res([
            'success' => true,
            'status' => 'online',
            'app' => 'Posyandu Pintar REST API',
            'version' => '4.0.0',
            'db_connected' => ($db !== null)
        ]);
        break;

    case 'auth/login':
        if ($method !== 'POST') json_res(['success' => false, 'message' => 'Method not allowed'], 405);
        $username = trim($input['username'] ?? '');
        $password = trim($input['password'] ?? '');

        if (!$db) {
            $roles = ['kader', 'bidan', 'admin'];
            $role = in_array($username, $roles) ? $username : 'kader';
            json_res([
                'success' => true,
                'user' => [
                    'id' => 1,
                    'nama' => $role === 'bidan' ? 'Siti Nurhaliza, S.Tr.Keb' : ($role === 'admin' ? 'Bambang Hermawan' : 'Sari Rahmawati'),
                    'username' => $username,
                    'role' => $role,
                    'dusun_binaan' => $role === 'kader' ? 'Gamping Wetan' : null,
                    'telepon' => '0812-3456-7890'
                ]
            ]);
        }

        $userModel = new User($db);
        $authResult = $userModel->authenticate($username, $password);
        if ($authResult['success']) {
            $user = $authResult['user'];
            unset($user['password']);
            $_SESSION['user'] = $user;
            json_res(['success' => true, 'user' => $user]);
        } else {
            json_res(['success' => false, 'message' => $authResult['message']], 401);
        }
        break;

    case 'auth/me':
        $user = $_SESSION['user'] ?? null;
        if ($user) {
            json_res(['success' => true, 'user' => $user]);
        } else {
            json_res(['success' => false, 'user' => null]);
        }
        break;

    case 'auth/logout':
        session_destroy();
        json_res(['success' => true, 'message' => 'Berhasil keluar']);
        break;

    case 'balita':
        if (!$db) json_res(['success' => false, 'message' => 'Database tidak terhubung'], 503);
        $anakModel = new Anak($db);

        if ($method === 'GET') {
            $dusun = $_GET['dusun'] ?? null;
            $q = $_GET['q'] ?? null;
            $data = $anakModel->getAllWithLastMeasurement($dusun, $q);
            json_res(['success' => true, 'data' => $data]);
        } elseif ($method === 'POST') {
            $nama = trim($input['nama'] ?? '');
            $nik = trim($input['nik'] ?? '');
            $tanggalLahir = trim($input['tanggal_lahir'] ?? '');
            $jenisKelamin = trim($input['jenis_kelamin'] ?? 'L');
            $namaOrtu = trim($input['nama_ortu'] ?? '');
            $dusun = trim($input['dusun'] ?? 'Gamping Wetan');

            if (!$nama || !$tanggalLahir || !$namaOrtu) {
                json_res(['success' => false, 'message' => 'Nama, tanggal lahir, dan nama orang tua wajib diisi'], 400);
            }

            $id = $anakModel->insert([
                'nama' => $nama,
                'nik' => $nik,
                'tanggal_lahir' => $tanggalLahir,
                'jenis_kelamin' => $jenisKelamin,
                'nama_ortu' => $namaOrtu,
                'dusun' => $dusun,
                'kader_id' => $_SESSION['user']['id'] ?? 1
            ]);
            json_res(['success' => true, 'id' => $id, 'message' => 'Balita berhasil ditambahkan']);
        } elseif ($method === 'PUT') {
            $id = (int)($_GET['id'] ?? $input['id'] ?? 0);
            if ($id <= 0) json_res(['success' => false, 'message' => 'ID balita tidak valid'], 400);

            $anakModel->update($id, [
                'nama' => trim($input['nama'] ?? ''),
                'nik' => trim($input['nik'] ?? ''),
                'tanggal_lahir' => trim($input['tanggal_lahir'] ?? ''),
                'jenis_kelamin' => trim($input['jenis_kelamin'] ?? 'L'),
                'nama_ortu' => trim($input['nama_ortu'] ?? ''),
                'dusun' => trim($input['dusun'] ?? '')
            ]);
            json_res(['success' => true, 'message' => 'Data balita berhasil diperbarui']);
        } elseif ($method === 'DELETE') {
            $id = (int)($_GET['id'] ?? $input['id'] ?? 0);
            if ($id <= 0) json_res(['success' => false, 'message' => 'ID balita tidak valid'], 400);
            $anakModel->softDelete($id);
            json_res(['success' => true, 'message' => 'Data balita berhasil dihapus']);
        }
        break;

    case 'balita/detail':
        if (!$db) json_res(['success' => false, 'message' => 'Database tidak terhubung'], 503);
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) json_res(['success' => false, 'message' => 'ID balita tidak valid'], 400);

        $anakModel = new Anak($db);
        $pengukuranModel = new Pengukuran($db);

        $anak = $anakModel->getById($id);
        if (!$anak) json_res(['success' => false, 'message' => 'Balita tidak ditemukan'], 404);

        $riwayat = $pengukuranModel->getByAnakIdAsc($id);
        json_res([
            'success' => true,
            'anak' => $anak,
            'riwayat' => $riwayat
        ]);
        break;

    case 'pengukuran':
        if (!$db) json_res(['success' => false, 'message' => 'Database tidak terhubung'], 503);
        $pengukuranModel = new Pengukuran($db);

        if ($method === 'GET') {
            $bulan = $_GET['bulan'] ?? null;
            $dusun = $_GET['dusun'] ?? null;
            $status = $_GET['status'] ?? null;
            $data = $pengukuranModel->getAllFiltered($bulan, $dusun, $status);
            json_res(['success' => true, 'data' => $data]);
        } elseif ($method === 'POST') {
            $anakId = (int)($input['anak_id'] ?? 0);
            $tanggalUkur = trim($input['tanggal_ukur'] ?? date('Y-m-d'));
            $beratKg = (float)($input['berat_kg'] ?? 0);
            $tinggiCm = (float)($input['tinggi_cm'] ?? 0);
            $catatan = trim($input['catatan'] ?? '');

            if ($anakId <= 0 || $beratKg <= 0 || $tinggiCm <= 0) {
                json_res(['success' => false, 'message' => 'Balita, berat badan, dan tinggi badan wajib diisi valid'], 400);
            }

            $anakModel = new Anak($db);
            $anak = $anakModel->getById($anakId);
            if (!$anak) json_res(['success' => false, 'message' => 'Balita tidak ditemukan'], 404);

            $umur = hitung_umur($anak['tanggal_lahir'], $tanggalUkur);
            $usiaBulan = $umur['total_bulan'];

            $lastUkur = $pengukuranModel->getLastByAnakId($anakId);
            $kategoriStatus = 'normal';

            if ($lastUkur) {
                $delta = $beratKg - (float)$lastUkur['berat_kg'];
                if ($delta < -0.3) {
                    $kategoriStatus = 'prioritas';
                } elseif ($delta <= 0) {
                    $kategoriStatus = 'pemantauan';
                }
            }

            $newId = $pengukuranModel->insert([
                'anak_id' => $anakId,
                'tanggal_ukur' => $tanggalUkur,
                'usia_bulan' => $usiaBulan,
                'berat_kg' => $beratKg,
                'tinggi_cm' => $tinggiCm,
                'kategori_status' => $input['kategori_status'] ?? $kategoriStatus,
                'catatan' => $catatan,
                'dicatat_oleh_id' => $_SESSION['user']['id'] ?? 1
            ]);

            json_res([
                'success' => true,
                'id' => $newId,
                'kategori_status' => $kategoriStatus,
                'message' => 'Hasil penimbangan berhasil dicatat!'
            ]);
        } elseif ($method === 'DELETE') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) json_res(['success' => false, 'message' => 'ID pengukuran tidak valid'], 400);
            $pengukuranModel->delete($id);
            json_res(['success' => true, 'message' => 'Pengukuran berhasil dihapus']);
        }
        break;

    case 'dashboard/kader':
        if (!$db) json_res(['success' => false, 'message' => 'Database tidak terhubung'], 503);
        $anakModel = new Anak($db);

        $bulanSekarang = date('Y-m');
        $allAnak = $anakModel->getAllWithLastMeasurement();
        $totalAnak = count($allAnak);

        $belumDitimbang = [];
        $perluPerhatian = [];
        $sudahDitimbangCount = 0;

        foreach ($allAnak as $a) {
            $terakhir = $a['tanggal_ukur_terakhir'] ?? null;
            $sudahBulanIni = ($terakhir && str_starts_with($terakhir, $bulanSekarang));

            if ($sudahBulanIni) {
                $sudahDitimbangCount++;
            } else {
                $belumDitimbang[] = $a;
            }

            if (in_array($a['status_terakhir'] ?? '', ['pemantauan', 'prioritas', 'berisiko'])) {
                $perluPerhatian[] = $a;
            }
        }

        json_res([
            'success' => true,
            'data' => [
                'total_anak' => $totalAnak,
                'sudah_ditimbang' => $sudahDitimbangCount,
                'belum_ditimbang' => count($belumDitimbang),
                'perlu_perhatian' => count($perluPerhatian),
                'daftar_belum_ditimbang' => array_slice($belumDitimbang, 0, 10),
                'daftar_perlu_perhatian' => array_slice($perluPerhatian, 0, 10)
            ]
        ]);
        break;

    case 'dashboard/bidan':
    case 'bidan/prioritas':
        if (!$db) json_res(['success' => false, 'message' => 'Database tidak terhubung'], 503);
        $stmt = $db->query("SELECT a.id, a.nama, a.nik, a.tanggal_lahir, a.jenis_kelamin, a.dusun, a.nama_ortu,
            p.berat_kg, p.tinggi_cm, p.kategori_status, p.tanggal_ukur, p.catatan
            FROM anak a
            JOIN pengukuran p ON p.anak_id = a.id
            WHERE p.id = (SELECT p2.id FROM pengukuran p2 WHERE p2.anak_id = a.id ORDER BY p2.tanggal_ukur DESC LIMIT 1)
              AND p.kategori_status IN ('pemantauan', 'prioritas', 'berisiko')
            ORDER BY FIELD(p.kategori_status, 'prioritas', 'berisiko', 'pemantauan'), p.tanggal_ukur DESC");
        $prioritasList = $stmt->fetchAll();

        $statsDusun = $db->query("SELECT dusun, COUNT(*) as total FROM anak WHERE status = 'aktif' GROUP BY dusun")->fetchAll();

        json_res([
            'success' => true,
            'prioritas' => $prioritasList,
            'stats_dusun' => $statsDusun
        ]);
        break;

    case 'bidan/rekap':
        if (!$db) json_res(['success' => false, 'message' => 'Database tidak terhubung'], 503);
        $bulan = $_GET['bulan'] ?? date('Y-m');
        $stmt = $db->prepare("SELECT p.*, a.nama as nama_anak, a.dusun, a.jenis_kelamin, a.nama_ortu
            FROM pengukuran p
            JOIN anak a ON a.id = p.anak_id
            WHERE p.tanggal_ukur LIKE ?
            ORDER BY p.tanggal_ukur DESC");
        $stmt->execute(["$bulan%"]);
        json_res(['success' => true, 'data' => $stmt->fetchAll()]);
        break;

    case 'berita':
        if (!$db) json_res(['success' => false, 'message' => 'Database tidak terhubung'], 503);
        $beritaModel = new Berita($db);
        $list = $beritaModel->getAllPublished();
        json_res(['success' => true, 'data' => $list]);
        break;

    case 'berita/detail':
        if (!$db) json_res(['success' => false, 'message' => 'Database tidak terhubung'], 503);
        $id = (int)($_GET['id'] ?? 0);
        $beritaModel = new Berita($db);
        $item = $beritaModel->getById($id);
        if (!$item) json_res(['success' => false, 'message' => 'Berita tidak ditemukan'], 404);
        json_res(['success' => true, 'data' => $item]);
        break;

    case 'jadwal':
        if (!$db) json_res(['success' => false, 'message' => 'Database tidak terhubung'], 503);
        if ($method === 'GET') {
            $stmt = $db->query("SELECT * FROM jadwal ORDER BY tanggal ASC");
            json_res(['success' => true, 'data' => $stmt->fetchAll()]);
        } elseif ($method === 'POST') {
            $ins = $db->prepare("INSERT INTO jadwal (nama_kegiatan, dusun, lokasi, tanggal, waktu_mulai, waktu_selesai, status, petugas) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $ins->execute([
                $input['nama_kegiatan'] ?? '',
                $input['dusun'] ?? '',
                $input['lokasi'] ?? '',
                $input['tanggal'] ?? date('Y-m-d'),
                $input['waktu_mulai'] ?? '08:00',
                $input['waktu_selesai'] ?? '11:00',
                $input['status'] ?? 'rencana',
                $input['petugas'] ?? ''
            ]);
            json_res(['success' => true, 'message' => 'Jadwal berhasil ditambahkan']);
        }
        break;

    case 'petugas':
        if (!$db) json_res(['success' => false, 'message' => 'Database tidak terhubung'], 503);
        $stmt = $db->query("SELECT id, nama, username, role, dusun_binaan, telepon, created_at FROM users ORDER BY role, nama");
        json_res(['success' => true, 'data' => $stmt->fetchAll()]);
        break;

    default:
        json_res(['success' => false, 'message' => "Endpoint '$endpoint' tidak ditemukan"], 404);
        break;
}
