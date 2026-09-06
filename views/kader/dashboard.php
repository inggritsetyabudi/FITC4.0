<?php
$pageTitle = $pageTitle ?? 'Dasbor Kader — Posyandu Pintar';
$activePage = $activePage ?? 'kader_dashboard';
$topbarTitle = $topbarTitle ?? 'Dasbor Pelayanan Balita';
$jumlahSudahUkur = $totalSudahUkur ?? ($totalAnak - count($daftarBelumDitimbang));
$jumlahBelumUkur = count($daftarBelumDitimbang);
$jumlahPerluPerhatian = count($daftarPerluPerhatian);

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="mb-4">
        <h2 class="h3 text-forest mb-1">
          Selamat datang, <?= htmlspecialchars($user['sapaan'] ?? $user['nama']) ?>
        </h2>
        <p class="text-muted-posyandu mb-0">
          <?= ($user['dusun_binaan']) ? 'Dusun ' . htmlspecialchars($user['dusun_binaan']) . ' · ' : '' ?>
          <?= format_tanggal_id(date('Y-m-d')) ?>
        </p>
      </div>

      <div class="panel-work-prompt">
        <div class="d-flex align-items-center gap-2 mb-2 text-green-100 font-sm">
          <i class="bi bi-calendar2-check"></i> Jadwal Penimbangan <?= $bulanNama ?>
        </div>
        <h2 id="taskBannerTitle">
          <?= $jumlahBelumUkur ?> balita belum ditimbang bulan ini.
        </h2>
        <p id="taskBannerSubtitle">
          <?= $jumlahSudahUkur ?> dari <?= $totalAnak ?> balita binaan sudah memiliki catatan penimbangan bulan ini.
        </p>
        <div class="prompt-actions">
          <a href="<?= base_url('index.php?controller=pengukuran&action=index') ?>" class="btn-posyandu-primary">
            <i class="bi bi-clipboard-plus"></i> Mulai Pengukuran
          </a>
          <a href="<?= base_url('index.php?controller=balita&action=index&tambah=1') ?>" class="btn-posyandu-secondary">
            <i class="bi bi-person-plus"></i> Tambah Balita
          </a>
        </div>
      </div>

      <div class="metrics-strip">
        <div class="metric-item">
          <div class="metric-label">Total Anak Binaan</div>
          <div class="metric-value"><?= $totalAnak ?></div>
          <div class="metric-sub">Balita terdaftar aktif</div>
        </div>
        <div class="metric-item">
          <div class="metric-label">Sudah Ditimbang</div>
          <div class="metric-value text-green-brand"><?= $jumlahSudahUkur ?></div>
          <div class="metric-sub">Bulan <?= $bulanNama ?></div>
        </div>
        <div class="metric-item">
          <div class="metric-label">Perlu Diperhatikan</div>
          <div class="metric-value text-warning"><?= $jumlahPerluPerhatian ?></div>
          <div class="metric-sub">Perlu pemantauan gizi</div>
        </div>
      </div>

      <section class="panel-posyandu">
        <div class="panel-header">
          <div>
            <h3 class="panel-title">Belum Ditimbang Bulan Ini</h3>
            <p class="panel-subtitle">Balita yang belum memiliki catatan pengukuran pada bulan berjalan</p>
          </div>
          <a href="<?= base_url('index.php?controller=balita&action=index') ?>" class="btn-posyandu-outline btn-sm">
            Lihat Semua Balita
          </a>
        </div>

        <div class="daftar-balita">
          <?php if (empty($daftarBelumDitimbang)): ?>
            <div class="empty-state py-4 text-center">
              <i class="bi bi-check-circle-fill text-success fs-3 mb-2 d-inline-block"></i>
              <h5 class="h6 text-forest mb-1">Semua balita sudah ditimbang</h5>
              <p class="font-sm text-ink-500 mb-0">Seluruh anak binaan telah memiliki catatan penimbangan bulan ini.</p>
            </div>
          <?php else: ?>
            <?php foreach ($daftarBelumDitimbang as $item): 
              $anak = $item['anak'];
              $last = $item['terakhir'];
              $umur = hitung_umur($anak['tanggal_lahir']);
              $tglTerakhir = $last ? format_tanggal_id($last['tanggal_ukur'], true) : 'Belum pernah diukur';
            ?>
              <div class="item-balita">
                <div class="item-balita-main">
                  <div class="avatar-circle"><?= strtoupper(substr($anak['nama'], 0, 1)) ?></div>
                  <div class="item-balita-info">
                    <span class="item-balita-name"><?= htmlspecialchars($anak['nama']) ?></span>
                    <span class="item-balita-meta">
                      <span><?= $umur['teks'] ?></span>
                      <span>·</span>
                      <span>Dusun <?= htmlspecialchars($anak['dusun']) ?></span>
                      <span>·</span>
                      <span>Orang Tua: <?= htmlspecialchars($anak['nama_ortu']) ?></span>
                    </span>
                    <span class="text-ink-500 font-sm">
                      Terakhir ditimbang: <strong><?= $tglTerakhir ?></strong>
                      <?php if ($last): ?>
                        (<?= $last['berat_kg'] ?> kg · <?= $last['tinggi_cm'] ?> cm)
                      <?php endif; ?>
                    </span>
                  </div>
                </div>
                <div class="item-balita-actions">
                  <a href="<?= base_url('index.php?controller=pengukuran&action=index&anak_id=' . $anak['id']) ?>" class="btn-posyandu-primary btn-sm">
                    <i class="bi bi-clipboard-plus"></i> Catat
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>

      <section class="panel-posyandu">
        <div class="panel-header">
          <div>
            <h3 class="panel-title">Perlu Diperhatikan</h3>
            <p class="panel-subtitle">Balita dengan catatan pemantauan atau perlambatan kenaikan berat badan</p>
          </div>
        </div>

        <div class="daftar-balita">
          <?php if (empty($daftarPerluPerhatian)): ?>
            <div class="empty-state py-4 text-center">
              <i class="bi bi-shield-check text-green-brand fs-3 mb-2 d-inline-block"></i>
              <h5 class="h6 text-forest mb-1">Kondisi Tumbuh Kembang Baik</h5>
              <p class="font-sm text-ink-500 mb-0">Tidak ada balita yang membutuhkan pemantauan khusus saat ini.</p>
            </div>
          <?php else: ?>
            <?php foreach ($daftarPerluPerhatian as $item): 
              $anak = $item['anak'];
              $last = $item['pengukuran'] ?? $item['terakhir'];
              $umur = hitung_umur($anak['tanggal_lahir']);
            ?>
              <div class="item-balita">
                <div class="item-balita-main">
                  <div class="avatar-circle" style="background-color: var(--warning-soft); color: var(--warning);">
                    <?= strtoupper(substr($anak['nama'], 0, 1)) ?>
                  </div>
                  <div class="item-balita-info">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                      <span class="item-balita-name mb-0"><?= htmlspecialchars($anak['nama']) ?></span>
                      <?= render_status_badge($last['kategori_status']) ?>
                    </div>
                    <span class="item-balita-meta">
                      <span><?= $umur['teks'] ?></span>
                      <span>·</span>
                      <span>Dusun <?= htmlspecialchars($anak['dusun']) ?></span>
                    </span>
                    <span class="text-ink-700 font-sm">
                      Catatan: <em><?= htmlspecialchars($last['catatan'] ?: 'Perlu pemantauan gizi lanjutan') ?></em>
                    </span>
                  </div>
                </div>
                <div class="item-balita-actions">
                  <a href="<?= base_url('index.php?controller=balita&action=detail&id=' . $anak['id']) ?>" class="btn-posyandu-secondary btn-sm">
                    <i class="bi bi-graph-up"></i> Lihat Riwayat
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>
    </main>

    <?php require_once __DIR__ . '/../../includes/mobile_nav.php'; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
