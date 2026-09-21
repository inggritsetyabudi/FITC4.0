<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/helpers.php';

$db = get_db();

$controller = $_GET['controller'] ?? null;
$action     = $_GET['action'] ?? null;
$id         = $_GET['id'] ?? null;

if (!$controller && $action) {
    switch ($action) {
        case 'prioritas':
        case 'rekap':
        case 'export':
            $controller = 'bidan';
            break;
        case 'balita':
            $controller = 'balita';
            $action = 'index';
            break;
        case 'pengukuran':
            $controller = 'pengukuran';
            $action = 'index';
            break;
        case 'profil':
            $controller = 'profil';
            $action = 'index';
            break;
        case 'dashboard':
            $role = current_user()['role'] ?? 'kader';
            $controller = match ($role) {
                'bidan' => 'bidan',
                'admin' => 'admin',
                default => 'kader',
            };
            $action = 'dashboard';
            break;
        case 'jadwal':
        case 'kader':
        case 'laporan':
        case 'export_pdf':
        case 'export_csv':
            $controller = 'admin';
            break;
        case 'bidan':
            $controller = 'bidan';
            $action = 'dashboard';
            break;
        case 'admin':
            $controller = 'admin';
            $action = 'dashboard';
            break;
        case 'login':
        case 'showLogin':
        case 'logout':
            $controller = 'auth';
            break;
        case 'berita':
        case 'blog':
        case 'detail-berita':
            $controller = 'berita';
            $action = 'detail';
            break;
        case 'beranda':
        case 'landing':
            $controller = 'landing';
            $action = 'beranda';
            break;
        default:
            $controller = 'landing';
            $action = 'beranda';
            break;
    }
}

if (!$controller) {
    $controller = 'landing';
}
if (!$action) {
    $action = match ($controller) {
        'bidan', 'admin', 'kader' => 'dashboard',
        'balita', 'pengukuran', 'profil' => 'index',
        'auth' => 'showLogin',
        'berita', 'blog', 'detail-berita' => 'detail',
        default => 'beranda',
    };
}

switch ($controller) {
    case 'landing':
    case 'beranda':
        require_once __DIR__ . '/controllers/BerandaController.php';
        $berandaController = new BerandaController($db);
        $berandaController->index();
        break;

    case 'auth':
        require_once __DIR__ . '/controllers/AuthController.php';
        $authController = new AuthController($db);
        switch ($action) {
            case 'login':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $authController->login();
                } else {
                    $authController->showLogin();
                }
                break;
            case 'showLogin':
                $authController->showLogin();
                break;
            case 'logout':
                $authController->logout();
                break;
            default:
                $authController->showLogin();
                break;
        }
        break;

    case 'kader':
        require_once __DIR__ . '/controllers/KaderController.php';
        $kaderController = new KaderController($db);
        $kaderController->dashboard();
        break;

    case 'balita':
        require_once __DIR__ . '/controllers/BalitaController.php';
        $balitaController = new BalitaController($db);
        switch ($action) {
            case 'index':
                $balitaController->index();
                break;
            case 'store':
                $balitaController->store();
                break;
            case 'update':
                $balitaController->update();
                break;
            case 'delete':
                $balitaController->delete();
                break;
            case 'detail':
                $balitaController->detail();
                break;
            default:
                $balitaController->index();
                break;
        }
        break;

    case 'pengukuran':
        require_once __DIR__ . '/controllers/PengukuranController.php';
        $pengukuranController = new PengukuranController($db);
        switch ($action) {
            case 'index':
                $pengukuranController->index();
                break;
            case 'store':
                $pengukuranController->store();
                break;
            case 'delete':
                $pengukuranController->delete();
                break;
            default:
                $pengukuranController->index();
                break;
        }
        break;

    case 'bidan':
        require_once __DIR__ . '/controllers/BidanController.php';
        $bidanController = new BidanController($db);
        switch ($action) {
            case 'dashboard':
                $bidanController->dashboard();
                break;
            case 'prioritas':
                $bidanController->prioritas();
                break;
            case 'rekap':
                $bidanController->rekap();
                break;
            case 'export':
                $bidanController->export();
                break;
            case 'export_pdf':
                $bidanController->export_pdf();
                break;
            default:
                $bidanController->dashboard();
                break;
        }
        break;

    case 'admin':
        require_once __DIR__ . '/controllers/AdminController.php';
        $adminController = new AdminController($db);
        switch ($action) {
            case 'dashboard':
                $adminController->dashboard();
                break;
            case 'kader':
                $adminController->kader();
                break;
            case 'jadwal':
                $adminController->jadwal();
                break;
            case 'laporan':
                $adminController->laporan();
                break;
            case 'export_pdf':
                $adminController->export_pdf();
                break;
            case 'export_csv':
                $adminController->export_csv();
                break;
            default:
                $adminController->dashboard();
                break;
        }
        break;

    case 'profil':
        require_once __DIR__ . '/controllers/ProfilController.php';
        $profilController = new ProfilController($db);
        switch ($action) {
            case 'index':
                $profilController->index();
                break;
            case 'update':
                $profilController->update();
                break;
            default:
                $profilController->index();
                break;
        }
        break;

    case 'berita':
    case 'blog':
    case 'detail-berita':
        require_once __DIR__ . '/controllers/BeritaController.php';
        $beritaController = new BeritaController($db);
        $beritaController->detail();
        break;

    default:
        require_once __DIR__ . '/views/shared/notFound.php';
        break;
}
