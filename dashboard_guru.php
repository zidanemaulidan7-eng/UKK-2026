
<?php
require_once "cek_session.php";

// Hanya Guru yang boleh masuk
if ($_SESSION['role'] !== 'guru') {
    header("Location: dashboard_admin.php");
    exit;
}

$nama_user = $_SESSION['nama_user'] ?? 'Guru';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru</title>
</head>
<body>

    <h1>Dashboard Guru</h1>
    <p>Selamat datang, <strong>
        <?php echo htmlspecialchars($nama_user); ?>
    </strong></p>

    <p>Role: Guru</p>
    <hr>

    <h2>Menu Guru</h2>

    <ul>
        <li><a href="lihat_data_siswa.php">Lihat Data Siswa</a></li>
        <li><a href="lihat_data_pelanggaran.php">Lihat Data Pelanggaran</a></li>
        <li><a href="input_pelanggaran.php">Input Pelanggaran</a></li>
        <li><a href="lihat_poin_siswa.php">Lihat Poin Siswa</a></li>
        <li><a href="laporan_guru.php">Laporan</a></li>
    </ul>

    <hr>

    <a href="logout.php"
       onclick="return confirm('Apakah kamu yakin ingin logout?')">
        Logout
    </a>

    <p>&copy; 2026 Sistem Informasi Sekolah</p>

</body>
</html>