<?php

include 'config/koneksi.php';
include 'layout/header.php';

// Ambil ID dari parameter URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<div class='alert alert-danger'>ID pegawai tidak valid.</div>";
    include 'layout/footer.php';
    exit;
}

// Ambil data pegawai
$q = $conn->query("SELECT * FROM pegawai WHERE id = $id");
$pegawai = $q->fetch_assoc();
if (!$pegawai) {
    echo "<div class='alert alert-warning'>Data pegawai tidak ditemukan.</div>";
    include 'layout/footer.php';
    exit;
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik      = $conn->real_escape_string($_POST['nik']);
    $nama     = $conn->real_escape_string($_POST['nama']);
    $unit     = $conn->real_escape_string($_POST['unit']);
    $golongan = $conn->real_escape_string($_POST['golongan']);

    $update = $conn->query("UPDATE pegawai SET 
        nik = '$nik',
        nama = '$nama',
        unit = '$unit',
        golongan = '$golongan'
        WHERE id = $id");

    if ($update) {
        echo "<div class='alert alert-success'>Data pegawai berhasil diperbarui.</div>";
        echo "<meta http-equiv='refresh' content='1;url=index.php'>";
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui data.</div>";
    }
    include 'layout/footer.php';
    exit;
}
?>


  <h2 class="mb-4">Edit Data Pegawai</h2>
  <form method="post" class="row g-3">
    <div class="col-md-3">
      <label class="form-label">NIK</label>
      <input type="text" name="nik" value="<?= htmlspecialchars($pegawai['nik']) ?>" class="form-control" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Nama</label>
      <input type="text" name="nama" value="<?= htmlspecialchars($pegawai['nama']) ?>" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Unit</label>
      <input type="text" name="unit" value="<?= htmlspecialchars($pegawai['unit']) ?>" class="form-control" required>
    </div>
    <div class="col-md-2">
      <label class="form-label">Golongan</label>
      <select name="golongan" class="form-select" required>
        <option value="medis" <?= $pegawai['golongan'] == 'medis' ? 'selected' : '' ?>>Medis</option>
        <option value="non-medis" <?= $pegawai['golongan'] == 'non-medis' ? 'selected' : '' ?>>Non-Medis</option>
      </select>
    </div>
    <div class="col-12">
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      <a href="index.php" class="btn btn-secondary">Batal</a>
    </div>
  </form>


<?php include 'layout/footer.php'; ?>
