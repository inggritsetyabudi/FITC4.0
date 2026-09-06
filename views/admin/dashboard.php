<?php
$pageTitle = $pageTitle ?? 'Dasbor Administrator — Posyandu Pintar';
$activePage = $activePage ?? 'admin_dashboard';
$topbarTitle = $topbarTitle ?? 'Pusat Kendali Layanan Posyandu Terpadu';
$breadcrumb = $breadcrumb ?? '<span>Dasbor Utama</span>';
$stats = $stats ?? [
  'total_balita' => 0,
  'total_bumil' => 0,
  'total_lansia' => 0,
  'total_warga' => 0,
  'total_kader' => 0,
  'total_bidan' => 0,
  'total_pos' => 0,
  'total_pengukuran' => 0,
  'total_kegiatan' => 0,
  'dusun_breakdown' => []
];
$auditLogs = $auditLogs ?? [];
$jadwalTerdekat = $jadwalTerdekat ?? [];
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
          <h1 class="h3 text-forest mb-1 fw-bold"><?= htmlspecialchars($profilDesa['nama_desa'] ?? 'Desa Sukatani') ?> — Panel Eksekutif</h1>
          <p class="text-muted-posyandu mb-0 font-sm">
            <?= htmlspecialchars($profilDesa['kecamatan'] ?? 'Kecamatan Balongbendo') ?>, <?= htmlspecialchars($profilDesa['kabupaten'] ?? 'Kabupaten Sidoarjo') ?> · <?= format_tanggal_id(date('Y-m-d')) ?>
          </p>
        </div>

        <div class="d-flex gap-2">
          <a href="<?= base_url('index.php?controller=admin&action=export_pdf') ?>" target="_blank" class="btn-posyandu-primary btn-sm">
            <i class="bi bi-file-earmark-pdf"></i> Unduh Laporan PDF
          </a>
          <a href="<?= base_url('index.php?controller=admin&action=export_csv') ?>" class="btn-posyandu-outline btn-sm">
            <i class="bi bi-file-earmark-arrow-down"></i> Ekspor CSV
          </a>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100">
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Total Balita</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1;"><?= $stats['total_balita'] ?></strong>
            <div class="font-xs text-muted-posyandu mt-1">4 Dusun Binaan</div>
          </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100" style="border-left: 4px solid #0284c7 !important;">
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Ibu Hamil</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1; color: #0284c7 !important;"><?= $stats['total_bumil'] ?></strong>
            <div class="font-xs text-muted-posyandu mt-1">Sasaran Aktif</div>
          </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100" style="border-left: 4px solid #7c3aed !important;">
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Peserta Lansia</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1; color: #7c3aed !important;"><?= $stats['total_lansia'] ?></strong>
            <div class="font-xs text-muted-posyandu mt-1">Posbindu Terpadu</div>
          </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100">
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Total Warga</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1;"><?= $stats['total_warga'] ?></strong>
            <div class="font-xs text-muted-posyandu mt-1">Master Penduduk</div>
          </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100" style="border-left: 4px solid #0d47a1 !important;">
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Kader &amp; Bidan</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1; color: #0d47a1 !important;"><?= $stats['total_kader'] + $stats['total_bidan'] ?></strong>
            <div class="font-xs text-muted-posyandu mt-1"><?= $stats['total_kader'] ?> Kader · <?= $stats['total_bidan'] ?> Bidan</div>
          </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100" style="border-left: 4px solid #d97706 !important;">
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Kunjungan/Ukur</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1; color: #d97706 !important;"><?= $stats['total_pengukuran'] ?></strong>
            <div class="font-xs text-muted-posyandu mt-1">Total Riwayat</div>
          </div>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-lg-7">
          <section class="panel-posyandu h-100 mb-0">
            <div class="panel-header d-flex justify-content-between align-items-center">
              <div>
                <h3 class="panel-title">Sebaran Sasaran per Dusun Binaan</h3>
                <p class="panel-subtitle">Rekapitulasi Balita, Ibu Hamil, dan Lansia tiap dusun</p>
              </div>
            </div>

            <div class="table-posyandu-wrap">
              <table class="table-posyandu">
                <thead>
                  <tr>
                    <th>Nama Dusun</th>
                    <th>Balita</th>
                    <th>Ibu Hamil</th>
                    <th>Lansia</th>
                    <th>Total Sasaran</th>
                    <th style="text-align: right;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($stats['dusun_breakdown'] as $dusunName => $dData): ?>
                    <tr>
                      <td class="fw-bold text-forest">
                        <i class="bi bi-geo-alt-fill text-primary me-1"></i> Dusun <?= htmlspecialchars($dusunName) ?>
                      </td>
                      <td><span class="badge bg-primary-subtle text-primary"><?= $dData['balita'] ?> Anak</span></td>
                      <td><span class="badge bg-info-subtle text-info-emphasis"><?= $dData['bumil'] ?> Orang</span></td>
                      <td><span class="badge bg-purple-subtle text-secondary"><?= $dData['lansia'] ?> Orang</span></td>
                      <td class="fw-bold text-forest"><?= $dData['total'] ?> Jiwa</td>
                      <td style="text-align: right;">
                        <a href="<?= base_url('index.php?controller=admin&action=penduduk&dusun=' . urlencode($dusunName)) ?>" class="btn-posyandu-outline btn-sm py-1 px-2 font-xs">
                          Lihat Warga &rarr;
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </section>
        </div>

        <div class="col-lg-5">
          <section class="panel-posyandu h-100 mb-0">
            <div class="panel-header d-flex justify-content-between align-items-center">
              <div>
                <h3 class="panel-title">Jadwal Agenda Terdekat</h3>
                <p class="panel-subtitle">Kegiatan posyandu mendatang di wilayah desa</p>
              </div>
              <a href="<?= base_url('index.php?controller=admin&action=jadwal') ?>" class="btn-posyandu-outline btn-sm font-xs">
                Semua &rarr;
              </a>
            </div>

            <div class="p-3 d-flex flex-column gap-2">
              <?php if (empty($jadwalTerdekat)): ?>
                <div class="text-center py-4 text-ink-500 font-sm">
                  <i class="bi bi-calendar-check fs-3 mb-2 d-inline-block text-primary"></i>
                  <div>Belum ada agenda kegiatan posyandu dalam waktu dekat.</div>
                </div>
              <?php else: ?>
                <?php foreach (array_slice($jadwalTerdekat, 0, 4) as $j): ?>
                  <div class="p-3 bg-surface-soft rounded-3 border-line">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                      <strong class="text-forest font-sm"><?= htmlspecialchars($j['judul_agenda']) ?></strong>
                      <span class="badge bg-primary text-white font-xs"><?= format_tanggal_id($j['tanggal_kegiatan'], true) ?></span>
                    </div>
                    <div class="font-xs text-muted-posyandu">
                      <i class="bi bi-geo-alt me-1"></i> Dusun <?= htmlspecialchars($j['dusun']) ?> · <?= htmlspecialchars($j['lokasi']) ?>
                    </div>
                    <div class="font-xs text-ink-700 mt-1">
                      PJ: <?= htmlspecialchars($j['penanggung_jawab']) ?> · <?= substr($j['jam_mulai'], 0, 5) ?> - <?= substr($j['jam_selesai'], 0, 5) ?> WIB
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </section>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-lg-8">
          <section class="panel-posyandu mb-0">
            <div class="panel-header d-flex justify-content-between align-items-center">
              <div>
                <h3 class="panel-title">Jejak Audit Aktivitas Sistem</h3>
                <p class="panel-subtitle">Log aktivitas petugas saat menginput atau mengubah data</p>
              </div>
              <a href="<?= base_url('index.php?controller=admin&action=audit_log') ?>" class="btn-posyandu-outline btn-sm font-xs">
                Lihat Lengkap &rarr;
              </a>
            </div>

            <div class="table-posyandu-wrap">
              <table class="table-posyandu">
                <thead>
                  <tr>
                    <th>Waktu</th>
                    <th>Nama Petugas</th>
                    <th>Peran</th>
                    <th>Aksi</th>
                    <th>Modul Target</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($auditLogs)): ?>
                    <tr>
                      <td colspan="6" class="text-center py-4 text-ink-500 font-sm">Belum ada catatan aktivitas sistem.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($auditLogs as $log): ?>
                      <tr>
                        <td class="font-xs text-muted-posyandu"><?= format_tanggal_id(substr($log['created_at'], 0, 10), true) ?> <?= substr($log['created_at'], 11, 5) ?></td>
                        <td class="font-sm fw-bold text-forest"><?= htmlspecialchars($log['user_nama']) ?></td>
                        <td><span class="role-badge-pill font-xs"><?= ucfirst($log['role']) ?></span></td>
                        <td><span class="badge bg-surface-soft border font-xs"><?= htmlspecialchars($log['action']) ?></span></td>
                        <td class="font-xs fw-semibold"><?= htmlspecialchars($log['target_modul']) ?></td>
                        <td class="font-xs text-ink-700"><?= htmlspecialchars($log['detail']) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </section>
        </div>

        <div class="col-lg-4">
          <section class="panel-posyandu mb-0">
            <div class="panel-header">
              <div>
                <h3 class="panel-title">Akses Cepat Modul Desa</h3>
                <p class="panel-subtitle">Pintasan ke modul administrasi utama</p>
              </div>
            </div>

            <div class="p-3 d-flex flex-column gap-2">
              <a href="<?= base_url('index.php?controller=admin&action=kader') ?>" class="btn btn-posyandu-outline text-start d-flex align-items-center gap-2 py-2">
                <i class="bi bi-person-plus text-primary fs-5"></i>
                <div>
                  <div class="fw-bold font-sm">Tambah Kader / Bidan Baru</div>
                  <div class="font-xs text-muted-posyandu">Pendaftaran akun petugas posyandu</div>
                </div>
              </a>

              <a href="<?= base_url('index.php?controller=admin&action=penduduk') ?>" class="btn btn-posyandu-outline text-start d-flex align-items-center gap-2 py-2">
                <i class="bi bi-person-lines-fill text-primary fs-5"></i>
                <div>
                  <div class="fw-bold font-sm">Kelola Data Warga</div>
                  <div class="font-xs text-muted-posyandu">Master NIK, KK, dan kategori sasaran</div>
                </div>
              </a>

              <a href="<?= base_url('index.php?controller=admin&action=notifikasi') ?>" class="btn btn-posyandu-outline text-start d-flex align-items-center gap-2 py-2">
                <i class="bi bi-megaphone text-primary fs-5"></i>
                <div>
                  <div class="fw-bold font-sm">Siarkan Pengumuman</div>
                  <div class="font-xs text-muted-posyandu">Kirim info jadwal ke kader/masyarakat</div>
                </div>
              </a>

              <a href="<?= base_url('index.php?controller=admin&action=backup') ?>" class="btn btn-posyandu-outline text-start d-flex align-items-center gap-2 py-2">
                <i class="bi bi-database-down text-primary fs-5"></i>
                <div>
                  <div class="fw-bold font-sm">Cadangkan Data (Backup)</div>
                  <div class="font-xs text-muted-posyandu">Unduh arsip sistem format JSON</div>
                </div>
              </a>
            </div>
          </section>
        </div>
      </div>
    </main>

    <?php require_once __DIR__ . '/../../includes/mobile_nav.php'; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
