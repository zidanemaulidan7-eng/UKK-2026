<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: pelanggaran.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM t_pelanggaran WHERE id = '$id'");
$pelanggaran = mysqli_fetch_assoc($query);

if (!$pelanggaran) {
    $_SESSION['pesan'] = "Data pelanggaran tidak ditemukan!";
    header("Location: pelanggaran.php");
    exit();
}

// Ambil data kategori untuk pilihan select
$sql_kategori = "SELECT * FROM t_pelanggaran_kategori ORDER BY nama ASC";
$query_kategori = mysqli_query($koneksi, $sql_kategori);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Pelanggaran - <?= htmlspecialchars($pelanggaran['nama']); ?></title>
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
                <li class="nav-item"><a class="nav-link" href="kelola_kelas.php">Kelola Kelas</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_wali_kelas.php">Kelola Wali Kelas</a></li>
                <li class="nav-item"><a class="nav-link" href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a></li>
                <li class="nav-item"><a class="nav-link active" href="pelanggaran.php">Kelola Jenis Pelanggaran</a></li>
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
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0">Edit Data Pelanggaran</h4>
                            </div>
                            <div class="card-body">
                                <form action="proses_pelanggaran.php" method="POST">
                                    <input type="hidden" name="aksi" value="edit">
                                    <input type="hidden" name="id" value="<?= $pelanggaran['id']; ?>">

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Kategori</label>
                                        <div class="col-sm-9">
                                            <select name="pelanggaran_kategori_id" class="form-control" required>
                                                <option value="">-- Pilih Kategori --</option>
                                                <?php while ($kategori = mysqli_fetch_assoc($query_kategori)) { ?>
                                                    <option value="<?= $kategori['id']; ?>" <?= ($pelanggaran['pelanggaran_kategori_id'] == $kategori['id']) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($kategori['nama']); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Kode</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="kode" class="form-control" value="<?= htmlspecialchars($pelanggaran['kode']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Nama Pelanggaran</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($pelanggaran['nama']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Poin</label>
                                        <div class="col-sm-9">
                                            <input type="number" name="poin" class="form-control" min="0" value="<?= htmlspecialchars($pelanggaran['poin']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Deskripsi</label>
                                        <div class="col-sm-9">
                                            <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($pelanggaran['deskripsi']); ?></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label">Status Aktif</label>
                                        <div class="col-sm-9">
                                            <select name="status_aktif" class="form-control" required>
                                                <option value="1" <?= ($pelanggaran['status_aktif'] == 1) ? 'selected' : ''; ?>>Aktif</option>
                                                <option value="0" <?= ($pelanggaran['status_aktif'] == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col-sm-9 offset-sm-3">
                                            <button type="submit" class="btn btn-primary">Update Data</button>
                                            <a href="pelanggaran.php" class="btn btn-secondary">Kembali</a>
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