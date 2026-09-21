<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/landing_nav.php';
?>
<main>
  <section class="hero-section" id="beranda">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <h1 class="hero-title">
            Tumbuh mereka berubah setiap bulan. Catatannya jangan sampai tertinggal.
          </h1>

          <p class="hero-desc">
            Posyandu Pintar membantu kader mencatat hasil timbang bulanan
            dan membantu bidan memantau perkembangan balita dengan lebih
            rapi.
          </p>

          <div class="hero-cta-group">
            <a href="<?= base_url('index.php?controller=auth&action=showLogin') ?>" class="btn-posyandu-primary btn-lg">
              <i class="bi bi-box-arrow-in-right"></i>
              Masuk ke Posyandu Pintar
            </a>

            <a href="#tentang" class="btn-posyandu-outline btn-lg">
              Pelajari Tentang Kami
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-4 bg-surface-soft border-bottom">
    <div class="container">
      <div class="row g-3">
        <div class="col-md-6">
          <div class="hero-subcard-banner d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-3 p-3 fs-3">
              <i class="bi bi-phone-vibrate"></i>
            </div>
            <div>
              <h3 class="fw-bold text-forest mb-1" style="font-size: 1.125rem;">Catat Timbangan Langsung di HP</h3>
              <p class="text-ink-700 mb-0" style="font-size: 0.95rem; line-height: 1.6;">Kader memasukkan angka timbangan balita secara praktis. Umur dan selisih berat bulan lalu langsung terhitung otomatis.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="hero-subcard-banner d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-3 p-3 fs-3">
              <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div>
              <h3 class="fw-bold text-forest mb-1" style="font-size: 1.125rem;">Pantau Grafik &amp; Risiko Gizi</h3>
              <p class="text-ink-700 mb-0" style="font-size: 0.95rem; line-height: 1.6;">Kenaikan berat badan dipantau berkala, memudahkan bidan desa mendeteksi balita yang membutuhkan tambahan asupan gizi.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 bg-canvas" id="tentang">
    <div class="container">
      <div class="text-center max-w-700 mx-auto mb-5">
        <h2 class="h1 fw-bold text-forest mb-2">Tentang Posyandu Pintar</h2>
        <p class="text-ink-700" style="font-size: 1.05rem; line-height: 1.6;">
          Sistem informasi pencatatan tumbuh kembang balita yang dirancang untuk mendukung kerja kader dan bidan desa di lapangan.
        </p>
      </div>

      <div class="row g-4">
        <div class="col-lg-6">
          <div class="feature-card card-yellow">
            <div class="feature-card-icon">
              <i class="bi bi-journal-check"></i>
            </div>
            <h3 class="h4 fw-bold text-forest mb-2">Pencatatan Rapi Tanpa Buku Rusak</h3>
            <p class="card-text mb-0" style="font-size: 1rem; line-height: 1.7;">
              Riwayat pengukuran balita tersimpan terpusat di sistem desa. Kader tidak lagi khawatir buku catatan fisik tercecer, robek, atau catatan bulan lalu hilang saat pergantian tugas posyandu.
            </p>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="feature-card card-blue" style="background-color: #f0f7ff; border: 1.5px solid #bbdefb;">
            <div class="feature-card-icon" style="background-color: #e3f2fd; color: #0d47a1;">
              <i class="bi bi-speedometer2"></i>
            </div>
            <h3 class="h4 fw-bold text-forest mb-2">Hasil Timbangan Langsung Terplot</h3>
            <p class="card-text mb-0" style="font-size: 1rem; line-height: 1.7;">
              Begitu kader memasukkan angka berat dan tinggi badan, grafik kurva KMS anak langsung terbarui saat itu juga tanpa perlu menghitung usia anak berbulan-bulan secara manual.
            </p>
          </div>
        </div>

        <div class="col-12">
          <div class="feature-card card-purple">
            <div class="feature-card-icon">
              <i class="bi bi-people-fill"></i>
            </div>
            <h3 class="h4 fw-bold text-forest mb-2">Koordinasi Cepat Kader dan Bidan Desa</h3>
            <p class="card-text mb-0" style="font-size: 1rem; line-height: 1.7;">
              Bidan desa langsung menerima rekap anak yang memerlukan penanganan khusus pada hari yang sama. Tindakan intervensi gizi, vitamin, dan rujukan ke puskesmas dapat dilakukan tanpa menanti laporan rekap akhir bulan.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 bg-surface border-top border-bottom" id="layanan">
    <div class="container">
      <div class="text-center max-w-700 mx-auto mb-5">
        <h2 class="h1 fw-bold text-forest mb-2">Layanan Posyandu</h2>
        <p class="text-ink-700" style="font-size: 1.05rem; line-height: 1.6;">
          Rangkaian pelayanan kesehatan dan pemantauan gizi balita yang diselenggarakan setiap bulan di desa.
        </p>
      </div>

      <div class="kategori-pills-row mb-5">
        <a href="#layanan" class="kategori-pill-card text-decoration-none">
          <div class="kategori-pill-icon" style="background-color: #e3f2fd; color: #0d47a1;">
            <i class="bi bi-person-arms-up"></i>
          </div>
          <span class="fw-bold text-forest mb-1" style="font-size: 0.95rem;">Balita 0–5 Tahun</span>
          <span class="text-ink-500" style="font-size: 0.85rem;">Antropometri Rutin</span>
        </a>

        <a href="#layanan" class="kategori-pill-card text-decoration-none">
          <div class="kategori-pill-icon" style="background-color: #e0f2fe; color: #0284c7;">
            <i class="bi bi-heart-pulse"></i>
          </div>
          <span class="fw-bold text-forest mb-1" style="font-size: 0.95rem;">Ibu &amp; Balita</span>
          <span class="text-ink-500" style="font-size: 0.85rem;">Konseling Menyusui</span>
        </a>

        <a href="#kalkulator" class="kategori-pill-card text-decoration-none">
          <div class="kategori-pill-icon" style="background-color: #ede9fe; color: #6d28d9;">
            <i class="bi bi-speedometer2"></i>
          </div>
          <span class="fw-bold text-forest mb-1" style="font-size: 0.95rem;">KMS Digital</span>
          <span class="text-ink-500" style="font-size: 0.85rem;">Grafik Timbangan</span>
        </a>

        <a href="#layanan" class="kategori-pill-card text-decoration-none">
          <div class="kategori-pill-icon" style="background-color: #fce7f3; color: #db2777;">
            <i class="bi bi-shield-plus"></i>
          </div>
          <span class="fw-bold text-forest mb-1" style="font-size: 0.95rem;">Imunisasi &amp; Vitamin</span>
          <span class="text-ink-500" style="font-size: 0.85rem;">Kapsul Vitamin A</span>
        </a>

        <a href="#layanan" class="kategori-pill-card text-decoration-none">
          <div class="kategori-pill-icon" style="background-color: #fef3c7; color: #d97706;">
            <i class="bi bi-egg-fried"></i>
          </div>
          <span class="fw-bold text-forest mb-1" style="font-size: 0.95rem;">Makanan Tambahan</span>
          <span class="text-ink-500" style="font-size: 0.85rem;">Menu Protein Hewani</span>
        </a>

        <a href="#layanan" class="kategori-pill-card text-decoration-none">
          <div class="kategori-pill-icon" style="background-color: #e0f2f1; color: #00695c;">
            <i class="bi bi-hospital"></i>
          </div>
          <span class="fw-bold text-forest mb-1" style="font-size: 0.95rem;">Bidan Desa</span>
          <span class="text-ink-500" style="font-size: 0.85rem;">Pemeriksaan &amp; Rujukan</span>
        </a>
      </div>

      <div class="pt-5 border-top" id="jadwal">
        <div class="text-center max-w-700 mx-auto mb-5">
          <span class="badge rounded-pill bg-primary-subtle text-primary border px-3 py-2 fw-bold mb-2" style="font-size: 0.9rem;">Agenda Terpadu Desa</span>
          <h3 class="h1 text-forest fw-bold mb-3" style="font-size: 2.2rem;">Jadwal Pelayanan Posyandu</h3>
          <p class="text-ink-700" style="font-size: 1.15rem; line-height: 1.7;">
            Informasi resmi jadwal penimbangan dan pemantauan tumbuh kembang balita yang terhubung langsung dengan sistem desa.
          </p>
        </div>

        <div class="row g-4 mb-5">
          <?php if (empty($jadwalPosyandu)): ?>
            <div class="col-12 text-center py-4">
              <div class="p-4 rounded-4 border-line bg-surface max-w-600 mx-auto shadow-xs">
                <i class="bi bi-calendar2-check text-primary fs-1 mb-2 d-block"></i>
                <h4 class="h5 fw-bold text-forest mb-1">Jadwal Sedang Disiapkan</h4>
                <p class="text-muted-posyandu mb-0 font-sm">Jadwal kegiatan posyandu periode berikutnya akan segera diumumkan oleh bidan dan kader desa.</p>
              </div>
            </div>
          <?php else: ?>
            <?php foreach ($jadwalPosyandu as $j): ?>
              <?php
              $statusBadge = match($j['status'] ?? 'rencana') {
                'selesai' => '<span class="badge bg-success font-xs px-2 py-1"><i class="bi bi-check-circle me-1"></i>Selesai</span>',
                default   => '<span class="badge bg-primary font-xs px-2 py-1"><i class="bi bi-clock me-1"></i>Akan Datang</span>'
              };
              ?>
              <div class="col-lg-6">
                <div class="card p-4 rounded-4 border-line h-100 shadow-xs bg-surface d-flex flex-column justify-content-between transition hover-shadow" style="border-left: 4px solid #1565C0 !important;">
                  <div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                      <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill bg-primary-subtle text-primary border px-3 py-1 font-xs fw-bold">
                          Dusun <?= htmlspecialchars($j['dusun']) ?>
                        </span>
                        <span class="text-muted-posyandu font-xs fw-semibold">
                          <?= htmlspecialchars($j['jenis_layanan'] ?: 'Layanan Posyandu') ?>
                        </span>
                      </div>
                      <?= $statusBadge ?>
                    </div>

                    <h4 class="fw-bold text-forest mb-2" style="font-size: 1.25rem; line-height: 1.4;">
                      <?= htmlspecialchars($j['judul_agenda']) ?>
                    </h4>

                    <div class="d-flex flex-column gap-2 my-3 font-sm">
                      <div class="d-flex align-items-center gap-2 text-forest">
                        <i class="bi bi-calendar-event text-primary fs-5" style="width: 24px;"></i>
                        <strong><?= format_tanggal_id($j['tanggal_kegiatan']) ?></strong>
                        <span class="text-muted-posyandu">· <?= substr($j['jam_mulai'], 0, 5) ?> - <?= substr($j['jam_selesai'], 0, 5) ?> WIB</span>
                      </div>
                      <div class="d-flex align-items-start gap-2 text-ink-700">
                        <i class="bi bi-geo-alt-fill text-primary fs-5" style="width: 24px;"></i>
                        <span><?= htmlspecialchars($j['lokasi']) ?></span>
                      </div>
                      <div class="d-flex align-items-center gap-2 text-ink-700">
                        <i class="bi bi-person-badge text-primary fs-5" style="width: 24px;"></i>
                        <span>Petugas: <strong><?= htmlspecialchars($j['penanggung_jawab']) ?></strong></span>
                      </div>
                    </div>

                    <?php if (!empty($j['keterangan'])): ?>
                      <p class="text-ink-500 font-sm mb-0 p-2 rounded-3 bg-surface-soft border-line">
                        <i class="bi bi-info-circle me-1 text-primary"></i> <?= htmlspecialchars($j['keterangan']) ?>
                      </p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <?php if (!empty($posLayanan)): ?>
          <div class="p-4 p-md-5 rounded-4 border-line bg-surface shadow-xs">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 pb-3 border-bottom">
              <div>
                <h4 class="h5 fw-bold text-forest mb-1"><i class="bi bi-hospital text-primary me-2"></i>Daftar Pos Pelayanan Tetap Dusun</h4>
                <p class="text-muted-posyandu font-sm mb-0">Posyandu binaan desa yang beroperasi rutin setiap bulan bagi warga sekitar</p>
              </div>
              <span class="badge bg-light text-forest border px-3 py-2 font-xs fw-bold"><?= count($posLayanan) ?> Pos Binaan</span>
            </div>

            <div class="row g-3">
              <?php foreach ($posLayanan as $pos): ?>
                <div class="col-md-6 col-lg-3">
                  <div class="p-3 rounded-3 border-line bg-surface-soft h-100 d-flex flex-column justify-content-between">
                    <div>
                      <div class="d-flex align-items-center justify-content-between mb-2">
                        <strong class="text-forest font-sm"><?= htmlspecialchars($pos['nama_pos']) ?></strong>
                        <span class="badge bg-success-subtle text-success font-xs border">Aktif</span>
                      </div>
                      <div class="font-xs text-primary fw-semibold mb-1">
                        <i class="bi bi-geo-alt me-1"></i> Dusun <?= htmlspecialchars($pos['dusun']) ?>
                      </div>
                      <p class="font-xs text-muted-posyandu mb-2">
                        <?= htmlspecialchars($pos['lokasi_alamat']) ?>
                      </p>
                    </div>
                    <div class="pt-2 border-top font-xs text-forest">
                      <div class="mb-1"><i class="bi bi-clock me-1 text-primary"></i> <?= htmlspecialchars($pos['jadwal_rutin']) ?></div>
                      <div><i class="bi bi-person me-1 text-primary"></i> PJ: <strong><?= htmlspecialchars($pos['kader_utama']) ?></strong></div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <div class="pt-5 mt-5 border-top" id="kalkulator">
        <div class="text-center max-w-700 mx-auto mb-5">
          <span class="badge rounded-pill bg-primary-subtle text-primary border px-3 py-2 fw-bold mb-2" style="font-size: 0.9rem;">Skrining Mandiri</span>
          <h2 class="h1 text-forest fw-bold mb-3" style="font-size: 2.2rem;">Kalkulator KMS Mandiri Balita</h2>
          <p class="text-ink-700" style="font-size: 1.15rem; line-height: 1.7;">
            Periksa estimasi kurva tumbuh kembang buah hati Anda secara mandiri di rumah berdasarkan standar antropometri Posyandu.
          </p>
        </div>

        <div class="row g-4 align-items-stretch">
          <div class="col-lg-6">
            <div class="card p-4 p-md-5 rounded-4 border-line shadow-xs bg-surface h-100 d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                  <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #e3f2fd; color: #0d47a1; font-size: 1.5rem;">
                    <i class="bi bi-calculator-fill"></i>
                  </div>
                  <div>
                    <h4 class="fw-bold text-forest mb-0" style="font-size: 1.3rem;">Parameter Pengukuran Anak</h4>
                    <span class="text-ink-500" style="font-size: 0.95rem;">Masukkan data fisik balita sesuai buku KIA</span>
                  </div>
                </div>

                <form id="formKmsMandiri" onsubmit="event.preventDefault(); hitungKmsMandiri();">
                  <div class="mb-4">
                    <label class="form-label fw-bold text-ink-900 mb-2" style="font-size: 1rem;">Jenis Kelamin Balita</label>
                    <div class="d-flex gap-3">
                      <label class="btn btn-posyandu-outline flex-fill py-3 text-center active" id="labelJkL" style="font-size: 1.05rem; font-weight: 600;">
                        <input type="radio" name="kmsJk" value="L" checked class="d-none" onchange="updateJkBtn(this)">
                        <i class="bi bi-gender-male me-2"></i> Laki-laki
                      </label>
                      <label class="btn btn-posyandu-outline flex-fill py-3 text-center" id="labelJkP" style="font-size: 1.05rem; font-weight: 600;">
                        <input type="radio" name="kmsJk" value="P" class="d-none" onchange="updateJkBtn(this)">
                        <i class="bi bi-gender-female me-2"></i> Perempuan
                      </label>
                    </div>
                  </div>

                  <div class="mb-4">
                    <label class="form-label fw-bold text-ink-900 mb-2" style="font-size: 1rem;">Usia Anak (Bulan)</label>
                    <div class="input-suffix-group">
                      <input type="number" id="kmsUsia" class="form-control-posyandu" min="0" max="60" value="24" required placeholder="Contoh: 24" style="font-size: 1.1rem; padding: 0.75rem 1rem;" oninput="hitungKmsMandiri()">
                      <span class="input-suffix-addon fw-semibold" style="font-size: 1rem;">bulan</span>
                    </div>
                  </div>

                  <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                      <label class="form-label fw-bold text-ink-900 mb-2" style="font-size: 1rem;">Berat Badan (kg)</label>
                      <div class="input-suffix-group">
                        <input type="number" id="kmsBerat" step="0.1" class="form-control-posyandu" min="2" max="35" value="11.5" required placeholder="Contoh: 11.5" style="font-size: 1.1rem; padding: 0.75rem 1rem;" oninput="hitungKmsMandiri()">
                        <span class="input-suffix-addon fw-semibold" style="font-size: 1rem;">kg</span>
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <label class="form-label fw-bold text-ink-900 mb-2" style="font-size: 1rem;">Panjang / Tinggi (cm)</label>
                      <div class="input-suffix-group">
                        <input type="number" id="kmsTinggi" step="0.1" class="form-control-posyandu" min="45" max="130" value="86.0" required placeholder="Contoh: 86.0" style="font-size: 1.1rem; padding: 0.75rem 1rem;" oninput="hitungKmsMandiri()">
                        <span class="input-suffix-addon fw-semibold" style="font-size: 1rem;">cm</span>
                      </div>
                    </div>
                  </div>

                  <button type="submit" class="btn-posyandu-primary w-100 py-3" style="font-size: 1.15rem; font-weight: 700;">
                    <i class="bi bi-graph-up-arrow me-2"></i> Hitung Estimasi Status Tumbuh Kembang
                  </button>
                </form>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="card p-4 p-md-5 rounded-4 border-line shadow-xs bg-surface h-100 d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                  <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #e3f2fd; color: #0d47a1; font-size: 1.5rem;">
                    <i class="bi bi-clipboard2-pulse-fill"></i>
                  </div>
                  <div>
                    <h4 class="fw-bold text-forest mb-0" style="font-size: 1.3rem;">Hasil Pemantauan KMS</h4>
                    <span class="text-ink-500" style="font-size: 0.95rem;">Standar Kurva Kemenkes RI</span>
                  </div>
                </div>

                <div id="hasilKmsMandiri" class="d-block">
                  <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <span class="fw-bold text-ink-900" style="font-size: 1.1rem;">Status Evaluasi:</span>
                    <span id="badgeHasilKms" class="status-badge status-badge-normal" style="font-size: 1.05rem; font-weight: 700; padding: 8px 18px;">
                      <i class="bi bi-check-circle-fill me-1"></i> Normal &amp; Ideal
                    </span>
                  </div>

                  <div class="d-flex align-items-center justify-content-between py-3 px-4 rounded-3 bg-surface-soft border-line my-3">
                    <div class="text-center flex-fill">
                      <span class="text-ink-500 d-block" style="font-size: 0.85rem;">Usia</span>
                      <strong id="rekapUsia" class="text-forest" style="font-size: 1.25rem;">24 bln</strong>
                    </div>
                    <div class="border-start ps-3 text-center flex-fill">
                      <span class="text-ink-500 d-block" style="font-size: 0.85rem;">Berat Badan</span>
                      <strong id="rekapBb" class="text-forest" style="font-size: 1.25rem;">11.5 kg</strong>
                    </div>
                    <div class="border-start ps-3 text-center flex-fill">
                      <span class="text-ink-500 d-block" style="font-size: 0.85rem;">Tinggi / Panjang</span>
                      <strong id="rekapTb" class="text-forest" style="font-size: 1.25rem;">86.0 cm</strong>
                    </div>
                  </div>

                  <p id="teksHasilKms" class="text-ink-700 mb-3" style="font-size: 1.05rem; line-height: 1.7;">
                    Berat 11.5 kg dan tinggi 86.0 cm berada dalam kisaran pertumbuhan yang wajar untuk usia 24 bulan.
                  </p>

                  <div class="p-3 rounded-3 bg-surface-soft border-line mb-4">
                    <strong class="text-forest d-block mb-1" style="font-size: 1rem;"><i class="bi bi-lightbulb-fill text-warning me-2"></i>Saran Tindakan:</strong>
                    <span id="rekomendasiHasilKms" class="text-ink-700" style="font-size: 1rem; line-height: 1.65;">
                      Pertahankan pemberian makanan bergizi seimbang (protein hewani, sayur, buah) dan timbang rutin di Posyandu setiap bulan.
                    </span>
                  </div>
                </div>
              </div>

              <div class="p-3 rounded-3 border bg-light text-ink-700 mt-3" style="font-size: 0.95rem; line-height: 1.65;">
                <i class="bi bi-info-circle-fill text-primary me-2"></i>
                <strong>Catatan Bidan:</strong> Skrining awal mandiri ini merupakan estimasi keluarga di rumah. Pemeriksaan resmi dan evaluasi kurva KMS dipastikan oleh Bidan Desa saat hari buka Posyandu.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 bg-surface border-bottom" id="blog">
    <div class="container">
      <div class="text-center max-w-700 mx-auto mb-5">
        <h2 class="h1 fw-bold text-forest mb-2">Blog &amp; Panduan Posyandu</h2>
        <p class="text-ink-700" style="font-size: 1.05rem; line-height: 1.6;">
          Panduan praktis seputar menu MPASI gizi seimbang, pola asuh anak, dan tips perawatan balita dari tenaga kesehatan desa.
        </p>
      </div>

      <div class="row g-4">
        <div class="col-lg-8">
          <a href="<?= base_url('index.php?controller=berita&action=detail&id=art_01') ?>" class="featured-image-container">
            <img src="<?= base_url('assets/images/hero_timbang.jpg') ?>" alt="Penimbangan Balita Bulanan">
            <div class="featured-card-body">
              <span class="berita-category-tag mb-2" style="font-size: 0.8rem;">Panduan Posyandu</span>
              <h3 class="mb-2" style="font-size: 1.4rem; line-height: 1.35;">Kenapa penimbangan balita perlu dilakukan secara rutin setiap bulan?</h3>
              <div class="featured-meta mb-2" style="font-size: 0.875rem;">
                <i class="bi bi-calendar3 me-1"></i> 24 Agustus 2026
                <span class="mx-2">·</span>
                <i class="bi bi-person me-1"></i> Bidan Siti Nurhaliza
              </div>
              <p class="mb-3 text-white-50 d-none d-md-block" style="font-size: 0.95rem; line-height: 1.65;">
                Penimbangan rutin adalah kunci utama mendeteksi perlambatan pertumbuhan sebelum anak mengalami risiko gizi kurang atau stunting. Catatan grafik yang berkesinambungan memberi kepastian bagi orang tua.
              </p>
              <span class="read-more text-white fw-bold" style="font-size: 0.95rem;">
                Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
              </span>
            </div>
          </a>
        </div>

        <div class="col-lg-4 d-flex flex-column justify-content-between">
          <div>
            <h6 class="text-uppercase fw-bold text-forest mb-3 letter-spacing-1" style="font-size: 0.85rem;">
              <i class="bi bi-star-fill text-warning me-1"></i> ARTIKEL PILIHAN
            </h6>

            <a href="<?= base_url('index.php?controller=berita&action=detail&id=art_02') ?>" class="side-article">
              <span class="text-primary fw-semibold d-block mb-1" style="font-size: 0.85rem;">Gizi Balita</span>
              <h6 class="fw-bold text-forest mb-1" style="font-size: 0.975rem; line-height: 1.45;">Protein hewani dan perannya dalam masa pertumbuhan</h6>
              <div class="d-flex align-items-center justify-content-between text-ink-500 mt-2" style="font-size: 0.85rem;">
                <span><i class="bi bi-calendar3 me-1"></i> 19 Agustus 2026</span>
                <span class="read-more fw-bold">Baca &rarr;</span>
              </div>
            </a>

            <a href="<?= base_url('index.php?controller=berita&action=detail&id=art_03') ?>" class="side-article">
              <span class="text-primary fw-semibold d-block mb-1" style="font-size: 0.85rem;">Tips Keluarga</span>
              <h6 class="fw-bold text-forest mb-1" style="font-size: 0.975rem; line-height: 1.45;">Daftar persiapan sederhana orang tua sebelum berangkat ke Posyandu</h6>
              <div class="d-flex align-items-center justify-content-between text-ink-500 mt-2" style="font-size: 0.85rem;">
                <span><i class="bi bi-calendar3 me-1"></i> 12 Agustus 2026</span>
                <span class="read-more fw-bold">Baca &rarr;</span>
              </div>
            </a>

            <a href="<?= base_url('index.php?controller=berita&action=detail&id=art_01') ?>" class="side-article">
              <span class="text-primary fw-semibold d-block mb-1" style="font-size: 0.85rem;">Literasi KMS</span>
              <h6 class="fw-bold text-forest mb-1" style="font-size: 0.975rem; line-height: 1.45;">Memahami arti garis pertumbuhan Hijau, Kuning, dan Merah di KMS</h6>
              <div class="d-flex align-items-center justify-content-between text-ink-500 mt-2" style="font-size: 0.85rem;">
                <span><i class="bi bi-calendar3 me-1"></i> 05 Agustus 2026</span>
                <span class="read-more fw-bold">Baca &rarr;</span>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 bg-canvas" id="berita">
    <div class="container">
      <div class="text-center max-w-700 mx-auto mb-5">
        <h2 class="h1 fw-bold text-forest mb-2">Berita &amp; Kegiatan Posyandu</h2>
        <p class="text-ink-700" style="font-size: 1.05rem; line-height: 1.6;">
          Pengumuman jadwal posyandu, kegiatan penimbangan balita, dan informasi gizi anak Desa Sukatani.
        </p>
      </div>

      <div class="row g-4">
        <?php if (!empty($daftarBerita)): ?>
          <?php foreach ($daftarBerita as $b): ?>
            <div class="col-lg-4 col-md-6">
              <div class="card rounded-4 border-line h-100 overflow-hidden shadow-xs bg-surface d-flex flex-column">
                <div style="height: 200px; overflow: hidden; background: #e2e8f0;">
                  <img
                    src="<?= base_url($b['gambar'] ?: 'assets/images/berita_vitamin_a.jpg') ?>"
                    alt="<?= htmlspecialchars($b['judul']) ?>"
                    class="w-100 h-100 object-fit-cover transition-transform"
                  />
                </div>
                <div class="p-4 d-flex flex-column flex-grow-1">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-primary fw-semibold" style="font-size: 0.85rem;"><?= htmlspecialchars($b['kategori']) ?></span>
                    <span class="text-ink-500" style="font-size: 0.85rem;">
                      <i class="bi bi-calendar3 me-1"></i><?= format_tanggal_id($b['published_at']) ?>
                    </span>
                  </div>
                  <h3 class="h5 fw-bold text-forest mb-2">
                    <a href="<?= base_url('index.php?controller=berita&action=detail&id=' . $b['id']) ?>" class="text-decoration-none text-forest">
                      <?= htmlspecialchars($b['judul']) ?>
                    </a>
                  </h3>
                  <p class="text-ink-700 mb-3 flex-grow-1" style="font-size: 0.95rem; line-height: 1.65;">
                    <?= htmlspecialchars($b['ringkasan']) ?>
                  </p>
                  <a href="<?= base_url('index.php?controller=berita&action=detail&id=' . $b['id']) ?>" class="read-more mt-auto" style="font-size: 0.925rem; font-weight: 700;">
                    Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center py-5 text-ink-500">
            Belum ada berita posyandu yang dipublikasikan.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<footer class="w-100 portal-footer" style="background-color: #0d47a1 !important; color: #ffffff !important;">
  <div class="footer-main" style="background-color: #0d47a1 !important; color: #ffffff !important; padding: 55px 0 25px;">
    <div class="container">
      <div class="row pb-4 g-4">
        <div class="col-lg-4 col-md-12 mb-3">
          <a href="<?= base_url('index.php') ?>" class="footer-brand text-decoration-none d-inline-flex align-items-center gap-2 mb-3">
            <img src="<?= base_url('assets/images/logo sementara.png') ?>" alt="Logo Posyandu Pintar" class="footer-logo-img" style="width: 44px; height: 44px; object-fit: contain;" />
            <span class="footer-brand-text text-white fw-bold" style="font-size: 1.45rem;">Posyandu Pintar</span>
          </a>
          <p class="footer-desc text-white-50 mt-2" style="font-size: 0.95rem; line-height: 1.7;">
            Sistem informasi posyandu untuk pemantauan tumbuh kembang balita dan percepatan penurunan risiko stunting di tingkat desa. Menghubungkan kader, bidan desa, dan para orang tua balita.
          </p>
          <div class="footer-social d-flex gap-2 mt-3">
            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="social-icon" aria-label="WhatsApp" style="background: rgba(255,255,255,0.15); color: #ffffff; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none;"><i class="bi bi-whatsapp"></i></a>
            <a href="https://instagram.com" target="_blank" rel="noopener" class="social-icon" aria-label="Instagram" style="background: rgba(255,255,255,0.15); color: #ffffff; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none;"><i class="bi bi-instagram"></i></a>
            <a href="https://facebook.com" target="_blank" rel="noopener" class="social-icon" aria-label="Facebook" style="background: rgba(255,255,255,0.15); color: #ffffff; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none;"><i class="bi bi-facebook"></i></a>
            <a href="https://youtube.com" target="_blank" rel="noopener" class="social-icon" aria-label="YouTube" style="background: rgba(255,255,255,0.15); color: #ffffff; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none;"><i class="bi bi-youtube"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 col-6 mb-3">
          <h6 class="footer-link-title text-white fw-bold text-uppercase mb-3" style="font-size: 0.9rem; letter-spacing: 0.06em;">Menu</h6>
          <ul class="footer-link-list list-unstyled">
            <li class="mb-2"><a href="<?= base_url('index.php#beranda') ?>" class="text-white-50 text-decoration-none" style="font-size: 0.95rem;">Beranda</a></li>
            <li class="mb-2"><a href="<?= base_url('index.php#tentang') ?>" class="text-white-50 text-decoration-none" style="font-size: 0.95rem;">Tentang Kami</a></li>
            <li class="mb-2"><a href="<?= base_url('index.php#layanan') ?>" class="text-white-50 text-decoration-none" style="font-size: 0.95rem;">Layanan</a></li>
            <li class="mb-2"><a href="<?= base_url('index.php#blog') ?>" class="text-white-50 text-decoration-none" style="font-size: 0.95rem;">Blog</a></li>
            <li class="mb-2"><a href="<?= base_url('index.php#berita') ?>" class="text-white-50 text-decoration-none" style="font-size: 0.95rem;">Berita</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-4 col-6 mb-3">
          <h6 class="footer-link-title text-white fw-bold text-uppercase mb-3" style="font-size: 0.9rem; letter-spacing: 0.06em;">Kontak &amp; Posko</h6>
          <ul class="footer-contact-list list-unstyled text-white-50" style="font-size: 0.95rem;">
            <li class="mb-3 d-flex align-items-start gap-2"><i class="bi bi-geo-alt-fill mt-1" style="color: #90caf9 !important;"></i> Balai Desa Sukatani, Kec. Prambon, Sidoarjo</li>
            <li class="mb-3 d-flex align-items-start gap-2"><i class="bi bi-telephone-fill mt-1" style="color: #90caf9 !important;"></i> (031) 897-1234</li>
            <li class="mb-3 d-flex align-items-start gap-2"><i class="bi bi-envelope-fill mt-1" style="color: #90caf9 !important;"></i> posyandu@sukatani.desa.id</li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-5 mb-3">
          <h6 class="footer-link-title text-white fw-bold text-uppercase mb-3" style="font-size: 0.9rem; letter-spacing: 0.06em;">Dapatkan Info Terbaru</h6>
          <p class="text-white-50 mb-2" style="font-size: 0.9rem; line-height: 1.6;">Daftarkan email untuk pengumuman jadwal posyandu dan tips nutrisi balita.</p>
          <div class="input-group email-input-group mt-2">
            <input type="email" class="form-control" placeholder="Alamat email Anda" aria-label="Alamat email Anda" id="footerEmailInput" style="background: rgba(255,255,255,0.12); color: #fff; border: 1px solid rgba(255,255,255,0.25); border-radius: 8px 0 0 8px; font-size: 0.9rem;" />
            <button class="btn btn-primary" type="button" id="footerSubscribeBtn" aria-label="Kirim Langganan" style="background-color: #2196f3; border-color: #2196f3; border-radius: 0 8px 8px 0;">
              <i class="bi bi-arrow-right-short fs-4"></i>
            </button>
          </div>
          <div class="footer-jam-box mt-3 p-3 rounded-3" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); font-size: 0.875rem; color: rgba(255,255,255,0.85); line-height: 1.6;">
            <strong class="text-white d-block mb-1"><i class="bi bi-clock-fill me-1" style="color: #90caf9 !important;"></i> Jam Pelayanan Posyandu</strong>
            Setiap bulan sesuai jadwal dusun masing-masing<br>
            Pukul 08.00 – 11.00 WIB
          </div>
        </div>
      </div>

      <div class="footer-bottom-bar pt-3 mt-3 border-top" style="border-color: rgba(255,255,255,0.15) !important; font-size: 0.85rem; color: rgba(255,255,255,0.75);">
        <div class="row align-items-center">
          <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
            Copyright &copy; 2026 <strong class="text-white">Posyandu Pintar</strong> &mdash; FITCOM 4.0. Hak Cipta Dilindungi.
          </div>
          <div class="col-md-6 text-center text-md-end">
            <a href="<?= base_url('index.php#tentang') ?>" class="text-white-50 text-decoration-none me-3" style="font-size: 0.85rem;">Tentang Kami</a> |
            <a href="<?= base_url('index.php#layanan') ?>" class="text-white-50 text-decoration-none mx-3" style="font-size: 0.85rem;">Layanan</a> |
            <a href="<?= base_url('index.php#blog') ?>" class="text-white-50 text-decoration-none ms-3" style="font-size: 0.85rem;">Blog &amp; Panduan</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>

<script>
  function updateJkBtn(radio) {
    document.getElementById('labelJkL').classList.remove('active');
    document.getElementById('labelJkP').classList.remove('active');
    if (radio.value === 'L') {
      document.getElementById('labelJkL').classList.add('active');
    } else {
      document.getElementById('labelJkP').classList.add('active');
    }
    hitungKmsMandiri();
  }

  function hitungKmsMandiri() {
    const usia = parseInt(document.getElementById('kmsUsia').value) || 0;
    const bb = parseFloat(document.getElementById('kmsBerat').value) || 0;
    const tb = parseFloat(document.getElementById('kmsTinggi').value) || 0;

    const badge = document.getElementById('badgeHasilKms');
    const teks = document.getElementById('teksHasilKms');
    const rek = document.getElementById('rekomendasiHasilKms');
    const rekapUsia = document.getElementById('rekapUsia');
    const rekapBb = document.getElementById('rekapBb');
    const rekapTb = document.getElementById('rekapTb');

    if (rekapUsia) rekapUsia.innerText = usia + ' bln';
    if (rekapBb) rekapBb.innerText = bb + ' kg';
    if (rekapTb) rekapTb.innerText = tb + ' cm';

    let bbIdealMin = (usia * 0.2) + 7.5;
    let bbIdealMax = (usia * 0.3) + 11.0;
    if (usia < 12) {
      bbIdealMin = 3.2 + (usia * 0.5);
      bbIdealMax = 4.2 + (usia * 0.7);
    }

    let statusText = 'Normal &amp; Ideal';
    let statusBadgeClass = 'status-badge-normal';
    let icon = 'bi-check-circle-fill';
    let penjelasan = `Berat ${bb} kg dan tinggi ${tb} cm berada dalam kisaran pertumbuhan yang wajar untuk usia ${usia} bulan.`;
    let saran = 'Pertahankan pemberian makanan bergizi seimbang (protein hewani, sayur, buah) dan timbang rutin di Posyandu setiap bulan.';

    if (bb < bbIdealMin * 0.85) {
      statusText = 'Prioritas Bidan';
      statusBadgeClass = 'status-badge-prioritas';
      icon = 'bi-exclamation-triangle-fill';
      penjelasan = `Berat badan ${bb} kg berada di bawah batas kurva penimbangan standar untuk usia ${usia} bulan.`;
      saran = 'Sangat disarankan segera berkonsultasi dengan Bidan Desa atau petugas Puskesmas saat kunjungan Posyandu terdekat.';
    } else if (bb < bbIdealMin || bb > bbIdealMax * 1.2) {
      statusText = 'Perlu Pemantauan';
      statusBadgeClass = 'status-badge-pemantauan';
      icon = 'bi-exclamation-circle-fill';
      penjelasan = `Berat badan ${bb} kg mendekati garis batas pemantauan pertumbuhan usia ${usia} bulan.`;
      saran = 'Evaluasi porsi makan harian dan pastikan asupan protein hewani terpenuhi. Lakukan penimbangan ulang bulan depan.';
    }

    if (badge) {
      badge.className = 'status-badge ' + statusBadgeClass;
      badge.innerHTML = `<i class="bi ${icon} me-1"></i> ${statusText}`;
    }
    if (teks) teks.innerHTML = penjelasan;
    if (rek) rek.innerHTML = saran;
  }

  document.addEventListener('DOMContentLoaded', () => {
    hitungKmsMandiri();
  });

  document.getElementById('footerSubscribeBtn')?.addEventListener('click', function() {
    const email = document.getElementById('footerEmailInput')?.value;
    if (email) {
      alert('Terima kasih! Email ' + email + ' telah terdaftar untuk menerima info jadwal Posyandu.');
      document.getElementById('footerEmailInput').value = '';
    } else {
      alert('Silakan masukkan alamat email Anda terlebih dahulu.');
    }
  });
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
