<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me - Pelanggaran Siswa</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        /* Style Sidebar */
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: #fff;
            width: 250px;
            flex-shrink: 0;
        }
        .sidebar a {
            color: #cfd8dc;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 6px;
            margin-bottom: 4px;
        }
        .sidebar a:hover {
            background-color: #495057;
            color: #fff;
        }
        .sidebar a.active {
            background-color: #0d6efd;
            color: #fff;
            font-weight: bold;
        }
        .bio-card {
            max-width: 500px;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- SIDEBAR -->
    <div class="sidebar p-3">
        <h4 class="fw-bold mb-4 px-2">Pelanggaran Siswa</h4>
        <nav class="nav flex-column">
            <a href="dashboard.php">Dashboard</a>
            <a href="kelola_siswa.php">Kelola Siswa</a>
            <a href="kelola_guru.php">Kelola Guru</a>
            <a href="kelola_kelas.php">Kelola Kelas</a>
            <a href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a>
            <a href="kelola_wali_kelas.php">Kelola Wali Kelas</a>
            <a href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a>
            <a href="penempatan_siswa.php">Penempatan Siswa</a>
            <a href="kelola_jenis_pelanggaran.php">Kelola Jenis Pelanggaran</a>
            <a href="pelanggaran.php">Laporan</a>
            <a href="tentang_saya.php" class="active">About Me</a>
            <a href="logout.php" class="text-danger mt-4">Logout</a>
        </nav>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="flex-grow-1 p-4 d-flex justify-content-center align-items-center">
        <div class="bio-card">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-dark text-white text-center py-3 rounded-top-4">
                    <h4 class="mb-0 fw-bold">PROFIL PENGEMBANG</h4>
                    <small class="text-secondary">Sistem Informasi Pelanggaran Siswa</small>
                </div>
                
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <!-- Foto Profil Anime -->
                        <div class="d-inline-block mb-2 shadow-sm rounded-circle" style="width: 90px; height: 90px; overflow: hidden; border: 3px solid #0d6efd;">
                            <img src="karakter.jfif" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h5 class="fw-bold mb-1">Zidan</h5>
                        <span class="badge bg-primary">Siswa Rekayasa Perangkat Lunak</span>
                    </div>

                    <table class="table table-borderless fs-6 mb-3">
                        <tbody>
                            <tr>
                                <th style="width: 35%;" class="text-secondary">Nama</th>
                                <td style="width: 5%;">:</td>
                                <td class="fw-semibold">Zidan</td>
                            </tr>
                            <tr>
                                <th class="text-secondary">Umur</th>
                                <td>:</td>
                                <td>19 Tahun</td>
                            </tr>
                            <tr>
                                <th class="text-secondary">Cita-cita</th>
                                <td>:</td>
                                <td>youtuber</td>
                            </tr>
                            <tr>
                                <th class="text-secondary">Asal Sekolah</th>
                                <td>:</td>
                                <td>SMK Muhammadiyah Tasikmalaya</td>
                            </tr>
                            <tr>
                                <th class="text-secondary">Kota</th>
                                <td>:</td>
                                <td>Tasikmalaya</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="bg-light p-3 rounded-3 border mb-3">
                        <small class="fw-bold text-secondary d-block mb-1">Bio:</small>
                        <p class="small text-dark mb-0">
                            saya ingin belajar berkebun di kyoto,jepang dan bekerja di sana. semoga berhasil my friend
                            walau terasa sulit.
                        </p>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="dashboard.php" class="btn btn-primary btn-lg rounded-3 fw-bold">
                            ← Kembali ke Dashboard
                        </a>
                    </div>
                </div>

                <div class="card-footer bg-white text-center text-muted border-0 pb-3">
                    <small>© UKK 2026 - Rekayasa Perangkat Lunak</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>