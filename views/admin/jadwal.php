<?php
$pageTitle = $pageTitle ?? 'Jadwal Posyandu — Posyandu Pintar';
$activePage = $activePage ?? 'admin_jadwal';
$topbarTitle = $topbarTitle ?? 'Manajemen Kalender & Agenda Layanan Posyandu';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Jadwal Kegiatan</span>';
$daftarJadwal = $daftarJadwal ?? [];
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
          <h1 class="h3 text-forest mb-1 fw-bold">Jadwal &amp; Agenda Layanan Posyandu</h1>
          <p class="text-muted-posyandu mb-0 font-sm">
            Penyusunan jadwal pelayanan penimbangan, imunisasi, dan skrining kesehatan tiap dusun
          </p>
        </div>

        <button type="button" class="btn-posyandu-primary" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
          <i class="bi bi-calendar-plus-fill"></i> Tambah Agenda Jadwal
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
          <input type="hidden" name="controller" value="admin">
          <input type="hidden" name="action" value="jadwal">

          <div class="col-md-9">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="semua">Semua Status Agenda</option>
              <option value="rencana" <?= (($filterStatus ?? '') === 'rencana') ? 'selected' : '' ?>>Akan Datang (Rencana)</option>
              <option value="selesai" <?= (($filterStatus ?? '') === 'selesai') ? 'selected' : '' ?>>Sudah Terlaksana (Selesai)</option>
            </select>
          </div>

          <div class="col-md-3">
            <button type="submit" class="btn btn-sm btn-posyandu-primary w-100">Filter Status</button>
          </div>
        </form>
      </div>

      <section class="panel-posyandu">
        <div class="table-posyandu-wrap">
          <table class="table-posyandu">
            <thead>
              <tr>
                <th>Tanggal Pelaksanaan</th>
                <th>Waktu</th>
                <th>Judul Agenda &amp; Jenis Layanan</th>
                <th>Dusun &amp; Lokasi</th>
                <th>Penanggung Jawab</th>
                <th>Keterangan</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($daftarJadwal)): ?>
                <tr>
                  <td colspan="7" class="text-center py-4 text-ink-500">Belum ada agenda jadwal tercatat.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($daftarJadwal as $j): ?>
                  <tr>
                    <td class="font-sm fw-bold text-forest">
                      <i class="bi bi-calendar-event text-primary me-1"></i><?= format_tanggal_id($j['tanggal_kegiatan'], true) ?>
                    </td>
                    <td class="font-sm"><?= substr($j['jam_mulai'], 0, 5) ?> - <?= substr($j['jam_selesai'], 0, 5) ?> WIB</td>
                    <td>
                      <strong class="font-sm text-forest d-block"><?= htmlspecialchars($j['judul_agenda']) ?></strong>
                      <span class="badge bg-primary-subtle text-primary font-xs"><?= htmlspecialchars($j['jenis_layanan']) ?></span>
                    </td>
                    <td class="font-sm">
                      <div class="fw-semibold">Dusun <?= htmlspecialchars($j['dusun']) ?></div>
                      <div class="font-xs text-muted-posyandu"><?= htmlspecialchars($j['lokasi']) ?></div>
                    </td>
                    <td class="font-sm"><i class="bi bi-person-fill text-ink-500 me-1"></i><?= htmlspecialchars($j['penanggung_jawab']) ?></td>
                    <td class="font-xs text-ink-700"><?= htmlspecialchars($j['keterangan'] ?: '-') ?></td>
                    <td>
                      <?php if ($j['status'] === 'rencana'): ?>
                        <span class="badge bg-warning-subtle text-warning-emphasis">Rencana</span>
                      <?php elseif ($j['status'] === 'selesai'): ?>
                        <span class="badge bg-success-subtle text-success">Selesai</span>
                      <?php else: ?>
                        <span class="badge bg-secondary-subtle text-secondary"><?= ucfirst($j['status']) ?></span>
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

<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-labelledby="modalTambahJadwalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-bottom">
        <h5 class="modal-title h6 text-forest fw-bold" id="modalTambahJadwalLabel">
          <i class="bi bi-calendar-plus-fill text-primary me-1"></i> Tambah Agenda Posyandu Baru
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form method="POST" action="<?= base_url('index.php?controller=admin&action=jadwal') ?>">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Judul Kegiatan / Agenda <span class="required-mark">*</span></label>
            <input type="text" name="judul_agenda" class="form-control-posyandu" placeholder="Contoh: Penimbangan Rutin Balita & Imunisasi" required>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Jenis Layanan</label>
              <select name="jenis_layanan" class="form-select-posyandu">
                <option value="Penimbangan Balita">Penimbangan Balita</option>
                <option value="Penimbangan & Imunisasi">Penimbangan &amp; Imunisasi</option>
                <option value="Pemberian PMT">Pemberian PMT Balita</option>
                <option value="Edukasi Gizi Balita">Edukasi Gizi Balita</option>
              </select>
            </div>
            <div class="col-sm-6">
              <label class="form-label font-xs fw-semibold">Dusun Lokasi <span class="required-mark">*</span></label>
              <select name="dusun" class="form-select-posyandu" required>
                <?php foreach ($daftarDusun as $d): ?>
                  <option value="<?= htmlspecialchars($d) ?>">Dusun <?= htmlspecialchars($d) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-4">
              <label class="form-label font-xs fw-semibold">Tanggal <span class="required-mark">*</span></label>
              <input type="date" name="tanggal_kegiatan" class="form-control-posyandu" required>
            </div>
            <div class="col-sm-4">
              <label class="form-label font-xs fw-semibold">Jam Mulai</label>
              <input type="time" name="jam_mulai" class="form-control-posyandu" value="08:00">
            </div>
            <div class="col-sm-4">
              <label class="form-label font-xs fw-semibold">Jam Selesai</label>
              <input type="time" name="jam_selesai" class="form-control-posyandu" value="11:30">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Lokasi / Tempat Acara <span class="required-mark">*</span></label>
            <input type="text" name="lokasi" class="form-control-posyandu" placeholder="Contoh: Balai Posyandu RW 01" required>
          </div>

          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Petugas Penanggung Jawab</label>
            <input type="text" name="penanggung_jawab" class="form-control-posyandu" placeholder="Contoh: Bidan Siti & Kader Sari">
          </div>

          <div class="mb-3">
            <label class="form-label font-xs fw-semibold">Keterangan / Rincian Sasaran</label>
            <textarea name="keterangan" class="form-control-posyandu" rows="2" placeholder="Catatan tambahan perlengkapan atau vaksin"></textarea>
          </div>
        </div>

        <div class="modal-footer border-top bg-surface-soft rounded-bottom-4">
          <button type="button" class="btn btn-sm btn-posyandu-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-posyandu-primary">Simpan Agenda</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
