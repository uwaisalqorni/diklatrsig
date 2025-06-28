<?php
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}

include 'config/koneksi.php';
require_once 'fungsi_perhitungan.php';

// Fungsi untuk format tanggal Indonesia
function format_tanggal_indonesia($datetime) {
    $bulanIndo = [
        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
        'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
        'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
        'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
    ];

    $timestamp = strtotime($datetime);
    $tanggal = date('d', $timestamp);
    $bulan = $bulanIndo[date('F', $timestamp)];
    $tahun = date('Y', $timestamp);
    $jam = date('H:i', $timestamp);

    return "$tanggal $bulan $tahun $jam";
}

// Ambil filter
$search = $_GET['search'] ?? '';
$filter_jenis = $_GET['filter_jenis'] ?? '';
$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$where = [];
if ($search) {
    $esc = $conn->real_escape_string($search);
    $where[] = "(pegawai.nama LIKE '%$esc%' OR pegawai.nik LIKE '%$esc%')";
}
if ($filter_jenis) {
    $esc = $conn->real_escape_string($filter_jenis);
    $where[] = "diklat.jenis = '$esc'";
}
if ($tanggal_awal && $tanggal_akhir) {
    $esc_awal = $conn->real_escape_string($tanggal_awal);
    $esc_akhir = $conn->real_escape_string($tanggal_akhir);
    $where[] = "DATE(diklat.created_at) BETWEEN '$esc_awal' AND '$esc_akhir'";
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Ambil data diklat
$res = $conn->query("SELECT diklat.*, pegawai.nama, pegawai.nik, pegawai.unit, pegawai.golongan
                     FROM diklat 
                     JOIN pegawai ON diklat.id_pegawai = pegawai.id
                     $whereSql
                     ORDER BY diklat.id DESC");

$no = 1;
while ($d = $res->fetch_assoc()) {
    $total_efektif = hitung_menit_diklat($d['golongan'], $d['jenis'], $d['durasi_menit']);
    $waktu = format_tanggal_indonesia($d['created_at']);

    echo "<tr>
        <td>{$no}</td>
        <td>{$d['nama']} ({$d['nik']})</td>
        <td>{$d['unit']}</td>
        <td>{$d['golongan']}</td>
        <td>{$d['jenis']}</td>
        <td>{$d['nama_diklat']}</td>
        <td>{$waktu}</td>
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