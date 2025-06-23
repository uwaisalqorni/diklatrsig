<!-- tambah_pegawai.php -->
<?php
include 'koneksi.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $unit = $_POST['unit'];
    $golongan = $_POST['golongan'];

    $stmt = $conn->prepare("INSERT INTO pegawai (nik, nama, unit, golongan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nik, $nama, $unit, $golongan);
    $stmt->execute();
    header("Location: index.php");
}
?>