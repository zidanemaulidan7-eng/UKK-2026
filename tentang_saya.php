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
    <title>Tentang Saya - Biodata</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .bio-card {
            max-width: 500px;
            width: 100%;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-4">

<div class="bio-card px-3">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-dark text-white text-center py-3 rounded-top-4">
            <h4 class="mb-0 fw-bold">PROFIL PENGEMBANG</h4>
            <small class="text-secondary">Sistem Informasi Pelanggaran Siswa</small>
        </div>
        
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold fs-1 mb-2" style="width: 80px; height: 80px;">
                    Z
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
                        <td>20 Tahun</td>
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
                    saya ingin belajar berkebun di kyoto,jepang dan bekerja di sana. semoga berhasil my friend.
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

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>