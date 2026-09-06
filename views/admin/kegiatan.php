<?php
$pageTitle = $pageTitle ?? 'Dokumentasi & Kegiatan — Posyandu Pintar';
$activePage = $activePage ?? 'admin_kegiatan';
$topbarTitle = $topbarTitle ?? 'Dokumentasi Pelaksanaan Program Kesehatan Desa';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Kegiatan</span>';
$daftarJadwal = $daftarJadwal ?? [];

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
          <h1 class="h3 text-forest mb-1 fw-bold">Agenda &amp; Catatan Pelaksanaan Kegiatan</h1>
          <p class="text-muted-posyandu mb-0 font-sm">
            Dokumentasi berkala pelaksanaan posyandu balita, ibu hamil, lansia, dan program intervensi stunting desa
          </p>
        </div>

        <a href="<?= base_url('index.php?controller=admin&action=jadwal') ?>" class="btn-posyandu-primary">
          <i class="bi bi-calendar-plus"></i> Kelola Jadwal Baru
        </a>
      </div>

      <div class="row g-4 mb-4">
        <?php foreach ($daftarJadwal as $k): ?>
          <div class="col-md-6 col-lg-4">
            <div class="card rounded-4 border-line bg-surface shadow-xs h-100 overflow-hidden d-flex flex-column justify-content-between">
              <div class="p-3 bg-surface-soft border-bottom d-flex align-items-center justify-content-between">
                <span class="badge bg-primary font-xs"><?= format_tanggal_id($k['tanggal_kegiatan'], true) ?></span>
                <span class="badge bg-primary-subtle text-primary font-xs">Dusun <?= htmlspecialchars($k['dusun']) ?></span>
              </div>
              <div class="p-3">
                <h3 class="h6 fw-bold text-forest mb-1"><?= htmlspecialchars($k['judul_agenda']) ?></h3>
                <div class="font-xs text-muted-posyandu mb-2">
                  <i class="bi bi-clock me-1"></i><?= substr($k['jam_mulai'], 0, 5) ?> - <?= substr($k['jam_selesai'], 0, 5) ?> WIB · <?= htmlspecialchars($k['lokasi']) ?>
                </div>
                <p class="font-xs text-ink-700 mb-0">
                  <?= htmlspecialchars($k['keterangan'] ?: 'Pelayanan kesehatan terpadu dan pengukuran balita desa.') ?>
                </p>
              </div>
              <div class="p-3 border-top bg-surface-soft font-xs d-flex align-items-center justify-content-between">
                <span class="text-muted-posyandu">PJ: <strong><?= htmlspecialchars($k['penanggung_jawab']) ?></strong></span>
                <span class="badge <?= ($k['status'] === 'selesai') ? 'bg-success text-white' : 'bg-warning text-dark' ?>">
                  <?= ucfirst($k['status']) ?>
                </span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </main>

    <?php require_once __DIR__ . '/../../includes/mobile_nav.php'; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
