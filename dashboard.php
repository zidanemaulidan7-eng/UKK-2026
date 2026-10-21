<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Hitung jumlah total wali kelas (opsional untuk card dashboard)
$query_wk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_wali_kelas");
$data_wk = mysqli_fetch_assoc($query_wk);
$jumlah_wali_kelas = $data_wk['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Dashboard - Pelanggaran Siswa</title>
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
                    <a href="dashboard.php" class="nav-link active">
                        Dashboard
                    </a>
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
                <!-- Menu Kelola Kategori Pelanggaran ditambahkan di bawah Kelola Wali Kelas -->
                <li class="nav-item mb-2">
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link text-white">Kelola Kategori Pelanggaran</a>
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
                Welcome, <strong>Zidan</strong> (Role: <?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'admin'; ?>)
            </p>
            <p>Anda berhasil login ke dalam sistem.</p>
            <hr>
            
            <div class="row">
                <!-- CARD DATA SISWA -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Data Siswa</h5>
                            <h2>120</h2>
                        </div>
                    </div>
                </div>
                <!-- CARD DATA GURU -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Data Guru</h5>
                            <h2>25</h2>
                        </div>
                    </div>
                </div>
                <!-- CARD KELAS -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Kelas</h5>
                            <h2>12</h2>
                        </div>
                    </div>
                </div>
                <!-- CARD WALI KELAS -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-left-primary">
                        <div class="card-body">
                            <h5 class="card-title">Wali Kelas</h5>
                            <h2><?php echo $jumlah_wali_kelas; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>