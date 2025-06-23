<?php include 'koneksi.php'; include 'layout/header.php'; ?>
<h2 class="mb-5">Master Data Pegawai</h2>

<!-- Form Tambah dan Import -->
<div class="row mb-4">
  <div class="col-md-8">
    <form class="row g-3" method="post" action="tambah_pegawai.php">
      <div class="col-md-2">
        <label class="form-label">NIK</label>
        <input type="text" name="nik" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" required>
      </div>
      <div class="col-md-2">
        <label class="form-label">Unit</label>
        <input type="text" name="unit" class="form-control" required>
      </div>
      <div class="col-md-2">
        <label class="form-label">Golongan</label>
        <select class="form-select" name="golongan">
          <option value="medis">Medis</option>
          <option value="non-medis">Non-Medis</option>
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Tambah</button>
      </div>
    </form>
  </div>

  <div class="col-md-4">
    <form method="post" action="import_pegawai.php" enctype="multipart/form-data" class="d-flex align-items-end gap-2 mb-2">
      <div class="flex-grow-1">
        <label class="form-label">Import Excel</label>
        <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls" required>
      </div>
      <div>
        <button type="submit" class="btn btn-success">Import</button>
      </div>
    </form>
    <a href="template_pegawai.xlsx" class="btn btn-outline-info btn-sm">Download Template Excel</a>
  </div>
</div>

<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr><th>NIK</th><th>Nama</th><th>Unit</th><th>Golongan</th><th>Aksi</th></tr>
  </thead>
  <tbody>
    <?php
    $q = $conn->query("SELECT * FROM pegawai");
    while ($row = $q->fetch_assoc()) {
      echo "<tr>
        <td>{$row['nik']}</td>
        <td>{$row['nama']}</td>
        <td>{$row['unit']}</td>
        <td>{$row['golongan']}</td>
       <td>
        <a href='input_diklat.php?id={$row['id']}' class='btn btn-sm btn-success'>Input</a>
        <a href='edit_pegawai.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
        <a href='hapus_pegawai.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin hapus pegawai ini?')\">Hapus</a>
      </td>
      </tr>";
    }
    ?>
  </tbody>
</table>
<?php include 'layout/footer.php'; ?>
