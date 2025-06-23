<!-- input_diklat.php -->
<?php 
include 'koneksi.php';
require_once 'fungsi_perhitungan.php';
include 'layout/header.php'; ?>
<h2>Input Data Diklat</h2>
<form method="get" class="row g-3 mb-3">
    <div class="col-md-3">
        <input type="text" class="form-control" name="search" placeholder="Cari Nama atau NIK" value="<?= $_GET['search'] ?? '' ?>">
    </div>
    <div class="col-md-3">
        <select name="filter_jenis" class="form-select">
            <option value="">- Semua Jenis -</option>
            <option value="keagamaan" <?= ($_GET['filter_jenis'] ?? '') == 'keagamaan' ? 'selected' : '' ?>>Keagamaan</option>
            <option value="internal" <?= ($_GET['filter_jenis'] ?? '') == 'internal' ? 'selected' : '' ?>>Internal</option>
            <option value="olahraga" <?= ($_GET['filter_jenis'] ?? '') == 'olahraga' ? 'selected' : '' ?>>Olahraga</option>
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="input_diklat.php" class="btn btn-secondary">Reset</a>
    </div>
</form>

<form method="post" action="simpan_diklat.php" class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label">Cari Pegawai</label>
        <input type="text" class="form-control" name="cari_pegawai" list="pegawaiList" placeholder="Ketik nama atau NIK..." required>
        <datalist id="pegawaiList">
            <?php
            $pegawaiList = $conn->query("SELECT * FROM pegawai");
            while ($pgw = $pegawaiList->fetch_assoc()) {
                echo "<option value='{$pgw['nama']} ({$pgw['nik']}) - ID:{$pgw['id']}'>";
            }
            ?>
        </datalist>
    </div>
    <div class="col-md-3">
        <label class="form-label">Jenis Diklat</label>
        <select class="form-select" name="jenis">
            <option value="keagamaan">Keagamaan</option>
            <option value="internal">Internal</option>
            <option value="olahraga">Olahraga</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Durasi (menit)</label>
        <input type="number" name="durasi_menit" class="form-control" required>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </div>
</form>

<h3>Riwayat Data Diklat</h3>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Pegawai</th>
            <th>Unit</th>
            <th>Golongan</th>
            <th>Jenis</th>
            <th>Durasi (menit)</th>
            <th>Total Efektif</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
    <tbody id="tabel-diklat-body">
        <?php include 'tabel_diklat.php'; ?>
    </tbody>
    </tbody>
</table>
<?php include 'layout/footer.php'; ?>