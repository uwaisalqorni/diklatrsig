<?php
// update_diklat.php
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $jenis = $_POST['jenis'];
    $nama_diklat = trim($_POST['nama_diklat']);
    $durasi = (int)$_POST['durasi_menit'];

    $stmt = $conn->prepare("UPDATE diklat SET jenis=?, nama_diklat=?, durasi_menit=? WHERE id=?");
    $stmt->bind_param("ssii", $jenis, $nama_diklat, $durasi, $id);
    $stmt->execute();

    header("Location: input_diklat.php");
    exit;
} else {
    echo "<div class='alert alert-danger'>Akses tidak valid</div>";
}