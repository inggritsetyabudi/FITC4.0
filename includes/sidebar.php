<?php
$user = current_user();
$role = $user['role'] ?? 'kader';
$activePage = $activePage ?? '';

$nameParts = explode(' ', $user['nama'] ?? 'Petugas Posyandu');
$initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
?>
<aside class="app-sidebar" id="appSidebar">
  <a href="<?= get_dashboard_url($role) ?>" class="sidebar-brand">
    <div class="brand-icon">
      <i class="bi bi-heart-pulse-fill"></i>
    </div>
    <div class="brand-info">
      <span class="brand-title">Posyandu Pintar</span>
      <span class="brand-subtitle">
        <?= match($role) {
          'bidan' => 'Panel Bidan Desa',
          'admin' => 'Panel Admin Desa',
          default => 'Panel Kader Desa'
        } ?>
      </span>
    </div>
  </a>

  <nav class="sidebar-nav">
    <div class="sidebar-section-title">Menu Utama</div>
    <ul class="sidebar-menu">
      <?php if ($role === 'kader'): ?>
        <li>
          <a href="<?= base_url('index.php?controller=kader&action=dashboard') ?>" class="sidebar-link <?= ($activePage === 'kader_dashboard') ? 'active' : '' ?>">
            <i class="bi bi-house-door"></i>
            <span>Beranda Tugas</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=balita&action=index') ?>" class="sidebar-link <?= ($activePage === 'balita') ? 'active' : '' ?>">
            <i class="bi bi-people"></i>
            <span>Data Balita</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=pengukuran&action=index') ?>" class="sidebar-link <?= ($activePage === 'pengukuran') ? 'active' : '' ?>">
            <i class="bi bi-clipboard2-plus"></i>
            <span>Catat Pengukuran</span>
          </a>
        </li>

      <?php elseif ($role === 'bidan'): ?>
        <li>
          <a href="<?= base_url('index.php?controller=bidan&action=dashboard') ?>" class="sidebar-link <?= ($activePage === 'bidan_dashboard') ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2"></i>
            <span>Overview &amp; Prioritas</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=bidan&action=prioritas') ?>" class="sidebar-link <?= ($activePage === 'prioritas') ? 'active' : '' ?>">
            <i class="bi bi-exclamation-triangle"></i>
            <span>Balita Prioritas</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=bidan&action=rekap') ?>" class="sidebar-link <?= ($activePage === 'rekap') ? 'active' : '' ?>">
            <i class="bi bi-journal-text"></i>
            <span>Rekap Pengukuran</span>
          </a>
        </li>

      <?php elseif ($role === 'admin'): ?>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=dashboard') ?>" class="sidebar-link <?= ($activePage === 'admin_dashboard') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Ringkasan Dasbor</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=balita&action=index') ?>" class="sidebar-link <?= ($activePage === 'balita') ? 'active' : '' ?>">
            <i class="bi bi-people"></i>
            <span>Data Balita</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=kader') ?>" class="sidebar-link <?= ($activePage === 'admin_kader') ? 'active' : '' ?>">
            <i class="bi bi-person-badge-fill"></i>
            <span>Kader &amp; Bidan</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=jadwal') ?>" class="sidebar-link <?= ($activePage === 'admin_jadwal') ? 'active' : '' ?>">
            <i class="bi bi-calendar-event"></i>
            <span>Jadwal Posyandu</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=laporan') ?>" class="sidebar-link <?= ($activePage === 'admin_laporan') ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-pdf"></i>
            <span>Laporan (PDF/CSV)</span>
          </a>
        </li>
      <?php endif; ?>
    </ul>

    <div class="sidebar-section-title">Akun &amp; Panduan</div>
    <ul class="sidebar-menu">
      <li>
        <a href="<?= base_url('index.php?controller=profil&action=index') ?>" class="sidebar-link <?= ($activePage === 'profil') ? 'active' : '' ?>">
          <i class="bi bi-person"></i>
          <span>Profil Petugas</span>
        </a>
      </li>
      <li>
        <a href="<?= base_url('index.php') ?>" class="sidebar-link" target="_blank">
          <i class="bi bi-box-arrow-up-right"></i>
          <span>Portal Publik</span>
        </a>
      </li>
    </ul>
  </nav>

  <div class="sidebar-footer">
    <div class="user-profile-widget">
      <div class="user-avatar js-user-avatar" style="background-color: var(--forest-900); color: #fff;">
        <?= $initials ?>
      </div>
      <div class="user-meta">
        <span class="user-name"><?= htmlspecialchars($user['sapaan'] ?? $user['nama']) ?></span>
        <span class="user-role">
          <?= match($role) {
            'bidan' => 'Bidan Desa',
            'admin' => 'Administrator',
            default => 'Kader · ' . ($user['dusun_binaan'] ?: 'Posyandu')
          } ?>
        </span>
      </div>
      <a href="<?= base_url('index.php?controller=auth&action=logout') ?>" class="btn btn-link text-danger p-0 ms-auto text-decoration-none" title="Keluar">
        <i class="bi bi-box-arrow-right fs-5"></i>
      </a>
    </div>
  </div>
</aside>
