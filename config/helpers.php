<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/auth.php';

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string {
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $root = rtrim(dirname($script), '/\\');
        if ($root === '/' || $root === '\\' || $root === '.') {
            $root = '';
        }
        return $root . '/' . ltrim($path, '/');
    }
}

function upload_asset(array $file, string $prefix = 'berita', ?string $id = null): ?string {
    if (empty($file['tmp_name']) || ($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return null;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
    if (!in_array($ext, $allowed, true)) {
        return null;
    }

    $idFile = $id ?: (time() . '_' . bin2hex(random_bytes(3)));
    $filename = "{$prefix}_{$idFile}.{$ext}";
    $targetDir = dirname(__DIR__) . '/assets';

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $targetPath = $targetDir . '/' . $filename;
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return '/assets/' . $filename;
    }

    return null;
}

function asset_url(?string $path): string {
    if (!$path) return '';
    return base_url($path);
}

function format_tanggal_id(?string $dateStr, bool $short = false): string {
    if (!$dateStr) return '-';
    $time = strtotime($dateStr);
    if (!$time) return $dateStr;

    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $bulanShort = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];

    $d = (int)date('j', $time);
    $m = (int)date('n', $time);
    $y = date('Y', $time);

    return "$d " . ($short ? ($bulanShort[$m] ?? '') : ($bulan[$m] ?? '')) . " $y";
}

function hitung_umur(string $tanggalLahir, ?string $tanggalUkur = null): array {
    $lahir = new DateTime($tanggalLahir);
    $sekarang = new DateTime($tanggalUkur ?: date('Y-m-d'));
    $diff = $lahir->diff($sekarang);

    $tahun = $diff->y;
    $bulan = $diff->m;
    $totalBulan = ($tahun * 12) + $bulan;

    $teks = '';
    if ($tahun > 0 && $bulan > 0) {
        $teks = "$tahun tahun $bulan bulan";
    } elseif ($tahun > 0) {
        $teks = "$tahun tahun";
    } elseif ($bulan > 0) {
        $teks = "$bulan bulan";
    } else {
        $teks = $diff->d . " hari";
    }

    return [
        'tahun'      => $tahun,
        'bulan'      => $bulan,
        'totalBulan' => $totalBulan,
        'hari'       => $diff->d,
        'teks'       => $teks
    ];
}

function render_status_badge(string $status): string {
    $status = strtolower($status);
    return match ($status) {
        'prioritas', 'berisiko' => '<span class="status-badge status-badge-prioritas"><i class="bi bi-exclamation-triangle-fill"></i> Prioritas Bidan</span>',
        'pemantauan' => '<span class="status-badge status-badge-pemantauan"><i class="bi bi-exclamation-circle-fill"></i> Perlu Pemantauan</span>',
        default      => '<span class="status-badge status-badge-normal"><i class="bi bi-check-circle-fill"></i> Normal</span>'
    };
}

function render_status_label(string $status): string {
    $status = strtolower($status);
    return match ($status) {
        'prioritas', 'berisiko' => 'Prioritas Bidan',
        'pemantauan' => 'Perlu Pemantauan',
        default      => 'Normal'
    };
}

function deteksi_anomali_pengukuran(int $anakId, float $bbBaru, float $tbBaru): array {
    $pdo = get_db();
    $stmt = $pdo->prepare("SELECT * FROM pengukuran WHERE anak_id = ? ORDER BY tanggal_ukur DESC LIMIT 1");
    $stmt->execute([$anakId]);
    $terakhir = $stmt->fetch();

    if (!$terakhir) {
        return ['ada_anomali' => false];
    }

    $tbLama = (float)$terakhir['tinggi_cm'];
    $bbLama = (float)$terakhir['berat_kg'];

    if ($tbLama > 0 && $tbBaru < ($tbLama - 0.5)) {
        $selisih = number_format($tbLama - $tbBaru, 1);
        return [
            'ada_anomali' => true,
            'jenis'       => 'tinggi_turun',
            'judul'       => 'Periksa Kembali Tinggi Badan',
            'deskripsi'   => "Angka tinggi badan ($tbBaru cm) lebih rendah {$selisih} cm dari bulan lalu ({$tbLama} cm). Pastikan posisi balita saat diukur dan tidak ada kesalahan pengetikan angka."
        ];
    }

    if ($bbLama > 0 && ($bbLama - $bbBaru) > 2.0) {
        $selisih = number_format($bbLama - $bbBaru, 1);
        return [
            'ada_anomali' => true,
            'jenis'       => 'berat_turun_ekstrem',
            'judul'       => 'Periksa Kembali Berat Badan',
            'deskripsi'   => "Terjadi penurunan drastis {$selisih} kg dari penimbangan bulan lalu ({$bbLama} kg). Pastikan angka timbangan sudah stabil dan tepat."
        ];
    }

    return ['ada_anomali' => false];
}

function evaluasi_status_pengukuran(array $anak, float $bb, float $tb, array $riwayat = []): array {
    $umur = hitung_umur($anak['tanggal_lahir']);
    $totalBulan = $umur['totalBulan'];

    $status = 'normal';
    $rekomendasi = 'Pertumbuhan anak dalam batas wajar. Lanjutkan pola asuh dan nutrisi bergizi seimbang.';

    if (!empty($riwayat)) {
        $prev = $riwayat[0];
        $deltaBb = $bb - (float)$prev['berat_kg'];

        if ($deltaBb < 0) {
            $status = 'pemantauan';
            $rekomendasi = 'Terjadi penurunan berat badan dari bulan sebelumnya. Pantau asupan makan harian dan jadwal penimbangan berikutnya.';
        } elseif ($deltaBb == 0 && $totalBulan <= 24) {
            $status = 'pemantauan';
            $rekomendasi = 'Berat badan tidak mengalami kenaikan (stagnan). Disarankan evaluasi porsi makan dan MPASI.';
        }

        if (count($riwayat) >= 2) {
            $prev2 = $riwayat[1];
            if ((float)$prev['berat_kg'] <= (float)$prev2['berat_kg'] && $deltaBb <= 0) {
                $status = 'prioritas';
                $rekomendasi = 'Kenaikan berat badan tidak adekuat selama 2 bulan berturut-turut. Disarankan peninjauan khusus oleh Bidan Desa.';
            }
        }
    }

    return [
        'status'      => $status,
        'rekomendasi' => $rekomendasi,
        'disclaimer'  => 'Hasil ini merupakan skrining awal posyandu dan dapat ditinjau lebih lanjut oleh tenaga kesehatan.'
    ];
}

function ambil_daftar_dusun(): array {
    return [
        'Gamping Wetan',
        'Boharan',
        'Kasengan',
        'Bendomungal'
    ];
}
