<?php
// koneksi.php
$host = '';
$user = 'root';
$pass = 'bismillah';
$db   = 'db_diklat';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}
?>