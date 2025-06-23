<?php
// Bersihkan output buffer agar tidak merusak file Excel
ob_end_clean();

// Aktifkan error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
include 'koneksi.php';
require_once 'fungsi_perhitungan.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$filter_nama = $_GET['nama'] ?? '';
$filter_unit = $_GET['unit'] ?? '';
$filter_gol  = $_GET['golongan'] ?? '';

$where = [];
if ($filter_nama) {
    $esc = $conn->real_escape_string($filter_nama);
    $where[] = "(nama LIKE '%$esc%' OR nik LIKE '%$esc%')";
}
if ($filter_unit) {
    $esc = $conn->real_escape_string($filter_unit);
    $where[] = "unit LIKE '%$esc%'";
}
if ($filter_gol) {
    $esc = $conn->real_escape_string($filter_gol);
    $where[] = "golongan = '$esc'";
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Laporan Diklat');

// Header kolom
$headers = ['NIK', 'Nama', 'Unit', 'Golongan', 'Total Menit Efektif', 'Total Jam'];
$sheet->fromArray($headers, NULL, 'A1');

// Bold dan autosize kolom
$style = $sheet->getStyle('A1:F1');
$style->getFont()->setBold(true);
$style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Isi data
$q = $conn->query("SELECT * FROM pegawai $whereSql");
$rowNum = 2;
while ($pegawai = $q->fetch_assoc()) {
    $id = $pegawai['id'];
    $gol = $pegawai['golongan'];
    $total_menit = 0;

    $res = $conn->query("SELECT * FROM diklat WHERE id_pegawai = $id");
    while ($d = $res->fetch_assoc()) {
        $total_menit += hitung_menit_diklat($gol, $d['jenis'], $d['durasi_menit']);
    }
    $total_jam = round($total_menit / 45, 2);

    $sheet->fromArray([
        $pegawai['nik'],
        $pegawai['nama'],
        $pegawai['unit'],
        $pegawai['golongan'],
        $total_menit,
        $total_jam
    ], NULL, 'A' . $rowNum);

    $rowNum++;
}

$filename = 'Laporan_Diklat_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
