<?php
include 'koneksi.php';
require_once 'fungsi_perhitungan.php';

$where = [];
if (!empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $where[] = "(pegawai.nama LIKE '%$search%' OR pegawai.nik LIKE '%$search%')";
}
if (!empty($_GET['filter_jenis'])) {
    $jenis = $conn->real_escape_string($_GET['filter_jenis']);
    $where[] = "diklat.jenis = '$jenis'";
}
$sql = "SELECT diklat.id, pegawai.nama, pegawai.nik, pegawai.unit, pegawai.golongan, diklat.jenis, diklat.durasi_menit, diklat.created_at
        FROM diklat
        JOIN pegawai ON diklat.id_pegawai = pegawai.id";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY diklat.id DESC";
$res = $conn->query($sql);

while ($d = $res->fetch_assoc()) {
    $efektif = hitung_menit_diklat($d['golongan'], $d['jenis'], $d['durasi_menit']);
    echo "<tr>
        <td>{$d['nama']} ({$d['nik']})</td>
        <td>{$d['unit']}</td>
        <td>{$d['golongan']}</td>
        <td>{$d['jenis']}</td>
        <td>{$d['durasi_menit']}</td>
        <td>{$efektif}</td>
        <td>
            <a href='edit_diklat.php?id={$d['id']}' class='btn btn-sm btn-warning'>Edit</a>
            <a href='hapus_diklat.php?id={$d['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin hapus data ini?')\">Hapus</a>
        </td>
    </tr>";
}
?>
