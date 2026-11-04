<?php
session_start();
include 'includes/cek_session.php';

if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tambah Kelas</title>
    <style>
        /* Styling tambahan untuk menyesuaikan dengan sidebar di gambar */
        #sidebar {
            background-color: #212529;
            min-height: 100vh;
        }
        #sidebar .nav-link {
            color: #adb5bd;
            padding: 10px 15px;
            margin-bottom: 5px;
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
        }
        #sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.075);
        }
        #sidebar .nav-link.active {
            color: #ffffff !important;
            background-color: #0d6efd !important;
        }
        #sidebar .nav-link.text-danger:hover {
            background-color: rgba(220, 53, 69, 0.1);
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block p-3 collapse">
            <div class="position-sticky">
                <h4 class="text-white px-2 mb-4">Pelanggaran Siswa</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_siswa.php" class="nav-link">Kelola Siswa</a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_guru.php" class="nav-link">Kelola Guru</a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_kelas.php" class="nav-link active">Kelola Kelas</a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_tahun_ajaran.php" class="nav-link">Kelola Tahun Ajaran</a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_wali_kelas.php" class="nav-link">Kelola Wali Kelas</a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_kategori_pelanggaran.php" class="nav-link">Kelola Kategori Pelanggaran</a>
                    </li>
                    <li class="nav-item">
                        <a href="kelola_jenis_pelanggaran.php" class="nav-link">Kelola Jenis Pelanggaran</a>
                    </li>
                    <li class="nav-item">
                        <a href="penempatan_siswa.php" class="nav-link">Penempatan Siswa</a>
                    </li>
                    <li class="nav-item">
                        <a href="laporan.php" class="nav-link">Laporan</a>
                    </li>
                    <li class="nav-item mb-3">
                        <a href="about.php" class="nav-link">About Me</a>
                    </li>
                    <hr class="border-secondary">
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link text-danger">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0">Tambah Data Kelas</h4>
                            </div>
                            <div class="card-body">
                                <form action="proses_kelas.php" method="POST">
                                    <input type="hidden" name="aksi" value="tambah">

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Nama Kelas</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="nama" class="form-control" placeholder="Contoh: XII RPL 1" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Tingkat</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="tingkat" class="form-control" placeholder="Contoh: XII" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Jurusan</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="jurusan" class="form-control" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Status Aktif</label>
                                        <div class="col-sm-9">
                                            <select name="status_aktif" class="form-control" required>
                                                <option value="1">Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col-sm-9 offset-sm-3">
                                            <button type="submit" class="btn btn-primary">Simpan Kelas</button>
                                            <a href="kelola_kelas.php" class="btn btn-secondary">Kembali</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>