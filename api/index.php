<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/models/Balita.php';
require_once __DIR__ . '/models/Pengukuran.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Berita.php';
require_once __DIR__ . '/models/Jadwal.php';

$uri = explode('?', $_SERVER['REQUEST_URI'] ?? '')[0];
if (str_contains($uri, '/uploads/berita/')) {
    $rel  = basename($uri);
    $file = __DIR__ . '/uploads/berita/' . $rel;
    $tipe = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'gif' => 'image/gif'];
    $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if (!is_file($file) || !isset($tipe[$ext])) {
        http_response_code(404);
        exit;
    }
    header('Content-Type: ' . $tipe[$ext]);
    header('Cache-Control: public, max-age=86400');
    readfile($file);
    exit;
}

header('Content-Type: application/json; charset=UTF-8');

session_set_cookie_params([
    'lifetime' => 60 * 60 * 24 * 7,
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax',
]);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
session_start();

function json_res(array $data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function json_input(): array
{
    $raw  = file_get_contents('php://input');
    $parsed = $raw ? json_decode($raw, true) : null;
    return is_array($parsed) ? $parsed : $_POST;
}

function require_login(): array
{
    if (empty($_SESSION['user'])) {
        json_res(['success' => false, 'message' => 'Sesi berakhir, silakan login kembali.'], 401);
    }
    return $_SESSION['user'];
}

function require_role(string $role): array
{
    $user = require_login();
    if ($user['role'] !== $role) {
        json_res(['success' => false, 'message' => 'Anda tidak punya akses ke fitur ini.'], 403);
    }
    return $user;
}

function require_editor(): array
{
    $user = require_login();
    if (!in_array($user['role'], ['admin', 'bidan'], true)) {
        json_res(['success' => false, 'message' => 'Hanya admin atau bidan yang boleh mengelola berita.'], 403);
    }
    return $user;
}

$endpoint = $_GET['endpoint'] ?? parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$endpoint = trim((string)$endpoint, '/');
if (str_starts_with($endpoint, 'api/')) {
    $endpoint = substr($endpoint, 4);
}
$input    = json_input();
$parts    = explode('/', trim((string)$endpoint, '/'));

$balitaModel     = null;
$pengukuranModel = null;
$userModel       = null;
$beritaModel    = null;
$jadwalModel    = null;

function db_balita(): Balita
{
    global $balitaModel;
    return $balitaModel ??= new Balita(get_db());
}

function db_pengukuran(): Pengukuran
{
    global $pengukuranModel;
    return $pengukuranModel ??= new Pengukuran(get_db());
}

function db_user(): User
{
    global $userModel;
    return $userModel ??= new User(get_db());
}

function db_berita(): Berita
{
    global $beritaModel;
    return $beritaModel ??= new Berita(get_db());
}

function db_jadwal(): Jadwal
{
    global $jadwalModel;
    return $jadwalModel ??= new Jadwal(get_db());
}

function dusun_id(string $nama): int
{
    $stmt = get_db()->prepare("SELECT id FROM dusun WHERE nama = ? LIMIT 1");
    $stmt->execute([$nama]);
    $id = $stmt->fetchColumn();
    if (!$id) {
        json_res(['success' => false, 'message' => "Dusun '$nama' tidak terdaftar."], 400);
    }
    return (int)$id;
}

function upload_gambar(): string
{
    if (empty($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
        json_res(['success' => false, 'message' => 'File gambar gagal diunggah.'], 400);
    }

    $file = $_FILES['gambar'];
    $tipe = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $mime = mime_content_type($file['tmp_name']);

    if (!isset($tipe[$mime])) {
        json_res(['success' => false, 'message' => 'Format gambar harus JPG, PNG, WebP, atau GIF.'], 400);
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        json_res(['success' => false, 'message' => 'Ukuran gambar maksimal 2 MB.'], 400);
    }

    $nama = 'berita-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $tipe[$mime];
    $dir  = __DIR__ . '/uploads/berita';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $nama)) {
        json_res(['success' => false, 'message' => 'Gagal menyimpan file gambar.'], 500);
    }

    return '/api/uploads/berita/' . $nama;
}

function data_berita(array $input): array
{
    if (!($input['judul'] ?? '') || !($input['ringkasan'] ?? '') || !($input['isi'] ?? '')) {
        json_res(['success' => false, 'message' => 'Judul, ringkasan, dan isi wajib diisi.'], 400);
    }

    $status = ($input['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
    $tipe   = ($input['tipe'] ?? 'berita') === 'blog' ? 'blog' : 'berita';

    return [
        'judul'        => $input['judul'],
        'kategori'     => $input['kategori'] ?? 'KABAR DESA',
        'tipe'         => $tipe,
        'ringkasan'    => $input['ringkasan'],
        'isi'          => $input['isi'],
        'gambar'       => $input['gambar'] ?? '',
        'status'       => $status,
        'published_at' => $status === 'published' ? ($input['published_at'] ?? date('Y-m-d H:i:s')) : null,
    ];
}

function simpan_berita(array $input, int $penulisId): int
{
    $data = data_berita($input);
    $data['penulis_id'] = $penulisId;
    return db_berita()->insert($data);
}

try {
    switch (true) {
        case $endpoint === 'health':
            json_res(['success' => true, 'service' => 'Posyandu Pintar API']);

        case $endpoint === 'auth/login':
            $user = db_user()->authenticate(trim($input['username'] ?? ''), (string)($input['password'] ?? ''));
            if (!$user['success']) {
                json_res(['success' => false, 'message' => $user['message']], 401);
            }
            session_regenerate_id(true);
            $_SESSION['user'] = $user['user'];
            json_res(['success' => true, 'user' => $user['user']]);

        case $endpoint === 'berita' && $_SERVER['REQUEST_METHOD'] === 'GET':
            $tipe = ($_GET['tipe'] ?? '') === 'blog' ? 'blog' : (($_GET['tipe'] ?? '') === 'berita' ? 'berita' : null);
            json_res(['success' => true, 'data' => db_berita()->getPublished($tipe)]);

        case $endpoint === 'berita/detail' && $_SERVER['REQUEST_METHOD'] === 'GET':
            $berita = db_berita()->getById((int)($_GET['id'] ?? 0));
            if (!$berita) {
                json_res(['success' => false, 'message' => 'Berita tidak ditemukan.'], 404);
            }
            json_res(['success' => true, 'data' => $berita]);

        case $endpoint === 'berita/admin' && $_SERVER['REQUEST_METHOD'] === 'GET':
            require_editor();
            json_res(['success' => true, 'data' => db_berita()->getAll()]);

        case $endpoint === 'berita/upload' && $_SERVER['REQUEST_METHOD'] === 'POST':
            require_editor();
            json_res(['success' => true, 'url' => upload_gambar()]);

        case $endpoint === 'berita' && $_SERVER['REQUEST_METHOD'] === 'POST':
            $user = require_editor();
            json_res(['success' => true, 'id' => simpan_berita($input, $user['id']), 'message' => 'Berita berhasil ditambahkan.']);

        case $endpoint === 'berita' && $_SERVER['REQUEST_METHOD'] === 'PUT':
            require_editor();
            db_berita()->update((int)($_GET['id'] ?? 0), data_berita($input));
            json_res(['success' => true, 'message' => 'Berita berhasil diperbarui.']);

        case $endpoint === 'berita' && $_SERVER['REQUEST_METHOD'] === 'DELETE':
            require_editor();
            db_berita()->delete((int)($_GET['id'] ?? 0));
            json_res(['success' => true, 'message' => 'Berita berhasil dihapus.']);

        case $endpoint === 'jadwal' && $_SERVER['REQUEST_METHOD'] === 'GET':
            json_res(['success' => true, 'data' => db_jadwal()->getAll()]);

        case $endpoint === 'auth/me':
            require_login();
            json_res(['success' => true, 'user' => $_SESSION['user']]);

        case $endpoint === 'auth/logout':
            $_SESSION = [];
            session_destroy();
            json_res(['success' => true, 'message' => 'Berhasil keluar.']);

        case $endpoint === 'auth/profile' && $_SERVER['REQUEST_METHOD'] === 'PUT':
            $user = require_login();
            db_user()->updateTelepon($user['id'], $input['telepon'] ?? null);
            $_SESSION['user']['telepon'] = $input['telepon'] ?? null;
            json_res(['success' => true, 'user' => $_SESSION['user'], 'message' => 'Profil berhasil disimpan.']);

        case $endpoint === 'auth/password' && $_SERVER['REQUEST_METHOD'] === 'PUT':
            $user  = require_login();
            $lama  = (string)($input['password_lama'] ?? '');
            $baru  = (string)($input['password_baru'] ?? '');
            $data  = db_user()->findById($user['id']);

            if (!$data || !password_verify($lama, $data['password'])) {
                json_res(['success' => false, 'message' => 'Kata sandi lama tidak sesuai.'], 400);
            }
            if (strlen($baru) < 6) {
                json_res(['success' => false, 'message' => 'Kata sandi baru minimal 6 karakter.'], 400);
            }
            db_user()->updatePassword($user['id'], $baru);
            json_res(['success' => true, 'message' => 'Kata sandi berhasil diperbarui.']);

        case $endpoint === 'balita' && $_SERVER['REQUEST_METHOD'] === 'GET':
            require_login();
            json_res(['success' => true, 'data' => db_balita()->getAll($_GET['dusun'] ?? null, $_GET['q'] ?? null)]);

        case $endpoint === 'balita/detail' && $_SERVER['REQUEST_METHOD'] === 'GET':
            require_login();
            $balita = db_balita()->getById((int)($_GET['id'] ?? 0));
            if (!$balita) {
                json_res(['success' => false, 'message' => 'Data balita tidak ditemukan.'], 404);
            }
            json_res(['success' => true, 'data' => $balita]);

        case $endpoint === 'balita' && $_SERVER['REQUEST_METHOD'] === 'POST':
            $user = require_login();
            if (!($input['nama'] ?? '') || !($input['tanggal_lahir'] ?? '') || !($input['jenis_kelamin'] ?? '') || !($input['nama_ortu'] ?? '')) {
                json_res(['success' => false, 'message' => 'Nama, tanggal lahir, jenis kelamin, dan nama orang tua wajib diisi.'], 400);
            }
            $id = db_balita()->insert([
                'nama'            => $input['nama'],
                'nik'             => $input['nik'] ?? '',
                'tanggal_lahir'   => $input['tanggal_lahir'],
                'jenis_kelamin'   => $input['jenis_kelamin'],
                'nama_ortu'       => $input['nama_ortu'],
                'dusun_id'        => dusun_id($input['dusun'] ?? ''),
                'status'          => $input['status'] ?? 'aktif',
                'dicatat_oleh_id' => $user['id'],
            ]);
            json_res(['success' => true, 'id' => $id, 'message' => 'Data balita berhasil disimpan.']);

        case $endpoint === 'balita' && $_SERVER['REQUEST_METHOD'] === 'PUT':
            require_login();
            if (!($input['nama'] ?? '') || !($input['tanggal_lahir'] ?? '') || !($input['jenis_kelamin'] ?? '') || !($input['nama_ortu'] ?? '')) {
                json_res(['success' => false, 'message' => 'Nama, tanggal lahir, jenis kelamin, dan nama orang tua wajib diisi.'], 400);
            }
            db_balita()->update((int)($_GET['id'] ?? 0), [
                'nama'          => $input['nama'],
                'nik'           => $input['nik'] ?? '',
                'tanggal_lahir' => $input['tanggal_lahir'],
                'jenis_kelamin' => $input['jenis_kelamin'],
                'nama_ortu'     => $input['nama_ortu'],
                'dusun_id'      => dusun_id($input['dusun'] ?? ''),
                'status'        => $input['status'] ?? 'aktif',
            ]);
            json_res(['success' => true, 'message' => 'Data balita berhasil diperbarui.']);

        case $endpoint === 'balita' && $_SERVER['REQUEST_METHOD'] === 'DELETE':
            require_login();
            db_balita()->delete((int)($_GET['id'] ?? 0));
            json_res(['success' => true, 'message' => 'Data balita berhasil dihapus.']);

        case $endpoint === 'pengukuran' && $_SERVER['REQUEST_METHOD'] === 'GET':
            require_login();
            json_res(['success' => true, 'data' => db_pengukuran()->getAll(
                $_GET['bulan'] ?? null,
                $_GET['dusun'] ?? null,
                $_GET['status'] ?? null,
                isset($_GET['anak']) ? (int)$_GET['anak'] : null
            )]);

        case $endpoint === 'pengukuran' && $_SERVER['REQUEST_METHOD'] === 'POST':
            $user = require_login();
            $balitaId = (int)($input['balita_id'] ?? 0);
            if (!$balitaId || !($input['tanggal_ukur'] ?? '') || !isset($input['berat_kg']) || !isset($input['tinggi_cm'])) {
                json_res(['success' => false, 'message' => 'Anak, tanggal ukur, berat, dan tinggi wajib diisi.'], 400);
            }
            $status = evaluasi_status_pengukuran(db_pengukuran()->getLastByBalitaId($balitaId), (float)$input['berat_kg']);
            $id = db_pengukuran()->insert([
                'balita_id'       => $balitaId,
                'tanggal_ukur'    => $input['tanggal_ukur'],
                'berat_kg'        => $input['berat_kg'],
                'tinggi_cm'       => $input['tinggi_cm'],
                'kategori_status' => $status,
                'catatan'         => $input['catatan'] ?? '',
                'dicatat_oleh_id' => $user['id'],
            ]);
            json_res(['success' => true, 'id' => $id, 'kategori_status' => $status, 'message' => 'Pengukuran berhasil disimpan.']);

        case $endpoint === 'pengukuran' && $_SERVER['REQUEST_METHOD'] === 'DELETE':
            require_login();
            db_pengukuran()->delete((int)($_GET['id'] ?? 0));
            json_res(['success' => true, 'message' => 'Data pengukuran berhasil dihapus.']);

        case $endpoint === 'petugas' && $_SERVER['REQUEST_METHOD'] === 'GET':
            require_login();
            json_res(['success' => true, 'data' => db_user()->getAll()]);

        case $endpoint === 'petugas' && $_SERVER['REQUEST_METHOD'] === 'POST':
            require_role('admin');
            if (!($input['nama'] ?? '') || !($input['username'] ?? '') || !($input['password'] ?? '')) {
                json_res(['success' => false, 'message' => 'Nama, username, dan kata sandi wajib diisi.'], 400);
            }
            try {
                $id = db_user()->create([
                    'nama'     => $input['nama'],
                    'username' => $input['username'],
                    'password' => $input['password'],
                    'role'     => $input['role'] ?? 'kader',
                    'dusun_id' => ($input['role'] ?? 'kader') === 'kader' && !empty($input['dusun_binaan'])
                        ? dusun_id($input['dusun_binaan'])
                        : null,
                    'telepon'  => $input['telepon'] ?? null,
                ]);
            } catch (PDOException $e) {
                json_res(['success' => false, 'message' => 'Username sudah dipakai petugas lain.'], 409);
            }
            json_res(['success' => true, 'id' => $id, 'message' => 'Petugas berhasil ditambahkan.']);

        case $endpoint === 'petugas' && $_SERVER['REQUEST_METHOD'] === 'DELETE':
            require_role('admin');
            db_user()->delete((int)($_GET['id'] ?? 0));
            json_res(['success' => true, 'message' => 'Petugas berhasil dihapus.']);

        case $endpoint === 'jadwal' && $_SERVER['REQUEST_METHOD'] === 'POST':
            require_role('admin');
            db_jadwal()->insert([
                'nama_kegiatan' => $input['nama_kegiatan'] ?? '',
                'dusun_id'      => dusun_id($input['dusun'] ?? ''),
                'lokasi'        => $input['lokasi'] ?? '',
                'tanggal'       => $input['tanggal'] ?? '',
                'waktu_mulai'   => $input['waktu_mulai'] ?? '08:00',
                'waktu_selesai' => $input['waktu_selesai'] ?? '11:00',
                'status'        => $input['status'] ?? 'rencana',
                'petugas'       => $input['petugas'] ?? '',
            ]);
            json_res(['success' => true, 'message' => 'Jadwal berhasil ditambahkan.']);

        case $endpoint === 'jadwal' && $_SERVER['REQUEST_METHOD'] === 'PUT':
            require_role('admin');
            db_jadwal()->update((int)($_GET['id'] ?? 0), [
                'nama_kegiatan' => $input['nama_kegiatan'] ?? '',
                'dusun_id'      => dusun_id($input['dusun'] ?? ''),
                'lokasi'        => $input['lokasi'] ?? '',
                'tanggal'       => $input['tanggal'] ?? '',
                'waktu_mulai'   => $input['waktu_mulai'] ?? '08:00',
                'waktu_selesai' => $input['waktu_selesai'] ?? '11:00',
                'status'        => $input['status'] ?? 'rencana',
                'petugas'       => $input['petugas'] ?? '',
            ]);
            json_res(['success' => true, 'message' => 'Jadwal berhasil diperbarui.']);

        case $endpoint === 'jadwal' && $_SERVER['REQUEST_METHOD'] === 'DELETE':
            require_role('admin');
            db_jadwal()->delete((int)($_GET['id'] ?? 0));
            json_res(['success' => true, 'message' => 'Jadwal berhasil dihapus.']);

        default:
            json_res(['success' => false, 'message' => 'Endpoint tidak ditemukan.'], 404);
    }
} catch (PDOException $e) {
    $kode = (int)($e->errorInfo[1] ?? 0);
    if ($kode === 1062) {
        json_res(['success' => false, 'message' => 'Data sudah terdaftar (duplikat). Periksa NIK, username, atau tanggal pengukuran.'], 409);
    }
    if ($kode === 3819 || $kode === 3820) {
        json_res(['success' => false, 'message' => 'Nilai di luar batas yang diizinkan (cek berat, tinggi, atau jam jadwal).'], 400);
    }
    json_res(['success' => false, 'message' => 'Terjadi kesalahan pada basis data.'], 500);
} catch (Throwable $e) {
    json_res(['success' => false, 'message' => 'Terjadi kesalahan server.'], 500);
}
