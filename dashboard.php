<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Fungsi helper aman untuk menghitung total baris tabel
function hitung_total($koneksi,$tabel_alternatif) {
    foreach ($tabel_alternatif as$tabel) {
        $check = mysqli_query($koneksi, "SHOW TABLES LIKE '$tabel'");
        if ($check && mysqli_num_rows($check) > 0) {
            $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM `$tabel`");
            if ($query) {
                $data = mysqli_fetch_assoc($query);
                return $data['total'] ?? 0;
            }
        }
    }
    return 0;
}

// Hitung statistik secara dinamis dari database
$jumlah_siswa     = hitung_total($koneksi, ['t_siswa', 'siswa']);
$jumlah_guru      = hitung_total($koneksi, ['t_guru', 'guru']);
$jumlah_kelas     = hitung_total($koneksi, ['t_kelas', 'kelas']);
$jumlah_wali_kelas = hitung_total($koneksi, ['t_wali_kelas', 'wali_kelas']);
$jumlah_kategori  = hitung_total($koneksi, ['t_kategori_pelanggaran', 'kategori_pelanggaran']);
$jumlah_pelanggaran = hitung_total($koneksi, ['t_pelanggaran', 'pelanggaran']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pelanggaran Siswa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h4 class="text-white mb-4">
                Pelanggaran Siswa
            </h4>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link active">Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link text-white">Kelola Siswa</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link text-white">Kelola Guru</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link text-white">Kelola Kelas</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link text-white">Kelola Tahun Ajaran</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link text-white">Kelola Wali Kelas</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link text-white">Kelola Kategori Pelanggaran</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link text-white">Kelola Jenis Pelanggaran</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="pelanggaran.php" class="nav-link text-white">Laporan</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="tentang_saya.php" class="nav-link text-white">About Me</a>
                </li>
                <hr class="text-secondary">
                <li class="nav-item mb-2">
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </li>
            </ul>
        </div>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">
            <h2>Dashboard</h2>
            <p>
                Welcome, <strong>Zidan</strong> (Role: <?php echo isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'admin'; ?>)
            </p>
            <p>Anda berhasil login ke dalam sistem.</p>
            <hr>
            
            <div class="row">
                <!-- CARD DATA SISWA -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-start border-primary border-4">
                        <div class="card-body">
                            <h5 class="card-title text-secondary">Data Siswa</h5>
                            <h2 class="fw-bold text-primary"><?php echo $jumlah_siswa; ?></h2>
                        </div>
                    </div>
                </div>

                <!-- CARD DATA GURU -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-start border-success border-4">
                        <div class="card-body">
                            <h5 class="card-title text-secondary">Data Guru</h5>
                            <h2 class="fw-bold text-success"><?php echo $jumlah_guru; ?></h2>
                        </div>
                    </div>
                </div>

                <!-- CARD KELAS -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-start border-info border-4">
                        <div class="card-body">
                            <h5 class="card-title text-secondary">Kelas</h5>
                            <h2 class="fw-bold text-info"><?php echo $jumlah_kelas; ?></h2>
                        </div>
                    </div>
                </div>

                <!-- CARD WALI KELAS -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-start border-warning border-4">
                        <div class="card-body">
                            <h5 class="card-title text-secondary">Wali Kelas</h5>
                            <h2 class="fw-bold text-warning"><?php echo $jumlah_wali_kelas; ?></h2>