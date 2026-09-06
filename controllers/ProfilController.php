<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

class ProfilController
{
    private PDO $db;
    private User $userModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
    }

    public function index(): void
    {
        $userSession = require_auth();
        $userData = $this->userModel->findById($userSession['id']);
        if (!$userData) {
            $userData = $userSession;
        }

        $successMsg = $_GET['success'] ?? '';
        $errorMsg = $_GET['error'] ?? '';

        $pageTitle = 'Profil Pengguna — Posyandu Pintar';
        $activePage = 'profil';
        $topbarTitle = 'Profil Akun Petugas';
        $breadcrumb = '<span>Profil Pengguna</span>';

        require_once __DIR__ . '/../views/shared/profil.php';
    }

    public function update(): void
    {
        $userSession = require_auth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $actionType = $_POST['action_type'] ?? 'profil';

            if ($actionType === 'password') {
                $passwordLama = $_POST['password_lama'] ?? '';
                $passwordBaru = $_POST['password_baru'] ?? '';
                $passwordKonf = $_POST['password_konfirmasi'] ?? '';

                if (empty($passwordBaru) || strlen($passwordBaru) < 3) {
                    header('Location: ' . base_url('index.php?controller=profil&action=index&error=' . urlencode('Kata sandi baru minimal 3 karakter.')));
                    exit;
                }

                if ($passwordBaru !== $passwordKonf) {
                    header('Location: ' . base_url('index.php?controller=profil&action=index&error=' . urlencode('Konfirmasi kata sandi tidak cocok.')));
                    exit;
                }

                $authCheck = $this->userModel->authenticate($userSession['username'], $passwordLama);
                if (!$authCheck['success']) {
                    header('Location: ' . base_url('index.php?controller=profil&action=index&error=' . urlencode('Kata sandi saat ini tidak sesuai.')));
                    exit;
                }

                $hashed = password_hash($passwordBaru, PASSWORD_BCRYPT);
                $this->userModel->updatePassword($userSession['id'], $hashed);

                header('Location: ' . base_url('index.php?controller=profil&action=index&success=' . urlencode('Kata sandi berhasil diperbarui!')));
                exit;
            } else {
                $sapaan = trim($_POST['sapaan'] ?? '');
                $telepon = trim($_POST['telepon'] ?? '');

                $this->userModel->updateProfile($userSession['id'], [
                    'sapaan'  => $sapaan,
                    'telepon' => $telepon
                ]);

                $_SESSION['user']['sapaan'] = $sapaan ?: $userSession['nama'];
                $_SESSION['user']['telepon'] = $telepon;

                header('Location: ' . base_url('index.php?controller=profil&action=index&success=' . urlencode('Profil berhasil disimpan!')));
                exit;
            }
        }

        header('Location: ' . base_url('index.php?controller=profil&action=index'));
        exit;
    }
}
