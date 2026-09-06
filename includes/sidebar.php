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
          <a href="<?= base_url('index.php?controller=admin&action=penduduk') ?>" class="sidebar-link <?= ($activePage === 'admin_penduduk') ? 'active' : '' ?>">
            <i class="bi bi-person-lines-fill"></i>
            <span>Data Penduduk</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=balita&action=index') ?>" class="sidebar-link <?= ($activePage === 'balita') ? 'active' : '' ?>">
            <i class="bi bi-people"></i>
            <span>Data Balita</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=ibu_hamil') ?>" class="sidebar-link <?= ($activePage === 'admin_ibu_hamil') ? 'active' : '' ?>">
            <i class="bi bi-heart-pulse"></i>
            <span>Data Ibu Hamil</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=lansia') ?>" class="sidebar-link <?= ($activePage === 'admin_lansia') ? 'active' : '' ?>">
            <i class="bi bi-person-wheelchair"></i>
            <span>Data Lansia</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=kader') ?>" class="sidebar-link <?= ($activePage === 'admin_kader') ? 'active' : '' ?>">
            <i class="bi bi-person-badge-fill"></i>
            <span>Kader &amp; Bidan</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=posyandu') ?>" class="sidebar-link <?= ($activePage === 'admin_posyandu') ? 'active' : '' ?>">
            <i class="bi bi-geo-alt"></i>
            <span>Data Posyandu</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=pemeriksaan') ?>" class="sidebar-link <?= ($activePage === 'admin_pemeriksaan') ? 'active' : '' ?>">
            <i class="bi bi-clipboard2-check"></i>
            <span>Audit Pemeriksaan</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=jadwal') ?>" class="sidebar-link <?= ($activePage === 'admin_jadwal') ? 'active' : '' ?>">
            <i class="bi bi-calendar-event"></i>
            <span>Jadwal Posyandu</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=kegiatan') ?>" class="sidebar-link <?= ($activePage === 'admin_kegiatan') ? 'active' : '' ?>">
            <i class="bi bi-camera-video"></i>
            <span>Agenda Kegiatan</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=laporan') ?>" class="sidebar-link <?= ($activePage === 'admin_laporan') ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-pdf"></i>
            <span>Laporan (PDF/CSV)</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=notifikasi') ?>" class="sidebar-link <?= ($activePage === 'admin_notifikasi') ? 'active' : '' ?>">
            <i class="bi bi-bell"></i>
            <span>Notifikasi Siaran</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=users') ?>" class="sidebar-link <?= ($activePage === 'admin_users') ? 'active' : '' ?>">
            <i class="bi bi-shield-lock"></i>
            <span>Manajemen User</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=profil_desa') ?>" class="sidebar-link <?= ($activePage === 'admin_profil_desa') ? 'active' : '' ?>">
            <i class="bi bi-building"></i>
            <span>Profil Desa</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=audit_log') ?>" class="sidebar-link <?= ($activePage === 'admin_audit_log') ? 'active' : '' ?>">
            <i class="bi bi-clock-history"></i>
            <span>Audit Log</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('index.php?controller=admin&action=backup') ?>" class="sidebar-link <?= ($activePage === 'admin_backup') ? 'active' : '' ?>">
            <i class="bi bi-database-down"></i>
            <span>Backup Data</span>
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
