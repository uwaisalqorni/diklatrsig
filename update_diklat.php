<!-- update_diklat.php -->
<?php
include 'koneksi.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $id_pegawai = $_POST['id_pegawai'];
    $jenis = $_POST['jenis'];
    $durasi = $_POST['durasi_menit'];

    $stmt = $conn->prepare("UPDATE diklat SET id_pegawai=?, jenis=?, durasi_menit=? WHERE id=?");
    $stmt->bind_param("issi", $id_pegawai, $jenis, $durasi, $id);
    $stmt->execute();
    header("Location: input_diklat.php");
}
?>
