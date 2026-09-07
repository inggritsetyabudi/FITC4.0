<?php
$pageTitle = $pageTitle ?? 'Dasbor Pemerintah Desa — Posyandu Pintar';
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
$jadwalTerdekat = $jadwalTerdekat ?? [];
$profilDesa = $profilDesa ?? [];
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
              <span class="badge bg-primary px-3 py-1 font-xs text-uppercase fw-semibold">Pemerintah Desa</span>
              <span class="text-muted-posyandu font-sm"><?= format_tanggal_id(date('Y-m-d')) ?></span>
            </div>
            <h1 class="h3 fw-bold text-forest mb-1">Selamat Datang, <?= htmlspecialchars($currentUser['sapaan'] ?? $currentUser['nama'] ?? 'Admin Desa') ?></h1>
            <p class="text-muted-posyandu mb-0 font-sm">
              Sistem Layanan Terpadu Posyandu <?= htmlspecialchars($profilDesa['nama_desa'] ?? 'Desa Sukatani') ?> — Siap melayani warga di 4 dusun.
            </p>
          </div>

          <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('index.php?controller=admin&action=export_pdf') ?>" target="_blank" class="btn-posyandu-primary">
              <i class="bi bi-printer me-1"></i> Cetak Laporan PDF
            </a>
            <a href="<?= base_url('index.php?controller=admin&action=jadwal') ?>" class="btn-posyandu-outline">
              <i class="bi bi-calendar-plus me-1"></i> Buat Agenda
            </a>
          </div>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
          <div class="panel-posyandu p-4 h-100 d-flex align-items-center gap-3">
            <div style="width: 56px; height: 56px; border-radius: 14px; background: #E3F2FD; color: #1565C0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <i class="bi bi-balloon-heart-fill fs-3"></i>
            </div>
            <div>
              <div class="text-muted-posyandu font-sm fw-medium">Balita Terdaftar</div>
              <div class="h2 fw-bold text-forest mb-0"><?= $stats['total_balita'] ?></div>
              <div class="font-xs text-muted-posyandu mt-1">Sasaran Gizi Balita</div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-xl-3">
          <div class="panel-posyandu p-4 h-100 d-flex align-items-center gap-3">
            <div style="width: 56px; height: 56px; border-radius: 14px; background: #E0F2FE; color: #0284C7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <i class="bi bi-heart-pulse-fill fs-3"></i>
            </div>
            <div>
              <div class="text-muted-posyandu font-sm fw-medium">Ibu Hamil</div>
              <div class="h2 fw-bold text-forest mb-0" style="color: #0284C7 !important;"><?= $stats['total_bumil'] ?></div>
              <div class="font-xs text-muted-posyandu mt-1">Pemantauan Kehamilan</div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-xl-3">
          <div class="panel-posyandu p-4 h-100 d-flex align-items-center gap-3">
            <div style="width: 56px; height: 56px; border-radius: 14px; background: #EDE7F6; color: #5E35B1; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <i class="bi bi-people-fill fs-3"></i>
            </div>
            <div>
              <div class="text-muted-posyandu font-sm fw-medium">Peserta Lansia</div>
              <div class="h2 fw-bold text-forest mb-0" style="color: #5E35B1 !important;"><?= $stats['total_lansia'] ?></div>
              <div class="font-xs text-muted-posyandu mt-1">Posbindu Sehat</div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-xl-3">
          <div class="panel-posyandu p-4 h-100 d-flex align-items-center gap-3">
            <div style="width: 56px; height: 56px; border-radius: 14px; background: #DCFCE7; color: #15803D; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <i class="bi bi-hospital fs-3"></i>
            </div>
            <div>
              <div class="text-muted-posyandu font-sm fw-medium">Petugas Posyandu</div>
              <div class="h2 fw-bold text-forest mb-0" style="color: #15803D !important;"><?= $stats['total_kader'] + $stats['total_bidan'] ?></div>
              <div class="font-xs text-muted-posyandu mt-1"><?= $stats['total_kader'] ?> Kader &amp; <?= $stats['total_bidan'] ?> Bidan</div>
            </div>
          </div>
        </div>
      </div>

      <div class="mb-4">
        <h2 class="h5 fw-bold text-forest mb-3">Pintasan Layanan Utama</h2>
        <div class="row g-3">
          <div class="col-md-3 col-sm-6">
            <a href="<?= base_url('index.php?controller=admin&action=penduduk') ?>" class="panel-posyandu p-3 text-decoration-none d-flex align-items-center gap-3 transition" style="background:#FFFFFF;">
              <div style="width:44px; height:44px; border-radius:10px; background:#EFF6FF; color:#2563EB; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="bi bi-person-lines-fill fs-4"></i>
              </div>
              <div>
                <strong class="text-forest d-block font-sm">Data Penduduk</strong>
                <span class="text-muted-posyandu font-xs">Master warga &amp; NIK</span>
              </div>
            </a>
          </div>

          <div class="col-md-3 col-sm-6">
            <a href="<?= base_url('index.php?controller=admin&action=kader') ?>" class="panel-posyandu p-3 text-decoration-none d-flex align-items-center gap-3 transition" style="background:#FFFFFF;">
              <div style="width:44px; height:44px; border-radius:10px; background:#F0FDF4; color:#16A34A; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="bi bi-person-plus-fill fs-4"></i>
              </div>
              <div>
                <strong class="text-forest d-block font-sm">Kelola Petugas</strong>
                <span class="text-muted-posyandu font-xs">Tambah kader &amp; bidan</span>
              </div>
            </a>
          </div>

          <div class="col-md-3 col-sm-6">
            <a href="<?= base_url('index.php?controller=admin&action=jadwal') ?>" class="panel-posyandu p-3 text-decoration-none d-flex align-items-center gap-3 transition" style="background:#FFFFFF;">
              <div style="width:44px; height:44px; border-radius:10px; background:#FEF3C7; color:#D97706; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="bi bi-calendar2-check-fill fs-4"></i>
              </div>
              <div>
                <strong class="text-forest d-block font-sm">Jadwal Posyandu</strong>
                <span class="text-muted-posyandu font-xs">Agenda kegiatan dusun</span>
              </div>
            </a>
          </div>

          <div class="col-md-3 col-sm-6">
            <a href="<?= base_url('index.php?controller=admin&action=laporan') ?>" class="panel-posyandu p-3 text-decoration-none d-flex align-items-center gap-3 transition" style="background:#FFFFFF;">
              <div style="width:44px; height:44px; border-radius:10px; background:#F3E8FF; color:#9333EA; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="bi bi-file-earmark-bar-graph-fill fs-4"></i>
              </div>
              <div>
                <strong class="text-forest d-block font-sm">Laporan &amp; Arsip</strong>
                <span class="text-muted-posyandu font-xs">Unduh rekap bulanan</span>
              </div>
            </a>
          </div>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-lg-7">
          <section class="panel-posyandu h-100 mb-0">
            <div class="panel-header p-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
              <div>
                <h3 class="h6 fw-bold text-forest mb-0">Sebaran Sasaran Warga per Dusun</h3>
                <p class="font-xs text-muted-posyandu mb-0">Ringkasan balita, ibu hamil, dan lansia di tiap wilayah binaan</p>
              </div>
              <a href="<?= base_url('index.php?controller=admin&action=penduduk') ?>" class="btn-posyandu-outline btn-sm font-xs">
                Data Lengkap &rarr;
              </a>
            </div>

            <div class="table-posyandu-wrap">
              <table class="table-posyandu">
                <thead>
                  <tr>
                    <th>Wilayah Dusun</th>
                    <th>Balita</th>
                    <th>Ibu Hamil</th>
                    <th>Lansia</th>
                    <th>Total Sasaran</th>
                    <th style="text-align: right;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($stats['dusun_breakdown'])): ?>
                    <tr>
                      <td colspan="6" class="text-center py-4 text-muted">Belum ada data sasaran per dusun.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($stats['dusun_breakdown'] as $dusunName => $dData): ?>
                      <tr>
                        <td class="fw-bold text-forest">
                          <i class="bi bi-geo-alt-fill text-primary me-1"></i> Dusun <?= htmlspecialchars($dusunName) ?>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary font-xs"><?= $dData['balita'] ?> Balita</span></td>
                        <td><span class="badge bg-info-subtle text-info-emphasis font-xs"><?= $dData['bumil'] ?> Bumil</span></td>
                        <td><span class="badge bg-purple-subtle text-secondary font-xs"><?= $dData['lansia'] ?> Lansia</span></td>
                        <td class="fw-bold text-forest font-sm"><?= $dData['total'] ?> Jiwa</td>
                        <td style="text-align: right;">
                          <a href="<?= base_url('index.php?controller=admin&action=penduduk&dusun=' . urlencode($dusunName)) ?>" class="btn btn-sm btn-outline-primary py-1 px-2 font-xs">
                            Buka Dusun &rarr;
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </section>
        </div>

        <div class="col-lg-5">
          <section class="panel-posyandu h-100 mb-0">
            <div class="panel-header p-3 border-bottom d-flex justify-content-between align-items-center">
              <div>
                <h3 class="h6 fw-bold text-forest mb-0">Jadwal Posyandu Terdekat</h3>
                <p class="font-xs text-muted-posyandu mb-0">Agenda kegiatan pemeriksaan di balai dusun</p>
              </div>
              <a href="<?= base_url('index.php?controller=admin&action=jadwal') ?>" class="btn-posyandu-outline btn-sm font-xs">
                Semua Jadwal &rarr;
              </a>
            </div>

            <div class="p-3 d-flex flex-column gap-3">
              <?php if (empty($jadwalTerdekat)): ?>
                <div class="text-center py-4 text-muted font-sm">
                  <i class="bi bi-calendar2-check fs-2 mb-2 d-inline-block text-primary"></i>
                  <div>Belum ada jadwal kegiatan baru yang direncanakan.</div>
                  <a href="<?= base_url('index.php?controller=admin&action=jadwal') ?>" class="btn btn-sm btn-primary mt-2">
                    <i class="bi bi-plus-lg me-1"></i> Buat Jadwal
                  </a>
                </div>
              <?php else: ?>
                <?php foreach (array_slice($jadwalTerdekat, 0, 4) as $j): ?>
                  <div class="p-3 rounded-3 border bg-light">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                      <strong class="text-forest font-sm"><?= htmlspecialchars($j['judul_agenda']) ?></strong>
                      <span class="badge bg-primary text-white font-xs"><?= format_tanggal_id($j['tanggal_kegiatan'], true) ?></span>
                    </div>
                    <div class="font-xs text-muted-posyandu mb-1">
                      <i class="bi bi-geo-alt me-1 text-primary"></i> Dusun <?= htmlspecialchars($j['dusun']) ?> · <?= htmlspecialchars($j['lokasi']) ?>
                    </div>
                    <div class="font-xs text-dark">
                      <i class="bi bi-clock me-1 text-primary"></i> Pukul <?= substr($j['jam_mulai'], 0, 5) ?> - <?= substr($j['jam_selesai'], 0, 5) ?> WIB
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </section>
        </div>
      </div>
    </main>

    <?php require_once __DIR__ . '/../../includes/mobile_nav.php'; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
