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
      margin: 0;
      padding: 0;
      height: 100vh;
      background: url('assets/bg_rs.png') no-repeat center center fixed;
      background-size: cover;
      backdrop-filter: blur(10px);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: sans-serif;
    }

    .login-box {
      width: 100%;
      max-width: 400px;
      padding: 30px;
      background: rgba(255, 255, 255, 0.45); /* semi-transparent */
      backdrop-filter: blur(10px);           /* blur efek */
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      text-align: center;
    }

    .login-box img {
      max-width: 100px;
      margin-bottom: 15px;
    }

    .login-box h3 {
      color: #198754;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="login-box">
  <img src="assets/logo_rs.png" alt="Logo RSIG">
  <h3>Login Aplikasi Diklat RSIG</h3>

  <?php if (!empty($pesan_error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($pesan_error) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3 text-start">
      <label for="username" class="form-label">Username</label>
      <input type="text" class="form-control" id="username" name="username" required autofocus>
    </div>
    <div class="mb-3 text-start">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-success w-100">Login</button>
  </form>
</div>

</body>
</html>
