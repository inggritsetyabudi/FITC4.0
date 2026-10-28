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
    $file = __DIR__ . '/uploads/berita/' . basename($uri);
    $mime = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'gif' => 'image/gif'];
    $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!is_file($file) || !isset($mime[$ext])) {
        http_response_code(404);
        exit;
    }
    header('Content-Type: ' . $mime[$ext]);
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

$balitaModel = null;
$pengukuranModel = null;
$userModel = null;
$beritaModel = null;
$jadwalModel = null;

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

$endpoint = $_GET['endpoint'] ?? parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$endpoint = trim((string)$endpoint, '/');
if (str_starts_with($endpoint, 'api/')) {
    $endpoint = substr($endpoint, 4);
}
$input = json_input();

if ($endpoint === 'health') {
    json_res(['success' => true, 'service' => 'Posyandu Pintar API']);
}

$routes = [
    'auth'       => 'auth.php',
    'berita'     => 'berita.php',
    'balita'     => 'balita.php',
    'pengukuran' => 'pengukuran.php',
    'petugas'    => 'petugas.php',
    'jadwal'     => 'jadwal.php',
];

$file = null;
foreach ($routes as $prefix => $f) {
    if (str_starts_with($endpoint, $prefix)) {
        $file = $f;
        break;
    }
}

try {
    if (!$file) {
        json_res(['success' => false, 'message' => 'Endpoint tidak ditemukan.'], 404);
    }
    require __DIR__ . '/routes/' . $file;
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
