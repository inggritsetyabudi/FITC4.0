<?php

function upload_gambar(): string
{
    if (empty($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
        json_res(['success' => false, 'message' => 'File gambar gagal diunggah.'], 400);
    }

    $file = $_FILES['gambar'];
    $mime = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $tipe = mime_content_type($file['tmp_name']);

    if (!isset($mime[$tipe])) {
        json_res(['success' => false, 'message' => 'Format gambar harus JPG, PNG, WebP, atau GIF.'], 400);
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        json_res(['success' => false, 'message' => 'Ukuran gambar maksimal 2 MB.'], 400);
    }

    $nama = 'berita-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $mime[$tipe];
    $dir  = __DIR__ . '/../uploads/berita';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $nama)) {
        json_res(['success' => false, 'message' => 'Gagal menyimpan file gambar.'], 500);
    }

    return '/api/uploads/berita/' . $nama;
}

function data_berita(array $input): array
{
    if (!($input['judul'] ?? '') || !($input['ringkasan'] ?? '') || !($input['isi'] ?? '')) {
        json_res(['success' => false, 'message' => 'Judul, ringkasan, dan isi wajib diisi.'], 400);
    }

    $status = ($input['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
    $tipe   = ($input['tipe'] ?? 'berita') === 'blog' ? 'blog' : 'berita';

    return [
        'judul'        => $input['judul'],
        'kategori'     => $input['kategori'] ?? 'KABAR DESA',
        'tipe'         => $tipe,
        'ringkasan'    => $input['ringkasan'],
        'isi'          => $input['isi'],
        'gambar'       => $input['gambar'] ?? '',
        'status'       => $status,
        'published_at' => $status === 'published' ? ($input['published_at'] ?? date('Y-m-d H:i:s')) : null,
    ];
}

switch (true) {
    case $endpoint === 'berita' && $_SERVER['REQUEST_METHOD'] === 'GET':
        $tipe = ($_GET['tipe'] ?? '') === 'blog' ? 'blog' : (($_GET['tipe'] ?? '') === 'berita' ? 'berita' : null);
        json_res(['success' => true, 'data' => db_berita()->getPublished($tipe)]);

    case $endpoint === 'berita/detail' && $_SERVER['REQUEST_METHOD'] === 'GET':
        $berita = db_berita()->getById((int)($_GET['id'] ?? 0));
        if (!$berita) {
            json_res(['success' => false, 'message' => 'Berita tidak ditemukan.'], 404);
        }
        json_res(['success' => true, 'data' => $berita]);

    case $endpoint === 'berita/admin' && $_SERVER['REQUEST_METHOD'] === 'GET':
        require_editor();
        json_res(['success' => true, 'data' => db_berita()->getAll()]);

    case $endpoint === 'berita/upload' && $_SERVER['REQUEST_METHOD'] === 'POST':
        require_editor();
        json_res(['success' => true, 'url' => upload_gambar()]);

    case $endpoint === 'berita' && $_SERVER['REQUEST_METHOD'] === 'POST':
        $user = require_editor();
        $data = data_berita($input);
        $data['penulis_id'] = $user['id'];
        json_res(['success' => true, 'id' => db_berita()->insert($data), 'message' => 'Berita berhasil ditambahkan.']);

    case $endpoint === 'berita' && $_SERVER['REQUEST_METHOD'] === 'PUT':
        require_editor();
        db_berita()->update((int)($_GET['id'] ?? 0), data_berita($input));
        json_res(['success' => true, 'message' => 'Berita berhasil diperbarui.']);

    case $endpoint === 'berita' && $_SERVER['REQUEST_METHOD'] === 'DELETE':
        require_editor();
        db_berita()->delete((int)($_GET['id'] ?? 0));
        json_res(['success' => true, 'message' => 'Berita berhasil dihapus.']);

    default:
        json_res(['success' => false, 'message' => 'Endpoint tidak ditemukan.'], 404);
}
