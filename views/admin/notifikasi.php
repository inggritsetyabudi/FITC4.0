<?php
$pageTitle = $pageTitle ?? 'Pengumuman & Notifikasi — Posyandu Pintar';
$activePage = $activePage ?? 'admin_notifikasi';
$topbarTitle = $topbarTitle ?? 'Pusat Informasi & Siaran Pengumuman Posyandu';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Pengumuman</span>';

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
          <h2 class="h3 text-forest mb-1">Siaran Pengumuman &amp; Notifikasi</h2>
          <p class="text-muted-posyandu mb-0">Publikasikan arahan posyandu, jadwal sweeping, dan himbauan kesehatan warga</p>
        </div>
        <button type="button" class="btn-posyandu-primary" data-bs-toggle="modal" data-bs-target="#modalTambahNotif">
          <i class="bi bi-megaphone-fill"></i> Buat Pengumuman Baru
        </button>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="panel-posyandu p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="stat-icon-wrap" style="background:#E3F2FD; color:#1565C0; width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-broadcast fs-4"></i>
              </div>
              <div>
                <div class="text-muted-posyandu font-sm">Total Terkirim</div>
                <div class="h3 fw-bold text-forest mb-0"><?= count($daftarNotif) ?></div>
              </div>
            </div>
            <p class="font-sm text-muted-posyandu mb-0">Seluruh pesan siaran otomatis tersimpan pada arsip pengumuman desa.</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="panel-posyandu p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="stat-icon-wrap" style="background:#E0F2FE; color:#0284C7; width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-people-fill fs-4"></i>
              </div>
              <div>
                <div class="text-muted-posyandu font-sm">Saluran Target</div>
                <div class="h5 fw-bold text-forest mb-0">Kader &amp; Masyarakat</div>
              </div>
            </div>
            <p class="font-sm text-muted-posyandu mb-0">Menjangkau pos-pos dusun dan masyarakat secara transparan dan akurat.</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="panel-posyandu p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="stat-icon-wrap" style="background:#EFF6FF; color:#2563EB; width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-shield-check fs-4"></i>
              </div>
              <div>
                <div class="text-muted-posyandu font-sm">Otoritas Pengirim</div>
                <div class="h5 fw-bold text-forest mb-0">Pemerintah Desa</div>
              </div>
            </div>
            <p class="font-sm text-muted-posyandu mb-0">Pemberitahuan resmi terverifikasi dengan stempel kelembagaan desa.</p>
          </div>
        </div>
      </div>

      <div class="panel-posyandu">
        <div class="panel-header p-3 border-bottom d-flex align-items-center justify-content-between">
          <h3 class="h5 fw-bold text-forest mb-0">Daftar Arsip Siaran Pengumuman</h3>
          <span class="badge bg-light text-forest border px-3 py-2 font-sm"><?= count($daftarNotif) ?> Pesan</span>
        </div>
        <div class="p-4">
          <?php if (empty($daftarNotif)): ?>
            <div class="text-center py-5 text-muted">
              <i class="bi bi-inbox fs-1 d-block mb-2 text-muted-posyandu"></i>
              Belum ada siaran pengumuman yang diterbitkan.
            </div>
          <?php else: ?>
            <div class="d-flex flex-column gap-3">
              <?php foreach ($daftarNotif as $n): ?>
                <?php
                $targetBadge = match($n['target'] ?? 'semua') {
                  'kader' => '<span class="badge bg-info bg-opacity-10 text-info px-3 py-1 font-xs border border-info border-opacity-25"><i class="bi bi-person-badge"></i> Khusus Kader</span>',
                  'masyarakat' => '<span class="badge bg-success bg-opacity-10 text-success px-3 py-1 font-xs border border-success border-opacity-25"><i class="bi bi-people"></i> Khusus Warga</span>',
                  default => '<span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 font-xs border border-primary border-opacity-25"><i class="bi bi-globe"></i> Semua Penerima</span>'
                };
                ?>
                <div class="p-3 border rounded-3 bg-white hover-shadow transition">
                  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2">
                      <span class="p-2 rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex">
                        <i class="bi bi-bell-fill"></i>
                      </span>
                      <h4 class="h6 fw-bold text-forest mb-0"><?= htmlspecialchars($n['judul']) ?></h4>
                      <?= $targetBadge ?>
                    </div>
                    <div class="font-xs text-muted-posyandu">
                      <i class="bi bi-clock me-1"></i><?= htmlspecialchars($n['created_at']) ?>
                    </div>
                  </div>
                  <p class="font-sm text-dark mb-2 ms-5 ps-1" style="white-space: pre-line; line-height: 1.6;">
                    <?= htmlspecialchars($n['pesan']) ?>
                  </p>
                  <div class="d-flex align-items-center justify-content-between font-xs text-muted-posyandu ms-5 ps-1 pt-2 border-top">
                    <div><i class="bi bi-person me-1"></i>Oleh: <strong><?= htmlspecialchars($n['dibuat_oleh'] ?? 'Admin Desa') ?></strong></div>
                    <div><i class="bi bi-check2-circle text-success me-1"></i>Status: <?= htmlspecialchars($n['status']) ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </main>
  </div>
</div>

<div class="modal fade" id="modalTambahNotif" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-base fw-bold"><i class="bi bi-megaphone me-2"></i>Terbitkan Pengumuman Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="<?= base_url('index.php?controller=admin&action=notifikasi') ?>">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label font-sm fw-bold">Judul Pengumuman <span class="text-danger">*</span></label>
            <input type="text" name="judul" class="form-control" placeholder="Contoh: Jadwal Sweeping Imunisasi Balita" required>
          </div>
          <div class="mb-3">
            <label class="form-label font-sm fw-bold">Target Sasaran <span class="text-danger">*</span></label>
            <select name="target" class="form-select" required>
              <option value="semua">Semua Penerima (Kader, Bidan &amp; Warga)</option>
              <option value="kader">Khusus Kader Posyandu</option>
              <option value="masyarakat">Khusus Warga / Masyarakat Desa</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label font-sm fw-bold">Isi Pesan Pengumuman <span class="text-danger">*</span></label>
            <textarea name="pesan" class="form-control" rows="5" placeholder="Tuliskan isi himbauan secara jelas dan informatif..." required></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light p-3">
          <button type="button" class="btn btn-secondary font-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn-posyandu-primary">
            <i class="bi bi-send-fill me-1"></i> Siarkan Sekarang
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
