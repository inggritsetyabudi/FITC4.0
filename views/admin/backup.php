<?php
$pageTitle = $pageTitle ?? 'Backup & Arsip Data — Posyandu Pintar';
$activePage = $activePage ?? 'admin_backup';
$topbarTitle = $topbarTitle ?? 'Cadangan Basis Data & Pemulihan Sistem';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Backup Data</span>';

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
          <h2 class="h3 text-forest mb-1">Pusat Backup &amp; Ekspor Data Posyandu</h2>
          <p class="text-muted-posyandu mb-0">Amankan data penduduk, riwayat pemeriksaan balita, data bumil, lansia, dan log aktivitas desa</p>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
          <div class="panel-posyandu p-4 h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="stat-icon-wrap mb-3" style="background:#E3F2FD; color:#1565C0; width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-database-down fs-4"></i>
              </div>
              <h3 class="h6 fw-bold text-forest mb-2">Cadangan Basis Data (JSON)</h3>
              <p class="font-sm text-muted-posyandu mb-4">
                Arsip komprehensif seluruh tabel sistem posyandu (warga, balita, ibu hamil, lansia, jadwal, dan audit log).
              </p>
            </div>
            <a href="<?= base_url('index.php?controller=admin&action=backup&download=json') ?>" class="btn-posyandu-primary w-100 text-center">
              <i class="bi bi-download me-1"></i> Unduh JSON Backup
            </a>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="panel-posyandu p-4 h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="stat-icon-wrap mb-3" style="background:#E0F2FE; color:#0284C7; width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-file-earmark-spreadsheet-fill fs-4"></i>
              </div>
              <h3 class="h6 fw-bold text-forest mb-2">Ekspor Penduduk (CSV)</h3>
              <p class="font-sm text-muted-posyandu mb-4">
                Tabel data induk kependudukan lengkap dengan NIK, dusun, kategori sasaran, dan kontak warga format spreadsheet.
              </p>
            </div>
            <a href="<?= base_url('index.php?controller=admin&action=export_csv') ?>" class="btn-posyandu-outline w-100 text-center">
              <i class="bi bi-file-earmark-excel me-1"></i> Unduh CSV Penduduk
            </a>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="panel-posyandu p-4 h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="stat-icon-wrap mb-3" style="background:#EDE7F6; color:#5E35B1; width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-file-earmark-pdf-fill fs-4"></i>
              </div>
              <h3 class="h6 fw-bold text-forest mb-2">Laporan Eksekutif (PDF)</h3>
              <p class="font-sm text-muted-posyandu mb-4">
                Laporan resmi berkepala surat desa siap cetak untuk arsip pertanggungjawaban Kepala Desa &amp; BPD.
              </p>
            </div>
            <a href="<?= base_url('index.php?controller=admin&action=export_pdf') ?>" target="_blank" class="btn-posyandu-outline w-100 text-center" style="border-color:#5E35B1; color:#5E35B1;">
              <i class="bi bi-printer me-1"></i> Cetak PDF Laporan
            </a>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="panel-posyandu p-4 h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="stat-icon-wrap mb-3" style="background:#E0F7FA; color:#00838F; width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-clipboard2-pulse-fill fs-4"></i>
              </div>
              <h3 class="h6 fw-bold text-forest mb-2">Laporan Klinis Bidan (PDF)</h3>
              <p class="font-sm text-muted-posyandu mb-4">
                Laporan rekapitulasi skrining gizi, kurva pertumbuhan balita, dan rujukan bidan siap cetak / PDF.
              </p>
            </div>
            <a href="<?= base_url('index.php?controller=bidan&action=export_pdf') ?>" target="_blank" class="btn-posyandu-outline w-100 text-center" style="border-color:#00838F; color:#00838F;">
              <i class="bi bi-file-pdf me-1"></i> Cetak PDF Bidan
            </a>
          </div>
        </div>
      </div>

      <div class="panel-posyandu p-4">
        <h4 class="h6 fw-bold text-forest mb-3"><i class="bi bi-shield-lock-fill me-2 text-primary"></i>Panduan Standar Operasional Keamanan Data Posyandu</h4>
        <div class="row g-4 font-sm text-dark">
          <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-light h-100">
              <h5 class="font-sm fw-bold text-forest mb-2"><i class="bi bi-clock me-1 text-primary"></i>Frekuensi Pencadangan Rutin</h5>
              <p class="text-muted-posyandu mb-0">
                Disarankan melakukan pengunduhan berkas cadangan (JSON/CSV) setiap akhir sesi posyandu bulanan atau sebelum pembaruan sistem berkala.
              </p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-light h-100">
              <h5 class="font-sm fw-bold text-forest mb-2"><i class="bi bi-hdd-network me-1 text-primary"></i>Penyimpanan Aman Terpisah</h5>
              <p class="text-muted-posyandu mb-0">
                Simpan salinan cadangan pada media eksternal terenkripsi atau drive penyimpanan resmi milik Pemerintah Desa Sukatani.
              </p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-light h-100">
              <h5 class="font-sm fw-bold text-forest mb-2"><i class="bi bi-file-lock2 me-1 text-primary"></i>Kerahasiaan Rekam Medis</h5>
              <p class="text-muted-posyandu mb-0">
                Data NIK dan status gizi masyarakat dilindungi oleh undang-undang privasi data kependudukan dan kesehatan warga desa.
              </p>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
