<?php
$pageTitle = $pageTitle ?? 'Pencatatan Penimbangan — Posyandu Pintar';
$activePage = $activePage ?? 'pengukuran';
$topbarTitle = $topbarTitle ?? 'Input Timbangan & Pengukuran Balita';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=kader&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Pencatatan</span>';

$balitaMap = [];
foreach ($daftarBalita as $b) {
    $umur = hitung_umur($b['tanggal_lahir']);
    $balitaMap[$b['id']] = [
        'id'              => $b['id'],
        'nama'            => $b['nama'],
        'dusun'           => $b['dusun'],
        'namaOrtu'        => $b['nama_ortu'],
        'umurTeks'        => $umur['teks'],
        'beratTerakhir'   => $b['berat_terakhir'] ? (float)$b['berat_terakhir'] : null,
        'tinggiTerakhir'  => $b['tinggi_terakhir'] ? (float)$b['tinggi_terakhir'] : null,
        'tanggalTerakhir' => $b['tanggal_ukur_terakhir'] ? format_tanggal_id($b['tanggal_ukur_terakhir'], true) : null
    ];
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="form-narrow-container">
        
        <div class="page-header-block mb-3">
          <div>
            <h1 class="page-header-title">Catat Pengukuran Bulanan</h1>
            <p class="page-header-subtitle">Masukkan hasil penimbangan berat dan pengukuran tinggi badan anak</p>
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

        <div id="cardFormPengukuran" class="panel-posyandu">
          <form method="POST" action="<?= base_url('index.php?controller=pengukuran&action=store') ?>" id="formPengukuran">
            <div class="mb-3">
              <label for="selectBalita" class="form-label">
                Pilih Balita <span class="required-mark">*</span>
              </label>
              <select name="anak_id" id="selectBalita" class="form-select-posyandu" required onchange="onPilihBalita(this.value)">
                <option value="">-- Pilih Balita Binaan --</option>
                <?php foreach ($daftarBalita as $b): ?>
                  <option value="<?= $b['id'] ?>" <?= (($selectedAnakId ?? 0) === (int)$b['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b['nama']) ?> · Dusun <?= htmlspecialchars($b['dusun']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div id="balitaInfoPreview" class="bg-surface-soft p-3 rounded-3 border-line mb-3" style="display: none;">
              <div class="d-flex align-items-center justify-content-between mb-1">
                <span id="previewNama" class="fw-bold text-forest fs-6">-</span>
                <span id="previewUmur" class="badge bg-surface text-ink-800 border">-</span>
              </div>
              <div id="previewOrtuDusun" class="font-sm text-ink-700">
                Orang Tua: - · Dusun -
              </div>
              <div class="font-sm text-ink-500 mt-1">
                Catatan Terakhir: <strong id="previewTerakhir">-</strong>
              </div>
            </div>

            <div class="mb-3">
              <label for="inputTanggal" class="form-label">
                Tanggal Pengukuran <span class="required-mark">*</span>
              </label>
              <input type="date" name="tanggal_ukur" id="inputTanggal" class="form-control-posyandu" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-sm-6">
                <label for="inputBerat" class="form-label">
                  Berat Badan <span class="required-mark">*</span>
                </label>
                <div class="input-suffix-group">
                  <input 
                    type="number" 
                    name="berat_kg" 
                    id="inputBerat" 
                    class="form-control-posyandu" 
                    placeholder="Contoh: 10.8" 
                    step="0.05" 
                    min="1" 
                    max="40" 
                    inputmode="decimal" 
                    required
                  >
                  <span class="input-suffix-addon">kg</span>
                </div>
                <div class="form-hint">Gunakan titik untuk desimal (contoh: 10.8)</div>
              </div>

              <div class="col-sm-6">
                <label for="inputTinggi" class="form-label">
                  Tinggi / Panjang Badan <span class="required-mark">*</span>
                </label>
                <div class="input-suffix-group">
                  <input 
                    type="number" 
                    name="tinggi_cm" 
                    id="inputTinggi" 
                    class="form-control-posyandu" 
                    placeholder="Contoh: 83.1" 
                    step="0.1" 
                    min="30" 
                    max="140" 
                    inputmode="decimal" 
                    required
                  >
                  <span class="input-suffix-addon">cm</span>
                </div>
                <div class="form-hint">Tidur (&lt; 2 thn) / berdiri (&ge; 2 thn)</div>
              </div>
            </div>

            <div class="mb-4">
              <label for="inputCatatan" class="form-label">Catatan Tambahan (Opsional)</label>
              <textarea 
                name="catatan" 
                id="inputCatatan" 
                class="form-control-posyandu" 
                rows="2" 
                placeholder="Kondisi kesehatan, nafsu makan, atau imunisasi terbaru..."
              ></textarea>
            </div>

            <button type="button" id="btnValidasiSimpan" class="btn-posyandu-primary w-100 btn-lg" onclick="validasiSebelumSimpan()">
              <i class="bi bi-check2-circle"></i> Simpan Pengukuran
            </button>
          </form>
        </div>

      </div>
    </main>

    <?php require_once __DIR__ . '/../../includes/mobile_nav.php'; ?>
  </div>
</div>

<div class="modal fade" id="modalAnomali" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-bottom bg-warning-soft">
        <h5 class="modal-title h6 text-warning-text fw-bold" id="anomaliJudul">
          <i class="bi bi-exclamation-triangle-fill me-1"></i> Periksa Kembali Angka Pengukuran
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <p class="font-sm text-ink-800 mb-0" id="anomaliDeskripsi">
          Terdeteksi anomali pada angka yang dimasukkan.
        </p>
      </div>
      <div class="modal-footer border-top bg-surface-soft rounded-bottom-4">
        <button type="button" class="btn btn-sm btn-posyandu-secondary" data-bs-dismiss="modal">
          Perbaiki Input
        </button>
        <button type="button" class="btn btn-sm btn-posyandu-primary" onclick="submitFormLangsung()">
          Tetap Simpan Data
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  const balitaData = <?= json_encode($balitaMap) ?>;

  function onPilihBalita(id) {
    const previewBox = document.getElementById('balitaInfoPreview');
    if (!id || !balitaData[id]) {
      previewBox.style.display = 'none';
      return;
    }

    const b = balitaData[id];
    document.getElementById('previewNama').textContent = b.nama;
    document.getElementById('previewUmur').textContent = b.umurTeks;
    document.getElementById('previewOrtuDusun').textContent = `Orang Tua: ${b.namaOrtu} · Dusun ${b.dusun}`;

    if (b.beratTerakhir !== null && b.tinggiTerakhir !== null) {
      document.getElementById('previewTerakhir').textContent = `${b.beratTerakhir} kg · ${b.tinggiTerakhir} cm (${b.tanggalTerakhir})`;
    } else {
      document.getElementById('previewTerakhir').textContent = 'Belum pernah diukur';
    }

    previewBox.style.display = 'block';
  }

  function validasiSebelumSimpan() {
    const id = document.getElementById('selectBalita').value;
    const bb = parseFloat(document.getElementById('inputBerat').value);
    const tb = parseFloat(document.getElementById('inputTinggi').value);

    if (!id) {
      alert('Pilih balita terlebih dahulu.');
      document.getElementById('selectBalita').focus();
      return;
    }

    if (!bb || !tb) {
      alert('Masukkan berat dan tinggi badan yang valid.');
      return;
    }

    const b = balitaData[id];
    if (b && b.beratTerakhir !== null) {
      if (b.tinggiTerakhir !== null && tb < (b.tinggiTerakhir - 0.5)) {
        const selisih = (b.tinggiTerakhir - tb).toFixed(1);
        tampilkanModalAnomali(
          'Periksa Kembali Tinggi Badan',
          `Angka tinggi badan (${tb} cm) tercatat ${selisih} cm lebih rendah dari penimbangan sebelumnya (${b.tinggiTerakhir} cm). Pastikan posisi balita saat diukur tegak atau terlentang dengan benar.`
        );
        return;
      }

      if ((b.beratTerakhir - bb) > 2.0) {
        const selisih = (b.beratTerakhir - bb).toFixed(1);
        tampilkanModalAnomali(
          'Periksa Kembali Berat Badan',
          `Terjadi penurunan drastis ${selisih} kg dari bulan sebelumnya (${b.beratTerakhir} kg). Pastikan jarum atau angka timbangan sudah stabil.`
        );
        return;
      }
    }

    submitFormLangsung();
  }

  function tampilkanModalAnomali(judul, deskripsi) {
    document.getElementById('anomaliJudul').innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1 text-warning"></i> ${judul}`;
    document.getElementById('anomaliDeskripsi').textContent = deskripsi;
    const modal = new bootstrap.Modal(document.getElementById('modalAnomali'));
    modal.show();
  }

  function submitFormLangsung() {
    document.getElementById('formPengukuran').submit();
  }

  document.addEventListener('DOMContentLoaded', () => {
    const curVal = document.getElementById('selectBalita').value;
    if (curVal) {
      onPilihBalita(curVal);
    }
  });
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
