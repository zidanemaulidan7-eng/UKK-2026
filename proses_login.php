<?php
session_start();
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = mysqli_real_escape_string(
    $koneksi,
    $_POST['email'] ?? ''
);
$password = $_POST['password'] ?? '';

$sql = "SELECT * FROM t_users WHERE email = '$email'";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Cek apakah email ditemukan di database
if (mysqli_num_rows($hasil) > 0) {
    $data = mysqli_fetch_assoc($hasil);

    // Verifikasi password
    if (password_verify($password, $data['password'])) {
        session_regenerate_id(true);
        $_SESSION['login'] = true;
        $_SESSION['id_user'] = $data['id'];
        $_SESSION['nama_lengkap'] = $data['name'];
        $_SESSION['role'] = $data['role'];

        if ($data['role'] == 'admin') {
            header("Location: dashboard.php");
            exit;
        } else {
            header("Location: dashboard.php");
            exit;
        }
    } else {
        echo "Password salah!";
    }
} else {
    echo "Email tidak ditemukan!";
}
?>