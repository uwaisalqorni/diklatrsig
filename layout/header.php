<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="manifest" href="manifest.json">
  <meta name="theme-color" content="#198754">
  <title>Aplikasi Diklat RSIG</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
  body { background-color: #f5f7fa; }

  .top-header {
    background-color: #00a859;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
  }

  .top-header img {
    height: 48px;
    margin-right: 10px;
  }

  .top-header .left {
    display: flex;
    align-items: center;
  }

  .top-header .left .title {
    line-height: 1.2;
  }

  .top-header .left .title strong {
    font-size: 18px;
  }

  .top-header .left .title small {
    font-size: 14px;
    color: #f7e91c;
  }

  .sidebar {
    width: 240px;
    background-color: #fff;
    min-height: 100vh;
    border-right: 1px solid #ccc;
  }

  @media (max-width: 768px) {
    .sidebar {
      width: 100%;
      min-height: auto;
      border-right: none;
      border-bottom: 1px solid #ccc;
    }
  }

  .sidebar .nav-link {
    color: #155724;
  }

  .sidebar .nav-link.active,
  .sidebar .nav-link:hover {
    background-color: #d4edda;
    font-weight: bold;
  }

  .card-icon {
    font-size: 2rem;
  }

  .main-wrapper {
    display: flex;
    flex-direction: row;
  }

  @media (max-width: 768px) {
    .main-wrapper {
      flex-direction: column;
    }
  }
</style>

<!-- Mulai Body -->
<div class="top-header">
  <div class="left">
    <img src="assets/logo_rs.png" alt="Logo RSIG">
    <div class="title">
      <strong>Rumah Sakit Islam <span style="font-weight:bold;">Gondanglegi</span></strong><br>
      <small>Kabupaten Malang</small>
    </div>
  </div>
  <div class="right">
    <i class="fa fa-user"></i> Administrator <a href="logout.php" class="btn btn-outline-primary btn-sm">Logout</a>

  </div>
</div>

<!-- Sidebar + Main Content -->
<div class="main-wrapper">
  <!-- Sidebar -->
  <div class="sidebar p-3">
    <h5 class="text-success">Diklat RSIG</h5>
    <ul class="nav flex-column flex-md-column flex-sm-row">
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
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
      <h4>Aplikasi Perhitungan Jam Diklat</h4>
      <div class="text-muted text-end">
        <span id="clock" class="fs-6 fw-bold"></span><br>
        <small id="tanggal"></small>
      </div>
    </div>

    <!-- Tempat konten lainnya di sini -->


    <script>
  function updateClock() {
    const now = new Date();
    const jam = now.toLocaleTimeString('id-ID');
    const tanggal = now.toLocaleDateString('id-ID', {
      day: 'numeric', month: 'long', year: 'numeric'
    });
    document.getElementById('clock').innerText = jam;
    document.getElementById('tanggal').innerText = tanggal;
  }
  setInterval(updateClock, 1000);
  updateClock();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>