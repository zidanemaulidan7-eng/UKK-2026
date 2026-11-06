<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: kelola_kelas.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM t_kelas WHERE id = '$id'");
$kelas = mysqli_fetch_assoc($query);

if (!$kelas) {
    header("Location: kelola_kelas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Kelas - <?= htmlspecialchars($kelas['nama']); ?></title>
    <style>
        /* Styling Sidebar */
        .sidebar {
            min-height: 100vh;
            background-color: #212529;
            color: #fff;
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
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
            border-radius: 0.375rem;
        }
        .sidebar .nav-link.logout {
            color: #dc3545;
        }
        .sidebar .nav-link.logout:hover {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse px-3 py-4">
            <h3 class="mb-3">Pelanggaran Siswa</h3>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_siswa.php">Kelola Siswa</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_guru.php">Kelola Guru</a></li>
                <li class="nav-item"><a class="nav-link active" href="kelola_kelas.php">Kelola Kelas</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_wali_kelas.php">Kelola Wali Kelas</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_jenis_pelanggaran.php">Kelola Jenis Pelanggaran</a></li>
                <li class="nav-item"><a class="nav-link" href="penempatan_siswa.php">Penempatan Siswa</a></li>
                <Spacer></Spacer>
                <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan</a></li>
                <li class="nav-item"><a class="nav-link" href="about_me.php">About Me</a></li>
            </ul>
            <hr class="text-secondary">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link logout" href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="container mt-2">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card shadow">
                            <!-- Diubah dari bg-warning text-dark menjadi bg-primary text-white -->
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0">Edit Data Kelas</h4>
                            </div>
                            <div class="card-body">
                                <form action="proses_kelas.php" method="POST">
                                    <input type="hidden" name="aksi" value="edit">
                                    <input type="hidden" name="id" value="<?= $kelas['id']; ?>">

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Nama Kelas</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($kelas['nama']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Tingkat</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="tingkat" class="form-control" value="<?= htmlspecialchars($kelas['tingkat']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Jurusan</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="jurusan" class="form-control" value="<?= htmlspecialchars($kelas['jurusan']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Status Aktif</label>
                                        <div class="col-sm-9">
                                            <select name="status_aktif" class="form-control" required>
                                                <option value="1" <?= ($kelas['status_aktif'] == 1) ? 'selected' : ''; ?>>Aktif</option>
                                                <option value="0" <?= ($kelas['status_aktif'] == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col-sm-9 offset-sm-3">
                                            <!-- Tombol juga disamakan menjadi btn-primary -->
                                            <button type="submit" class="btn btn-primary">Update Data</button>
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