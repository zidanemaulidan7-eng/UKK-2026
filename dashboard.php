<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Fungsi helper aman untuk menghitung total baris tabel
function hitung_total($koneksi, $tabel_alternatif) {
    foreach ($tabel_alternatif as $tabel) {
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
$jumlah_siswa       = hitung_total($koneksi, ['t_siswa', 'siswa']);
$jumlah_guru        = hitung_total($koneksi, ['t_guru', 'guru']);
$jumlah_kelas       = hitung_total($koneksi, ['t_kelas', 'kelas']);
$jumlah_wali_kelas  = hitung_total($koneksi, ['t_wali_kelas', 'wali_kelas']);
$jumlah_kategori    = hitung_total($koneksi, ['t_kategori_pelanggaran', 'kategori_pelanggaran']);
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
    
    <style>
        /* Mengunci sidebar agar diam total dan tidak bisa di-scroll / muncul scrollbar */
        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 16.666667%; /* Lebar standar col-lg-2 */
            height: 100vh;
            z-index: 100;
            overflow: hidden; /* Mencegah pergerakan atau scroll terpisah */
            background-color: #212529;
            padding: 15px;
        }

        /* Membuat jarak dan ukuran menu ringkas agar pas di berbagai tinggi layar */
        .sidebar-fixed h4 {
            font-size: 1.1rem;
            margin-bottom: 0.8rem !important;
            color: #fff;
            border-bottom: 1px solid #373b3e;
            padding-bottom: 0.5rem;
        }
        .sidebar-fixed .nav-item {
            margin-bottom: 2px !important;
        }
        .sidebar-fixed .nav-link {
            color: #fff !important; /* Teks menu warna putih */
            padding: 6px 10px;
            font-size: 0.88rem;
            border-radius: 0.375rem;
        }
        .sidebar-fixed .nav-link:hover {
            background-color: #343a40;
        }
        .sidebar-fixed .nav-link.active {
            background-color: #0d6efd !important;
            color: #fff !important;
        }
        .sidebar-fixed .nav-link.logout {
            color: #dc3545 !important;
        }
        .sidebar-fixed .nav-link.logout:hover {
            background-color: #dc3545 !important;
            color: #fff !important;
        }
        .sidebar-fixed hr {
            margin: 0.5rem 0;
            border-color: #373b3e;
        }

        /* Memberikan jarak margin kiri pada konten utama agar tidak tertutup sidebar */
        .main-content {
            margin-left: 16.666667%;
            width: 83.333333%;
        }

        /* Responsif untuk layar medium / tablet */
        @media (max-width: 991.98px) {
            .sidebar-fixed {
                width: 25%; /* Lebar col-md-3 */
            }
            .main-content {
                margin-left: 25%;
                width: 75%;
            }
        }

        /* Responsif untuk layar kecil / HP */
        @media (max-width: 767.98px) {
            .sidebar-fixed {
                position: relative;
                width: 100%;
                height: auto;
                overflow: visible;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="sidebar-fixed">
            <h4 class="mb-3">Pelanggaran Siswa</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link active">Dashboard</a></li>
                <li class="nav-item"><a href="kelola_siswa.php" class="nav-link">Kelola Siswa</a></li>
                <li class="nav-item"><a href="kelola_guru.php" class="nav-link">Kelola Guru</a></li>
                <li class="nav-item"><a href="kelola_kelas.php" class="nav-link">Kelola Kelas</a></li>
                <li class="nav-item"><a href="kelola_tahun_ajaran.php" class="nav-link">Kelola Tahun Ajaran</a></li>
                <li class="nav-item"><a href="kelola_wali_kelas.php" class="nav-link">Kelola Wali Kelas</a></li>
                <li class="nav-item"><a href="kelola_kategori_pelanggaran.php" class="nav-link">Kelola Kategori Pelanggaran</a></li>
                <li class="nav-item"><a href="kelola_jenis_pelanggaran.php" class="nav-link">Kelola Jenis Pelanggaran</a></li>
                <li class="nav-item"><a href="penempatan_siswa.php" class="nav-link">Penempatan Siswa</a></li>
                <li class="nav-item"><a href="tentang_saya.php" class="nav-link">About Me</a></li>
            </ul>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="logout.php" class="nav-link logout">Logout</a></li>
            </ul>
        </div>
            </ul>
            <hr>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link logout">Logout</a>
                </li>
            </ul>
        </div>

        <!-- KONTEN UTAMA -->
        <main class="p-4 main-content">
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
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>