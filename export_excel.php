<?php
// Bersihkan output buffer agar tidak merusak file Excel
// Bersihkan output buffer
ob_end_clean();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
include 'config/koneksi.php';
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

//tambahan 
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Tambahkan logo (di kiri atas)
$drawing = new Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo');
$drawing->setPath('assets/logo_rs.png'); // Ganti dengan path logo Anda
$drawing->setHeight(70); // Tinggi logo (px)
$drawing->setCoordinates('A1'); // Posisi sel awal
$drawing->setOffsetX(10);
$drawing->setOffsetY(10);
$drawing->setWorksheet($sheet);

// Judul (merge cell dan style)
$sheet->mergeCells('A1:G1');
$sheet->mergeCells('A2:G2');
$sheet->mergeCells('A3:G3');

$sheet->setCellValue('A2', 'LAPORAN TOTAL JAM DIKLAT');
$sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);



// Header dengan kolom No
$headers = ['No', 'NIK', 'Nama', 'Unit', 'Golongan', 'Total Menit Efektif', 'Total Jam'];
$sheet->fromArray($headers, NULL, 'A5');

// Gaya header
$headerStyle = $sheet->getStyle('A5:G5');
$headerStyle->getFont()->setBold(true);
$headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('92D050');
$headerStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Auto size kolom
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Data isi
$q = $conn->query("SELECT * FROM pegawai $whereSql");
$rowNum = 6;
$no = 1;

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
        $no++,
        $pegawai['nik'],
        $pegawai['nama'],
        $pegawai['unit'],
        $pegawai['golongan'],
        $total_menit,
        $total_jam
    ], NULL, 'A' . $rowNum);

    // Tambahkan border setiap baris data
    $sheet->getStyle("A{$rowNum}:G{$rowNum}")
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);

    $rowNum++;
}

// Output
$filename = 'Laporan_Diklat_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;