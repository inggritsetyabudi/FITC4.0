<?php
$user = current_user();
$topbarTitle = $topbarTitle ?? 'Beranda Kerja';
$role = $user['role'] ?? 'kader';

$nameParts = explode(' ', $user['nama'] ?? 'Petugas');
$initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
?>
<header class="app-topbar">
  <div class="topbar-left">
    <button type="button" id="btnToggleSidebar" class="btn btn-link text-ink-700 d-md-none p-0 me-2" aria-label="Buka Menu">
      <i class="bi bi-list fs-3"></i>
    </button>
    <div>
      <?php if (isset($breadcrumb)): ?>
        <div class="topbar-breadcrumb">
          <?= $breadcrumb ?>
        </div>
      <?php else: ?>
        <h1 class="topbar-title mb-0"><?= htmlspecialchars($topbarTitle) ?></h1>
      <?php endif; ?>
    </div>
  </div>

  <div class="topbar-right">
    <span class="role-badge-pill">
      <i class="bi bi-shield-check"></i>
      <?= match($role) {
        'bidan' => 'Bidan Desa',
        'admin' => 'Admin Posyandu',
        default => 'Kader Posyandu'
      } ?>
    </span>

    <a href="<?= base_url('index.php?controller=profil&action=index') ?>" class="text-decoration-none d-flex align-items-center gap-2" title="Lihat Profil">
      <div class="user-avatar js-user-avatar" style="width:34px; height:34px; font-size:0.75rem; background-color: var(--forest-800); color: #fff;">
        <?= $initials ?>
      </div>
    </a>
  </div>
</header>
