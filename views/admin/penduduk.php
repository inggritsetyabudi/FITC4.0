<?php
$pageTitle = $pageTitle ?? 'Data Kependudukan — Posyandu Pintar';
$activePage = $activePage ?? 'admin_penduduk';
$topbarTitle = $topbarTitle ?? 'Master Data Penduduk & Sasaran Posyandu';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Data Warga</span>';
$daftarWarga = $daftarWarga ?? [];
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
          <h1 class="h3 text-forest mb-1 fw-bold">Master Data Penduduk Desa</h1>
          <p class="text-muted-posyandu mb-0 font-sm">
            Total <?= count($daftarWarga) ?> data warga terdaftar sebagai basis sasaran layanan Posyandu
          </p>
        </div>

        <div class="d-flex gap-2">
          <button type="button" class="btn-posyandu-primary" data-bs-toggle="modal" data-bs-target="#modalTambahWarga">
            <i class="bi bi-person-plus-fill"></i> Tambah Warga Baru
          </button>
          <a href="<?= base_url('index.php?controller=admin&action=export_csv') ?>" class="btn-posyandu-outline">
            <i class="bi bi-file-earmark-arrow-down"></i> Unduh CSV
          </a>
        </div>
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
          <input type="hidden" name="controller" value="admin">
          <input type="hidden" name="action" value="penduduk">

          <div class="col-md-4">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-surface border-line"><i class="bi bi-search"></i></span>
              <input type="text" name="q" class="form-control" placeholder="Cari nama, NIK, atau No KK..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            </div>
          </div>

          <div class="col-md-3">
            <select name="dusun" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="semua">Semua Dusun</option>
              <?php foreach ($daftarDusun as $d): ?>
                <option value="<?= htmlspecialchars($d) ?>" <?= (($filterDusun ?? '') === $d) ? 'selected' : '' ?>>
                  Dusun <?= htmlspecialchars($d) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-3">
            <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="semua">Semua Kategori Sasaran</option>
              <option value="balita" <?= (($filterKategori ?? '') === 'balita') ? 'selected' : '' ?>>Balita</option>
              <option value="ibu_hamil" <?= (($filterKategori ?? '') === 'ibu_hamil') ? 'selected' : '' ?>>Ibu Hamil</option>
              <option value="lansia" <?= (($filterKategori ?? '') === 'lansia') ? 'selected' : '' ?>>Lansia</option>
              <option value="umum" <?= (($filterKategori ?? '') === 'umum') ? 'selected' : '' ?>>Warga Umum</option>
            </select>
          </div>

          <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-posyandu-primary flex-fill">Cari</button>
            <?php if (($filterDusun ?? 'semua') !== 'semua' || ($filterKategori ?? 'semua') !== 'semua' || !empty($_GET['q'])): ?>
              <a href="<?= base_url('index.php?controller=admin&action=penduduk') ?>" class="btn btn-sm btn-posyandu-outline">Reset</a>
            <?php endif; ?>
          </div>
        </form>
      </div>

      <section class="panel-posyandu">
        <div class="table-posyandu-wrap">
          <table class="table-posyandu">
            <thead>
              <tr>
                <th style="width: 40px;">No</th>
                <th>NIK &amp; No KK</th>
                <th>Nama Warga</th>
                <th>Tgl Lahir / Usia</th>
                <th>JK</th>
                <th>Dusun</th>
                <th>RT/RW</th>
                <th>Kategori Sasaran</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($daftarWarga)): ?>
                <tr>
                  <td colspan="10" class="text-center py-4 text-ink-500">
                    Tidak ditemukan data warga sesuai kriteria pencarian.
                  </td>
                </tr>
              <?php else: ?>
                <?php $no = 1; foreach ($daftarWarga as $w): 
                  $umur = hitung_umur($w['tanggal_lahir']);
                  $isAktif = ($w['status'] === 'aktif');
                ?>
                  <tr style="<?= !$isAktif ? 'opacity: 0.6; background-color: #f8fafc;' : '' ?>">
                    <td class="text-muted font-xs text-center"><?= $no++ ?></td>
                    <td>
                      <div class="fw-bold font-sm text-forest"><?= htmlspecialchars($w['nik']) ?></div>
                      <div class="font-xs text-muted-posyandu">KK: <?= htmlspecialchars($w['no_kk'] ?: '-') ?></div>
                    </td>
                    <td>
                      <strong class="font-sm text-forest"><?= htmlspecialchars($w['nama']) ?></strong>
                      <?php if ($w['telepon']): ?>
                        <div class="font-xs text-muted-posyandu"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($w['telepon']) ?></div>
                      <?php endif; ?>
                    </td>
                    <td class="font-sm">
                      <div><?= format_tanggal_id($w['tanggal_lahir'], true) ?></div>
                      <div class="font-xs text-ink-500"><?= $umur['teks'] ?></div>
                    </td>
                    <td class="text-center font-sm">
                      <span class="badge <?= ($w['jenis_kelamin'] === 'L') ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' ?>">
                        <?= $w['jenis_kelamin'] ?>
                      </span>
                    </td>
                    <td class="font-sm"><?= htmlspecialchars($w['dusun']) ?></td>
                    <td class="font-sm"><?= htmlspecialchars($w['rt_rw']) ?></td>
                    <td>
                      <?php if ($w['kategori'] === 'balita'): ?>
                        <span class="badge bg-primary-subtle text-primary">Balita</span>
                      <?php elseif ($w['kategori'] === 'ibu_hamil'): ?>
                        <span class="badge bg-info-subtle text-info-emphasis">Ibu Hamil</span>
                      <?php elseif ($w['kategori'] === 'lansia'): ?>
                        <span class="badge bg-purple-subtle text-secondary">Lansia</span>
                      <?php else: ?>
                        <span class="badge bg-secondary-subtle text-secondary">Umum</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="badge <?= $isAktif ? 'bg-success-subtle text-success' : 'bg-secondary text-white' ?>">
                        <?= ucfirst($w['status']) ?>
                      </span>
                    </td>
                    <td style="text-align: right;">
                      <form method="POST" action="<?= base_url('index.php?controller=admin&action=penduduk') ?>" class="d-inline" onsubmit="return confirm('Ubah status aktif/nonaktif warga ini?')">
                        <input type="hidden" name="sub_action" value="toggle">
                        <input type="hidden" name="id" value="<?= $w['id'] ?>">
                        <button type="submit" class="btn btn-sm <?= $isAktif ? 'btn-outline-warning' : 'btn-outline-success' ?> py-1 px-2 font-xs">
                          <?= $isAktif ? '<i class="bi bi-person-x"></i> Nonaktifkan' : '<i class="bi bi-person-check"></i> Aktifkan' ?>
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
    </main>

    <?php require_once __DIR__ . '/../../includes/mobile_nav.php'; ?>
  </div>
</div>

<div class="modal fade" id="modalTambahWarga" tabindex="-1" aria-labelledby="modalTambahWargaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-bottom">
        <h5 class="modal-title h6 text-forest fw-bold" id="modalTambahWargaLabel">
          <i class="bi bi-person-plus-fill text-primary me-1"></i> Tambah Data Penduduk Baru
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form method="POST" action="<?= base_url('index.php?controller=admin&action=penduduk') ?>">
        <input type="hidden" name="sub_action" value="store">
        <div class="modal-body p-4">
          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">NIK (16 Digit) <span class="required-mark">*</span></label>
              <input type="text" name="nik" class="form-control-posyandu" placeholder="351508..." maxlength="16" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Nomor Kartu Keluarga (KK)</label>
              <input type="text" name="no_kk" class="form-control-posyandu" placeholder="351508..." maxlength="16">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Nama Lengkap Warga <span class="required-mark">*</span></label>
            <input type="text" name="nama" class="form-control-posyandu" placeholder="Nama sesuai KTP/Akta" required>
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
              <label class="form-label font-xs fw-semibold">RT / RW</label>
              <input type="text" name="rt_rw" class="form-control-posyandu" placeholder="Contoh: 002/001" value="001/001">
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Kategori Sasaran</label>
              <select name="kategori" class="form-select-posyandu">
                <option value="umum">Warga Umum</option>
                <option value="balita">Balita</option>
                <option value="ibu_hamil">Ibu Hamil</option>
                <option value="lansia">Lansia</option>
              </select>
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Nomor Telepon / WA</label>
              <input type="text" name="telepon" class="form-control-posyandu" placeholder="0812...">
            </div>
          </div>
        </div>

        <div class="modal-footer border-top bg-surface-soft rounded-bottom-4">
          <button type="button" class="btn btn-sm btn-posyandu-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-posyandu-primary">Simpan Warga</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
