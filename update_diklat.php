<?php
// update_diklat.php
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $jenis = $_POST['jenis'];
    $nama_diklat = trim($_POST['nama_diklat']);
    $durasi = (int)$_POST['durasi_menit'];
    $created_at = $_POST['created_at'];

    // Format datetime-local (Y-m-d\TH:i) ke MySQL (Y-m-d H:i:s)
    $created_at = str_replace('T', ' ', $created_at) . ':00';

    // Cek apakah ID diklat valid
    $cek = $conn->query("SELECT id FROM diklat WHERE id = $id");
    if ($cek->num_rows === 0) {
        die("Data tidak ditemukan.");
    }

    $stmt = $conn->prepare("UPDATE diklat 
        SET jenis = ?, nama_diklat = ?, durasi_menit = ?, created_at = ? 
        WHERE id = ?");
    if (!$stmt) {
        die("Query gagal: " . $conn->error);
    }

    $stmt->bind_param("ssisi", $jenis, $nama_diklat, $durasi, $created_at, $id);
    $stmt->execute();

    header("Location: input_diklat.php");
    exit;
} else {
    echo "<div class='alert alert-danger'>Akses tidak valid</div>";
}