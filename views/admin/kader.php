<?php
$pageTitle = $pageTitle ?? 'Kelola Kader & Bidan — Posyandu Pintar';
$activePage = $activePage ?? 'admin_kader';
$topbarTitle = $topbarTitle ?? 'Manajemen Petugas Posyandu Desa';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Kader &amp; Bidan</span>';
$daftarKader = $daftarKader ?? [];
$daftarBidan = $daftarBidan ?? [];
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
          <h1 class="h3 text-forest mb-1 fw-bold">Petugas Posyandu Desa Sukatani</h1>
          <p class="text-muted-posyandu mb-0 font-sm">
            Kelola data dan hak akses akun Kader Posyandu dan Bidan Desa penanggung jawab
          </p>
        </div>

        <button type="button" class="btn-posyandu-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPetugas">
          <i class="bi bi-person-plus-fill"></i> Tambah Petugas Baru
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

      <div class="row g-4 mb-4">
        <div class="col-12">
          <section class="panel-posyandu mb-4">
            <div class="panel-header d-flex justify-content-between align-items-center">
              <div>
                <h3 class="panel-title">Bidan Desa Penanggung Jawab Klinis</h3>
                <p class="panel-subtitle">Tenaga kesehatan yang memiliki wewenang evaluasi kurva tumbuh kembang dan rujukan</p>
              </div>
              <span class="badge bg-primary px-3 py-1 rounded-pill font-xs"><?= count($daftarBidan) ?> Tenaga Medis</span>
            </div>

            <div class="table-posyandu-wrap">
              <table class="table-posyandu">
                <thead>
                  <tr>
                    <th>Nama &amp; Sapaan</th>
                    <th>Nama Pengguna (Username)</th>
                    <th>Wilayah Tanggung Jawab</th>
                    <th>Kontak / Telepon</th>
                    <th>Tanggal Terdaftar</th>
                    <th style="text-align: right;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($daftarBidan)): ?>
                    <tr>
                      <td colspan="6" class="text-center py-4 text-ink-500">Belum ada akun bidan terdaftar.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($daftarBidan as $b): ?>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width: 36px; height: 36px; font-size: 0.85rem; background-color: #0d47a1; color: #fff;">
                              <?= strtoupper(substr($b['nama'], 0, 1)) ?>
                            </div>
                            <div>
                              <strong class="font-sm text-forest"><?= htmlspecialchars($b['nama']) ?></strong>
                              <div class="font-xs text-muted-posyandu"><?= htmlspecialchars($b['sapaan'] ?: $b['nama']) ?></div>
                            </div>
                          </div>
                        </td>
                        <td class="font-sm fw-bold text-primary">@<?= htmlspecialchars($b['username']) ?></td>
                        <td class="font-sm">Seluruh Desa Sukatani</td>
                        <td class="font-sm"><i class="bi bi-telephone me-1 text-ink-500"></i><?= htmlspecialchars($b['telepon'] ?: '-') ?></td>
                        <td class="font-xs text-muted-posyandu"><?= format_tanggal_id(substr($b['created_at'], 0, 10), true) ?></td>
                        <td style="text-align: right;">
                          <form method="POST" action="<?= base_url('index.php?controller=admin&action=kader') ?>" class="d-inline" onsubmit="return confirm('Reset kata sandi akun ini ke 123?')">
                            <input type="hidden" name="sub_action" value="reset_pass">
                            <input type="hidden" name="id" value="<?= $b['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-posyandu-outline py-1 px-2 font-xs" title="Reset Kata Sandi">
                              <i class="bi bi-key"></i> Reset Sandi
                            </button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </section>

          <section class="panel-posyandu">
            <div class="panel-header d-flex justify-content-between align-items-center">
              <div>
                <h3 class="panel-title">Kader Posyandu Tiap Dusun</h3>
                <p class="panel-subtitle">Petugas pencatatan antropometri dan pelayanan lapangan di pos masing-masing dusun</p>
              </div>
              <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill font-xs"><?= count($daftarKader) ?> Kader Aktif</span>
            </div>

            <div class="table-posyandu-wrap">
              <table class="table-posyandu">
                <thead>
                  <tr>
                    <th>Nama Kader</th>
                    <th>Nama Pengguna (Username)</th>
                    <th>Dusun Binaan</th>
                    <th>Nomor Telepon</th>
                    <th>Terdaftar Sejak</th>
                    <th style="text-align: right;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($daftarKader)): ?>
                    <tr>
                      <td colspan="6" class="text-center py-4 text-ink-500">Belum ada kader posyandu terdaftar.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($daftarKader as $k): ?>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width: 36px; height: 36px; font-size: 0.85rem; background-color: #1565c0; color: #fff;">
                              <?= strtoupper(substr($k['nama'], 0, 1)) ?>
                            </div>
                            <div>
                              <strong class="font-sm text-forest"><?= htmlspecialchars($k['nama']) ?></strong>
                              <div class="font-xs text-muted-posyandu"><?= htmlspecialchars($k['sapaan'] ?: $k['nama']) ?></div>
                            </div>
                          </div>
                        </td>
                        <td class="font-sm fw-bold text-primary">@<?= htmlspecialchars($k['username']) ?></td>
                        <td class="font-sm fw-semibold"><i class="bi bi-geo-alt-fill text-primary me-1"></i>Dusun <?= htmlspecialchars($k['dusun_binaan'] ?: 'Seluruh Desa') ?></td>
                        <td class="font-sm"><i class="bi bi-telephone me-1 text-ink-500"></i><?= htmlspecialchars($k['telepon'] ?: '-') ?></td>
                        <td class="font-xs text-muted-posyandu"><?= format_tanggal_id(substr($k['created_at'], 0, 10), true) ?></td>
                        <td style="text-align: right;">
                          <div class="d-inline-flex gap-1">
                            <form method="POST" action="<?= base_url('index.php?controller=admin&action=kader') ?>" onsubmit="return confirm('Reset kata sandi kader ini ke 123?')">
                              <input type="hidden" name="sub_action" value="reset_pass">
                              <input type="hidden" name="id" value="<?= $k['id'] ?>">
                              <button type="submit" class="btn btn-sm btn-posyandu-outline py-1 px-2 font-xs">
                                <i class="bi bi-key"></i> Reset
                              </button>
                            </form>
                            <form method="POST" action="<?= base_url('index.php?controller=admin&action=kader') ?>" onsubmit="return confirm('Yakin ingin menghapus akun kader ini?')">
                              <input type="hidden" name="sub_action" value="delete">
                              <input type="hidden" name="id" value="<?= $k['id'] ?>">
                              <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2 font-xs">
                                <i class="bi bi-trash"></i>
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </section>
        </div>
      </div>
    </main>

    <?php require_once __DIR__ . '/../../includes/mobile_nav.php'; ?>
  </div>
</div>

<div class="modal fade" id="modalTambahPetugas" tabindex="-1" aria-labelledby="modalTambahPetugasLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-bottom">
        <h5 class="modal-title h6 text-forest fw-bold" id="modalTambahPetugasLabel">
          <i class="bi bi-person-plus-fill text-primary me-1"></i> Pendaftaran Akun Petugas Baru
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form method="POST" action="<?= base_url('index.php?controller=admin&action=kader') ?>">
        <input type="hidden" name="sub_action" value="store">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Peran / Jabatan Petugas <span class="required-mark">*</span></label>
            <select name="role" class="form-select-posyandu" required id="selectRolePetugas">
              <option value="kader">Kader Posyandu</option>
              <option value="bidan">Bidan Desa</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Nama Lengkap Petugas <span class="required-mark">*</span></label>
            <input type="text" name="nama" class="form-control-posyandu" placeholder="Contoh: Siti Aminah / Bidan Siti" required>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Nama Pengguna (Username) <span class="required-mark">*</span></label>
              <input type="text" name="username" class="form-control-posyandu" placeholder="Contoh: sitiaminah" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Kata Sandi Awal <span class="required-mark">*</span></label>
              <input type="password" name="password" class="form-control-posyandu" placeholder="Minimal 3 karakter" value="123" required>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Nama Sapaan Akrab</label>
              <input type="text" name="sapaan" class="form-control-posyandu" placeholder="Contoh: Bu Siti">
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Nomor Telepon / WhatsApp</label>
              <input type="text" name="telepon" class="form-control-posyandu" placeholder="0812...">
            </div>
          </div>

          <div class="mb-3" id="groupDusunBinaan">
            <label class="form-label font-xs fw-semibold">Dusun Binaan (Khusus Kader)</label>
            <select name="dusun_binaan" class="form-select-posyandu">
              <option value="">Pilih Dusun Binaan (Jika Kader)</option>
              <?php foreach ($daftarDusun as $d): ?>
                <option value="<?= htmlspecialchars($d) ?>">Dusun <?= htmlspecialchars($d) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="modal-footer border-top bg-surface-soft rounded-bottom-4">
          <button type="button" class="btn btn-sm btn-posyandu-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-posyandu-primary">Daftarkan Petugas</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
