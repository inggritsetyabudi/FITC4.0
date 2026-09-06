<?php
$pageTitle = $pageTitle ?? 'Daftar Pos Posyandu — Posyandu Pintar';
$activePage = $activePage ?? 'admin_posyandu';
$topbarTitle = $topbarTitle ?? 'Manajemen Pos Layanan Posyandu Wilayah Desa';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Pos Posyandu</span>';
$daftarPos = $daftarPos ?? [];
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
          <h1 class="h3 text-forest mb-1 fw-bold">Pos Layanan Posyandu Desa Sukatani</h1>
          <p class="text-muted-posyandu mb-0 font-sm">
            Total <?= count($daftarPos) ?> pos layanan aktif yang tersebar di 4 dusun binaan
          </p>
        </div>

        <button type="button" class="btn-posyandu-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPos">
          <i class="bi bi-geo-alt-fill"></i> Tambah Pos Layanan
        </button>
      </div>

      <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success font-sm py-2 px-3 mb-3 d-flex align-items-center justify-content-between" style="border-radius: var(--radius-md)">
          <div><i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($successMsg) ?></div>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <div class="row g-3 mb-4">
        <?php foreach ($daftarPos as $p): ?>
          <div class="col-md-6 col-lg-3">
            <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100 d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                  <span class="badge bg-primary-subtle text-primary font-xs fw-bold">Dusun <?= htmlspecialchars($p['dusun']) ?></span>
                  <span class="badge bg-success-subtle text-success font-xs"><?= ucfirst($p['status']) ?></span>
                </div>
                <h3 class="h5 fw-bold text-forest mb-1"><?= htmlspecialchars($p['nama_pos']) ?></h3>
                <p class="font-xs text-ink-500 mb-2"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($p['lokasi_alamat']) ?></p>
              </div>
              <div class="pt-2 border-top font-xs">
                <div class="text-ink-700 mb-1"><strong>Jadwal:</strong> <?= htmlspecialchars($p['jadwal_rutin']) ?></div>
                <div class="text-ink-700"><strong>PJ:</strong> <?= htmlspecialchars($p['kader_utama']) ?></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <section class="panel-posyandu">
        <div class="panel-header">
          <div>
            <h3 class="panel-title">Tabel Rincian Pos Layanan</h3>
            <p class="panel-subtitle">Data lengkap jadwal, kontak, dan kader penanggung jawab</p>
          </div>
        </div>

        <div class="table-posyandu-wrap">
          <table class="table-posyandu">
            <thead>
              <tr>
                <th>Nama Pos</th>
                <th>Dusun</th>
                <th>Lokasi / Alamat Lengkap</th>
                <th>Jadwal Penimbangan</th>
                <th>Kader Utama</th>
                <th>Telepon / WA</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($daftarPos as $p): ?>
                <tr>
                  <td class="fw-bold text-forest"><i class="bi bi-hospital me-1 text-primary"></i> <?= htmlspecialchars($p['nama_pos']) ?></td>
                  <td class="font-sm"><?= htmlspecialchars($p['dusun']) ?></td>
                  <td class="font-sm"><?= htmlspecialchars($p['lokasi_alamat']) ?></td>
                  <td class="font-sm"><?= htmlspecialchars($p['jadwal_rutin']) ?></td>
                  <td class="font-sm fw-semibold"><?= htmlspecialchars($p['kader_utama']) ?></td>
                  <td class="font-sm"><?= htmlspecialchars($p['telepon'] ?: '-') ?></td>
                  <td><span class="badge bg-success-subtle text-success font-xs"><?= ucfirst($p['status']) ?></span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>

    <?php require_once __DIR__ . '/../../includes/mobile_nav.php'; ?>
  </div>
</div>

<div class="modal fade" id="modalTambahPos" tabindex="-1" aria-labelledby="modalTambahPosLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-bottom">
        <h5 class="modal-title h6 text-forest fw-bold" id="modalTambahPosLabel">
          <i class="bi bi-geo-alt-fill text-primary me-1"></i> Tambah Pos Layanan Posyandu
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form method="POST" action="<?= base_url('index.php?controller=admin&action=posyandu') ?>">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Nama Pos Posyandu <span class="required-mark">*</span></label>
            <input type="text" name="nama_pos" class="form-control-posyandu" placeholder="Contoh: Posyati Melati 3" required>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Dusun Wilayah <span class="required-mark">*</span></label>
              <select name="dusun" class="form-select-posyandu" required>
                <?php foreach ($daftarDusun as $d): ?>
                  <option value="<?= htmlspecialchars($d) ?>">Dusun <?= htmlspecialchars($d) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Kader Penanggung Jawab</label>
              <input type="text" name="kader_utama" class="form-control-posyandu" placeholder="Nama kader utama">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Alamat / Lokasi Pelaksanaan <span class="required-mark">*</span></label>
            <textarea name="lokasi_alamat" class="form-control-posyandu" rows="2" placeholder="Contoh: Balai RW 02 Dusun..." required></textarea>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Jadwal Rutin</label>
              <input type="text" name="jadwal_rutin" class="form-control-posyandu" placeholder="Contoh: Minggu ke-1 setiap bulan">
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Nomor Telepon Kontak</label>
              <input type="text" name="telepon" class="form-control-posyandu" placeholder="0812...">
            </div>
          </div>
        </div>

        <div class="modal-footer border-top bg-surface-soft rounded-bottom-4">
          <button type="button" class="btn btn-sm btn-posyandu-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-posyandu-primary">Simpan Pos</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
