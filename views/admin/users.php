<?php
$pageTitle = $pageTitle ?? 'Manajemen Pengguna — Posyandu Pintar';
$activePage = $activePage ?? 'admin_users';
$topbarTitle = $topbarTitle ?? 'Pengelolaan Akun Petugas & Hak Akses';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Akun Petugas</span>';

require_once __DIR__ . '/../../includes/header.php';

$totalAdmin = 0;
$totalBidan = 0;
$totalKader = 0;
foreach ($daftarUsers as $u) {
  if ($u['role'] === 'admin') $totalAdmin++;
  elseif ($u['role'] === 'bidan') $totalBidan++;
  elseif ($u['role'] === 'kader') $totalKader++;
}
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
          <h2 class="h3 text-forest mb-1">Manajemen Pengguna &amp; Hak Akses</h2>
          <p class="text-muted-posyandu mb-0">Kelola akun administrator, bidan desa, dan kader posyandu wilayah</p>
        </div>
        <button type="button" class="btn-posyandu-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
          <i class="bi bi-person-plus-fill"></i> Tambah Akun Baru
        </button>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <div class="panel-posyandu p-4">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="text-muted-posyandu font-sm">Total Akun</div>
                <div class="h3 fw-bold text-forest mb-0"><?= count($daftarUsers) ?></div>
              </div>
              <div class="stat-icon-wrap" style="background:#E3F2FD; color:#1565C0; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-people-fill fs-5"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="panel-posyandu p-4">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="text-muted-posyandu font-sm">Administrator</div>
                <div class="h3 fw-bold text-forest mb-0"><?= $totalAdmin ?></div>
              </div>
              <div class="stat-icon-wrap" style="background:#EDE7F6; color:#5E35B1; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-shield-lock-fill fs-5"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="panel-posyandu p-4">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="text-muted-posyandu font-sm">Bidan Desa</div>
                <div class="h3 fw-bold text-forest mb-0"><?= $totalBidan ?></div>
              </div>
              <div class="stat-icon-wrap" style="background:#E0F7FA; color:#00838F; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-hospital fs-5"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="panel-posyandu p-4">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="text-muted-posyandu font-sm">Kader Posyandu</div>
                <div class="h3 fw-bold text-forest mb-0"><?= $totalKader ?></div>
              </div>
              <div class="stat-icon-wrap" style="background:#E8F5E9; color:#2E7D32; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-heart-pulse-fill fs-5"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel-posyandu">
        <div class="panel-header p-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
          <h3 class="h5 fw-bold text-forest mb-0">Daftar Akun Terdaftar</h3>
          <input type="text" id="filterUserTable" class="form-control form-control-sm" placeholder="Cari nama atau username..." style="max-width: 250px;">
        </div>

        <div class="table-posyandu-wrap">
          <table class="table-posyandu" id="tabelUsers">
            <thead>
              <tr>
                <th style="width: 50px;">No</th>
                <th>Nama Pengguna</th>
                <th>Username</th>
                <th>Hak Akses (Role)</th>
                <th>Wilayah / Dusun</th>
                <th>Telepon</th>
                <th>Terdaftar</th>
                <th style="text-align: right; width: 180px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($daftarUsers)): ?>
                <tr>
                  <td colspan="8" class="text-center py-4 text-muted">Belum ada akun pengguna.</td>
                </tr>
              <?php else: ?>
                <?php $no = 1; foreach ($daftarUsers as $u): ?>
                  <?php
                  $roleBadge = match($u['role']) {
                    'admin' => '<span class="badge bg-purple bg-opacity-10 text-purple border border-purple border-opacity-25 px-2 py-1 font-xs" style="background:#F3E8FF; color:#7E22CE;"><i class="bi bi-shield-fill me-1"></i>Administrator</span>',
                    'bidan' => '<span class="badge px-2 py-1 font-xs" style="background:#E0F2FE; color:#0284C7; border:1px solid #BAE6FD;"><i class="bi bi-hospital me-1"></i>Bidan Desa</span>',
                    'kader' => '<span class="badge px-2 py-1 font-xs" style="background:#DCFCE7; color:#15803D; border:1px solid #BBF7D0;"><i class="bi bi-person-heart me-1"></i>Kader</span>',
                    default => '<span class="badge bg-secondary font-xs">' . htmlspecialchars($u['role']) . '</span>'
                  };
                  ?>
                  <tr>
                    <td class="font-sm text-center"><?= $no++ ?></td>
                    <td>
                      <div class="fw-bold text-forest"><?= htmlspecialchars($u['nama']) ?></div>
                    </td>
                    <td class="font-sm text-dark"><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= $roleBadge ?></td>
                    <td class="font-sm"><?= htmlspecialchars($u['dusun_binaan'] ?: '-') ?></td>
                    <td class="font-sm"><?= htmlspecialchars($u['telepon'] ?: '-') ?></td>
                    <td class="font-xs text-muted-posyandu"><?= htmlspecialchars(substr($u['created_at'] ?? '-', 0, 10)) ?></td>
                    <td style="text-align: right;">
                      <div class="d-inline-flex gap-1">
                        <form method="POST" action="<?= base_url('index.php?controller=admin&action=users') ?>" onsubmit="return confirm('Reset password untuk akun <?= htmlspecialchars($u['username']) ?> menjadi 123?');">
                          <input type="hidden" name="sub_action" value="reset">
                          <input type="hidden" name="id" value="<?= $u['id'] ?>">
                          <button type="submit" class="btn btn-sm btn-outline-warning" title="Reset Kata Sandi">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                          </button>
                        </form>
                        <?php if ((int)$u['id'] !== (int)$user['id']): ?>
                          <form method="POST" action="<?= base_url('index.php?controller=admin&action=users') ?>" onsubmit="return confirm('Hapus akun <?= htmlspecialchars($u['username']) ?> secara permanen?');">
                            <input type="hidden" name="sub_action" value="delete">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Akun">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                        <?php endif; ?>
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
  </div>
</div>

<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-base fw-bold"><i class="bi bi-person-plus me-2"></i>Tambah Akun Pengguna</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="<?= base_url('index.php?controller=admin&action=users') ?>">
        <input type="hidden" name="sub_action" value="create">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label font-sm fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" placeholder="Nama lengkap petugas" required>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-md-6">
              <label class="form-label font-sm fw-bold">Username <span class="text-danger">*</span></label>
              <input type="text" name="username" class="form-control" placeholder="username_unik" required>
            </div>
            <div class="col-md-6">
              <label class="form-label font-sm fw-bold">Kata Sandi Awal</label>
              <input type="password" name="password" class="form-control" value="123" placeholder="Default: 123">
            </div>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-md-6">
              <label class="form-label font-sm fw-bold">Role / Peran <span class="text-danger">*</span></label>
              <select name="role" class="form-select" required>
                <option value="kader">Kader Posyandu</option>
                <option value="bidan">Bidan Desa</option>
                <option value="admin">Administrator Desa</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label font-sm fw-bold">Dusun Binaan</label>
              <select name="dusun_binaan" class="form-select">
                <option value="">Semua Wilayah</option>
                <?php foreach ($daftarDusun as $d): ?>
                  <option value="<?= htmlspecialchars($d) ?>">Dusun <?= htmlspecialchars($d) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label font-sm fw-bold">Nomor Telepon / WhatsApp</label>
            <input type="text" name="telepon" class="form-control" placeholder="08xxxxxxxxxx">
          </div>
        </div>
        <div class="modal-footer bg-light p-3">
          <button type="button" class="btn btn-secondary font-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn-posyandu-primary">
            <i class="bi bi-save me-1"></i> Buat Akun
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('filterUserTable');
  const table = document.getElementById('tabelUsers');
  if (searchInput && table) {
    searchInput.addEventListener('keyup', function() {
      const query = this.value.toLowerCase();
      const rows = table.querySelectorAll('tbody tr');
      rows.forEach(function(row) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
      });
    });
  }
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
