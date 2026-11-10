<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me - Pelanggaran Siswa</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background-color: #f8f9fa;
        }
        /* Style Sidebar agar konsisten dan sticky tanpa scroll internal */
        .sidebar-sticky {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            background-color: #212529;
            color: #fff;
            flex-shrink: 0;
        }
        /* Memperkecil padding link sidebar agar muat sempurna */
        .sidebar-sticky .nav-link {
            color: #adb5bd;
            padding-top: 0.35rem !important;
            padding-bottom: 0.35rem !important;
            padding-left: 1rem;
            padding-right: 1rem;
            margin-bottom: 0.15rem !important;
            font-size: 0.95rem;
            border-radius: 0.375rem;
        }
        .sidebar-sticky .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar-sticky .nav-link.logout {
            color: #dc3545;
        }
        .sidebar-sticky .nav-link.logout:hover {
            background-color: #dc3545;
            color: #fff;
        }
        /* Mengatur agar konten mengisi penuh layar di sebelah kanan */
        .main-content {
            flex-grow: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .full-card {
            width: 100%;
            height: 100%;
            border-radius: 0 !important;
        }
    </style>
</head>
<body class="bg-light">

<div class="d-flex">
    <!-- SIDEBAR -->
    <div class="col-md-3 col-lg-2 bg-dark p-3 sidebar-sticky d-flex flex-column justify-content-between">
        <div>
            <h4 class="text-white mb-3 fs-5">
                Pelanggaran Siswa
            </h4>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link text-white">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_siswa.php" class="nav-link text-white">Kelola Siswa</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_guru.php" class="nav-link text-white">Kelola Guru</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_kelas.php" class="nav-link text-white">Kelola Kelas</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_tahun_ajaran.php" class="nav-link text-white">Kelola Tahun Ajaran</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_wali_kelas.php" class="nav-link text-white">Kelola Wali Kelas</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link text-white">Kelola Kategori Pelanggaran</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link text-white">Kelola Jenis Pelanggaran</a>
                </li>
                <li class="nav-item">
                    <a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a>
                </li>
                <li class="nav-item">
                    <a href="pelanggaran.php" class="nav-link text-white">Laporan</a>
                </li>
                <li class="nav-item">
                    <!-- Menu About Me aktif (Tombol Biru) -->
                    <a href="tentang_saya.php" class="nav-link btn btn-primary text-white text-start">About Me</a>
                </li>
                <hr class="text-secondary my-2">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link logout">Logout</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- KONTEN UTAMA PENUH DI LAYAR -->
    <div class="main-content">
        <div class="card full-card shadow-sm border-0 d-flex flex-column justify-content-between">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0 fw-bold">PROFIL PENGEMBANG</h4>
                <small class="text-white-50">Sistem Informasi Pelanggaran Siswa</small>
            </div>
            
            <div class="card-body p-4 p-lg-5 overflow-auto my-auto" style="max-width: 800px; margin: 0 auto; width: 100%;">
                <div class="text-center mb-4">
                    <!-- Foto Profil -->
                    <div class="d-inline-block mb-2 shadow-sm rounded-circle" style="width: 100px; height: 100px; overflow: hidden; border: 3px solid #0d6efd;">
                        <img src="karakter.jfif" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h4 class="fw-bold mb-1">Zidan</h4>
                    <span class="badge bg-primary">Siswa Rekayasa Perangkat Lunak</span>
                </div>

                <table class="table table-borderless fs-6 mb-4">
                    <tbody>
                        <tr>
                            <th style="width: 30%;" class="text-secondary">Nama</th>
                            <td style="width: 5%;">:</td>
                            <td class="fw-semibold">Zidan</td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Nama Panggilan</th>
                            <td>:</td>
                            <td class="fw-semibold">Zee</td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Umur</th>
                            <td>:</td>
                            <td>19 Tahun</td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Cita-cita</th>
                            <td>:</td>
                            <td>Youtuber</td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Asal Sekolah</th>
                            <td>:</td>
                            <td>SMK Muhammadiyah Tasikmalaya</td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Kota</th>
                            <td>:</td>
                            <td>Jepang</td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Kota Asal</th>
                            <td>:</td>
                            <td>Tasikmalaya</td>
                        </tr>
                    </tbody>
                </table>

                <div class="bg-light p-3 rounded-3 border mb-4">
                    <small class="fw-bold text-secondary d-block mb-1">Bio:</small>
                    <p class="text-dark mb-0">
                        Saya ingin belajar berkebun di Kyoto, Jepang dan bekerja di sana. Semoga berhasil my friend walau terasa sulit.
                    </p>
                </div>

                <div class="d-grid gap-2">
                    <a href="dashboard.php" class="btn btn-primary btn-lg rounded-3 fw-bold">
                        ← Kembali ke Dashboard
                    </a>
                </div>
            </div>

            <div class="card-footer bg-white text-center text-muted border-0 py-3">
                <small>© UKK 2026 - Rekayasa Perangkat Lunak</small>
            </div>
        </div>
    </div>
</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>