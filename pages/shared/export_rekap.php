<?php
require_once __DIR__ . '/../../config/helpers.php';
$qs = $_SERVER['QUERY_STRING'] ?? '';
header("Location: " . base_url('index.php?controller=bidan&action=export' . ($qs ? '&' . $qs : '')));
exit;
