<?php
$pageTitle = $pageTitle ?? ('Rekam KMS: ' . htmlspecialchars($anak['nama']) . ' — Posyandu Pintar');
$activePage = $activePage ?? 'balita';
$topbarTitle = $topbarTitle ?? 'Buku KMS Digital Balita';
$breadcrumb = $breadcrumb ?? ('<a href="' . base_url('index.php?controller=balita&action=index') . '" class="text-decoration-none text-forest">Data Balita</a> <i class="bi bi-chevron-right fs-xs"></i> <span>' . htmlspecialchars($anak['nama']) . '</span>');

$riwayatAsc = $riwayatUkur ?? [];
$riwayatDesc = array_reverse($riwayatAsc);

$lastPengukuran = end($riwayatAsc) ?: null;
$prevPengukuran = count($riwayatAsc) > 1 ? $riwayatAsc[count($riwayatAsc) - 2] : null;

$umur = hitung_umur($anak['tanggal_lahir']);
$statusTerakhir = $lastPengukuran['kategori_status'] ?? 'normal';

$deltaBbStr = '+0.0 kg';
if ($lastPengukuran && $prevPengukuran) {
    $delta = (float)$lastPengukuran['berat_kg'] - (float)$prevPengukuran['berat_kg'];
    $deltaBbStr = ($delta >= 0 ? '+' : '') . number_format($delta, 1, ',', '.') . ' kg';
}

$chartLabels = [];
$chartBb = [];
$chartTb = [];

foreach ($riwayatAsc as $r) {
    $chartLabels[] = format_tanggal_id($r['tanggal_ukur'], true);
    $chartBb[] = (float)$r['berat_kg'];
    $chartTb[] = (float)$r['tinggi_cm'];
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <section class="child-profile-hero mb-4">
        <div class="child-profile-main">
          <div class="child-identity">
            <div class="child-avatar-large" style="background-color: var(--blue-900, #0d47a1); color: #fff;">
              <?= strtoupper(substr($anak['nama'], 0, 1)) ?>
            </div>
            <div>
              <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                <h1 class="h3 text-forest mb-0 fw-bold"><?= htmlspecialchars($anak['nama']) ?></h1>
                <div>
                  <?= render_status_badge($statusTerakhir) ?>
                </div>
              </div>
              <div class="text-ink-700 font-sm">
                <?= ($anak['jenis_kelamin'] === 'L') ? 'Laki-laki' : 'Perempuan' ?> · <?= $umur['teks'] ?> · Dusun <?= htmlspecialchars($anak['dusun']) ?>
              </div>
              <div class="font-sm text-ink-500 mt-1">
                NIK: <strong><?= htmlspecialchars($anak['nik'] ?: '-') ?></strong> · Orang Tua: <strong><?= htmlspecialchars($anak['nama_ortu']) ?></strong>
              </div>
            </div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('index.php?controller=pengukuran&action=index&anak_id=' . $anak['id']) ?>" class="btn-posyandu-primary btn-sm">
              <i class="bi bi-clipboard2-plus"></i> Catat Pengukuran Baru
            </a>
          </div>
        </div>

        <div class="child-meta-grid">
          <div class="child-meta-cell">
            <span class="label">Berat Terakhir</span>
            <span class="val"><?= $lastPengukuran ? number_format((float)$lastPengukuran['berat_kg'], 1, ',', '.') . ' kg' : '-' ?></span>
          </div>
          <div class="child-meta-cell">
            <span class="label">Tinggi Terakhir</span>
            <span class="val"><?= $lastPengukuran ? number_format((float)$lastPengukuran['tinggi_cm'], 1, ',', '.') . ' cm' : '-' ?></span>
          </div>
          <div class="child-meta-cell">
            <span class="label">Perubahan BB</span>
            <span class="val text-primary"><?= $deltaBbStr ?></span>
          </div>
          <div class="child-meta-cell">
            <span class="label">Tanggal Pengukuran</span>
            <span class="val" style="font-size: 0.9375rem;"><?= $lastPengukuran ? format_tanggal_id($lastPengukuran['tanggal_ukur']) : 'Belum pernah diukur' ?></span>
          </div>
        </div>
      </section>

      <section class="panel-posyandu mb-4">
        <div class="panel-header d-flex align-items-center justify-content-between flex-wrap gap-2">
          <div>
            <h3 class="panel-title">Grafik Tren Pertumbuhan</h3>
            <p class="panel-subtitle">Perkembangan berat dan tinggi badan balita dari kunjungan ke kunjungan</p>
          </div>
          <div class="chart-toggle-group btn-group" role="group">
            <button type="button" id="btnToggleChartBb" class="btn btn-sm btn-posyandu-outline active" onclick="switchChartMode('bb')">
              Berat Badan (kg)
            </button>
            <button type="button" id="btnToggleChartTb" class="btn btn-sm btn-posyandu-outline" onclick="switchChartMode('tb')">
              Tinggi Badan (cm)
            </button>
          </div>
        </div>

        <div style="position: relative; min-height: 280px; height: 300px; width: 100%;">
          <?php if (empty($chartLabels)): ?>
            <div class="text-center py-5 text-ink-500 font-sm">
              <i class="bi bi-graph-up fs-2 mb-2 d-inline-block"></i>
              <div>Belum ada riwayat pengukuran untuk menampilkan grafik pertumbuhan.</div>
            </div>
          <?php else: ?>
            <canvas id="chartPertumbuhan"></canvas>
          <?php endif; ?>
        </div>
      </section>

      <section class="panel-posyandu">
        <div class="panel-header">
          <div>
            <h3 class="panel-title">Riwayat Pengukuran Lengkap</h3>
            <p class="panel-subtitle">Catatan penimbangan berat dan pengukuran tinggi badan bulanan</p>
          </div>
        </div>

        <div class="table-posyandu-wrap">
          <table class="table-posyandu">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Usia</th>
                <th>Berat Badan</th>
                <th>Tinggi Badan</th>
                <th>Status Skrining</th>
                <th>Catatan</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($riwayatDesc)): ?>
                <tr>
                  <td colspan="6" class="text-center py-4 text-ink-500">
                    Belum ada riwayat pengukuran balita.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($riwayatDesc as $r): ?>
                  <tr>
                    <td class="font-sm"><?= format_tanggal_id($r['tanggal_ukur'], true) ?></td>
                    <td class="font-sm"><?= $r['usia_bulan'] ?> bulan</td>
                    <td class="font-sm fw-bold text-forest"><?= $r['berat_kg'] ?> kg</td>
                    <td class="font-sm fw-bold text-forest"><?= $r['tinggi_cm'] ?> cm</td>
                    <td><?= render_status_badge($r['kategori_status']) ?></td>
                    <td class="font-xs text-ink-500"><?= htmlspecialchars($r['catatan'] ?: '-') ?></td>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

<script>
  let chartInstance = null;
  const labels = <?= json_encode($chartLabels) ?>;
  const dataBb = <?= json_encode($chartBb) ?>;
  const dataTb = <?= json_encode($chartTb) ?>;

  function initChart(mode = 'bb') {
    const canvas = document.getElementById('chartPertumbuhan');
    if (!canvas || labels.length === 0) return;

    if (chartInstance) {
      chartInstance.destroy();
    }

    const isBb = (mode === 'bb');
    const datasetLabel = isBb ? 'Berat Badan (kg)' : 'Tinggi Badan (cm)';
    const dataValues = isBb ? dataBb : dataTb;
    const strokeColor = isBb ? '#0D47A1' : '#1565C0';
    const bgColor = isBb ? 'rgba(13, 71, 161, 0.12)' : 'rgba(21, 101, 192, 0.12)';

    chartInstance = new Chart(canvas.getContext('2d'), {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: datasetLabel,
          data: dataValues,
          borderColor: strokeColor,
          backgroundColor: bgColor,
          fill: true,
          tension: 0.3,
          pointRadius: 6,
          pointBackgroundColor: strokeColor,
          pointBorderColor: '#fff',
          pointBorderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'top', labels: { boxWidth: 12, font: { family: 'Poppins' } } }
        },
        scales: {
          x: { grid: { display: false } },
          y: {
            beginAtZero: false,
            ticks: {
              callback: function(val) {
                return val + (isBb ? ' kg' : ' cm');
              }
            }
          }
        }
      }
    });
  }

  function switchChartMode(mode) {
    const btnBb = document.getElementById('btnToggleChartBb');
    const btnTb = document.getElementById('btnToggleChartTb');

    if (mode === 'bb') {
      btnBb.classList.add('active');
      btnTb.classList.remove('active');
    } else {
      btnTb.classList.add('active');
      btnBb.classList.remove('active');
    }

    initChart(mode);
  }

  document.addEventListener('DOMContentLoaded', () => {
    initChart('bb');
  });
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
