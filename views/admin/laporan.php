<?php
$pageTitle = $pageTitle ?? 'Laporan & Statistik — Posyandu Pintar';
$activePage = $activePage ?? 'admin_laporan';
$topbarTitle = $topbarTitle ?? 'Pusat Unduh Laporan Eksekutif Desa (PDF & CSV)';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Laporan Terpadu</span>';
$stats = $stats ?? [];
$profilDesa = $profilDesa ?? [];

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
          <h1 class="h3 text-forest mb-1 fw-bold">Pusat Laporan &amp; Rekapitulasi Desa</h1>
          <p class="text-muted-posyandu mb-0 font-sm">
            Ekspor dokumen resmi pemerintah desa format PDF siap cetak dan data olahan spreadsheet CSV
          </p>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-lg-6">
          <div class="card p-4 rounded-4 border-line bg-surface shadow-xs h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex align-items-center gap-2 text-primary mb-2">
                <i class="bi bi-file-earmark-pdf-fill fs-3"></i>
                <span class="font-xs text-uppercase fw-bold">Laporan Eksekutif Desa</span>
              </div>
              <h2 class="h5 fw-bold text-forest mb-2">Rekapitulasi Kependudukan &amp; Sasaran Posyandu</h2>
              <p class="font-sm text-ink-700 mb-3">
                Laporan komprehensif mencakup seluruh data balita, ibu hamil, lansia, dan kader posyandu lengkap dengan kop surat resmi Pemerintah Desa Sukatani dan kolom pengesahan Kepala Desa.
              </p>
            </div>
            <div class="d-flex gap-2">
              <a href="<?= base_url('index.php?controller=admin&action=export_pdf') ?>" target="_blank" class="btn-posyandu-primary btn-sm flex-fill text-center">
                <i class="bi bi-printer-fill me-1"></i> Cetak Dokumen PDF
              </a>
              <a href="<?= base_url('index.php?controller=admin&action=export_csv') ?>" class="btn-posyandu-outline btn-sm flex-fill text-center">
                <i class="bi bi-file-earmark-arrow-down me-1"></i> Unduh Data CSV
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card p-4 rounded-4 border-line bg-surface shadow-xs h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex align-items-center gap-2 text-success mb-2">
                <i class="bi bi-file-earmark-medical-fill fs-3"></i>
                <span class="font-xs text-uppercase fw-bold text-primary">Laporan Klinis Bidan</span>
              </div>
              <h2 class="h5 fw-bold text-forest mb-2">Rekapitulasi Pertumbuhan &amp; Evaluasi Gizi Balita</h2>
              <p class="font-sm text-ink-700 mb-3">
                Laporan antropometri bulanan balita (Berat Badan, Tinggi Badan, kurva pertumbuhan KMS, kategori gizi) dengan pengesahan tanda tangan Bidan Desa Sukatani.
              </p>
            </div>
            <div class="d-flex gap-2">
              <a href="<?= base_url('index.php?controller=bidan&action=export_pdf') ?>" target="_blank" class="btn-posyandu-primary btn-sm flex-fill text-center">
                <i class="bi bi-printer-fill me-1"></i> Cetak Laporan Gizi PDF
              </a>
              <a href="<?= base_url('index.php?controller=bidan&action=export') ?>" class="btn-posyandu-outline btn-sm flex-fill text-center">
                <i class="bi bi-file-earmark-arrow-down me-1"></i> Unduh Rekap CSV
              </a>
            </div>
          </div>
        </div>
      </div>

      <section class="panel-posyandu">
        <div class="panel-header">
          <div>
            <h3 class="panel-title">Ringkasan Statistik Angka Sasaran Wilayah Desa</h3>
            <p class="panel-subtitle">Data agregat per dusun binaan berdasarkan penimbangan berjalan</p>
          </div>
        </div>

        <div class="table-posyandu-wrap">
          <table class="table-posyandu">
            <thead>
              <tr>
                <th>Wilayah Dusun</th>
                <th>Balita Aktif</th>
                <th>Ibu Hamil</th>
                <th>Lansia Binaan</th>
                <th>Total Sasaran</th>
                <th>Cakupan Wilayah</th>
              </tr>
            </thead>
            <tbody>
              <?php if (isset($stats['dusun_breakdown'])): ?>
                <?php foreach ($stats['dusun_breakdown'] as $dusunName => $data): ?>
                  <tr>
                    <td class="fw-bold text-forest">Dusun <?= htmlspecialchars($dusunName) ?></td>
                    <td><?= $data['balita'] ?> Balita</td>
                    <td><?= $data['bumil'] ?> Ibu Hamil</td>
                    <td><?= $data['lansia'] ?> Lansia</td>
                    <td class="fw-bold text-forest"><?= $data['total'] ?> Jiwa</td>
                    <td>
                      <div class="progress" style="height: 8px; border-radius: 4px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?= min(100, round(($data['total'] / 15) * 100)) ?>%;"></div>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>

    <?php require_once __DIR__ . '/../../includes/mobile_nav.php'; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
