<?php
$pageTitle = $pageTitle ?? 'Data Peserta Lansia — Posyandu Pintar';
$activePage = $activePage ?? 'admin_lansia';
$topbarTitle = $topbarTitle ?? 'Kesehatan & Skrining Penyakit Tidak Menular Lansia';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Peserta Lansia</span>';
$daftarLansia = $daftarLansia ?? [];
$daftarDusun = $daftarDusun ?? ['Gamping Wetan', 'Boharan', 'Kemangsen', 'Bendomungal'];

$totalSehat = count(array_filter($daftarLansia, fn($l) => $l['status_kesehatan'] === 'sehat'));
$totalPantau = count(array_filter($daftarLansia, fn($l) => $l['status_kesehatan'] === 'pemantauan'));
$totalRisti = count(array_filter($daftarLansia, fn($l) => $l['status_kesehatan'] === 'resiko_tinggi'));

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
          <h1 class="h3 text-forest mb-1 fw-bold">Data Peserta Lansia Posbindu</h1>
          <p class="text-muted-posyandu mb-0 font-sm">
            Pencatatan pemeriksaan tensi darah, skrining gula darah, dan pemantauan lansia sehat
          </p>
        </div>

        <button type="button" class="btn-posyandu-primary" data-bs-toggle="modal" data-bs-target="#modalTambahLansia">
          <i class="bi bi-person-plus-fill"></i> Tambah Peserta Lansia
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
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Total Lansia</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1;"><?= count($daftarLansia) ?></strong>
            <div class="font-xs text-muted-posyandu mt-1">Peserta terdaftar</div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100" style="border-left: 4px solid #16a34a !important;">
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Kondisi Sehat</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1; color: #16a34a !important;"><?= $totalSehat ?></strong>
            <div class="font-xs text-muted-posyandu mt-1">Tensi &amp; gula stabil</div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100" style="border-left: 4px solid #f59e0b !important;">
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Perlu Pemantauan</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1; color: #d97706 !important;"><?= $totalPantau ?></strong>
            <div class="font-xs text-muted-posyandu mt-1">Gejala hipertensi ringan</div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100" style="border-left: 4px solid #e11d48 !important;">
            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold mb-1">Risiko Tinggi</span>
            <strong class="text-forest" style="font-size: 1.6rem; line-height: 1; color: #e11d48 !important;"><?= $totalRisti ?></strong>
            <div class="font-xs text-muted-posyandu mt-1">Rujukan Puskesmas</div>
          </div>
        </div>
      </div>

      <div class="panel-posyandu mb-4 p-3">
        <form method="GET" action="<?= base_url('index.php') ?>" class="row g-2 align-items-center">
          <input type="hidden" name="controller" value="admin">
          <input type="hidden" name="action" value="lansia">

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
              <option value="semua">Semua Status Kesehatan</option>
              <option value="sehat" <?= (($filterStatus ?? '') === 'sehat') ? 'selected' : '' ?>>Sehat</option>
              <option value="pemantauan" <?= (($filterStatus ?? '') === 'pemantauan') ? 'selected' : '' ?>>Pemantauan</option>
              <option value="resiko_tinggi" <?= (($filterStatus ?? '') === 'resiko_tinggi') ? 'selected' : '' ?>>Risiko Tinggi</option>
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
                <th>Nama Lansia</th>
                <th>NIK</th>
                <th>Usia / Tgl Lahir</th>
                <th>JK</th>
                <th>Dusun</th>
                <th>Tensi Darah</th>
                <th>Gula Darah</th>
                <th>Keluhan / Catatan</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($daftarLansia)): ?>
                <tr>
                  <td colspan="10" class="text-center py-4 text-ink-500">Belum ada data lansia untuk filter yang dipilih.</td>
                </tr>
              <?php else: ?>
                <?php $no = 1; foreach ($daftarLansia as $l): 
                  $umur = hitung_umur($l['tanggal_lahir']);
                ?>
                  <tr>
                    <td class="text-center text-muted font-xs"><?= $no++ ?></td>
                    <td>
                      <strong class="font-sm text-forest"><?= htmlspecialchars($l['nama']) ?></strong>
                    </td>
                    <td class="font-sm"><?= htmlspecialchars($l['nik']) ?></td>
                    <td class="font-sm">
                      <div><?= $umur['tahun'] ?> Tahun</div>
                      <div class="font-xs text-muted-posyandu"><?= format_tanggal_id($l['tanggal_lahir'], true) ?></div>
                    </td>
                    <td class="text-center font-sm">
                      <span class="badge <?= ($l['jenis_kelamin'] === 'L') ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' ?>">
                        <?= $l['jenis_kelamin'] ?>
                      </span>
                    </td>
                    <td class="font-sm"><?= htmlspecialchars($l['dusun']) ?></td>
                    <td class="font-sm fw-bold text-forest"><?= htmlspecialchars($l['tensi_terakhir']) ?></td>
                    <td class="font-sm"><?= htmlspecialchars($l['gula_darah']) ?></td>
                    <td class="font-xs text-ink-700"><?= htmlspecialchars($l['keluhan'] ?: '-') ?></td>
                    <td>
                      <?php if ($l['status_kesehatan'] === 'sehat'): ?>
                        <span class="badge bg-success-subtle text-success">Sehat</span>
                      <?php elseif ($l['status_kesehatan'] === 'pemantauan'): ?>
                        <span class="badge bg-warning-subtle text-warning-emphasis">Pemantauan</span>
                      <?php else: ?>
                        <span class="badge bg-danger-subtle text-danger">Risiko Tinggi</span>
                      <?php endif; ?>
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

<div class="modal fade" id="modalTambahLansia" tabindex="-1" aria-labelledby="modalTambahLansiaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-bottom">
        <h5 class="modal-title h6 text-forest fw-bold" id="modalTambahLansiaLabel">
          <i class="bi bi-person-wheelchair text-primary me-1"></i> Pendaftaran Peserta Lansia Baru
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form method="POST" action="<?= base_url('index.php?controller=admin&action=lansia') ?>">
        <div class="modal-body p-4">
          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Nama Lengkap Lansia <span class="required-mark">*</span></label>
              <input type="text" name="nama" class="form-control-posyandu" placeholder="Nama lengkap" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">NIK (16 Digit) <span class="required-mark">*</span></label>
              <input type="text" name="nik" class="form-control-posyandu" placeholder="351508..." maxlength="16" required>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Tanggal Lahir <span class="required-mark">*</span></label>
              <input type="date" name="tanggal_lahir" class="form-control-posyandu" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Jenis Kelamin <span class="required-mark">*</span></label>
              <select name="jenis_kelamin" class="form-select-posyandu" required>
                <option value="L">Laki-laki (L)</option>
                <option value="P">Perempuan (P)</option>
              </select>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Dusun <span class="required-mark">*</span></label>
              <select name="dusun" class="form-select-posyandu" required>
                <?php foreach ($daftarDusun as $d): ?>
                  <option value="<?= htmlspecialchars($d) ?>">Dusun <?= htmlspecialchars($d) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Status Kesehatan</label>
              <select name="status_kesehatan" class="form-select-posyandu">
                <option value="sehat">Sehat</option>
                <option value="pemantauan">Pemantauan</option>
                <option value="resiko_tinggi">Risiko Tinggi</option>
              </select>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Tekanan Darah (Tensi)</label>
              <input type="text" name="tensi_terakhir" class="form-control-posyandu" placeholder="120/80">
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Gula Darah Sewaktu</label>
              <input type="text" name="gula_darah" class="form-control-posyandu" placeholder="110 mg/dL">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Keluhan atau Riwayat Penyakit</label>
            <textarea name="keluhan" class="form-control-posyandu" rows="2" placeholder="Contoh: Pegal linu, pusing di pagi hari"></textarea>
          </div>
        </div>

        <div class="modal-footer border-top bg-surface-soft rounded-bottom-4">
          <button type="button" class="btn btn-sm btn-posyandu-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-posyandu-primary">Simpan Lansia</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
