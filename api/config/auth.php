<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool {
    return !empty($_SESSION['user']['id']);
}

function has_role(string|array $roles): bool {
    $user = current_user();
    if (!$user) return false;
    $roles = (array)$roles;
    return in_array($user['role'], $roles, true);
}

function require_auth(string|array $allowedRoles = []): array {
    if (!is_logged_in()) {
        header("Location: " . base_url('login.php'));
        exit;
    }

    $user = current_user();
    if (!empty($allowedRoles) && !has_role($allowedRoles)) {
        redirect_to_dashboard($user['role']);
        exit;
    }

    return $user;
}

function login_user_by_credentials(string $username, string $password): array {
    $pdo = get_db();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Username atau kata sandi tidak sesuai.'];
    }

    $_SESSION['user'] = [
        'id'           => (int)$user['id'],
        'username'     => $user['username'],
        'nama'         => $user['nama'],
        'role'         => $user['role'],
        'dusun_binaan' => $user['dusun_binaan'] ?: '',
        'telepon'      => $user['telepon'] ?: ''
    ];

    return [
        'success'  => true,
        'user'     => $_SESSION['user'],
        'redirect' => get_dashboard_url($user['role'])
    ];
}

function logout_user(): void {
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
}

function get_dashboard_url(string $role): string {
    return match ($role) {
        'bidan' => base_url('index.php?controller=bidan&action=dashboard'),
        'admin' => base_url('index.php?controller=admin&action=dashboard'),
        default => base_url('index.php?controller=kader&action=dashboard')
    };
}

function redirect_to_dashboard(?string $role = null): void {
    $role = $role ?: (current_user()['role'] ?? 'kader');
    header("Location: " . get_dashboard_url($role));
    exit;
}

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string {
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $root = rtrim(dirname($script), '/\\');
        if ($root === '/' || $root === '\\' || $root === '.') {
            $root = '';
        }
        return $root . '/' . ltrim($path, '/');
    }
}
