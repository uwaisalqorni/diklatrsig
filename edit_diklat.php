<?php
// edit_diklat.php
include 'config/koneksi.php';
include 'layout/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$q = $conn->query("SELECT diklat.*, pegawai.nama, pegawai.nik FROM diklat JOIN pegawai ON diklat.id_pegawai = pegawai.id WHERE diklat.id = $id");
if ($q->num_rows == 0) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan</div>";
    echo "<a href='input_diklat.php' class='btn btn-secondary'>Kembali</a>";
    include 'layout/footer.php';
    exit;
}
$d = $q->fetch_assoc();
?>

<div class="container">
    <h2>Edit Data Diklat</h2>
    <form method="post" action="update_diklat.php">
        <input type="hidden" name="id" value="<?= $d['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Pegawai</label>
            <input type="text" class="form-control" value="<?= $d['nama'] ?> (<?= $d['nik'] ?>)" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Diklat</label>
            <select class="form-select" name="jenis" required>
                <option value="keagamaan" <?= $d['jenis'] == 'keagamaan' ? 'selected' : '' ?>>Keagamaan</option>
                <option value="internal" <?= $d['jenis'] == 'internal' ? 'selected' : '' ?>>Internal</option>
                <option value="olahraga" <?= $d['jenis'] == 'olahraga' ? 'selected' : '' ?>>Olahraga</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Diklat</label>
            <input type="text" name="nama_diklat" class="form-control" value="<?= htmlspecialchars($d['nama_diklat']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Durasi (menit)</label>
            <input type="number" name="durasi_menit" class="form-control" value="<?= $d['durasi_menit'] ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="input_diklat.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?php include 'layout/footer.php'; ?>