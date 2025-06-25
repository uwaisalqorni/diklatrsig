<?php 
include 'config/koneksi.php';
// include 'tabel_pegawai.php';
include 'layout/header.php';

session_start();
if (isset($_SESSION['sukses'])) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            {$_SESSION['sukses']}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
    unset($_SESSION['sukses']);
}

?>
<h2 class="mb-5">Master Data Pegawai</h2>

<!-- Form Pencarian -->
<form method="get" class="row mb-3">
  <div class="col-md-4">
    <input type="text" class="form-control" name="search" placeholder="Cari NIK atau Nama..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
  </div>
  <div class="col-md-2">
    <button type="submit" class="btn btn-outline-primary">Cari</button>
    <a href="index.php" class="btn btn-outline-secondary">Reset</a>
  </div>
</form>

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

<!-- <div class="row mb-4">
    <div class="col-md-3">
        <input type="text" class="form-control" name="search" placeholder="Cari Nama atau NIK" value="<?= $_GET['search'] ?? '' ?>">
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="index.php" class="btn btn-secondary">Reset</a>
    </div>
</div> -->

<?php
// Setup Pagination
$limit = 10; // Tampilkan 10 pegawai per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$search_sql = '';
if (!empty($search)) {
  $search_safe = $conn->real_escape_string($search);
  $search_sql = "WHERE nik LIKE '%$search_safe%' OR nama LIKE '%$search_safe%'";
}

// Total data
$total_res = $conn->query("SELECT COUNT(*) as total FROM pegawai $search_sql");
$total_rows = $total_res->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Data pegawai
$q = $conn->query("SELECT * FROM pegawai $search_sql ORDER BY id DESC LIMIT $offset, $limit");
$search_param = !empty($search) ? '&search=' . urlencode($search) : '';
?>
<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr><th>NIK</th><th>Nama</th><th>Unit</th><th>Golongan</th><th>Aksi</th></tr>
  </thead>
  <tbody>
    <?php while ($row = $q->fetch_assoc()) { ?>
      <tr>
        <td><?= htmlspecialchars($row['nik']) ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['unit']) ?></td>
        <td><?= htmlspecialchars($row['golongan']) ?></td>
        <td>
          <a href='input_diklat.php?id=<?= $row['id'] ?>' class='btn btn-sm btn-success'>Input</a>
          <a href='edit_pegawai.php?id=<?= $row['id'] ?>' class='btn btn-sm btn-warning'>Edit</a>
          <a href='hapus_pegawai.php?id=<?= $row['id'] ?>' class='btn btn-sm btn-danger' onclick="return confirm('Yakin hapus pegawai ini?')">Hapus</a>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>

<!-- Navigasi Pagination -->
<nav>
  <ul class="pagination justify-content-center flex-wrap">
    <!-- Tombol Sebelumnya -->
    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $page - 1 ?>">Sebelumnya</a>
    </li>

    <?php
    $adjacents = 2; // jumlah halaman di kiri dan kanan halaman aktif

    for ($i = 1; $i <= $total_pages; $i++) {
      if (
        $i == 1 || $i == $total_pages ||
        ($i >= $page - $adjacents && $i <= $page + $adjacents)
      ) {
        if ($i == $page) {
          echo "<li class='page-item active'><span class='page-link'>{$i}</span></li>";
        } else {
          echo "<li class='page-item'><a class='page-link' href='?page={$i}'>{$i}</a></li>";
        }
      } elseif (
        $i == 2 && $page - $adjacents > 2 ||
        $i == $total_pages - 1 && $page + $adjacents < $total_pages - 1
      ) {
        echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
      }
    }
    ?>

    <!-- Tombol Berikutnya -->
    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $page + 1 ?>">Berikutnya</a>
    </li>
  </ul>
</nav>

<?php include 'layout/footer.php'; ?>