<?php

switch (true) {
    case $endpoint === 'auth/login':
        $user = db_user()->authenticate(trim($input['username'] ?? ''), (string)($input['password'] ?? ''));
        if (!$user['success']) {
            json_res(['success' => false, 'message' => $user['message']], 401);
        }
        session_regenerate_id(true);
        $_SESSION['user'] = $user['user'];
        json_res(['success' => true, 'user' => $user['user']]);

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
        $user = require_login();
        $lama = (string)($input['password_lama'] ?? '');
        $baru = (string)($input['password_baru'] ?? '');
        $data = db_user()->findById($user['id']);

        if (!$data || !password_verify($lama, $data['password'])) {
            json_res(['success' => false, 'message' => 'Kata sandi lama tidak sesuai.'], 400);
        }
        if (strlen($baru) < 6) {
            json_res(['success' => false, 'message' => 'Kata sandi baru minimal 6 karakter.'], 400);
        }
        db_user()->updatePassword($user['id'], $baru);
        json_res(['success' => true, 'message' => 'Kata sandi berhasil diperbarui.']);

    default:
        json_res(['success' => false, 'message' => 'Endpoint tidak ditemukan.'], 404);
}
