<?php
$pageTitle = $pageTitle ?? 'Audit Rekap Pemeriksaan — Posyandu Pintar';
$activePage = $activePage ?? 'admin_pemeriksaan';
$topbarTitle = $topbarTitle ?? 'Audit & Log Rekap Hasil Pemeriksaan Lapangan';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Hasil Pemeriksaan</span>';
$daftarPemeriksaan = $daftarPemeriksaan ?? [];
$daftarDusun = $daftarDusun ?? ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
          <h1 class="h3 text-forest mb-1 fw-bold">Audit Hasil Pengukuran Balita</h1>
          <p class="text-muted-posyandu mb-0 font-sm">
            Rekap riwayat pengukuran antropometri dan penilaian status gizi yang dicatat oleh Kader dan Bidan Desa
          </p>
        </div>

        <div class="d-flex gap-2">
          <a href="<?= base_url('index.php?controller=bidan&action=export_pdf') ?>" target="_blank" class="btn-posyandu-primary btn-sm">
            <i class="bi bi-file-earmark-pdf"></i> Cetak Rekap PDF
          </a>
        </div>
      </div>

      <div class="alert alert-info py-2 px-3 mb-4 d-flex align-items-center gap-2 font-sm" style="border-radius: var(--radius-md);">
        <i class="bi bi-shield-lock-fill fs-5 text-primary flex-shrink-0"></i>
        <div>
          <strong>Hak Akses Audit Terbuka:</strong> Administrator Desa dapat melihat dan merekapitulasi seluruh catatan hasil pemeriksaan klinis balita, namun tidak memiliki wewenang untuk mengubah angka medis demi menjaga integritas data kesehatan.
        </div>
      </div>

      <div class="panel-posyandu mb-4 p-3">
        <form method="GET" action="<?= base_url('index.php') ?>" class="row g-2 align-items-center">
          <input type="hidden" name="controller" value="admin">
          <input type="hidden" name="action" value="pemeriksaan">

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

          <div class="col-md-5">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="semua">Semua Status Gizi</option>
              <option value="normal" <?= (($filterStatus ?? '') === 'normal') ? 'selected' : '' ?>>Normal</option>
              <option value="pemantauan" <?= (($filterStatus ?? '') === 'pemantauan') ? 'selected' : '' ?>>Pemantauan</option>
              <option value="berisiko" <?= (($filterStatus ?? '') === 'berisiko') ? 'selected' : '' ?>>Prioritas / Berisiko</option>
            </select>
          </div>

          <div class="col-md-2">
            <button type="submit" class="btn btn-sm btn-posyandu-primary w-100">Filter</button>
          </div>
        </form>
      </div>

      <section class="panel-posyandu">
        <div class="table-posyandu-wrap">
          <table class="table-posyandu">
            <thead>
              <tr>
                <th>Tanggal Ukur</th>
                <th>Nama Balita</th>
                <th>Dusun</th>
                <th>Usia</th>
                <th>Berat (kg)</th>
                <th>Tinggi (cm)</th>
                <th>Status Skrining</th>
                <th>Dicatat Oleh</th>
                <th>Catatan &amp; Indikasi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($daftarPemeriksaan)): ?>
                <tr>
                  <td colspan="9" class="text-center py-4 text-ink-500">Belum ada riwayat pengukuran untuk filter ini.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($daftarPemeriksaan as $p): ?>
                  <tr>
                    <td class="font-sm"><?= format_tanggal_id($p['tanggal_ukur'], true) ?></td>
                    <td>
                      <strong class="font-sm text-forest"><?= htmlspecialchars($p['nama_anak']) ?></strong>
                      <div class="font-xs text-muted-posyandu">Ortu: <?= htmlspecialchars($p['nama_ortu']) ?></div>
                    </td>
                    <td class="font-sm"><?= htmlspecialchars($p['dusun']) ?></td>
                    <td class="font-sm"><?= $p['usia_bulan'] ?> Bulan</td>
                    <td class="font-sm fw-bold text-forest"><?= $p['berat_kg'] ?> kg</td>
                    <td class="font-sm fw-bold text-forest"><?= $p['tinggi_cm'] ?> cm</td>
                    <td><?= render_status_badge($p['kategori_status']) ?></td>
                    <td class="font-sm">
                      <span class="badge bg-surface-soft border text-ink-700 font-xs">
                        <i class="bi bi-person-fill me-1"></i><?= htmlspecialchars($p['nama_petugas'] ?: 'Kader Posyandu') ?>
                      </span>
                    </td>
                    <td class="font-xs text-ink-700"><?= htmlspecialchars($p['catatan'] ?: '-') ?></td>
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
