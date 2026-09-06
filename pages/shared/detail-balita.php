<?php
require_once __DIR__ . '/../../config/helpers.php';
$id = $_GET['id'] ?? '';
header("Location: " . base_url('index.php?controller=balita&action=detail&id=' . urlencode($id)));
exit;
