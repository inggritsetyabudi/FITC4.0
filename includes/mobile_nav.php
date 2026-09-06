<?php
$activePage = $activePage ?? '';
$user = current_user();
$role = $user['role'] ?? 'kader';
?>
<nav class="nav-kader-mobile d-md-none">
  <?php if ($role === 'bidan'): ?>
    <a href="<?= base_url('index.php?controller=bidan&action=dashboard') ?>" class="nav-kader-item <?= ($activePage === 'bidan_dashboard') ? 'active' : '' ?>">
      <i class="bi bi-grid-1x2<?= ($activePage === 'bidan_dashboard') ? '-fill' : '' ?>"></i>
      <span>Overview</span>
    </a>
    <a href="<?= base_url('index.php?controller=bidan&action=prioritas') ?>" class="nav-kader-item <?= ($activePage === 'prioritas') ? 'active' : '' ?>">
      <i class="bi bi-exclamation-triangle<?= ($activePage === 'prioritas') ? '-fill' : '' ?>"></i>
      <span>Prioritas</span>
    </a>
    <a href="<?= base_url('index.php?controller=bidan&action=rekap') ?>" class="nav-kader-item <?= ($activePage === 'rekap') ? 'active' : '' ?>">
      <i class="bi bi-journal-text"></i>
      <span>Rekap</span>
    </a>
  <?php elseif ($role === 'admin'): ?>
    <a href="<?= base_url('index.php?controller=admin&action=dashboard') ?>" class="nav-kader-item <?= ($activePage === 'admin_dashboard') ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i>
      <span>Dasbor</span>
    </a>
    <a href="<?= base_url('index.php?controller=balita&action=index') ?>" class="nav-kader-item <?= ($activePage === 'balita') ? 'active' : '' ?>">
      <i class="bi bi-people<?= ($activePage === 'balita') ? '-fill' : '' ?>"></i>
      <span>Balita</span>
    </a>
    <a href="<?= base_url('index.php?controller=bidan&action=rekap') ?>" class="nav-kader-item <?= ($activePage === 'rekap') ? 'active' : '' ?>">
      <i class="bi bi-journal-text"></i>
      <span>Rekap</span>
    </a>
  <?php else: ?>
    <a href="<?= base_url('index.php?controller=kader&action=dashboard') ?>" class="nav-kader-item <?= ($activePage === 'kader_dashboard') ? 'active' : '' ?>">
      <i class="bi bi-house-door<?= ($activePage === 'kader_dashboard') ? '-fill' : '' ?>"></i>
      <span>Beranda</span>
    </a>
    <a href="<?= base_url('index.php?controller=balita&action=index') ?>" class="nav-kader-item <?= ($activePage === 'balita') ? 'active' : '' ?>">
      <i class="bi bi-people<?= ($activePage === 'balita') ? '-fill' : '' ?>"></i>
      <span>Balita</span>
    </a>
    <a href="<?= base_url('index.php?controller=pengukuran&action=index') ?>" class="nav-kader-item <?= ($activePage === 'pengukuran') ? 'active' : '' ?>">
      <i class="bi bi-clipboard2-plus<?= ($activePage === 'pengukuran') ? '-fill' : '' ?>"></i>
      <span>Ukur</span>
    </a>
  <?php endif; ?>
</nav>
