<?php
session_start();
if (!isset($_SESSION['login'])) {
  header('Location: login.php');
  exit;
}
require 'vendor/autoload.php'; // pastikan PhpSpreadsheet terinstall
include 'config/koneksi.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_FILES['file_excel']['tmp_name'])) {
    $file = $_FILES['file_excel']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        // Lewati baris pertama (header)
        $success = 0;
        $fail = 0;
        foreach ($data as $i => $row) {
            if ($i === 0) continue;

            $nik      = trim($row[0]);
            $nama     = trim($row[1]);
            $unit     = trim($row[2]);
            $golongan = strtolower(trim($row[3]));

            if ($nik && $nama && $unit && in_array($golongan, ['medis', 'non-medis'])) {
                // Cek duplikat NIK
                $cek = $conn->query("SELECT id FROM pegawai WHERE nik = '$nik'");
                if ($cek->num_rows === 0) {
                    $stmt = $conn->prepare("INSERT INTO pegawai (nik, nama, unit, golongan) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $nik, $nama, $unit, $golongan);
                    $stmt->execute();
                    $success++;
                } else {
                    $fail++;
                }
            } else {
                $fail++;
            }
        }

        echo "<script>alert('Import selesai. Berhasil: $success | Gagal/duplikat: $fail'); window.location='index.php';</script>";
    } catch (Exception $e) {
        echo "Gagal membaca file Excel: " . $e->getMessage();
    }
} else {
    echo "File tidak ditemukan!";
}
