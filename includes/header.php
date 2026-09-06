<?php
require_once __DIR__ . '/../config/helpers.php';

if (!isset($pageTitle)) {
    $pageTitle = 'Posyandu Pintar — Layanan Tumbuh Kembang & Skrining Balita Desa';
}
if (!isset($pageDesc)) {
    $pageDesc = 'Sistem digital pencatatan tumbuh kembang balita dan pemantauan pertumbuhan untuk Posyandu desa.';
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDesc) ?>" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

  <link rel="stylesheet" href="<?= base_url('assets/css/tokens.css?v=' . filemtime(__DIR__ . '/../assets/css/tokens.css')) ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/base.css?v=' . filemtime(__DIR__ . '/../assets/css/base.css')) ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/layout.css?v=' . filemtime(__DIR__ . '/../assets/css/layout.css')) ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/components.css?v=' . filemtime(__DIR__ . '/../assets/css/components.css')) ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/pages.css?v=' . filemtime(__DIR__ . '/../assets/css/pages.css')) ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/responsive.css?v=' . filemtime(__DIR__ . '/../assets/css/responsive.css')) ?>" />
</head>
<body class="<?= $bodyClass ?? '' ?>">
