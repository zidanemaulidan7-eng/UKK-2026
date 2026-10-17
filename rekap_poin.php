<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Poin Siswa - Guru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background-color: #f8f9fa; }
        .sidebar { width: 250px; min-height: 100vh; background-color: #343a40; color: #fff; position: fixed; }
        .sidebar .brand { padding: 1.5rem 1rem; font-size: 1.25rem; font-weight: bold; border-bottom: 1px solid #4f5962; }
        .sidebar a { color: #c2c7d0; text-decoration: none; padding: 12px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background-color: #0d6efd; color: #fff; }
        .content { margin-left: 250px; padding: 30px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="brand">Pelanggaran Siswa</div>
        <div class="nav flex-column mt-2">
            <a href="dashboard_guru.php">Dashboard</a>
            <a href="catat_pelanggaran.php">Catat Pelanggaran</a>
            <a href="tindakan.php">Tindakan</a>
            <a href="riwayat.php">Riwayat</a>
            <a href="rekap_poin.php" class="active">Rekap Poin</a>
            <hr class="dropdown-divider my-3 border-secondary">
            <a href="logout.php" class="text-danger">Logout</a>
        </div>
    </div>

    <div class="content">
        <h2>Rekap Poin Pelanggaran Siswa</h2>
        <p class="text-muted">Total poin akumulasi dari setiap siswa.</p>

        <div class="card p-3 shadow-sm border-0">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Total Poin</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada rekap poin tersedia.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>