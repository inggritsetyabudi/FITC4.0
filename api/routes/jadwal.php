<?php

function data_jadwal(array $input): array
{
    if (trim((string)($input['nama_kegiatan'] ?? '')) === '' || empty($input['tanggal'])) {
        json_res(['success' => false, 'message' => 'Nama kegiatan dan tanggal wajib diisi.'], 400);
    }

    $tanggal = DateTime::createFromFormat('!Y-m-d', (string)$input['tanggal']);
    if (!$tanggal || $tanggal->format('Y-m-d') !== $input['tanggal']) {
        json_res(['success' => false, 'message' => 'Format tanggal tidak valid.'], 400);
    }

    return [
        'nama_kegiatan' => trim((string)$input['nama_kegiatan']),
        'dusun_id'      => dusun_id($input['dusun'] ?? ''),
        'lokasi'        => $input['lokasi'] ?? '',
        'tanggal'       => $input['tanggal'] ?? '',
        'waktu_mulai'   => $input['waktu_mulai'] ?? '08:00',
        'waktu_selesai' => $input['waktu_selesai'] ?? '11:00',
        'status'        => $input['status'] ?? 'rencana',
        'petugas'       => $input['petugas'] ?? '',
    ];
}

switch (true) {
    case $endpoint === 'jadwal' && $_SERVER['REQUEST_METHOD'] === 'GET':
        json_res(['success' => true, 'data' => db_jadwal()->getAll()]);

    case $endpoint === 'jadwal' && $_SERVER['REQUEST_METHOD'] === 'POST':
        require_role('admin');
        $id = db_jadwal()->insert(data_jadwal($input));
        json_res(['success' => true, 'id' => $id, 'message' => 'Jadwal berhasil ditambahkan.']);

    case $endpoint === 'jadwal' && $_SERVER['REQUEST_METHOD'] === 'PUT':
        require_role('admin');
        db_jadwal()->update((int)($_GET['id'] ?? 0), data_jadwal($input));
        json_res(['success' => true, 'message' => 'Jadwal berhasil diperbarui.']);

    case $endpoint === 'jadwal' && $_SERVER['REQUEST_METHOD'] === 'DELETE':
        require_role('admin');
        db_jadwal()->delete((int)($_GET['id'] ?? 0));
        json_res(['success' => true, 'message' => 'Jadwal berhasil dihapus.']);

    default:
        json_res(['success' => false, 'message' => 'Endpoint tidak ditemukan.'], 404);
}
