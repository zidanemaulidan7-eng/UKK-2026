<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Proses penyimpanan data saat tombol "Tambah Siswa" diklik
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nis           = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nisn          = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $nama          = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $status_aktif  = mysqli_real_escape_string($koneksi, $_POST['status_aktif']);

    $query = "INSERT INTO t_siswa (nis, nisn, nama, jenis_kelamin, tanggal_lahir, alamat, status_aktif) 
              VALUES ('$nis', '$nisn', '$nama', '$jenis_kelamin', '$tanggal_lahir', '$alamat', '$status_aktif')";
              
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan'] = "Data siswa berhasil ditambahkan!";
        header("Location: kelola_siswa.php");
        exit();
    } else {
        $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        /* Styling Sidebar */
        .sidebar {
            min-height: 100vh;
            background-color: #212529;
            color: #fff;
        }
        .sidebar h3 {
            font-size: 1.25rem;
            font-weight: bold;
            padding: 20px 15px;
            color: #fff;
            border-bottom: 1px solid #373b3e;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 10px 15px;
            margin: 4px 10px;
            border-radius: 6px;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: #343a40;
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse px-0">
            <div class="position-sticky pt-2">
                <h3 class="px-3 mb-2">Pelanggaran<br>Siswa</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="kelola_siswa.php">Kelola Siswa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kelola_guru.php">Kelola Guru</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kelola_kelas.php">Kelola Kelas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kelola_wali_kelas.php">Kelola Wali Kelas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kelola_jenis_pelanggaran.php">Kelola Jenis Pelanggaran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="penempatan_siswa.php">Penempatan Siswa</a>
                    </li>
                    <li class="nav-item mb-2">
                    <a href="pelanggaran.php" class="nav-link">Laporan</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="tentang_saya.php" class="nav-link">About Me</a>
                </li>
                </ul>
            </div>
        </nav>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="container" style="max-width: 800px;">
                <h2>Kelola Siswa</h2>
                <hr>
                
                <h4 class="mb-3">Tambah Data Siswa</h4>

                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger"><?= $error; ?></div>
                <?php } ?>

                <!-- Form Tambah Siswa -->
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">NIS</label>
                        <input type="text" name="nis" class="form-control" maxlength="15" inputmode="numeric" pattern="[0-9]*" oninput="if(this.value.length > 15) this.value = this.value.slice(0, 15);" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NISN</label>
                        <input type="text" name="nisn" class="form-control" maxlength="10" inputmode="numeric" pattern="[0-9]*" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="" disabled selected>-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Aktif</label>
                        <select name="status_aktif" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Tambah Siswa</button>
                        <a href="kelola_siswa.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>