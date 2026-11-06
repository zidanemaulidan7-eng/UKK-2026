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
        /* Style Sidebar agar konsisten */
        .sidebar {
            min-height: 100vh;
            background-color: #212529;
            color: #fff;
            width: 260px;
            flex-shrink: 0;
        }
        .sidebar h3 {
            font-size: 1.25rem;
            padding: 1rem;
            color: #fff;
            border-bottom: 1px solid #373b3e;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1rem;
            margin-bottom: 0.2rem;
            border-radius: 0.375rem;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
        .sidebar .nav-link.logout {
            color: #dc3545;
        }
        .sidebar .nav-link.logout:hover {
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
    <nav class="sidebar px-3 py-4 d-flex flex-column justify-content-between">
        <div>
            <h3 class="mb-3">Pelanggaran Siswa</h3>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_siswa.php">Kelola Siswa</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_guru.php">Kelola Guru</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_kelas.php">Kelola Kelas</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_wali_kelas.php">Kelola Wali Kelas</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a></li>
                <li class="nav-item"><a class="nav-link" href="pelanggaran.php">Kelola Jenis Pelanggaran</a></li>
                <li class="nav-item"><a class="nav-link" href="penempatan_siswa.php">Penempatan Siswa</a></li>
                <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan</a></li>
                <li class="nav-item"><a class="nav-link active" href="tentang_saya.php">About Me</a></li>
            </ul>
        </div>
        <div>
            <hr class="text-secondary">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link logout" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

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