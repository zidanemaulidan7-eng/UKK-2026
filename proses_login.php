<?php
// proses_login.php
session_start();
include 'config/koneksi.php';
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = $_POST['password'];

$email = mysqli_real_escape_string($koneksi, $_POST['email']);

$sql = "SELECT * FROM t_users WHERE email = '$email'";

if (mysqli_num_rows($hasil) == 1) {
    $data = mysqli_fetch_assoc($hasil);

    if (password_verify($password, $data['password'])) {
        // password cocok, buat session
$_SESSION['login'] = true;
$_SESSION['name'] = $data['nama'];
$_SESSION['email'] = $data['email'];
$_SESSION['role'] = $data['role'];


        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['pesan_error'] = 'password salah!';
        header('Location: login.php');
        exit;
    }
} else {
    $_SESSION['pesan_error'] = 'email tidak ditemukan!';
    header('Location: login.php');
}
?>