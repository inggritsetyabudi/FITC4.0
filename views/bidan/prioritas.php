<?php
$pageTitle = $pageTitle ?? 'Daftar Balita Prioritas Penanganan — Posyandu Pintar';
$activePage = $activePage ?? 'prioritas';
$topbarTitle = $topbarTitle ?? 'Balita Butuh Intervensi & Konseling Bidan';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=bidan&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Prioritas</span>';
$daftarPrioritas = $balitaPrioritas ?? [];

$totalKasus = count($daftarPrioritas);
$totalMendesak = count(array_filter($daftarPrioritas, fn($b) => in_array($b['kategori_status'], ['prioritas', 'berisiko'])));
$totalPemantauan = count(array_filter($daftarPrioritas, fn($b) => $b['kategori_status'] === 'pemantauan'));

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
          <h1 class="h3 text-forest mb-1 fw-bold">Daftar Balita Prioritas Tinjauan</h1>
          <p class="text-muted-posyandu mb-0 font-sm">
            Daftar anak yang memerlukan evaluasi kurva tumbuh kembang, intervensi gizi, atau rujukan klinis Bidan Desa.
          </p>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100 d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background-color: #e3f2fd; color: #0d47a1; font-size: 1.5rem;">
              <i class="bi bi-people-fill"></i>
            </div>
            <div>
              <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold">Total Butuh Tinjauan</span>
              <strong class="text-forest" style="font-size: 1.6rem; line-height: 1;"><?= $totalKasus ?> <span style="font-size: 0.95rem; font-weight: 500;">Balita</span></strong>
            </div>
          </div>
        </div>

        <div class="col-md-4 col-sm-6">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100 d-flex flex-row align-items-center gap-3" style="border-left: 4px solid #e11d48 !important;">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background-color: #ffe4e6; color: #e11d48; font-size: 1.5rem;">
              <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div>
              <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold">Prioritas Bidan (Mendesak)</span>
              <strong class="text-forest" style="font-size: 1.6rem; line-height: 1; color: #e11d48 !important;"><?= $totalMendesak ?> <span style="font-size: 0.95rem; font-weight: 500;">Balita</span></strong>
            </div>
          </div>
        </div>

        <div class="col-md-4 col-sm-12">
          <div class="card p-3 rounded-4 border-line bg-surface shadow-xs h-100 d-flex flex-row align-items-center gap-3" style="border-left: 4px solid #f59e0b !important;">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background-color: #fef3c7; color: #d97706; font-size: 1.5rem;">
              <i class="bi bi-exclamation-circle-fill"></i>
            </div>
            <div>
              <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold">Perlu Pemantauan</span>
              <strong class="text-forest" style="font-size: 1.6rem; line-height: 1; color: #d97706 !important;"><?= $totalPemantauan ?> <span style="font-size: 0.95rem; font-weight: 500;">Balita</span></strong>
            </div>
          </div>
        </div>
      </div>

      <div class="card p-3 p-md-4 rounded-4 border-line shadow-xs bg-surface mb-4">
        <form method="GET" action="<?= base_url('index.php') ?>" id="formFilterPrioritas">
          <input type="hidden" name="controller" value="bidan">
          <input type="hidden" name="action" value="prioritas">
          <div class="row g-3 align-items-center">
            <div class="col-lg-4 col-md-6">
              <div class="input-group">
                <span class="input-group-text bg-surface-soft border-end-0 text-ink-500">
                  <i class="bi bi-search"></i>
                </span>
                <input 
                  type="text" 
                  id="cariBalitaInput" 
                  class="form-control border-start-0 ps-0" 
                  placeholder="Cari nama balita, ortu, atau NIK..."
                  oninput="filterBalitaPrioritas()"
                >
              </div>
            </div>

            <div class="col-lg-4 col-md-6">
              <div class="input-group">
                <span class="input-group-text bg-surface-soft border-end-0 text-primary">
                  <i class="bi bi-geo-alt-fill"></i>
                </span>
                <select name="dusun" class="form-select border-start-0" onchange="this.form.submit()">
                  <option value="semua">Semua Dusun Binaan</option>
                  <?php foreach ($daftarDusun as $d): ?>
                    <option value="<?= htmlspecialchars($d) ?>" <?= (($filterDusun ?? '') === $d) ? 'selected' : '' ?>>
                      Dusun <?= htmlspecialchars($d) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="col-lg-4 col-md-12 d-flex gap-2 justify-content-lg-end align-items-center">
              <div class="btn-group w-100" role="group" aria-label="Filter Status">
                <button type="button" class="btn btn-sm btn-posyandu-secondary active py-2" id="btnFilterSemua" onclick="setTabStatus('semua')">
                  Semua (<?= $totalKasus ?>)
                </button>
                <button type="button" class="btn btn-sm btn-posyandu-outline py-2" id="btnFilterPrioritas" onclick="setTabStatus('prioritas')">
                  Prioritas (<?= $totalMendesak ?>)
                </button>
                <button type="button" class="btn btn-sm btn-posyandu-outline py-2" id="btnFilterPemantauan" onclick="setTabStatus('pemantauan')">
                  Pantau (<?= $totalPemantauan ?>)
                </button>
              </div>

              <?php if (($filterDusun ?? '') !== '' && ($filterDusun ?? '') !== 'semua'): ?>
                <a href="<?= base_url('index.php?controller=bidan&action=prioritas') ?>" class="btn btn-sm btn-posyandu-outline px-3 text-nowrap py-2" title="Reset filter dusun">
                  <i class="bi bi-x-circle"></i>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </form>
      </div>

      <div class="d-flex flex-column gap-3" id="kontainerDaftarPrioritas">
        <?php if (empty($daftarPrioritas)): ?>
          <div class="card p-5 rounded-4 border-line bg-surface text-center shadow-xs">
            <div class="d-inline-flex align-items-center justify-content-center mx-auto rounded-circle mb-3" style="width: 70px; height: 70px; background-color: #e8f5e9; color: #2e7d32; font-size: 2.2rem;">
              <i class="bi bi-check-circle-fill"></i>
            </div>
            <h4 class="h5 text-forest fw-bold mb-1">Seluruh Pertumbuhan Balita Terkendali</h4>
            <p class="text-ink-500 mb-0 font-sm max-w-500 mx-auto">
              Tidak ada balita yang memerlukan tindakan prioritas atau pemantauan khusus pada filter dusun saat ini.
            </p>
          </div>
        <?php else: ?>
          <?php foreach ($daftarPrioritas as $b): 
            $umur = hitung_umur($b['tanggal_lahir']);
            $isPrioritas = in_array($b['kategori_status'], ['prioritas', 'berisiko']);
            $statusKey = $isPrioritas ? 'prioritas' : 'pemantauan';
            $searchString = strtolower($b['nama'] . ' ' . $b['nik'] . ' ' . $b['nama_ortu'] . ' ' . $b['dusun']);
          ?>
            <article class="card rounded-4 border-line shadow-xs bg-surface p-4 card-prioritas-item" 
                 data-status="<?= $statusKey ?>" 
                 data-search="<?= htmlspecialchars($searchString) ?>"
                 style="border-left: 6px solid <?= $isPrioritas ? '#e11d48' : '#f59e0b' ?> !important;">
              
              <div class="row g-4 align-items-center">
                <div class="col-xl-8 col-lg-7">
                  <div class="d-flex align-items-start gap-3">
                    <div class="avatar-prioritas-circle rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width: 54px; height: 54px; background-color: <?= $isPrioritas ? '#ffe4e6' : '#fef3c7' ?>; color: <?= $isPrioritas ? '#e11d48' : '#d97706' ?>; font-size: 1.35rem; font-weight: 800;">
                      <?= strtoupper(substr($b['nama'], 0, 1)) ?>
                    </div>

                    <div class="flex-grow-1 min-w-0">
                      <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        <h2 class="fw-bold text-forest mb-0" style="font-size: 1.25rem; line-height: 1.25;">
                          <?= htmlspecialchars($b['nama']) ?>
                        </h2>
                        
                        <?php if ($isPrioritas): ?>
                          <span class="badge px-3 py-1 rounded-pill bg-danger-subtle text-danger border border-danger-subtle fw-bold" style="font-size: 0.8rem;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Prioritas Bidan
                          </span>
                        <?php else: ?>
                          <span class="badge px-3 py-1 rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle fw-bold" style="font-size: 0.8rem;">
                            <i class="bi bi-exclamation-circle-fill me-1"></i> Perlu Pemantauan
                          </span>
                        <?php endif; ?>

                        <span class="badge bg-surface-soft text-ink-700 border font-xs fw-semibold px-2 py-1">
                          NIK: <?= htmlspecialchars($b['nik'] ?: '-') ?>
                        </span>
                      </div>

                      <div class="d-flex flex-wrap align-items-center gap-2 text-ink-700 mb-3" style="font-size: 0.925rem;">
                        <span class="d-inline-flex align-items-center gap-1">
                          <i class="bi bi-cake2-fill text-primary"></i> <strong><?= $umur['teks'] ?></strong>
                        </span>
                        <span class="text-ink-500">·</span>
                        <span class="d-inline-flex align-items-center gap-1">
                          <i class="bi bi-geo-alt-fill text-primary"></i> Dusun <?= htmlspecialchars($b['dusun']) ?>
                        </span>
                        <span class="text-ink-500">·</span>
                        <span class="d-inline-flex align-items-center gap-1">
                          <i class="bi bi-people-fill text-primary"></i> Orang Tua: <?= htmlspecialchars($b['nama_ortu']) ?>
                        </span>
                      </div>

                      <div class="row g-2 mb-3">
                        <div class="col-sm-4 col-6">
                          <div class="p-2 px-3 rounded-3 bg-surface-soft border-line text-center text-sm-start">
                            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold">Berat Badan</span>
                            <strong class="text-forest" style="font-size: 1.15rem;"><?= number_format((float)$b['berat_kg'], 2) ?> kg</strong>
                          </div>
                        </div>
                        <div class="col-sm-4 col-6">
                          <div class="p-2 px-3 rounded-3 bg-surface-soft border-line text-center text-sm-start">
                            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold">Tinggi Badan</span>
                            <strong class="text-forest" style="font-size: 1.15rem;"><?= number_format((float)$b['tinggi_cm'], 1) ?> cm</strong>
                          </div>
                        </div>
                        <div class="col-sm-4 col-12">
                          <div class="p-2 px-3 rounded-3 bg-surface-soft border-line text-center text-sm-start">
                            <span class="text-ink-500 d-block font-xs text-uppercase fw-semibold">Tgl Penimbangan</span>
                            <strong class="text-forest" style="font-size: 1rem;"><?= format_tanggal_id($b['tanggal_ukur'], true) ?></strong>
                          </div>
                        </div>
                      </div>

                      <div class="p-3 rounded-3 border-line" style="background-color: <?= $isPrioritas ? 'rgba(239, 68, 68, 0.05)' : 'rgba(245, 158, 11, 0.05)' ?>;">
                        <div class="d-flex align-items-start gap-2">
                          <i class="bi <?= $isPrioritas ? 'bi-exclamation-octagon-fill text-danger' : 'bi-info-circle-fill text-warning' ?> fs-5 mt-n1 flex-shrink-0"></i>
                          <div class="font-sm" style="line-height: 1.6;">
                            <strong class="text-forest d-block mb-1" style="font-size: 0.95rem;">Catatan Bidan &amp; Indikasi:</strong>
                            <span class="text-ink-800">
                              <?= htmlspecialchars($b['catatan'] ?: ($isPrioritas ? 'Kenaikan berat badan tidak adekuat selama 2 bulan berturut-turut. Segera lakukan konseling gizi dan evaluasi bidan.' : 'Pertumbuhan anak perlu ditinjau kembali pada kunjungan berikutnya.')) ?>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xl-4 col-lg-5 d-flex flex-column justify-content-center gap-2 ps-lg-4 border-start-lg">
                  <a href="<?= base_url('index.php?controller=balita&action=detail&id=' . $b['id']) ?>" class="btn-posyandu-primary py-2 px-3 text-center fw-bold d-flex align-items-center justify-content-center gap-2 shadow-xs" style="font-size: 0.95rem;">
                    <i class="bi bi-graph-up-arrow"></i> Tinjau Riwayat &amp; Grafik
                  </a>

                  <a href="<?= base_url('index.php?controller=pengukuran&action=index&anak_id=' . $b['id']) ?>" class="btn-posyandu-secondary py-2 px-3 text-center d-flex align-items-center justify-content-center gap-2 font-sm">
                    <i class="bi bi-clipboard2-plus"></i> Catat Timbangan Baru
                  </a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>

        <div id="pencarianKosongMsg" class="card p-5 rounded-4 border-line bg-surface text-center shadow-xs d-none">
          <div class="d-inline-flex align-items-center justify-content-center mx-auto rounded-circle mb-3" style="width: 60px; height: 60px; background-color: #f1f5f9; color: #64748b; font-size: 1.8rem;">
            <i class="bi bi-search"></i>
          </div>
          <h4 class="h5 text-forest fw-bold mb-1">Tidak Ditemukan Balita</h4>
          <p class="text-ink-500 mb-0 font-sm">
            Tidak ada balita prioritas yang sesuai dengan kata kunci pencarian Anda.
          </p>
        </div>
      </div>
    </main>

    <?php require_once __DIR__ . '/../../includes/mobile_nav.php'; ?>
  </div>
</div>

<script>
  let activeStatusTab = 'semua';

  function setTabStatus(status) {
    activeStatusTab = status;
    document.getElementById('btnFilterSemua').classList.toggle('btn-posyandu-secondary', status === 'semua');
    document.getElementById('btnFilterSemua').classList.toggle('btn-posyandu-outline', status !== 'semua');
    document.getElementById('btnFilterSemua').classList.toggle('active', status === 'semua');

    document.getElementById('btnFilterPrioritas').classList.toggle('btn-posyandu-secondary', status === 'prioritas');
    document.getElementById('btnFilterPrioritas').classList.toggle('btn-posyandu-outline', status !== 'prioritas');
    document.getElementById('btnFilterPrioritas').classList.toggle('active', status === 'prioritas');

    document.getElementById('btnFilterPemantauan').classList.toggle('btn-posyandu-secondary', status === 'pemantauan');
    document.getElementById('btnFilterPemantauan').classList.toggle('btn-posyandu-outline', status !== 'pemantauan');
    document.getElementById('btnFilterPemantauan').classList.toggle('active', status === 'pemantauan');

    filterBalitaPrioritas();
  }

  function filterBalitaPrioritas() {
    const query = (document.getElementById('cariBalitaInput')?.value || '').toLowerCase().trim();
    const items = document.querySelectorAll('.card-prioritas-item');
    let visibleCount = 0;

    items.forEach(item => {
      const searchData = item.getAttribute('data-search') || '';
      const statusData = item.getAttribute('data-status') || '';

      const matchSearch = query === '' || searchData.includes(query);
      const matchStatus = activeStatusTab === 'semua' || statusData === activeStatusTab;

      if (matchSearch && matchStatus) {
        item.classList.remove('d-none');
        visibleCount++;
      } else {
        item.classList.add('d-none');
      }
    });

    const kosongMsg = document.getElementById('pencarianKosongMsg');
    if (kosongMsg) {
      if (visibleCount === 0 && items.length > 0) {
        kosongMsg.classList.remove('d-none');
      } else {
        kosongMsg.classList.add('d-none');
      }
    }
  }
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
