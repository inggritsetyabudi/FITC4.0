<?php
$pageTitle = $pageTitle ?? 'Data Ibu Hamil — Posyandu Pintar';
$activePage = $activePage ?? 'admin_ibu_hamil';
$topbarTitle = $topbarTitle ?? 'Pemantauan & Skrining Risiko Ibu Hamil Desa';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Ibu Hamil</span>';
$daftarBumil = $daftarBumil ?? [];
$daftarDusun = $daftarDusun ?? ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];

$totalSehat = count(array_filter($daftarBumil, fn($b) => $b['status_kehamilan'] === 'sehat'));
$totalRisti = count(array_filter($daftarBumil, fn($b) => $b['status_kehamilan'] === 'resiko_tinggi'));
$totalKek = count(array_filter($daftarBumil, fn($b) => $b['status_kehamilan'] === 'kek'));

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
          <h1 class="h3 text-forest mb-1 fw-bold">Data Ibu Hamil Desa Sukatani</h1>
          <p class="text-muted-posyandu mb-0 font-sm">
            Pemantauan masa kehamilan, deteksi dini resiko KEK, dan persiapan persalinan aman
          </p>
        </div>

        <button type="button" class="btn-posyandu-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBumil">
          <i class="bi bi-person-plus-fill"></i> Tambah Ibu Hamil
        </button>
      </div>

      <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success font-sm py-2 px-3 mb-3 d-flex align-items-center justify-content-between" style="border-radius: var(--radius-md)">
          <div><i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($successMsg) ?></div>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger font-sm py-2 px-3 mb-3 d-flex align-items-center justify-content-between" style="border-radius: var(--radius-md)">
          <div><i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($errorMsg) ?></div>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100">
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Total Ibu Hamil</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1;"><?= count($daftarBumil) ?></strong>
            <div class="font-xs text-muted-posyandu mt-1">Terdata aktif di desa</div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100" style="border-left: 4px solid #16a34a !important;">
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Kehamilan Sehat</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1; color: #16a34a !important;"><?= $totalSehat ?></strong>
            <div class="font-xs text-muted-posyandu mt-1">Tumbuh kembang janin baik</div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100" style="border-left: 4px solid #e11d48 !important;">
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Risiko Tinggi</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1; color: #e11d48 !important;"><?= $totalRisti ?></strong>
            <div class="font-xs text-muted-posyandu mt-1">Rujukan &amp; pantau bidan</div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100" style="border-left: 4px solid #f59e0b !important;">
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Kurang Energi Kronis</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1; color: #d97706 !important;"><?= $totalKek ?></strong>
            <div class="font-xs text-muted-posyandu mt-1">Penerima PMT Pemulihan</div>
          </div>
        </div>
      </div>

      <div class="panel-posyandu mb-4 p-3">
        <form method="GET" action="<?= base_url('index.php') ?>" class="row g-2 align-items-center">
          <input type="hidden" name="controller" value="admin">
          <input type="hidden" name="action" value="ibu_hamil">

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
              <option value="semua">Semua Status Skrining</option>
              <option value="sehat" <?= (($filterStatus ?? '') === 'sehat') ? 'selected' : '' ?>>Sehat</option>
              <option value="resiko_tinggi" <?= (($filterStatus ?? '') === 'resiko_tinggi') ? 'selected' : '' ?>>Risiko Tinggi</option>
              <option value="kek" <?= (($filterStatus ?? '') === 'kek') ? 'selected' : '' ?>>Kurang Energi Kronis (KEK)</option>
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
                <th style="width: 40px;">No</th>
                <th>Nama Ibu Hamil</th>
                <th>NIK</th>
                <th>Nama Suami</th>
                <th>Dusun</th>
                <th>Usia Kehamilan</th>
                <th>Taksiran Lahir (HPL)</th>
                <th>Status Skrining</th>
                <th>Catatan Klinis</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($daftarBumil)): ?>
                <tr>
                  <td colspan="9" class="text-center py-4 text-ink-500">Belum ada data ibu hamil untuk filter yang dipilih.</td>
                </tr>
              <?php else: ?>
                <?php $no = 1; foreach ($daftarBumil as $b): ?>
                  <tr>
                    <td class="text-center text-muted font-xs"><?= $no++ ?></td>
                    <td>
                      <strong class="font-sm text-forest"><?= htmlspecialchars($b['nama']) ?></strong>
                    </td>
                    <td class="font-sm"><?= htmlspecialchars($b['nik']) ?></td>
                    <td class="font-sm"><?= htmlspecialchars($b['nama_suami']) ?></td>
                    <td class="font-sm"><?= htmlspecialchars($b['dusun']) ?></td>
                    <td class="font-sm fw-bold text-forest"><?= $b['usia_kehamilan_minggu'] ?> Minggu</td>
                    <td class="font-sm"><strong><?= format_tanggal_id($b['hpl'], true) ?></strong></td>
                    <td>
                      <?php if ($b['status_kehamilan'] === 'sehat'): ?>
                        <span class="badge bg-success-subtle text-success">Sehat</span>
                      <?php elseif ($b['status_kehamilan'] === 'resiko_tinggi'): ?>
                        <span class="badge bg-danger-subtle text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Risti</span>
                      <?php else: ?>
                        <span class="badge bg-warning-subtle text-warning-emphasis"><i class="bi bi-exclamation-circle-fill me-1"></i>KEK</span>
                      <?php endif; ?>
                    </td>
                    <td class="font-xs text-ink-700"><?= htmlspecialchars($b['catatan'] ?: '-') ?></td>
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

<div class="modal fade" id="modalTambahBumil" tabindex="-1" aria-labelledby="modalTambahBumilLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-bottom">
        <h5 class="modal-title h6 text-forest fw-bold" id="modalTambahBumilLabel">
          <i class="bi bi-heart-pulse-fill text-primary me-1"></i> Pendaftaran Ibu Hamil Baru
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form method="POST" action="<?= base_url('index.php?controller=admin&action=ibu_hamil') ?>">
        <div class="modal-body p-4">
          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Nama Ibu Hamil <span class="required-mark">*</span></label>
              <input type="text" name="nama" class="form-control-posyandu" placeholder="Nama lengkap ibu" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">NIK (16 Digit) <span class="required-mark">*</span></label>
              <input type="text" name="nik" class="form-control-posyandu" placeholder="351508..." maxlength="16" required>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Nama Suami <span class="required-mark">*</span></label>
              <input type="text" name="nama_suami" class="form-control-posyandu" placeholder="Nama suami" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Dusun <span class="required-mark">*</span></label>
              <select name="dusun" class="form-select-posyandu" required>
                <?php foreach ($daftarDusun as $d): ?>
                  <option value="<?= htmlspecialchars($d) ?>">Dusun <?= htmlspecialchars($d) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Usia Kehamilan (Minggu)</label>
              <input type="number" name="usia_kehamilan_minggu" class="form-control-posyandu" min="1" max="42" value="12">
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Taksiran Lahir (HPL) <span class="required-mark">*</span></label>
              <input type="date" name="hpl" class="form-control-posyandu" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Status Skrining Awal</label>
            <select name="status_kehamilan" class="form-select-posyandu">
              <option value="sehat">Sehat / Normal</option>
              <option value="resiko_tinggi">Risiko Tinggi (Risti)</option>
              <option value="kek">Kurang Energi Kronis (KEK)</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Catatan Riwayat Kesehatan</label>
            <textarea name="catatan" class="form-control-posyandu" rows="2" placeholder="Tensi, riwayat penyakit, atau konsumsi vitamin"></textarea>
          </div>
        </div>

        <div class="modal-footer border-top bg-surface-soft rounded-bottom-4">
          <button type="button" class="btn btn-sm btn-posyandu-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-posyandu-primary">Simpan Data Bumil</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
