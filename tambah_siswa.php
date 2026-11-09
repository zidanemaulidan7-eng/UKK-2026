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
</head>
<body class="bg-white">

<div class="container my-4" style="max-width: 800px;">
    <h2>Kelola Siswa</h2>
    <hr>
    
    <h4 class="mb-3">Tambah Data Siswa</h4>

    <?php if (isset($error)) { ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php } ?>

    <!-- Form dengan tata letak vertikal sesuai gambar referensi -->
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
            <!-- Menggunakan input text/readonly atau select yang tampilannya menyerupai gambar -->
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>