<?php
$pageTitle = $pageTitle ?? 'Masuk — Posyandu Pintar';
$pageDesc = $pageDesc ?? 'Halaman masuk Posyandu Pintar untuk Kader, Bidan, dan Admin Posyandu Desa.';
$bodyClass = 'login-body';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="login-page">
  <div class="login-image-panel">
    <img src="<?= base_url('assets/images/hero_bidan.jpg') ?>" alt="Pelayanan Posyandu Desa" />

    <div class="login-image-overlay">
      <a href="<?= base_url('index.php') ?>" class="login-brand">
        <div class="login-logo">
          <i class="bi bi-heart-pulse-fill"></i>
        </div>
        <div>
          <strong>Posyandu Pintar</strong>
        </div>
      </a>

      <div class="login-image-content">
        <span class="login-image-label">POSYANDU PINTAR</span>
        <h1>Pencatatan lebih rapi, pemantauan lebih mudah.</h1>
        <p>
          Kelola data balita, penimbangan, dan pemantauan tumbuh kembang
          dalam satu sistem terpadu desa.
        </p>
      </div>
    </div>
  </div>

  <div class="login-form-panel">
    <div class="login-card">
      <div class="login-header">
        <div class="login-mobile-logo">
          <div class="login-logo">
            <i class="bi bi-heart-pulse-fill"></i>
          </div>
        </div>

        <span class="login-welcome">SELAMAT DATANG</span>
        <h2 class="h4 mb-1">Masuk ke Posyandu Pintar</h2>
        <p class="text-ink-500 font-sm mb-0">
          Silakan masukkan akun petugas Posyandu Anda
        </p>
      </div>

      <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger font-sm py-2 px-3 mb-3" style="border-radius: var(--radius-md)">
          <i class="bi bi-exclamation-circle me-1"></i> <?= htmlspecialchars($errorMsg) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= base_url('index.php?controller=auth&action=login') ?>" id="loginForm">
        <div class="mb-3">
          <label for="inputUsername" class="form-label">
            Nama Pengguna / Akun <span class="required-mark">*</span>
          </label>
          <input
            type="text"
            name="username"
            id="inputUsername"
            class="form-control-posyandu"
            placeholder="Masukkan nama pengguna"
            value="<?= htmlspecialchars($username ?? '') ?>"
            autocomplete="username"
            required
            autofocus
          />
        </div>

        <div class="mb-3">
          <label for="inputPassword" class="form-label">
            Kata Sandi <span class="required-mark">*</span>
          </label>
          <input
            type="password"
            name="password"
            id="inputPassword"
            class="form-control-posyandu"
            placeholder="Masukkan kata sandi"
            autocomplete="current-password"
            required
          />
        </div>

        <button
          type="submit"
          id="btnSubmitLogin"
          class="btn-posyandu-primary w-100 mb-2"
        >
          <i class="bi bi-box-arrow-in-right"></i> Masuk
        </button>

        <a href="<?= base_url('index.php') ?>" class="btn-posyandu-secondary w-100">
          Kembali ke Beranda
        </a>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
