<?php

switch (true) {
    case $endpoint === 'pengukuran' && $_SERVER['REQUEST_METHOD'] === 'GET':
        require_login();
        json_res(['success' => true, 'data' => db_pengukuran()->getAll(
            $_GET['bulan'] ?? null,
            $_GET['dusun'] ?? null,
            $_GET['status'] ?? null,
            isset($_GET['anak']) ? (int)$_GET['anak'] : null
        )]);

    case $endpoint === 'pengukuran' && $_SERVER['REQUEST_METHOD'] === 'POST':
        $user = require_login();
        $balitaId = (int)($input['balita_id'] ?? 0);
        if (!$balitaId || !($input['tanggal_ukur'] ?? '') || !isset($input['berat_kg']) || !isset($input['tinggi_cm'])) {
            json_res(['success' => false, 'message' => 'Anak, tanggal ukur, berat, dan tinggi wajib diisi.'], 400);
        }
        $status = evaluasi_status_pengukuran(db_pengukuran()->getLastByBalitaId($balitaId), (float)$input['berat_kg']);
        $id = db_pengukuran()->insert([
            'balita_id'       => $balitaId,
            'tanggal_ukur'    => $input['tanggal_ukur'],
            'berat_kg'        => $input['berat_kg'],
            'tinggi_cm'       => $input['tinggi_cm'],
            'kategori_status' => $status,
            'catatan'         => $input['catatan'] ?? '',
            'dicatat_oleh_id' => $user['id'],
        ]);
        json_res(['success' => true, 'id' => $id, 'kategori_status' => $status, 'message' => 'Pengukuran berhasil disimpan.']);

    case $endpoint === 'pengukuran' && $_SERVER['REQUEST_METHOD'] === 'DELETE':
        require_login();
        db_pengukuran()->delete((int)($_GET['id'] ?? 0));
        json_res(['success' => true, 'message' => 'Data pengukuran berhasil dihapus.']);

    default:
        json_res(['success' => false, 'message' => 'Endpoint tidak ditemukan.'], 404);
}
