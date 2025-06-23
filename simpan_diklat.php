<!-- simpan_diklat.php -->
<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = $_POST['cari_pegawai'];
    $jenis = $_POST['jenis'];
    $durasi = $_POST['durasi_menit'];
    $tanggal = date('Y-m-d H:i:s');

    if (preg_match('/ID:(\d+)/', $input, $matches)) {
        $id_pegawai = $matches[1];

        $stmt = $conn->prepare("INSERT INTO diklat (id_pegawai, jenis, durasi_menit, created_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isis", $id_pegawai, $jenis, $durasi, $tanggal);
        $stmt->execute();
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        echo json_encode(["success" => true]);
        exit;
    }

    header("Location: input_diklat.php?id=$id_pegawai");
    exit;
}
?>