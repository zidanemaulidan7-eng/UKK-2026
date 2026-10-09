<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

$nama = $_SESSION['nama_lengkap'] ?? 'Pengguna';
$role = $_SESSION['role'] ?? '';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

    <h1>Dashboard Sistem Pelanggaran Siswa</h1>

    <p>Selamat datang, <?php echo htmlspecialchars($nama); ?>!</p>
    <p>Role: <?php echo htmlspecialchars($role); ?></p>

    <hr>

    <?php if ($role === 'admin') { ?>

        <h2>Menu Admin</h2>
        <ul>
            <li><a href="kelola_guru.php">kelola Guru</a></li>
            <li><a href="kelola_kelas.php">kelola Kelas</a></li>
            <li><a href="kelola_siswa.php">kelola Siswa</a></li>
            <li><a href="pelanggaran.php">kelola Pelanggaran</a></li>
            <li><a href="kelola_tahun_ajaran.php">kelola tahun ajaran</a></li>
            <li><a href="kelola_wali_kelas.php">kelola wali kelas</a></li>
            <li><a href="penempatan_siswa.php">penempatan siswa</a></li>
        </ul>

    <?php } else { ?>

        <h2>Menu Pengguna</h2>
        <p>Selamat menggunakan sistem.</p>

    <?php } ?>

    <p><a href="logout.php">Logout</a></p>

</body>
</html>
```
