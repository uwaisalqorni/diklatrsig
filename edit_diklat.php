<!-- edit_diklat.php -->
<?php
include 'koneksi.php';
include 'layout/header.php';
$id = $_GET['id'];
$q = $conn->query("SELECT * FROM diklat WHERE id = $id");
$data = $q->fetch_assoc();
?>
<h2>Edit Data Diklat</h2>
<form method="post" action="update_diklat.php" class="row g-3 mb-4">
    <input type="hidden" name="id" value="<?= $data['id'] ?>">
    <div class="col-md-6">
        <label class="form-label">Pegawai</label>
        <select class="form-select" name="id_pegawai">
            <?php
            $q = $conn->query("SELECT * FROM pegawai");
            while ($row = $q->fetch_assoc()) {
                $selected = $data['id_pegawai'] == $row['id'] ? 'selected' : '';
                echo "<option value='{$row['id']}' $selected>{$row['nama']} ({$row['nik']})</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Jenis Diklat</label>
        <select class="form-select" name="jenis">
            <option value="keagamaan" <?= $data['jenis'] == 'keagamaan' ? 'selected' : '' ?>>Keagamaan</option>
            <option value="internal" <?= $data['jenis'] == 'internal' ? 'selected' : '' ?>>Internal</option>
            <option value="olahraga" <?= $data['jenis'] == 'olahraga' ? 'selected' : '' ?>>Olahraga</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Durasi (menit)</label>
        <input type="number" name="durasi_menit" class="form-control" value="<?= $data['durasi_menit'] ?>" required>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="input_diklat.php" class="btn btn-secondary">Kembali</a>
    </div>
</form>
<?php include 'layout/footer.php'; ?>

