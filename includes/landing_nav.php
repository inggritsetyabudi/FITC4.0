<?php
$currentUser = current_user();
?>
<header class="landing-nav">
  <div class="container">
    <div class="landing-nav-inner">
      <a href="<?= base_url('index.php') ?>" class="landing-brand">
        <img
          src="<?= base_url('assets/images/logo sementara.png') ?>"
          alt="Logo Posyandu Pintar"
          class="navbar-logo-sementara"
        />
        <div>
          <span class="brand-title">Posyandu Pintar</span>
          <span class="brand-subtitle">Layanan Tumbuh Kembang Desa</span>
        </div>
      </a>

      <nav aria-label="Navigasi Utama">
        <ul class="landing-menu">
          <li><a href="<?= base_url('index.php#beranda') ?>">Beranda</a></li>
          <li><a href="<?= base_url('index.php#tentang') ?>">Tentang Kami</a></li>
          <li><a href="<?= base_url('index.php#jadwal') ?>">Jadwal Posyandu</a></li>
          <li><a href="<?= base_url('index.php#blog') ?>">Blog</a></li>
          <li><a href="<?= base_url('index.php#berita') ?>">Berita</a></li>
        </ul>
      </nav>

      <div>
        <?php if ($currentUser): ?>
          <a href="<?= get_dashboard_url($currentUser['role']) ?>" class="btn-posyandu-nav">
            <i class="bi bi-speedometer2"></i> Masuk ke Dasbor (<?= htmlspecialchars($currentUser['sapaan']) ?>)
          </a>
        <?php else: ?>
          <a href="<?= base_url('index.php?controller=auth&action=showLogin') ?>" class="btn-posyandu-nav">
            <i class="bi bi-box-arrow-in-right"></i> Masuk
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>
