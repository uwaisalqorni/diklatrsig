<?php
include 'koneksi.php';
require_once 'fungsi_perhitungan.php';
include 'layout/header.php';

// Handle filter input
$filter_nama = $_GET['nama'] ?? '';
$filter_unit = $_GET['unit'] ?? '';
$filter_gol = $_GET['golongan'] ?? '';
?>
<div class="container">
    <h2 class="mb-4">Laporan Total Jam Diklat</h2>

    <form method="get" class="row g-2 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label">Nama / NIK</label>
            <input type="text" name="nama" class="form-control" placeholder="Cari Nama/NIK" value="<?= htmlspecialchars($filter_nama) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Unit</label>
            <input type="text" name="unit" class="form-control" placeholder="Filter Unit" value="<?= htmlspecialchars($filter_unit) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Golongan</label>
            <select name="golongan" class="form-select">
                <option value="">- Semua Golongan -</option>
                <option value="medis" <?= $filter_gol == 'medis' ? 'selected' : '' ?>>Medis</option>
                <option value="non-medis" <?= $filter_gol == 'non-medis' ? 'selected' : '' ?>>Non-Medis</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
            <a href="laporan.php" class="btn btn-secondary">Reset</a>
        </div>
        <div class="col-md-12 mt-2 text-end">
            <a href="export_excel.php?nama=<?= urlencode($filter_nama) ?>&unit=<?= urlencode($filter_unit) ?>&golongan=<?= urlencode($filter_gol) ?>" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Unit</th>
                    <th>Golongan</th>
                    <th>Total Menit Efektif</th>
                    <th>Total Jam</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $where = [];
                if ($filter_nama) {
                    $esc = $conn->real_escape_string($filter_nama);
                    $where[] = "(nama LIKE '%$esc%' OR nik LIKE '%$esc%')";
                }
                if ($filter_unit) {
                    $esc = $conn->real_escape_string($filter_unit);
                    $where[] = "unit LIKE '%$esc%'";
                }
                if ($filter_gol) {
                    $esc = $conn->real_escape_string($filter_gol);
                    $where[] = "golongan = '$esc'";
                }
                $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

                $q = $conn->query("SELECT * FROM pegawai $whereSql");
                while ($pegawai = $q->fetch_assoc()) {
                    $id = $pegawai['id'];
                    $gol = $pegawai['golongan'];
                    $total_menit = 0;
                    $res = $conn->query("SELECT * FROM diklat WHERE id_pegawai = $id");
                    while ($d = $res->fetch_assoc()) {
                        $total_menit += hitung_menit_diklat($gol, $d['jenis'], $d['durasi_menit']);
                    }
                    $total_jam = round($total_menit / 45, 2);
                    echo "<tr>
                        <td>{$pegawai['nik']}</td>
                        <td>{$pegawai['nama']}</td>
                        <td>{$pegawai['unit']}</td>
                        <td>{$pegawai['golongan']}</td>
                        <td>$total_menit</td>
                        <td>$total_jam</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>
</div>
<?php include 'layout/footer.php'; ?>
