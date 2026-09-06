<?php
require_once __DIR__ . '/../../config/helpers.php';
$id = $_GET['id'] ?? 'art_01';
header("Location: " . base_url('index.php?controller=berita&action=detail&id=' . urlencode($id)));
exit;
