<?php
// fungsi_perhitungan.php
function hitung_menit_diklat($golongan, $jenis, $durasi_menit) {
    if ($jenis == 'keagamaan') {
        return $golongan == 'medis' ? $durasi_menit * 0.3 : $durasi_menit;
    }
    if ($jenis == 'internal') {
        return $durasi_menit; // 100%
    }
    if ($jenis == 'olahraga') {
        return $golongan == 'medis' ? $durasi_menit * 0.2 : $durasi_menit;
    }
    return 0;
}
?>