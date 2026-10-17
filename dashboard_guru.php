<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Batasi halaman ini untuk akun Guru
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'guru') {
    header("Location: akses_ditolak.php");
    exit();
}

// Nama Guru
$nama_guru = $_SESSION['nama_lengkap']
    ?? $_SESSION['name']
    ?? 'Guru';

// Hitung total laporan pelanggaran
$jumlah_pelanggaran = 0;
$query_pelanggaran = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_pelanggaran"
);

if ($query_pelanggaran) {
    $data_pelanggaran = mysqli_fetch_assoc($query_pelanggaran);
    $jumlah_pelanggaran = (int) ($data_pelanggaran['total'] ?? 0);
}

// Hitung total siswa
$jumlah_siswa = 0;
$query_siswa = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_siswa"
);

if ($query_siswa) {
    $data_siswa = mysqli_fetch_assoc($query_siswa);
    $jumlah_siswa = (int) ($data_siswa['total'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { margin: 0; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #343a40; }
        .sidebar .nav-link { color: #c2c7d0; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #0d6efd; color: white; }
        .stat-card { min-height: 120px; border-radius: 8px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR GURU -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 sidebar">
            <h4 class="text-white mb-4 px-2 fs-5">Pelanggaran Siswa</h4>

            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-1">
                    <a href="dashboard_guru.php" class="nav-link active">Dashboard</a>
                </li>
                <li class="nav-item mb-1">
                    <a href="catat_pelanggaran.php" class="nav-link">Catat Pelanggaran</a>
                </li>
                <li class="nav-item mb-1">
                    <a href="tindakan.php" class="nav-link">Tindakan</a>
                </li>
                <li class="nav-item mb-1">
                    <a href="riwayat.php" class="nav-link">Riwayat</a>
                </li>
                <li class="nav-item mb-1">
                    <a href="rekap_poin.php" class="nav-link">Rekap Poin</a>
                </li>

                <hr class="text-secondary my-3">

                <li class="nav-item mb-1">
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </li>
            </ul>
        </div>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">
            <h2 class="fw-bold">Dashboard</h2>
            <p class="text-muted">
                Selamat datang, <strong><?= htmlspecialchars($nama_guru); ?></strong> (Role: guru)
            </p>
            <p>Anda berhasil login ke dalam sistem.</p>

            <hr class="my-4">

            <div class="row g-3">

                <!-- CARD DATA SISWA -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 stat-card p-3">
                        <div class="card-body p-0">
                            <h6 class="text-muted">Data Siswa</h6>
                            <h2 class="fw-bold my-2"><?= $jumlah_siswa; ?></h2>
                        </div>
                    </div>
                </div>

                <!-- CARD LAPORAN PELANGGARAN -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 stat-card p-3">
                        <div class="card-body p-0">
                            <h6 class="text-muted">Laporan Pelanggaran</h6>
                            <h2 class="fw-bold my-2"><?= $jumlah_pelanggaran; ?></h2>
                        </div>
                    </div>
                </div>

                <!-- CARD RIWAYAT PELANGGARAN -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 stat-card p-3">
                        <div class="card-body p-0">
                            <h6 class="text-muted">Riwayat Pelanggaran</h6>
                            <a href="riwayat.php" class="btn btn-success btn-sm mt-3">Lihat Riwayat</a>
                        </div>
                    </div>
                </div>

                <!-- CARD REKAP POIN -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 stat-card p-3">
                        <div class="card-body p-0">
                            <h6 class="text-muted">Rekap Poin</h6>
                            <a href="rekap_poin.php" class="btn btn-primary btn-sm mt-3">Lihat Rekap</a>
                        </div>
                    </div>
                </div>

            </div>
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>