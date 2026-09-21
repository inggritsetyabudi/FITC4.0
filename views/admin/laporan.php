<?php
$pageTitle = $pageTitle ?? 'Laporan Balita — Posyandu Pintar';
$activePage = $activePage ?? 'admin_laporan';
$topbarTitle = $topbarTitle ?? 'Laporan Pertumbuhan Balita (PDF & CSV)';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Laporan Balita</span>';
$bulan = $bulan ?? date('Y-m');
$dataRekap = $dataRekap ?? [];

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>
  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>
    <main class="app-content">
      <div class="mb-4">
        <h1 class="h3 fw-bold text-forest mb-1">Laporan Pertumbuhan Balita</h1>
        <p class="text-muted-posyandu mb-0">Unduh rekap pengukuran balita dalam format PDF atau CSV.</p>
      </div>

      <section class="panel-posyandu p-4 mb-4">
        <form method="GET" action="<?= base_url('index.php') ?>" class="row g-3 align-items-end">
          <input type="hidden" name="controller" value="admin">
          <input type="hidden" name="action" value="laporan">
          <div class="col-md-4">
            <label class="form-label font-sm fw-semibold">Bulan Pengukuran</label>
            <input type="month" name="bulan" value="<?= htmlspecialchars($bulan) ?>" class="form-control">
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn-posyandu-outline w-100">Tampilkan</button>
          </div>
        </form>
      </section>

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="panel-posyandu p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div style="width:52px;height:52px;border-radius:12px;background:#FEE2E2;color:#B91C1C;display:flex;align-items:center;justify-content:center;"><i class="bi bi-file-earmark-pdf-fill fs-4"></i></div>
              <div><h2 class="h5 fw-bold text-forest mb-1">Laporan PDF</h2><p class="font-xs text-muted-posyandu mb-0">Siap dicetak atau disimpan sebagai PDF.</p></div>
            </div>
            <a target="_blank" href="<?= base_url('index.php?controller=admin&action=export_pdf&bulan=' . urlencode($bulan)) ?>" class="btn-posyandu-primary w-100 text-center">Cetak Laporan PDF</a>
          </div>
        </div>
        <div class="col-md-6">
          <div class="panel-posyandu p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div style="width:52px;height:52px;border-radius:12px;background:#DCFCE7;color:#15803D;display:flex;align-items:center;justify-content:center;"><i class="bi bi-file-earmark-spreadsheet-fill fs-4"></i></div>
              <div><h2 class="h5 fw-bold text-forest mb-1">Rekap CSV</h2><p class="font-xs text-muted-posyandu mb-0">Data pengukuran balita untuk spreadsheet.</p></div>
            </div>
            <a href="<?= base_url('index.php?controller=admin&action=export_csv&bulan=' . urlencode($bulan)) ?>" class="btn-posyandu-outline w-100 text-center">Unduh Data CSV</a>
          </div>
        </div>
      </div>

      <section class="panel-posyandu p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h2 class="h6 fw-bold text-forest mb-1">Ringkasan Data</h2>
            <p class="font-xs text-muted-posyandu mb-0"><?= count($dataRekap) ?> data balita/pengukuran untuk periode terpilih.</p>
          </div>
          <a href="<?= base_url('index.php?controller=bidan&action=rekap&bulan=' . urlencode($bulan)) ?>" class="btn-posyandu-outline btn-sm">Lihat Rekap Lengkap</a>
        </div>
      </section>
    </main>
  </div>
</div>
