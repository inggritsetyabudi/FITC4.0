<?php

function json_res(array $data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function json_input(): array
{
    $raw = file_get_contents('php://input');
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

function evaluasi_status_pengukuran(?array $ukurTerakhir, float $bbBaru): string
{
    if (!$ukurTerakhir) {
        return 'normal';
    }

    $delta = $bbBaru - (float)$ukurTerakhir['berat_kg'];

    if ($delta < -0.3) {
        return 'prioritas';
    }
    if ($delta <= 0) {
        return 'pemantauan';
    }
    return 'normal';
}
