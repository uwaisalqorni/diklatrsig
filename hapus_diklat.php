<!-- hapus_diklat.php -->
<?php
include 'config/koneksi.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM diklat WHERE id = $id");
}
header("Location: input_diklat.php");
?>
