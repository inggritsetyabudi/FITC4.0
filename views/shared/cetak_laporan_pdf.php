<?php
$tipeLaporan = $tipeLaporan ?? 'Laporan Rekapitulasi Data Posyandu Terpadu';
$tanggalCetak = $tanggalCetak ?? format_tanggal_id(date('Y-m-d'));
$nomorSurat = $nomorSurat ?? '045/POSYANDU/' . date('Y');
$namaPenandatangan = $namaPenandatangan ?? 'Bambang Sudiro, S.Sos';
$jabatanPenandatangan = $jabatanPenandatangan ?? 'Kepala Desa Sukatani';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($tipeLaporan) ?> — Cetak PDF</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background-color: #f1f5f9;
      color: #0f172a;
      line-height: 1.5;
      font-size: 13px;
      padding: 24px;
    }
    .print-container {
      max-width: 1040px;
      margin: 0 auto;
      background: #ffffff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    .print-actions {
      max-width: 1040px;
      margin: 0 auto 20px auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }
    .btn-action {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s;
      border: none;
    }
    .btn-print {
      background-color: #0d47a1;
      color: #fff;
    }
    .btn-print:hover {
      background-color: #1565c0;
    }
    .btn-back {
      background-color: #e2e8f0;
      color: #334155;
    }
    .btn-back:hover {
      background-color: #cbd5e1;
    }
    .kop-surat {
      display: flex;
      align-items: center;
      gap: 20px;
      border-bottom: 3px double #0f172a;
      padding-bottom: 16px;
      margin-bottom: 24px;
      text-align: center;
    }
    .kop-logo {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      background: #0d47a1;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      flex-shrink: 0;
    }
    .kop-text {
      flex: 1;
    }
    .kop-instansi {
      font-size: 15px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #0d47a1;
    }
    .kop-desa {
      font-size: 20px;
      font-weight: 800;
      text-transform: uppercase;
      color: #0f172a;
      margin: 2px 0;
    }
    .kop-alamat {
      font-size: 11px;
      color: #475569;
    }
    .doc-meta {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 1px solid #e2e8f0;
      font-size: 12px;
    }
    .doc-title {
      font-size: 17px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 4px;
      text-transform: uppercase;
    }
    .doc-number {
      font-size: 12px;
      color: #64748b;
    }
    .doc-date {
      text-align: right;
      color: #334155;
    }
    .stats-summary {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin-bottom: 24px;
    }
    .stat-box {
      border: 1px solid #e2e8f0;
      background: #f8fafc;
      padding: 12px;
      border-radius: 8px;
      text-align: center;
    }
    .stat-label {
      font-size: 11px;
      text-transform: uppercase;
      font-weight: 600;
      color: #64748b;
      margin-bottom: 4px;
    }
    .stat-number {
      font-size: 20px;
      font-weight: 800;
      color: #0d47a1;
      line-height: 1;
    }
    table.data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
      font-size: 11.5px;
    }
    table.data-table th,
    table.data-table td {
      border: 1px solid #cbd5e1;
      padding: 7px 10px;
      vertical-align: middle;
    }
    table.data-table th {
      background-color: #f1f5f9;
      color: #1e293b;
      font-weight: 700;
      text-align: left;
    }
    table.data-table tr:nth-child(even) {
      background-color: #f8fafc;
    }
    .signature-section {
      display: flex;
      justify-content: flex-end;
      margin-top: 30px;
      page-break-inside: avoid;
    }
    .signature-box {
      width: 260px;
      text-align: center;
      font-size: 12px;
    }
    .signature-space {
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #0d47a1;
      font-size: 36px;
      opacity: 0.25;
    }
    .signature-name {
      font-weight: 700;
      text-decoration: underline;
      font-size: 13px;
    }
    .signature-role {
      color: #475569;
      font-size: 11.5px;
    }
    @media print {
      body {
        background: #fff !important;
        padding: 0 !important;
      }
      .print-actions {
        display: none !important;
      }
      .print-container {
        box-shadow: none !important;
        padding: 0 !important;
        max-width: 100% !important;
      }
      @page {
        size: A4 landscape;
        margin: 15mm;
      }
    }
  </style>
</head>
<body>

  <div class="print-actions">
    <button type="button" onclick="window.history.back()" class="btn-action btn-back">
      <i class="bi bi-arrow-left"></i> Kembali ke Sistem
    </button>
    <button type="button" onclick="window.print()" class="btn-action btn-print">
      <i class="bi bi-printer-fill"></i> Cetak / Simpan sebagai PDF
    </button>
  </div>

  <div class="print-container">
    <div class="kop-surat">
      <div class="kop-logo">
        <i class="bi bi-heart-pulse-fill"></i>
      </div>
      <div class="kop-text">
        <div class="kop-instansi">Pemerintah Kabupaten Sidoarjo · Kecamatan Balongbendo</div>
        <div class="kop-desa">Pemerintah Desa Sukatani — Posyandu Terpadu</div>
        <div class="kop-alamat">Sekretariat: Jl. Raya Sukatani No. 12, Kode Pos 61263 · Telepon: (031) 8971234 · Email: posyandu@sukatani.desa.id</div>
      </div>
    </div>

    <div class="doc-meta">
      <div>
        <h1 class="doc-title"><?= htmlspecialchars($tipeLaporan) ?></h1>
        <div class="doc-number">Nomor Dokumen: <?= htmlspecialchars($nomorSurat) ?></div>
      </div>
      <div class="doc-date">
        <div>Tanggal Cetak: <strong><?= $tanggalCetak ?></strong></div>
        <div>Wilayah Kerja: <strong>Desa Sukatani (4 Dusun)</strong></div>
      </div>
    </div>

    <?php if (isset($stats)): ?>
      <div class="stats-summary">
        <div class="stat-box">
          <div class="stat-label">Total Balita</div>
          <div class="stat-number"><?= $stats['total_balita'] ?? 0 ?></div>
        </div>
        <div class="stat-box">
          <div class="stat-label">Kader &amp; Bidan</div>
          <div class="stat-number"><?= ($stats['total_kader'] ?? 0) + ($stats['total_bidan'] ?? 0) ?></div>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($dataRekap)): ?>
      <table class="data-table">
        <thead>
          <tr>
            <th style="width: 35px; text-align: center;">No</th>
            <th>Nama Balita</th>
            <th>NIK</th>
            <th>JK</th>
            <th>Dusun</th>
            <th>Orang Tua</th>
            <th>Tgl Timbang</th>
            <th>Usia</th>
            <th>BB (kg)</th>
            <th>TB (cm)</th>
            <th>Status Gizi</th>
            <th>Catatan Klinis</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; foreach ($dataRekap as $r): ?>
            <tr>
              <td style="text-align: center;"><?= $no++ ?></td>
              <td><strong><?= htmlspecialchars($r['nama']) ?></strong></td>
              <td><?= htmlspecialchars($r['nik'] ?: '-') ?></td>
              <td style="text-align: center;"><?= htmlspecialchars($r['jenis_kelamin']) ?></td>
              <td><?= htmlspecialchars($r['dusun']) ?></td>
              <td><?= htmlspecialchars($r['nama_ortu']) ?></td>
              <td><?= $r['tanggal_ukur'] ? format_tanggal_id($r['tanggal_ukur'], true) : 'Belum Ukur' ?></td>
              <td><?= $r['usia_bulan'] ? $r['usia_bulan'] . ' bln' : '-' ?></td>
              <td><strong><?= $r['berat_kg'] ?: '-' ?></strong></td>
              <td><strong><?= $r['tinggi_cm'] ?: '-' ?></strong></td>
              <td><?= $r['kategori_status'] ? ucfirst($r['kategori_status']) : 'Belum Ditimbang' ?></td>
              <td><?= htmlspecialchars($r['catatan'] ?: '-') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    <?php elseif (!empty($daftarWarga)): ?>
      <table class="data-table">
        <thead>
          <tr>
            <th style="width: 35px; text-align: center;">No</th>
            <th>NIK</th>
            <th>No KK</th>
            <th>Nama Warga</th>
            <th>Tgl Lahir</th>
            <th>JK</th>
            <th>Dusun</th>
            <th>RT/RW</th>
            <th>Telepon</th>
            <th>Kategori</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; foreach ($daftarWarga as $w): ?>
            <tr>
              <td style="text-align: center;"><?= $no++ ?></td>
              <td><?= htmlspecialchars($w['nik']) ?></td>
              <td><?= htmlspecialchars($w['no_kk'] ?: '-') ?></td>
              <td><strong><?= htmlspecialchars($w['nama']) ?></strong></td>
              <td><?= format_tanggal_id($w['tanggal_lahir'], true) ?></td>
              <td style="text-align: center;"><?= htmlspecialchars($w['jenis_kelamin']) ?></td>
              <td><?= htmlspecialchars($w['dusun']) ?></td>
              <td><?= htmlspecialchars($w['rt_rw']) ?></td>
              <td><?= htmlspecialchars($w['telepon'] ?: '-') ?></td>
              <td><?= strtoupper($w['kategori']) ?></td>
              <td><?= ucfirst($w['status']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <div class="signature-section">
      <div class="signature-box">
        <div>Sukatani, <?= $tanggalCetak ?></div>
        <div class="signature-role"><?= htmlspecialchars($jabatanPenandatangan) ?></div>
        <div class="signature-space">
          <i class="bi bi-patch-check-fill"></i>
        </div>
        <div class="signature-name"><?= htmlspecialchars($namaPenandatangan) ?></div>
        <div>NIP / NIK Resmi Pemerintah Desa</div>
      </div>
    </div>
  </div>

</body>
</html>
