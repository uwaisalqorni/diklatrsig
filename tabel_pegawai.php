<?php
include 'config/koneksi.php';

// Ambil filter jika ada
$search = $_GET['search'] ?? '';

$where = [];
if ($search) {
    $esc = $conn->real_escape_string($search);
    $where[] = "(pegawai.nama LIKE '%$esc%' OR pegawai.nik LIKE '%$esc%')";
}
if ($filter_jenis) {
    $esc = $conn->real_escape_string($filter_jenis);
    $where[] = "diklat.jenis = '$esc'";
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

?>