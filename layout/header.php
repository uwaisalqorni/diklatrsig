<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aplikasi Diklat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { background-color: #f5f7fa; }
    .sidebar { width: 240px; background-color: #fff; min-height: 100vh; border-right: 1px solid #ccc; }
    .sidebar .nav-link { color: #155724; }
    .sidebar .nav-link.active, .sidebar .nav-link:hover { background-color: #d4edda; font-weight: bold; }
    .card-icon { font-size: 2rem; }
  </style>
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar p-3">
    <h5 class="text-success">Diklat RSIG</h5>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? ' active' : '' ?>" href="index.php">
          <i class="fas fa-users"></i> Data Pegawai
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'input_diklat.php' ? ' active' : '' ?>" href="input_diklat.php">
          <i class="fas fa-edit"></i> Input Diklat
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'laporan.php' ? ' active' : '' ?>" href="laporan.php">
          <i class="fas fa-chart-bar"></i> Laporan
        </a>
      </li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="flex-fill p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4>Aplikasi Perhitungan Jam Diklat</h4>
      <div class="text-muted">
        <span id="clock" class="fs-6 fw-bold"></span><br>
        <small id="tanggal"></small>
      </div>
    </div>

    <script>
      function updateClock() {
        const now = new Date();
        const jam = now.toLocaleTimeString('id-ID');
        const tanggal = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        document.getElementById('clock').innerText = jam;
        document.getElementById('tanggal').innerText = tanggal;
      }
      setInterval(updateClock, 1000);
      updateClock();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
