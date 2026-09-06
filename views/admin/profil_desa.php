<?php
$pageTitle = $pageTitle ?? 'Profil Desa — Posyandu Pintar';
$activePage = $activePage ?? 'admin_profil_desa';
$topbarTitle = $topbarTitle ?? 'Identitas Desa & Struktur Layanan Posyandu';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Profil Desa</span>';

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
          <i class="bi bi-check-circle-fill fs-5"></i>
          <div><?= htmlspecialchars($successMsg) ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
          <i class="bi bi-exclamation-triangle-fill fs-5"></i>
          <div><?= htmlspecialchars($errorMsg) ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
          <h2 class="h3 text-forest mb-1">Profil Pemerintahan Desa</h2>
          <p class="text-muted-posyandu mb-0">Identitas resmi desa, legalitas administrasi, kontak layanan, dan pos posyandu binaan</p>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-4">
          <div class="panel-posyandu p-4 text-center mb-4">
            <div class="mx-auto mb-3" style="width: 100px; height: 100px; border-radius: 50%; background: #EFF6FF; border: 3px solid #BFDBFE; display: flex; align-items: center; justify-content: center;">
              <i class="bi bi-bank2 fs-1 text-primary"></i>
            </div>
            <h3 class="h5 fw-bold text-forest mb-1"><?= htmlspecialchars($profil['nama_desa'] ?? 'Desa Sukatani') ?></h3>
            <p class="font-sm text-muted-posyandu mb-3">
              <?= htmlspecialchars($profil['kecamatan'] ?? 'Kecamatan Balongbendo') ?>, <?= htmlspecialchars($profil['kabupaten'] ?? 'Kabupaten Sidoarjo') ?>
            </p>
            <div class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 font-xs">
              <i class="bi bi-geo-alt-fill me-1"></i>Kode Pos: <?= htmlspecialchars($profil['kode_pos'] ?? '61263') ?>
            </div>

            <hr class="my-4">

            <div class="text-start font-sm">
              <div class="mb-2">
                <span class="text-muted-posyandu d-block font-xs">Kepala Desa:</span>
                <strong class="text-forest"><?= htmlspecialchars($profil['kepala_desa'] ?? 'Bambang Sudiro, S.Sos') ?></strong>
              </div>
              <div class="mb-2">
                <span class="text-muted-posyandu d-block font-xs">Alamat Kantor:</span>
                <span class="text-dark"><?= htmlspecialchars($profil['alamat_kantor'] ?? 'Jl. Raya Desa No. 12') ?></span>
              </div>
              <div class="mb-2">
                <span class="text-muted-posyandu d-block font-xs">Kontak Layanan:</span>
                <span class="text-dark"><?= htmlspecialchars($profil['telepon'] ?? '031-8971234') ?></span>
              </div>
              <div>
                <span class="text-muted-posyandu d-block font-xs">Surat Elektronik:</span>
                <span class="text-dark"><?= htmlspecialchars($profil['email'] ?? 'pemdes@sukatani.desa.id') ?></span>
              </div>
            </div>
          </div>

          <div class="panel-posyandu p-4">
            <h4 class="h6 fw-bold text-forest mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Jaringan Posyandu Binaan</h4>
            <ul class="list-unstyled mb-0 font-sm d-flex flex-column gap-2">
              <li class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                <span>Pos Mawar (Gamping Wetan)</span>
                <span class="badge bg-success font-xs">Aktif</span>
              </li>
              <li class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                <span>Pos Melati (Boharan)</span>
                <span class="badge bg-success font-xs">Aktif</span>
              </li>
              <li class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                <span>Pos Anggrek (Kemangsen)</span>
                <span class="badge bg-success font-xs">Aktif</span>
              </li>
              <li class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                <span>Pos Cempaka (Bendomungal)</span>
                <span class="badge bg-success font-xs">Aktif</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="col-lg-8">
          <div class="panel-posyandu p-4">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
              <h3 class="h5 fw-bold text-forest mb-0">Perbarui Informasi Desa</h3>
              <span class="font-xs text-muted-posyandu">Terakhir diperbarui: <?= htmlspecialchars($profil['updated_at'] ?? date('Y-m-d')) ?></span>
            </div>

            <form method="POST" action="<?= base_url('index.php?controller=admin&action=profil_desa') ?>">
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label font-sm fw-bold">Nama Desa <span class="text-danger">*</span></label>
                  <input type="text" name="nama_desa" class="form-control" value="<?= htmlspecialchars($profil['nama_desa'] ?? 'Desa Sukatani') ?>" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label font-sm fw-bold">Kepala Desa <span class="text-danger">*</span></label>
                  <input type="text" name="kepala_desa" class="form-control" value="<?= htmlspecialchars($profil['kepala_desa'] ?? 'Bambang Sudiro, S.Sos') ?>" required>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-4">
                  <label class="form-label font-sm fw-bold">Kecamatan <span class="text-danger">*</span></label>
                  <input type="text" name="kecamatan" class="form-control" value="<?= htmlspecialchars($profil['kecamatan'] ?? 'Kecamatan Balongbendo') ?>" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label font-sm fw-bold">Kabupaten / Kota <span class="text-danger">*</span></label>
                  <input type="text" name="kabupaten" class="form-control" value="<?= htmlspecialchars($profil['kabupaten'] ?? 'Kabupaten Sidoarjo') ?>" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label font-sm fw-bold">Kode Pos</label>
                  <input type="text" name="kode_pos" class="form-control" value="<?= htmlspecialchars($profil['kode_pos'] ?? '61263') ?>">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label font-sm fw-bold">Alamat Lengkap Kantor Balai Desa <span class="text-danger">*</span></label>
                <textarea name="alamat_kantor" class="form-control" rows="2" required><?= htmlspecialchars($profil['alamat_kantor'] ?? 'Jl. Raya Desa Sukatani No. 12, Balongbendo, Sidoarjo') ?></textarea>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label font-sm fw-bold">Nomor Telepon Kantor</label>
                  <input type="text" name="telepon" class="form-control" value="<?= htmlspecialchars($profil['telepon'] ?? '031-8971234') ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label font-sm fw-bold">Alamat Email Resmi</label>
                  <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($profil['email'] ?? 'pemdes@sukatani.desa.id') ?>">
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label font-sm fw-bold">Visi &amp; Misi Posyandu Desa</label>
                <textarea name="visi_misi" class="form-control" rows="4" placeholder="Tuliskan komitmen penurunan stunting dan visi kesehatan keluarga di desa..."><?= htmlspecialchars($profil['visi_misi'] ?? '') ?></textarea>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn-posyandu-primary">
                  <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Profil
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
