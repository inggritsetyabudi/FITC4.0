<?php
$pageTitle = $pageTitle ?? (htmlspecialchars($berita['judul'] ?? 'Detail Berita') . ' — Posyandu Pintar');
$pageDesc = $pageDesc ?? htmlspecialchars($berita['ringkasan'] ?? '');
$bodyClass = 'bg-canvas';

$artikel = [
    'kategori'     => $berita['kategori'] ?? 'PANDUAN POSYANDU',
    'judul'        => $berita['judul'] ?? 'Panduan Tumbuh Kembang',
    'ringkasan'    => $berita['ringkasan'] ?? '',
    'gambar'       => $berita['gambar'] ?: 'assets/images/hero_timbang.jpg',
    'waktu_baca'   => '4 menit baca',
    'published_at' => $berita['published_at'] ?? date('Y-m-d'),
    'penulis'      => 'Bidan Desa Sukatani',
    'isi'          => $berita['isi'] ?? '<p>Konten artikel sedang disiapkan.</p>'
];

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/landing_nav.php';
?>

<main class="py-5">
  <div class="container">
    <div class="mb-4">
      <a href="<?= base_url('index.php#berita') ?>" class="btn-posyandu-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
      </a>
    </div>

    <div class="row g-4 g-lg-5">
      <div class="col-lg-8">
        <article class="bg-surface border-line rounded-4 p-4 p-md-5 shadow-sm">
          <div class="mb-3">
            <span class="berita-category-tag"><?= htmlspecialchars($artikel['kategori']) ?></span>
          </div>

          <h1 class="h2 text-forest mb-3 fw-bold" style="line-height: 1.3;">
            <?= htmlspecialchars($artikel['judul']) ?>
          </h1>

          <div class="d-flex flex-wrap align-items-center gap-3 font-sm text-ink-500 pb-3 mb-4 border-bottom">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-person-circle text-primary"></i>
              <span class="fw-semibold text-ink-800"><?= htmlspecialchars($artikel['penulis']) ?></span>
            </div>
            <span>·</span>
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-calendar3"></i>
              <span><?= format_tanggal_id($artikel['published_at']) ?></span>
            </div>
            <span>·</span>
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-clock"></i>
              <span><?= htmlspecialchars($artikel['waktu_baca']) ?></span>
            </div>
          </div>

          <div class="mb-4 rounded-4 overflow-hidden border-line" style="max-height: 420px; background-color: var(--surface-soft);">
            <img 
              src="<?= base_url($artikel['gambar'] ?: 'assets/images/hero_timbang.jpg') ?>" 
              alt="<?= htmlspecialchars($artikel['judul']) ?>" 
              class="w-100 h-100 object-fit-cover"
              style="min-height: 260px;"
            >
          </div>

          <div class="text-ink-800" style="line-height: 1.8; font-size: 1.05rem;">
            <?= $artikel['isi'] ?>
          </div>

          <div class="border-top pt-4 mt-5 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span class="font-sm text-muted-posyandu">
              Dipublikasikan oleh Tim Layanan Kesehatan Desa Sukatani
            </span>
            <a href="<?= base_url('index.php#blog') ?>" class="btn-posyandu-primary btn-sm">
              Lihat Panduan Lainnya &rarr;
            </a>
          </div>
        </article>
      </div>

      <div class="col-lg-4">
        <div class="bg-surface border-line rounded-4 p-4 mb-4 shadow-xs">
          <h5 class="text-forest fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 1.15rem;">
            <i class="bi bi-newspaper text-primary"></i> Berita &amp; Artikel Lainnya
          </h5>
          <div class="d-flex flex-column gap-3">
            <?php if (!empty($beritaLainnya)): ?>
              <?php foreach (array_slice($beritaLainnya, 0, 4) as $bl): ?>
                <a href="<?= base_url('index.php?controller=berita&action=detail&id=' . $bl['id']) ?>" class="text-decoration-none p-2 rounded-3 border-line bg-surface-soft d-block transition-hover">
                  <span class="text-primary fw-semibold d-block font-xs mb-1"><?= htmlspecialchars($bl['kategori']) ?></span>
                  <h6 class="text-forest fw-bold mb-1 font-sm" style="line-height: 1.4;"><?= htmlspecialchars($bl['judul']) ?></h6>
                  <span class="text-ink-500 font-xs"><i class="bi bi-calendar3 me-1"></i><?= format_tanggal_id($bl['published_at']) ?></span>
                </a>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-ink-500 font-sm mb-0">Belum ada artikel lainnya.</p>
            <?php endif; ?>
          </div>
        </div>

        <div class="bg-surface border-line rounded-4 p-4 shadow-xs">
          <h5 class="text-forest fw-bold mb-2" style="font-size: 1.15rem;">Posyandu Pintar</h5>
          <p class="font-sm text-ink-700 mb-3" style="line-height: 1.6;">
            Pusat informasi, pemantauan kurva KMS digital, dan penimbangan balita Desa Sukatani.
          </p>
          <?php if (is_logged_in()): ?>
            <a href="<?= get_dashboard_url(current_user()['role']) ?>" class="btn-posyandu-primary btn-sm w-100 text-center d-block">
              <i class="bi bi-speedometer2 me-1"></i> Buka Dasbor Petugas
            </a>
          <?php else: ?>
            <a href="<?= base_url('index.php?controller=auth&action=showLogin') ?>" class="btn-posyandu-primary btn-sm w-100 text-center d-block">
              <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Sistem
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
