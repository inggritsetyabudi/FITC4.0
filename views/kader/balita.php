<?php
$pageTitle = $pageTitle ?? 'Data Balita — Posyandu Pintar';
$activePage = $activePage ?? 'balita';
$topbarTitle = $topbarTitle ?? 'Data Balita Desa';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=kader&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Data Balita</span>';

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
          <h2 class="h3 text-forest mb-1">Daftar Balita Binaan</h2>
          <p class="text-muted-posyandu mb-0">Total <?= count($daftarBalita) ?> balita terdaftar di Posyandu Desa Sukatani</p>
        </div>

        <button type="button" class="btn-posyandu-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBalita">
          <i class="bi bi-person-plus"></i> Tambah Balita Baru
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

      <div class="panel-posyandu mb-4 p-3">
        <form method="GET" action="<?= base_url('index.php') ?>" class="row g-2 align-items-center">
          <input type="hidden" name="controller" value="balita">
          <input type="hidden" name="action" value="index">
          <div class="col-md-5">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-surface border-line"><i class="bi bi-search"></i></span>
              <input
                type="text"
                name="q"
                class="form-control"
                placeholder="Cari nama balita, NIK, atau nama ortu..."
                value="<?= htmlspecialchars($searchQuery ?? '') ?>"
              >
            </div>
          </div>

          <div class="col-md-4">
            <select name="dusun" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="semua">Semua Dusun</option>
              <?php foreach ($daftarDusun as $d): ?>
                <option value="<?= htmlspecialchars($d) ?>" <?= (($filterDusun ?? '') === $d) ? 'selected' : '' ?>>
                  Dusun <?= htmlspecialchars($d) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-posyandu-primary flex-fill">
              Filter
            </button>
            <?php if (($filterDusun ?? '') !== '' || ($searchQuery ?? '') !== ''): ?>
              <a href="<?= base_url('index.php?controller=balita&action=index') ?>" class="btn btn-sm btn-posyandu-outline">
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
                <th>Nama &amp; NIK</th>
                <th>Usia</th>
                <th>JK</th>
                <th>Dusun</th>
                <th>Orang Tua</th>
                <th>Pengukuran Terakhir</th>
                <th>Status Skrining</th>
                <th style="text-align: right;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($daftarBalita)): ?>
                <tr>
                  <td colspan="8" class="text-center py-4 text-ink-500">
                    Tidak ditemukan data balita sesuai kriteria pencarian.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($daftarBalita as $b): 
                  $umur = hitung_umur($b['tanggal_lahir']);
                  $status = $b['status_terakhir'] ?: 'normal';
                  $tglUkur = $b['tanggal_ukur_terakhir'] ? format_tanggal_id($b['tanggal_ukur_terakhir'], true) : 'Belum diukur';
                ?>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <div class="avatar-circle" style="width: 32px; height: 32px; font-size: 0.8rem;">
                          <?= strtoupper(substr($b['nama'], 0, 1)) ?>
                        </div>
                        <div>
                          <a href="<?= base_url('index.php?controller=balita&action=detail&id=' . $b['id']) ?>" class="fw-bold text-forest text-decoration-none">
                            <?= htmlspecialchars($b['nama']) ?>
                          </a>
                          <div class="font-xs text-muted-posyandu"><?= htmlspecialchars($b['nik'] ?: 'NIK belum diisi') ?></div>
                        </div>
                      </div>
                    </td>
                    <td class="font-sm"><?= $umur['teks'] ?></td>
                    <td>
                      <span class="badge <?= ($b['jenis_kelamin'] === 'L') ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' ?>">
                        <?= $b['jenis_kelamin'] ?>
                      </span>
                    </td>
                    <td class="font-sm"><?= htmlspecialchars($b['dusun']) ?></td>
                    <td class="font-sm text-ink-700"><?= htmlspecialchars($b['nama_ortu']) ?></td>
                    <td class="font-sm">
                      <?php if ($b['berat_terakhir']): ?>
                        <strong><?= $b['berat_terakhir'] ?> kg</strong> · <?= $b['tinggi_terakhir'] ?> cm
                        <div class="font-xs text-muted-posyandu"><?= $tglUkur ?></div>
                      <?php else: ?>
                        <span class="text-muted-posyandu">Belum ada</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?= render_status_badge($status) ?>
                    </td>
                    <td style="text-align: right;">
                      <div class="d-inline-flex gap-1">
                        <a href="<?= base_url('index.php?controller=pengukuran&action=index&anak_id=' . $b['id']) ?>" class="btn-posyandu-primary btn-sm py-1 px-2 font-xs" title="Catat Pengukuran">
                          <i class="bi bi-clipboard2-plus"></i> Ukur
                        </a>
                        <a href="<?= base_url('index.php?controller=balita&action=detail&id=' . $b['id']) ?>" class="btn-posyandu-outline btn-sm py-1 px-2 font-xs" title="Lihat Profil">
                          <i class="bi bi-eye"></i> Detail
                        </a>
                      </div>
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

<div class="modal fade" id="modalTambahBalita" tabindex="-1" aria-labelledby="modalTambahBalitaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-bottom">
        <h5 class="modal-title h6 text-forest fw-bold" id="modalTambahBalitaLabel">
          <i class="bi bi-person-plus text-primary me-1"></i> Tambah Data Balita Baru
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form method="POST" action="<?= base_url('index.php?controller=balita&action=store') ?>">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Nama Lengkap Balita <span class="required-mark">*</span></label>
            <input type="text" name="nama" class="form-control-posyandu" placeholder="Contoh: Dinda Aulia" required>
          </div>

          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Nomor Induk Kependudukan (NIK)</label>
            <input type="text" name="nik" class="form-control-posyandu" placeholder="16 Digit NIK (Opsional)" maxlength="16">
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
              <label class="form-label font-xs fw-semibold">Nama Orang Tua (Ibu/Ayah) <span class="required-mark">*</span></label>
              <input type="text" name="nama_ortu" class="form-control-posyandu" placeholder="Nama Ibu/Ayah" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Dusun Binaan <span class="required-mark">*</span></label>
              <select name="dusun" class="form-select-posyandu" required>
                <?php foreach ($daftarDusun as $d): ?>
                  <option value="<?= htmlspecialchars($d) ?>">Dusun <?= htmlspecialchars($d) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer border-top bg-surface-soft rounded-bottom-4">
          <button type="button" class="btn btn-sm btn-posyandu-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-posyandu-primary">Simpan Balita</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php if (isset($_GET['tambah']) && $_GET['tambah'] == '1'): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modal = new bootstrap.Modal(document.getElementById('modalTambahBalita'));
      modal.show();
    });
  </script>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
