<?php
session_start();
if (isset($_SESSION['login'])) {
    header('Location: index.php'); // Sudah login, arahkan ke index
    exit;
}

$pesan_error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Username & password default (bisa diubah ke DB)
    if ($user === 'admin' && $pass === '123456') {
        $_SESSION['login'] = true;
        header('Location: index.php'); // Login sukses
        exit;
    } else {
        $pesan_error = 'Username atau Password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Aplikasi Diklat RSIG</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f5f7fa;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .login-box {
      width: 100%;
      max-width: 400px;
      padding: 30px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .login-box h3 {
      color: #198754;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="login-box">
  <h3 class="text-center">Login Aplikasi Diklat RSIG</h3>

  <?php if ($pesan_error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($pesan_error) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" class="form-control" id="username" name="username" required autofocus>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-success w-100">Login</button>
  </form>
</div>

</body>
</html>
