<?php

if (!isset($_SESSION['login'])) {
  header('Location: login.php');
  exit;
}


include 'config/koneksi.php';
require_once 'fungsi_perhitungan.php';

// Ambil filter jika ada
$search = $_GET['search'] ?? '';
$filter_jenis = $_GET['filter_jenis'] ?? '';

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

$res = $conn->query("SELECT diklat.*, pegawai.nama, pegawai.nik, pegawai.unit, pegawai.golongan
                     FROM diklat 
                     JOIN pegawai ON diklat.id_pegawai = pegawai.id
                     $whereSql
                     ORDER BY diklat.id DESC");
$no=1;
while ($d = $res->fetch_assoc()) {
    $total_efektif = hitung_menit_diklat($d['golongan'], $d['jenis'], $d['durasi_menit']);
    echo "<tr>
        <td>{$no}</td>
        <td>{$d['nama']} ({$d['nik']})</td>
        <td>{$d['unit']}</td>
        <td>{$d['golongan']}</td>
        <td>{$d['jenis']}</td>
        <td>{$d['nama_diklat']}</td>
        <td>{$d['durasi_menit']}</td>
        <td>{$total_efektif}</td>
        <td>
            <a href='edit_diklat.php?id={$d['id']}' class='btn btn-sm btn-warning'>Edit</a>
            <a href='hapus_diklat.php?id={$d['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin hapus data ini?')\">Hapus</a>
        </td>
    </tr>";
    $no++;
}
?>