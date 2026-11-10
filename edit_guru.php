<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

// Cek apakah ada parameter ID di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: kelola_guru.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil data guru berdasarkan ID
$sql = "SELECT * FROM t_guru WHERE id = '$id'";
$query = mysqli_query($koneksi, $sql);
$guru = mysqli_fetch_assoc($query);

if (!$guru) {
    $_SESSION['pesan'] = "Data guru tidak ditemukan!";
    header("Location: kelola_guru.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Guru - <?= htmlspecialchars($guru['nama'] ?? ''); ?></title>
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
                <li class="nav-item"><a class="nav-link active" href="kelola_guru.php">Kelola Guru</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_kelas.php">Kelola Kelas</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_wali_kelas.php">Kelola Wali Kelas</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_jenis_pelanggaran.php">Kelola Jenis Pelanggaran</a></li>
                <li class="nav-item"><a class="nav-link" href="penempatan_siswa.php">Penempatan Siswa</a></li>
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
                        <div class="card shadow border-0">
                            <div class="card-header bg-primary text-white py-3">
                                <h4 class="mb-0 fs-5">Edit Data Guru</h4>
                            </div>
                            <div class="card-body p-4">
                                <form action="proses_guru.php" method="POST">
                                    <!-- Kirim aksi edit dan id guru -->
                                    <input type="hidden" name="aksi" value="edit">
                                    <input type="hidden" name="id" value="<?= $guru['id']; ?>">

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label fw-semibold">NIP</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($guru['nip']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label fw-semibold">Nama Guru</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($guru['nama']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label fw-semibold">Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($guru['email'] ?? ''); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label fw-semibold">Password Baru</label>
                                        <div class="col-sm-9">
                                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                                            <div class="form-text text-muted">Biarkan kosong jika password tidak ingin diganti.</div>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label fw-semibold">Konfirmasi Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password baru">
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label fw-semibold">Status Aktif</label>
                                        <div class="col-sm-9">
                                            <select name="status_aktif" class="form-select" required>
                                                <option value="1" <?= ($guru['status_aktif'] == 1) ? 'selected' : ''; ?>>Aktif</option>
                                                <option value="0" <?= ($guru['status_aktif'] == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="row">
                                        <div class="col-sm-9 offset-sm-3">
                                            <button type="submit" class="btn btn-primary px-4 me-2">Update Data</button>
                                            <a href="kelola_guru.php" class="btn btn-secondary px-4">Cancel</a>
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