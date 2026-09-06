<?php
$pageTitle = 'Halaman Tidak Ditemukan — Posyandu Pintar';
$bodyClass = 'bg-canvas';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="d-flex align-items-center justify-content-center min-vh-100 p-4">
  <div class="text-center bg-surface p-5 rounded-4 border-line shadow-sm" style="max-width: 480px;">
    <div class="d-inline-flex p-3 bg-warning-soft text-warning rounded-circle mb-3">
      <i class="bi bi-exclamation-octagon fs-1"></i>
    </div>
    <h1 class="h3 text-forest fw-bold mb-2">Halaman Tidak Ditemukan</h1>
    <p class="font-sm text-ink-500 mb-4">
      Halaman yang Anda tuju tidak tersedia atau tautan telah dipindahkan.
    </p>
    <a href="<?= base_url('index.php') ?>" class="btn-posyandu-primary w-100">
      <i class="bi bi-house-door me-1"></i> Kembali ke Beranda
    </a>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
