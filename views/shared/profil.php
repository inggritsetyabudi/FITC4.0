<?php
$pageTitle = $pageTitle ?? 'Profil Petugas — Posyandu Pintar';
$activePage = $activePage ?? 'profil';
$topbarTitle = $topbarTitle ?? 'Profil Akun Petugas';
$breadcrumb = $breadcrumb ?? '<span>Profil Pengguna</span>';
$user = $userData ?? ($userSession ?? []);

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="max-w-700 mx-auto" style="max-width: 640px;">
        <div class="mb-4">
          <h2 class="h3 text-forest mb-1">Informasi Akun Petugas</h2>
          <p class="text-muted-posyandu mb-0">Kelola identitas sapaan dan kontak operasional posyandu</p>
        </div>

        <?php if (!empty($successMsg)): ?>
          <div class="alert alert-success font-sm py-2 px-3 mb-3 d-flex align-items-center justify-content-between" style="border-radius: var(--radius-md)">
            <div><i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($successMsg) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <?php if (!empty($errorMsg)): ?>
          <div class="alert alert-danger font-sm py-2 px-3 mb-3 d-flex align-items-center justify-content-between" style="border-radius: var(--radius-md)">
            <div><i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($errorMsg) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <div class="panel-posyandu p-4 mb-4">
          <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <div class="user-avatar" style="width: 56px; height: 56px; font-size: 1.25rem; background-color: var(--blue-900, #0d47a1); color: #fff;">
              <?= strtoupper(substr($user['nama'] ?? 'P', 0, 1)) ?>
            </div>
            <div>
              <h3 class="h5 text-forest mb-1 fw-bold"><?= htmlspecialchars($user['nama'] ?? '') ?></h3>
              <div class="d-flex align-items-center gap-2">
                <span class="role-badge-pill font-xs">
                  <i class="bi bi-shield-check"></i> <?= ucfirst($user['role'] ?? 'Petugas') ?> Posyandu
                </span>
                <?php if (!empty($user['dusun_binaan'])): ?>
                  <span class="font-xs text-muted-posyandu">· Dusun <?= htmlspecialchars($user['dusun_binaan']) ?></span>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <form method="POST" action="<?= base_url('index.php?controller=profil&action=update') ?>">
            <input type="hidden" name="action_type" value="profil">

            <div class="mb-3">
              <label class="form-label font-xs fw-semibold">Nama Pengguna (Username)</label>
              <input type="text" class="form-control-posyandu bg-surface-soft" value="<?= htmlspecialchars($user['username'] ?? '') ?>" disabled readonly>
            </div>

            <div class="mb-3">
              <label class="form-label font-xs fw-semibold">Nama Lengkap</label>
              <input type="text" class="form-control-posyandu bg-surface-soft" value="<?= htmlspecialchars($user['nama'] ?? '') ?>" disabled readonly>
            </div>

            <div class="mb-3">
              <label for="inputSapaan" class="form-label font-xs fw-semibold">Nama Panggilan / Sapaan Ramah <span class="required-mark">*</span></label>
              <input type="text" name="sapaan" id="inputSapaan" class="form-control-posyandu" value="<?= htmlspecialchars($user['sapaan'] ?? '') ?>" required placeholder="Contoh: Bu Sari / Bidan Siti">
              <div class="form-hint">Nama sapaan yang tampil pada ucapan selamat datang di beranda.</div>
            </div>

            <div class="mb-4">
              <label for="inputTelepon" class="form-label font-xs fw-semibold">Nomor WhatsApp / Telepon</label>
              <input type="text" name="telepon" id="inputTelepon" class="form-control-posyandu" value="<?= htmlspecialchars($user['telepon'] ?? '') ?>" placeholder="Contoh: 0812-3456-7890">
            </div>

            <button type="submit" class="btn-posyandu-primary w-100">
              <i class="bi bi-check2-circle"></i> Simpan Perubahan Profil
            </button>
          </form>
        </div>

        <div class="panel-posyandu p-4">
          <h4 class="h6 text-forest fw-bold mb-3"><i class="bi bi-key me-1 text-primary"></i> Ganti Kata Sandi</h4>
          <form method="POST" action="<?= base_url('index.php?controller=profil&action=update') ?>">
            <input type="hidden" name="action_type" value="password">

            <div class="mb-3">
              <label for="inputPasswordLama" class="form-label font-xs fw-semibold">Kata Sandi Saat Ini <span class="required-mark">*</span></label>
              <input type="password" name="password_lama" id="inputPasswordLama" class="form-control-posyandu" required placeholder="Masukkan kata sandi lama">
            </div>

            <div class="row g-3 mb-3">
              <div class="col-sm-6">
                <label for="inputPasswordBaru" class="form-label font-xs fw-semibold">Kata Sandi Baru <span class="required-mark">*</span></label>
                <input type="password" name="password_baru" id="inputPasswordBaru" class="form-control-posyandu" required placeholder="Minimal 3 karakter">
              </div>
              <div class="col-sm-6">
                <label for="inputPasswordKonf" class="form-label font-xs fw-semibold">Ulangi Kata Sandi Baru <span class="required-mark">*</span></label>
                <input type="password" name="password_konfirmasi" id="inputPasswordKonf" class="form-control-posyandu" required placeholder="Konfirmasi kata sandi">
              </div>
            </div>

            <button type="submit" class="btn-posyandu-outline w-100">
              <i class="bi bi-shield-lock"></i> Perbarui Kata Sandi
            </button>
          </form>

          <div class="border-top pt-3 mt-4 text-center">
            <a href="<?= base_url('index.php?controller=auth&action=logout') ?>" class="btn btn-sm btn-outline-danger">
              <i class="bi bi-box-arrow-right me-1"></i> Keluar dari Akun
            </a>
          </div>
        </div>

      </div>
    </main>

    <?php require_once __DIR__ . '/../../includes/mobile_nav.php'; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
