<?php
include 'config/koneksi.php';

$name = 'Guru';
$email = 'guru@gmail.com';
$password = password_hash('guru123', PASSWORD_DEFAULT);
$role = 'guru';

$sql = "INSERT INTO t_users (name, email, password, role)
        VALUES (?, ?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $password, $role);

if (mysqli_stmt_execute($stmt)) {
    echo "Akun Guru berhasil dibuat.";
} else {
    echo "Gagal: " . mysqli_error($koneksi);
}
?>