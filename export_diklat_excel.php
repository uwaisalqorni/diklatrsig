<?php
ob_end_clean();
require 'vendor/autoload.php';
include 'config/koneksi.php';
require_once 'fungsi_perhitungan.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Ambil filter dari URL
$search = $_GET['search'] ?? '';
$filter_jenis = $_GET['filter_jenis'] ?? '';
$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

// Bangun WHERE SQL
$where = [];
if ($search) {
    $esc = $conn->real_escape_string($search);
    $where[] = "(pegawai.nama LIKE '%$esc%' OR pegawai.nik LIKE '%$esc%')";
}
if ($filter_jenis) {
    $esc = $conn->real_escape_string($filter_jenis);
    $where[] = "diklat.jenis = '$esc'";
}
if ($tanggal_awal && $tanggal_akhir) {
    $esc_awal = $conn->real_escape_string($tanggal_awal);
    $esc_akhir = $conn->real_escape_string($tanggal_akhir);
    $where[] = "DATE(diklat.created_at) BETWEEN '$esc_awal' AND '$esc_akhir'";
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Ambil data
$q = $conn->query("SELECT diklat.*, pegawai.nama, pegawai.nik, pegawai.unit, pegawai.golongan
                   FROM diklat 
                   JOIN pegawai ON diklat.id_pegawai = pegawai.id
                   $whereSql
                   ORDER BY diklat.created_at DESC");

// Siapkan spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Laporan Diklat');

// Tambah logo
$logo = new Drawing();
$logo->setName('Logo');
$logo->setDescription('Logo');
$logo->setPath('assets/logo_rs.png'); // Ubah sesuai lokasi logo Anda
$logo->setHeight(70);
$logo->setCoordinates('A1');
$logo->setOffsetX(10);
$logo->setOffsetY(10);
$logo->setWorksheet($sheet);

// Judul
$sheet->mergeCells('A2:J2');
$sheet->setCellValue('A2', 'LAPORAN DIKLAT PEGAWAI');
$sheet->getStyle('A2')->getFont()->setSize(14)->setBold(true);
$sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Header tabel
$headers = ['No', 'Nama', 'NIK', 'Unit', 'Golongan', 'Jenis', 'Nama Diklat', 'Tanggal', 'Durasi (menit)', 'Total Efektif'];
$sheet->fromArray($headers, NULL, 'A4');

// Style header
$sheet->getStyle('A4:J4')->getFont()->setBold(true);
$sheet->getStyle('A4:J4')->getFill()
      ->setFillType(Fill::FILL_SOLID)
      ->getStartColor()->setRGB('92D050');
$sheet->getStyle('A4:J4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Auto width + border
foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Isi data
$rowNum = 5;
$no = 1;
while ($d = $q->fetch_assoc()) {
    $total_efektif = hitung_menit_diklat($d['golongan'], $d['jenis'], $d['durasi_menit']);
    $waktu = date('d-m-Y H:i', strtotime($d['created_at']));

    $sheet->fromArray([
        $no++,
        $d['nama'],
        $d['nik'],
        $d['unit'],
        $d['golongan'],
        $d['jenis'],
        $d['nama_diklat'],
        $waktu,
        $d['durasi_menit'],
        $total_efektif
    ], NULL, 'A' . $rowNum);

    // Border per baris
    $sheet->getStyle("A{$rowNum}:J{$rowNum}")
          ->getBorders()->getAllBorders()
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