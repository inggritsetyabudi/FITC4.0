<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

class AuthController
{
    private PDO $db;
    private User $userModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
    }

    public function showLogin(string $errorMsg = '', string $username = ''): void
    {
        if (is_logged_in()) {
            redirect_to_dashboard();
            exit;
        }

        $pageTitle = 'Masuk — Posyandu Pintar';
        $pageDesc = 'Halaman masuk Posyandu Pintar untuk Kader, Bidan, dan Admin Posyandu Desa.';
        $bodyClass = 'login-body';

        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void
    {
        if (is_logged_in()) {
            redirect_to_dashboard();
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $this->showLogin('Nama pengguna dan kata sandi wajib diisi.', $username);
            return;
        }

        $authResult = $this->userModel->authenticate($username, $password);
        if (!$authResult['success']) {
            $this->showLogin($authResult['message'], $username);
            return;
        }

        $user = $authResult['user'];

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user'] = [
            'id'           => (int)$user['id'],
            'nama'         => $user['nama'],
            'username'     => $user['username'],
            'role'         => $user['role'],
            'dusun_binaan' => $user['dusun_binaan'] ?? '',
            'sapaan'       => $user['sapaan'] ?? $user['nama'],
            'telepon'      => $user['telepon'] ?? ''
        ];

        $redirectUrl = match ($user['role']) {
            'bidan' => base_url('index.php?controller=bidan&action=dashboard'),
            'admin' => base_url('index.php?controller=admin&action=dashboard'),
            default => base_url('index.php?controller=kader&action=dashboard'),
        };

        header("Location: $redirectUrl");
        exit;
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header('Location: ' . base_url('index.php?controller=auth&action=showLogin'));
        exit;
    }
}
