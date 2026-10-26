<?php

function evaluasi_status_pengukuran(?array $ukurTerakhir, float $bbBaru): string
{
    if (!$ukurTerakhir) {
        return 'normal';
    }

    $delta = $bbBaru - (float)$ukurTerakhir['berat_kg'];

    if ($delta < -0.3) {
        return 'prioritas';
    }
    if ($delta <= 0) {
        return 'pemantauan';
    }
    return 'normal';
}
