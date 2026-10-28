<?php

function data_balita(array $input): array
{
    if (!($input['nama'] ?? '') || !($input['tanggal_lahir'] ?? '') || !($input['jenis_kelamin'] ?? '') || !($input['nama_ortu'] ?? '')) {
        json_res(['success' => false, 'message' => 'Nama, tanggal lahir, jenis kelamin, dan nama orang tua wajib diisi.'], 400);
    }

    return [
        'nama'          => $input['nama'],
        'nik'           => $input['nik'] ?? '',
        'tanggal_lahir' => $input['tanggal_lahir'],
        'jenis_kelamin' => $input['jenis_kelamin'],
        'nama_ortu'     => $input['nama_ortu'],
        'status'        => $input['status'] ?? 'aktif',
    ];
}

switch (true) {
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
        $data = data_balita($input);
        $data['dusun_id'] = dusun_id($input['dusun'] ?? '');
        $data['dicatat_oleh_id'] = $user['id'];
        json_res(['success' => true, 'id' => db_balita()->insert($data), 'message' => 'Data balita berhasil disimpan.']);

    case $endpoint === 'balita' && $_SERVER['REQUEST_METHOD'] === 'PUT':
        require_login();
        $data = data_balita($input);
        $data['dusun_id'] = dusun_id($input['dusun'] ?? '');
        db_balita()->update((int)($_GET['id'] ?? 0), $data);
        json_res(['success' => true, 'message' => 'Data balita berhasil diperbarui.']);

    case $endpoint === 'balita' && $_SERVER['REQUEST_METHOD'] === 'DELETE':
        require_login();
        db_balita()->delete((int)($_GET['id'] ?? 0));
        json_res(['success' => true, 'message' => 'Data balita berhasil dihapus.']);

    default:
        json_res(['success' => false, 'message' => 'Endpoint tidak ditemukan.'], 404);
}
