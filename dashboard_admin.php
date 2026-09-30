
<?php
require_once "cek_session.php";

// Hanya Admin yang boleh masuk
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard_guru.php");
    exit;
}

$nama_user = $_SESSION['nama_user'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
</head>
<body>

    <h1>Dashboard Admin</h1>
    <p>Selamat datang, <strong>
        <?php echo htmlspecialchars($nama_user); ?>
    </strong></p>

    <p>Role: Admin</p>
    <hr>

    <h2>Menu Admin</h2>

    <ul>
        <li><a href="data_siswa.php">Kelola Data Siswa</a></li>
        <li><a href="data_guru.php">Kelola Data Guru</a></li>
        <li><a href="pencatatan_pelanggaran.php">Pencatatan Pelanggaran</a></li>
        <li><a href="perhitungan_poin.php">Perhitungan Poin</a></li>
        <li><a href="laporan.php">Laporan</a></li>
    </ul>

    <hr>

    <a href="logout.php"
       onclick="return confirm('Apakah kamu yakin ingin logout?')">
        Logout
    </a>

    <p>&copy; 2026 Sistem Pelanggaran siswa</p>

</body>
</html>