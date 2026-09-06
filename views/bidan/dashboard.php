<?php
$pageTitle = $pageTitle ?? 'Dasbor Bidan Desa — Posyandu Pintar';
$activePage = $activePage ?? 'bidan_dashboard';
$topbarTitle = $topbarTitle ?? 'Pemantauan & Evaluasi Gizi Balita Desa';
$breadcrumb = $breadcrumb ?? '<span>Dasbor Bidan</span>';

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
          <h2 class="h3 text-forest mb-1">Panel Evaluasi Bidan Desa</h2>
          <p class="text-muted-posyandu mb-0">
            Wilayah Kerja Desa Sukatani · <?= format_tanggal_id(date('Y-m-d')) ?>
          </p>
        </div>

        <div class="d-flex gap-2">
          <a href="<?= base_url('index.php?controller=bidan&action=rekap') ?>" class="btn-posyandu-outline btn-sm">
            <i class="bi bi-journal-text"></i> Rekap Pengukuran
          </a>
          <a href="<?= base_url('index.php?controller=bidan&action=prioritas') ?>" class="btn-posyandu-primary btn-sm">
            <i class="bi bi-exclamation-triangle"></i> Semua Prioritas (<?= count($balitaPrioritas) ?>)
          </a>
        </div>
      </div>

      <section class="panel-posyandu border-start border-4 border-warning mb-4 shadow-sm">
        <div class="panel-header d-flex align-items-center justify-content-between">
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="badge bg-warning-soft text-warning-text border-warning-border font-xs fw-bold">
                <i class="bi bi-pin-angle-fill"></i> Tinjauan Utama
              </span>
            </div>
            <h3 class="panel-title h5 text-forest mb-0">Daftar Balita Perlu Ditinjau Bidan</h3>
            <p class="panel-subtitle mb-0">
              Balita dengan perlambatan kenaikan berat badan atau status prioritas dari kunjungan penimbangan terakhir.
            </p>
          </div>
          <a href="<?= base_url('index.php?controller=bidan&action=prioritas') ?>" class="btn-posyandu-outline btn-sm font-xs">
            Buka Halaman Lengkap &rarr;
          </a>
        </div>

        <div class="daftar-balita">
          <?php if (empty($balitaPrioritas)): ?>
            <div class="empty-state py-4 text-center">
              <i class="bi bi-check-circle-fill text-success fs-3 mb-2 d-inline-block"></i>
              <h5 class="h6 text-forest mb-1">Tidak Ada Balita Prioritas</h5>
              <p class="font-sm text-ink-500 mb-0">Seluruh balita desa dalam status pertumbuhan normal.</p>
            </div>
          <?php else: ?>
            <?php foreach (array_slice($balitaPrioritas, 0, 4) as $b): 
              $umur = hitung_umur($b['tanggal_lahir']);
              $isPrioritas = in_array($b['kategori_status'], ['prioritas', 'berisiko']);
            ?>
              <div class="item-balita">
                <div class="item-balita-main">
                  <div class="avatar-circle" style="background-color: <?= $isPrioritas ? 'var(--danger-soft)' : 'var(--warning-soft)' ?>; color: <?= $isPrioritas ? 'var(--danger)' : 'var(--warning)' ?>;">
                    <?= strtoupper(substr($b['nama'], 0, 1)) ?>
                  </div>
                  <div class="item-balita-info">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                      <span class="item-balita-name mb-0"><?= htmlspecialchars($b['nama']) ?></span>
                      <?= render_status_badge($b['kategori_status']) ?>
                      <span class="font-xs text-muted-posyandu">· Terakhir ukur: <?= format_tanggal_id($b['tanggal_ukur'], true) ?></span>
                    </div>
                    <span class="item-balita-meta">
                      <span><?= $umur['teks'] ?></span>
                      <span>·</span>
                      <span>Dusun <?= htmlspecialchars($b['dusun']) ?></span>
                      <span>·</span>
                      <span>BB: <strong><?= $b['berat_kg'] ?> kg</strong>, TB: <strong><?= $b['tinggi_cm'] ?> cm</strong></span>
                    </span>
                    <span class="text-ink-700 font-sm">
                      <i class="bi bi-info-circle me-1 text-ink-500"></i>
                      <em><?= htmlspecialchars($b['catatan'] ?: 'Perlu evaluasi pola makan dan asupan gizi') ?></em>
                    </span>
                  </div>
                </div>
                <div class="item-balita-actions">
                  <a href="<?= base_url('index.php?controller=balita&action=detail&id=' . $b['id']) ?>" class="btn-posyandu-primary btn-sm">
                    <i class="bi bi-graph-up"></i> Tinjau Riwayat
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>

      <div class="metrics-strip mb-4">
        <div class="metric-item">
          <div class="metric-label">Total Balita Binaan</div>
          <div class="metric-value"><?= $totalBalita ?></div>
          <div class="metric-sub">Tersebar di 4 dusun</div>
        </div>
        <div class="metric-item">
          <div class="metric-label">Status Normal</div>
          <div class="metric-value text-green-brand"><?= (int)$statGizi['normal'] ?></div>
          <div class="metric-sub">Pertumbuhan optimal</div>
        </div>
        <div class="metric-item">
          <div class="metric-label">Perlu Pemantauan</div>
          <div class="metric-value text-warning"><?= (int)$statGizi['pemantauan'] ?></div>
          <div class="metric-sub">BB stagnan / fluktuatif</div>
        </div>
        <div class="metric-item">
          <div class="metric-label">Prioritas Intervensi</div>
          <div class="metric-value text-danger"><?= (int)$statGizi['prioritas'] ?></div>
          <div class="metric-sub">Perlu konseling bidan</div>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-lg-6">
          <section class="panel-posyandu h-100 mb-0">
            <div class="panel-header">
              <div>
                <h3 class="panel-title">Sebaran Status Gizi per Dusun</h3>
                <p class="panel-subtitle">Kondisi balita berdasarkan hasil penimbangan terakhir</p>
              </div>
            </div>
            <div style="position: relative; height: 260px;">
              <canvas id="chartStatusDusun"></canvas>
            </div>
          </section>
        </div>

        <div class="col-lg-6">
          <section class="panel-posyandu h-100 mb-0">
            <div class="panel-header">
              <div>
                <h3 class="panel-title">Tren Cakupan Penimbangan Desa</h3>
                <p class="panel-subtitle">Kehadiran balita di posyandu 6 bulan terakhir</p>
              </div>
            </div>
            <div style="position: relative; height: 260px;">
              <canvas id="chartCakupan6Bulan"></canvas>
            </div>
          </section>
        </div>
      </div>

    </main>

    <?php require_once __DIR__ . '/../../includes/mobile_nav.php'; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const dusunLabels = <?= json_encode(array_keys($dusunStats)) ?>;
    const dataNormal = <?= json_encode(array_column($dusunStats, 'normal')) ?>;
    const dataPemantauan = <?= json_encode(array_column($dusunStats, 'pemantauan')) ?>;
    const dataPrioritas = <?= json_encode(array_column($dusunStats, 'prioritas')) ?>;

    const ctxDusun = document.getElementById('chartStatusDusun')?.getContext('2d');
    if (ctxDusun) {
      new Chart(ctxDusun, {
        type: 'bar',
        data: {
          labels: dusunLabels,
          datasets: [
            {
              label: 'Normal',
              data: dataNormal,
              backgroundColor: '#0D47A1',
              borderRadius: 6
            },
            {
              label: 'Pemantauan',
              data: dataPemantauan,
              backgroundColor: '#D97706',
              borderRadius: 6
            },
            {
              label: 'Prioritas Bidan',
              data: dataPrioritas,
              backgroundColor: '#DC2626',
              borderRadius: 6
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, font: { family: 'Poppins', size: 11 } } }
          },
          scales: {
            x: { stacked: false, grid: { display: false } },
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
          }
        }
      });
    }

    const bulanLabels = <?= json_encode($bulanLabels) ?>;
    const cakupanData = <?= json_encode($cakupanData) ?>;

    const ctxCakupan = document.getElementById('chartCakupan6Bulan')?.getContext('2d');
    if (ctxCakupan) {
      new Chart(ctxCakupan, {
        type: 'line',
        data: {
          labels: bulanLabels,
          datasets: [{
            label: 'Balita Hadir Ditimbang',
            data: cakupanData,
            borderColor: '#1565C0',
            backgroundColor: 'rgba(21, 101, 192, 0.12)',
            fill: true,
            tension: 0.35,
            pointRadius: 5,
            pointBackgroundColor: '#0D47A1',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, max: 18, ticks: { stepSize: 2 } }
          }
        }
      });
    }
  });
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
