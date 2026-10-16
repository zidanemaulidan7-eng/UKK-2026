
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <title>Dashboard Guru - Pelanggaran Siswa</title>

    <style>
        body {
            margin: 0;
        }

        .sidebar {
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: white;
        }

        .sidebar .nav-link.active {
            background-color: #0d6efd;
        }

        .stat-card {
            min-height: 125px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR GURU -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 sidebar">

            <h4 class="text-white mb-4">
                Pelanggaran Siswa
            </h4>

            <ul class="nav nav-pills flex-column">

                <li class="nav-item mb-2">
                    <a href="dashboard_guru.php"
                       class="nav-link active">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="pelanggaran.php"
                       class="nav-link">
                        Laporan Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="riwayat.php"
                       class="nav-link">
                        Riwayat
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="rekap_poin.php"
                       class="nav-link">
                        Rekap Poin
                    </a>
                </li>

                <hr class="text-secondary">

                <li class="nav-item mb-2">
                    <a href="logout.php"
                       class="nav-link text-danger">
                        Logout
                    </a>
                </li>

            </ul>
        </div>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2>Dashboard</h2>

            <p>
                Selamat datang,
                <strong>
                    <?= htmlspecialchars($nama_guru); ?>
                </strong>
                (Role: guru)
            </p>

            <p>Anda berhasil login ke dalam sistem.</p>

            <hr>

            <div class="row">

                <!-- CARD DATA SISWA -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm stat-card">
                        <div class="card-body">
                            <h5 class="card-title">Data Siswa</h5>
                            <h2><?= $jumlah_siswa; ?></h2>
                        </div>
                    </div>
                </div>

                <!-- CARD LAPORAN PELANGGARAN -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm stat-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                Laporan Pelanggaran
                            </h5>
                            <h2><?= $jumlah_pelanggaran; ?></h2>
                        </div>
                    </div>
                </div>

                <!-- CARD RIWAYAT -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm stat-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                Riwayat Pelanggaran
                            </h5>

                            <a href="riwayat.php"
                               class="btn btn-success btn-sm mt-2">
                                Lihat Riwayat
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CARD REKAP POIN -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm stat-card">
                        <div class="card-body">
                            <h5 class="card-title">Rekap Poin</h5>

                            <a href="rekap_poin.php"
                               class="btn btn-primary btn-sm mt-2">
                                Lihat Rekap
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </main>

    </div>
</div>

</body>
</html>
