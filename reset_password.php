<?php
include 'config/koneksi.php';

$email_target = 'guru@gmail.com';
$password_baru = 'guru123';

// Generate hash yang dijamin 100% cocok dengan PHP
$password_hashed = password_hash($password_baru, PASSWORD_DEFAULT);

$sql = "UPDATE t_users SET password = ? WHERE email = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "ss", $password_hashed, $email_target);

if (mysqli_stmt_execute($stmt)) {
    echo "<h3>Berhasil!</h3>";
    echo "Password untuk <b>$email_target</b> telah di-reset menjadi: <b>$password_baru</b><br>";
    echo "<a href='login.php'>Kembali ke Halaman Login</a>";
} else {
    echo "Gagal mereset password: " . mysqli_error($koneksi);
}
?>