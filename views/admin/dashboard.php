<?php
$pageTitle = $pageTitle ?? 'Dasbor Admin — Posyandu Pintar';
$activePage = $activePage ?? 'admin_dashboard';
$topbarTitle = $topbarTitle ?? 'Pusat Kendali Pemantauan Balita';
$breadcrumb = $breadcrumb ?? '<span>Dasbor Utama</span>';
$stats = $stats ?? [
  'total_balita' => 0,
  'total_pengukuran' => 0,
  'total_prioritas' => 0,
  'total_kader' => 0,
  'total_bidan' => 0,
  'sudah_ukur_bulan_ini' => 0
];
$currentUser = current_user() ?? [];

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="panel-posyandu p-4 mb-4" style="background: linear-gradient(135deg, #EFF6FF 0%, #FFFFFF 100%); border-left: 5px solid #1D4ED8;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="badge bg-primary px-3 py-1 font-xs text-uppercase fw-semibold">Admin Posyandu</span>
              <span class="text-muted-posyandu font-sm"><?= format_tanggal_id(date('Y-m-d')) ?></span>
            </div>
            <h1 class="h3 fw-bold text-forest mb-1">Selamat Datang, <?= htmlspecialchars($currentUser['sapaan'] ?? $currentUser['nama'] ?? 'Admin') ?></h1>
            <p class="text-muted-posyandu mb-0 font-sm">Kelola data balita, petugas, dan jadwal penimbangan dari satu tempat.</p>
          </div>
          <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('index.php?controller=balita&action=index') ?>" class="btn-posyandu-primary">
              <i class="bi bi-people-fill me-1"></i> Data Balita
            </a>
            <a href="<?= base_url('index.php?controller=admin&action=jadwal') ?>" class="btn-posyandu-outline">
              <i class="bi bi-calendar-plus me-1"></i> Jadwal
            </a>
            <a href="<?= base_url('index.php?controller=admin&action=laporan') ?>" class="btn-posyandu-outline">
              <i class="bi bi-file-earmark-pdf me-1"></i> Laporan
            </a>
          </div>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
          <div class="panel-posyandu p-4 h-100 d-flex align-items-center gap-3">
            <div style="width:56px;height:56px;border-radius:14px;background:#E3F2FD;color:#1565C0;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="bi bi-balloon-heart-fill fs-3"></i>
            </div>
            <div><div class="text-muted-posyandu font-sm fw-medium">Balita Aktif</div><div class="h2 fw-bold text-forest mb-0"><?= $stats['total_balita'] ?></div><div class="font-xs text-muted-posyandu mt-1">Data sasaran utama</div></div>
          </div>
        </div>
        <div class="col-sm-6 col-xl-3">
          <div class="panel-posyandu p-4 h-100 d-flex align-items-center gap-3">
            <div style="width:56px;height:56px;border-radius:14px;background:#DCFCE7;color:#15803D;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="bi bi-clipboard2-check-fill fs-3"></i>
            </div>
            <div><div class="text-muted-posyandu font-sm fw-medium">Ukur Bulan Ini</div><div class="h2 fw-bold text-forest mb-0"><?= $stats['sudah_ukur_bulan_ini'] ?></div><div class="font-xs text-muted-posyandu mt-1">Balita sudah dicatat</div></div>
          </div>
        </div>
        <div class="col-sm-6 col-xl-3">
          <div class="panel-posyandu p-4 h-100 d-flex align-items-center gap-3">
            <div style="width:56px;height:56px;border-radius:14px;background:#FEF3C7;color:#D97706;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="bi bi-exclamation-triangle-fill fs-3"></i>
            </div>
            <div><div class="text-muted-posyandu font-sm fw-medium">Perlu Perhatian</div><div class="h2 fw-bold text-forest mb-0"><?= $stats['total_prioritas'] ?></div><div class="font-xs text-muted-posyandu mt-1">Pantau bersama bidan</div></div>
          </div>
        </div>
        <div class="col-sm-6 col-xl-3">
          <div class="panel-posyandu p-4 h-100 d-flex align-items-center gap-3">
            <div style="width:56px;height:56px;border-radius:14px;background:#F0FDF4;color:#16A34A;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="bi bi-person-badge-fill fs-3"></i>
            </div>
            <div><div class="text-muted-posyandu font-sm fw-medium">Petugas</div><div class="h2 fw-bold text-forest mb-0"><?= $stats['total_kader'] + $stats['total_bidan'] ?></div><div class="font-xs text-muted-posyandu mt-1"><?= $stats['total_kader'] ?> Kader · <?= $stats['total_bidan'] ?> Bidan</div></div>
          </div>
        </div>
      </div>

      <section class="mb-4">
        <h2 class="h5 fw-bold text-forest mb-3">Menu Utama</h2>
        <div class="row g-3">
          <div class="col-md-4">
            <a href="<?= base_url('index.php?controller=balita&action=index') ?>" class="panel-posyandu p-4 text-decoration-none d-flex align-items-center gap-3 h-100">
              <div style="width:48px;height:48px;border-radius:12px;background:#EFF6FF;color:#2563EB;display:flex;align-items:center;justify-content:center;"><i class="bi bi-people-fill fs-4"></i></div>
              <div><strong class="text-forest d-block">Data Balita</strong><span class="text-muted-posyandu font-xs">Tambah, lihat, dan buka detail balita</span></div>
            </a>
          </div>
          <div class="col-md-4">
            <a href="<?= base_url('index.php?controller=admin&action=kader') ?>" class="panel-posyandu p-4 text-decoration-none d-flex align-items-center gap-3 h-100">
              <div style="width:48px;height:48px;border-radius:12px;background:#F0FDF4;color:#16A34A;display:flex;align-items:center;justify-content:center;"><i class="bi bi-person-badge-fill fs-4"></i></div>
              <div><strong class="text-forest d-block">Kader & Bidan</strong><span class="text-muted-posyandu font-xs">Kelola petugas yang melakukan pemantauan</span></div>
            </a>
          </div>
          <div class="col-md-4">
            <a href="<?= base_url('index.php?controller=admin&action=jadwal') ?>" class="panel-posyandu p-4 text-decoration-none d-flex align-items-center gap-3 h-100">
              <div style="width:48px;height:48px;border-radius:12px;background:#FEF3C7;color:#D97706;display:flex;align-items:center;justify-content:center;"><i class="bi bi-calendar-event-fill fs-4"></i></div>
              <div><strong class="text-forest d-block">Jadwal Posyandu</strong><span class="text-muted-posyandu font-xs">Atur jadwal penimbangan balita</span></div>
            </a>
          </div>
        </div>
      </section>

      <section class="panel-posyandu p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
          <div>
            <h2 class="h6 fw-bold text-forest mb-1">Alur Sistem</h2>
            <p class="font-xs text-muted-posyandu mb-0">Fokus aplikasi hanya pada pertumbuhan dan pemantauan balita.</p>
          </div>
        </div>
        <div class="row g-3 text-center">
          <div class="col-md-3"><div class="p-3 rounded-3 bg-light"><div class="fs-3 text-primary mb-2"><i class="bi bi-person-plus-fill"></i></div><strong class="font-sm d-block">1. Data Balita</strong><span class="font-xs text-muted-posyandu">Kader mendaftarkan balita</span></div></div>
          <div class="col-md-3"><div class="p-3 rounded-3 bg-light"><div class="fs-3 text-primary mb-2"><i class="bi bi-clipboard2-plus-fill"></i></div><strong class="font-sm d-block">2. Pengukuran</strong><span class="font-xs text-muted-posyandu">BB, TB, dan usia dicatat</span></div></div>
          <div class="col-md-3"><div class="p-3 rounded-3 bg-light"><div class="fs-3 text-warning mb-2"><i class="bi bi-graph-up-arrow"></i></div><strong class="font-sm d-block">3. Status</strong><span class="font-xs text-muted-posyandu">Hasil dikelompokkan</span></div></div>
          <div class="col-md-3"><div class="p-3 rounded-3 bg-light"><div class="fs-3 text-success mb-2"><i class="bi bi-heart-pulse-fill"></i></div><strong class="font-sm d-block">4. Pemantauan</strong><span class="font-xs text-muted-posyandu">Bidan melihat prioritas</span></div></div>
        </div>
      </section>
    </main>
  </div>
</div>
