<?php
$pageTitle = $pageTitle ?? 'Rekapitulasi Layanan Posyandu — Posyandu Pintar';
$activePage = $activePage ?? 'rekap';
$topbarTitle = $topbarTitle ?? 'Rekap Timbang & Pertumbuhan Balita';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=bidan&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Rekap Data</span>';
$rekapRows = $dataRekap ?? [];

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
          <h2 class="h3 text-forest mb-1">Rekapitulasi Pengukuran Desa</h2>
          <p class="text-muted-posyandu mb-0">Total <?= count($rekapRows) ?> riwayat penimbangan tercatat</p>
        </div>

        <div class="d-flex gap-2">
          <a href="<?= base_url('index.php?controller=bidan&action=export_pdf&bulan=' . urlencode($bulan ?? '2026-08') . '&dusun=' . urlencode($filterDusun ?? '')) ?>" target="_blank" class="btn-posyandu-primary">
            <i class="bi bi-file-earmark-pdf"></i> Ekspor PDF
          </a>
          <a href="<?= base_url('index.php?controller=bidan&action=export&bulan=' . urlencode($bulan ?? '2026-08') . '&dusun=' . urlencode($filterDusun ?? '')) ?>" class="btn-posyandu-outline">
            <i class="bi bi-file-earmark-arrow-down"></i> Ekspor CSV
          </a>
        </div>
      </div>

      <div class="panel-posyandu mb-4 p-3">
        <form method="GET" action="<?= base_url('index.php') ?>" class="row g-2 align-items-center">
          <input type="hidden" name="controller" value="bidan">
          <input type="hidden" name="action" value="rekap">
          <div class="col-md-5">
            <select name="dusun" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="semua">Semua Dusun</option>
              <?php foreach ($daftarDusun as $d): ?>
                <option value="<?= htmlspecialchars($d) ?>" <?= (($filterDusun ?? '') === $d) ? 'selected' : '' ?>>
                  Dusun <?= htmlspecialchars($d) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-4">
            <select name="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="2026-08" <?= (($bulan ?? '2026-08') === '2026-08') ? 'selected' : '' ?>>Agustus 2026</option>
              <option value="2026-07" <?= (($bulan ?? '') === '2026-07') ? 'selected' : '' ?>>Juli 2026</option>
              <option value="2026-06" <?= (($bulan ?? '') === '2026-06') ? 'selected' : '' ?>>Juni 2026</option>
            </select>
          </div>

          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-posyandu-primary flex-fill">
              Filter
            </button>
            <?php if (($filterDusun ?? '') !== '' && ($filterDusun ?? '') !== 'semua'): ?>
              <a href="<?= base_url('index.php?controller=bidan&action=rekap') ?>" class="btn btn-sm btn-posyandu-outline">
                Reset
              </a>
            <?php endif; ?>
          </div>
        </form>
      </div>

      <section class="panel-posyandu">
        <div class="table-posyandu-wrap">
          <table class="table-posyandu">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Nama Balita</th>
                <th>Dusun</th>
                <th>Usia</th>
                <th>Berat (kg)</th>
                <th>Tinggi (cm)</th>
                <th>Status Skrining</th>
                <th>Catatan</th>
                <th style="text-align: right;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($rekapRows)): ?>
                <tr>
                  <td colspan="9" class="text-center py-4 text-ink-500">
                    Tidak ditemukan data pengukuran untuk filter yang dipilih.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($rekapRows as $r): ?>
                  <tr>
                    <td class="font-sm"><?= $r['tanggal_ukur'] ? format_tanggal_id($r['tanggal_ukur'], true) : '<span class="text-muted">Belum Ukur</span>' ?></td>
                    <td>
                      <a href="<?= base_url('index.php?controller=balita&action=detail&id=' . $r['id']) ?>" class="fw-bold text-forest text-decoration-none">
                        <?= htmlspecialchars($r['nama']) ?>
                      </a>
                      <div class="font-xs text-muted-posyandu"><?= htmlspecialchars($r['nama_ortu']) ?></div>
                    </td>
                    <td class="font-sm"><?= htmlspecialchars($r['dusun']) ?></td>
                    <td class="font-sm"><?= $r['usia_bulan'] ? $r['usia_bulan'] . ' bln' : '-' ?></td>
                    <td class="font-sm fw-bold text-forest"><?= $r['berat_kg'] ?: '-' ?></td>
                    <td class="font-sm fw-bold text-forest"><?= $r['tinggi_cm'] ?: '-' ?></td>
                    <td><?= $r['kategori_status'] ? render_status_badge($r['kategori_status']) : '<span class="badge bg-secondary-subtle text-secondary">Belum Ditimbang</span>' ?></td>
                    <td class="font-xs text-ink-700">
                      <div><?= htmlspecialchars($r['catatan'] ?: '-') ?></div>
                    </td>
                    <td style="text-align: right;">
                      <a href="<?= base_url('index.php?controller=balita&action=detail&id=' . $r['id']) ?>" class="btn-posyandu-outline btn-sm py-1 px-2 font-xs">
                        <i class="bi bi-graph-up"></i> Detail
                      </a>
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
