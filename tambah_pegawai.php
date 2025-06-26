<?php
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik      = trim($_POST['nik']);
    $nama     = trim($_POST['nama']);
    $unit     = trim($_POST['unit']);
    $golongan = trim($_POST['golongan']);

    // Cek apakah NIK sudah digunakan
    $cek = $conn->prepare("SELECT id FROM pegawai WHERE nik = ?");
    $cek->bind_param("s", $nik);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        echo "<script>alert('NIK sudah terdaftar. Gunakan NIK lain!');window.history.back();</script>";
        exit;
    }

    // Insert data jika NIK unik
    $stmt = $conn->prepare("INSERT INTO pegawai (nik, nama, unit, golongan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nik, $nama, $unit, $golongan);
    $stmt->execute();
    
    session_start();
    $_SESSION['sukses'] = "Data pegawai berhasil ditambahkan!";
    header("Location: index.php");
    exit;
}
?>
