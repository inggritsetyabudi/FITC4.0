<?php
$pageTitle = $pageTitle ?? 'Audit Log Aktivitas — Posyandu Pintar';
$activePage = $activePage ?? 'admin_audit_log';
$topbarTitle = $topbarTitle ?? 'Riwayat Rekam Jejak Aktivitas Pengguna';
$breadcrumb = $breadcrumb ?? '<a href="' . base_url('index.php?controller=admin&action=dashboard') . '" class="text-decoration-none text-forest">Dasbor</a> <i class="bi bi-chevron-right fs-xs"></i> <span>Audit Log</span>';

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="app-layout">
  <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

  <div class="app-main">
    <?php require_once __DIR__ . '/../../includes/topbar.php'; ?>

    <main class="app-content">
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
          <h2 class="h3 text-forest mb-1">Audit Log Rekam Jejak Sistem</h2>
          <p class="text-muted-posyandu mb-0">Pemantauan transparansi penambahan, pembaruan, penghapusan, dan pengubahan data kesehatan</p>
        </div>
        <div class="d-flex gap-2">
          <button type="button" class="btn-posyandu-outline" onclick="window.location.reload()">
            <i class="bi bi-arrow-clockwise"></i> Segarkan Log
          </button>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="panel-posyandu p-4">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="text-muted-posyandu font-sm">Total Entri Audit</div>
                <div class="h3 fw-bold text-forest mb-0"><?= count($logs) ?></div>
              </div>
              <div class="stat-icon-wrap" style="background:#E3F2FD; color:#1565C0; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-clock-history fs-5"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="panel-posyandu p-4">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="text-muted-posyandu font-sm">Integritas Log</div>
                <div class="h5 fw-bold text-success mb-0"><i class="bi bi-shield-check me-1"></i>Tervalidasi Aman</div>
              </div>
              <div class="stat-icon-wrap" style="background:#DCFCE7; color:#15803D; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-check2-all fs-5"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="panel-posyandu p-4">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="text-muted-posyandu font-sm">Pengawas Utama</div>
                <div class="h5 fw-bold text-forest mb-0">Administrator Desa</div>
              </div>
              <div class="stat-icon-wrap" style="background:#EDE7F6; color:#5E35B1; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-eye-fill fs-5"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel-posyandu">
        <div class="panel-header p-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
          <h3 class="h5 fw-bold text-forest mb-0">Riwayat Aktivitas Terkini</h3>
          <input type="text" id="filterAuditTable" class="form-control form-control-sm" placeholder="Cari aksi, pengguna, atau modul..." style="max-width: 280px;">
        </div>

        <div class="table-posyandu-wrap">
          <table class="table-posyandu" id="tabelAudit">
            <thead>
              <tr>
                <th style="width: 140px;">Waktu Kejadian</th>
                <th style="width: 160px;">Pengguna (User)</th>
                <th style="width: 100px;">Peran (Role)</th>
                <th style="width: 120px;">Jenis Tindakan</th>
                <th style="width: 140px;">Modul</th>
                <th>Rincian Catatan Perubahan</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($logs)): ?>
                <tr>
                  <td colspan="6" class="text-center py-4 text-muted">Belum ada catatan aktivitas di log sistem.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($logs as $l): ?>
                  <?php
                  $aksiBadge = match($l['aksi']) {
                    'TAMBAH' => '<span class="badge px-2 py-1 font-xs" style="background:#DCFCE7; color:#15803D; border:1px solid #BBF7D0;"><i class="bi bi-plus-circle me-1"></i>TAMBAH</span>',
                    'UPDATE' => '<span class="badge px-2 py-1 font-xs" style="background:#E0F2FE; color:#0284C7; border:1px solid #BAE6FD;"><i class="bi bi-pencil-square me-1"></i>UBAH</span>',
                    'HAPUS' => '<span class="badge px-2 py-1 font-xs" style="background:#FEE2E2; color:#B91C1C; border:1px solid #FECACA;"><i class="bi bi-trash me-1"></i>HAPUS</span>',
                    'STATUS' => '<span class="badge px-2 py-1 font-xs" style="background:#FEF3C7; color:#B45309; border:1px solid #FDE68A;"><i class="bi bi-toggle-on me-1"></i>STATUS</span>',
                    'RESET_PASS' => '<span class="badge px-2 py-1 font-xs" style="background:#F3E8FF; color:#7E22CE; border:1px solid #E9D5FF;"><i class="bi bi-key me-1"></i>RESET</span>',
                    'KIRIM_NOTIF' => '<span class="badge px-2 py-1 font-xs" style="background:#E0F7FA; color:#00838F; border:1px solid #B2EBF2;"><i class="bi bi-megaphone me-1"></i>SIARAN</span>',
                    default => '<span class="badge bg-secondary font-xs">' . htmlspecialchars($l['aksi']) . '</span>'
                  };

                  $roleColor = match($l['role']) {
                    'admin' => 'text-purple fw-bold',
                    'bidan' => 'text-primary fw-bold',
                    'kader' => 'text-success fw-bold',
                    default => 'text-dark'
                  };
                  ?>
                  <tr>
                    <td class="font-xs text-muted-posyandu" style="white-space: nowrap;">
                      <i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($l['created_at']) ?>
                    </td>
                    <td>
                      <div class="font-sm fw-bold text-forest"><?= htmlspecialchars($l['pengguna_nama']) ?></div>
                    </td>
                    <td class="font-xs text-uppercase <?= $roleColor ?>"><?= htmlspecialchars($l['role']) ?></td>
                    <td><?= $aksiBadge ?></td>
                    <td class="font-sm"><span class="badge bg-light text-dark border font-xs"><?= htmlspecialchars($l['modul']) ?></span></td>
                    <td class="font-sm text-dark"><?= htmlspecialchars($l['detail']) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('filterAuditTable');
  const table = document.getElementById('tabelAudit');
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
