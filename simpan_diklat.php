<?php
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cari_pegawai = $_POST['cari_pegawai'];
    $jenis = $_POST['jenis'];
    $nama_diklat = trim($_POST['nama_diklat']);
    $durasi = (int)$_POST['durasi_menit'];

    // Ambil ID pegawai dari input format: Nama (NIK) - ID:123
    preg_match('/ID:(\d+)/', $cari_pegawai, $matches);
    if (!isset($matches[1])) {
        die("ID pegawai tidak ditemukan dari input.");
    }
    $id_pegawai = (int)$matches[1];

    $stmt = $conn->prepare("INSERT INTO diklat (id_pegawai, jenis, nama_diklat, durasi_menit, created_at) VALUES (?, ?, ?, ?, NOW())");
    if (!$stmt) {
        die("Query gagal: " . $conn->error);
    }
    $stmt->bind_param("issi", $id_pegawai, $jenis, $nama_diklat, $durasi);
    $stmt->execute();

    header("Location: input_diklat.php");
    exit;
}
?>