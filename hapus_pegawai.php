<?php
include 'config/koneksi.php';
session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $conn->query("DELETE FROM pegawai WHERE id = $id");
    $_SESSION['sukses'] = "Data pegawai berhasil dihapus!";
}
header("Location: index.php");
exit;
?>