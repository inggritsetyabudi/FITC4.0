<?php

switch (true) {
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

    default:
        json_res(['success' => false, 'message' => 'Endpoint tidak ditemukan.'], 404);
}
