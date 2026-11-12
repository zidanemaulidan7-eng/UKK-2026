<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil data siswa berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM t_siswa WHERE id = $id");
$siswa = mysqli_fetch_assoc($query);

if (!$siswa) {
    $_SESSION['pesan'] = "Data siswa tidak ditemukan!";
    header("Location: kelola_siswa.php");
    exit();
}

// Proses Update Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nis            = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama           = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jenis_kelamin  = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tanggal_lahir  = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $alamat         = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $update = mysqli_query($koneksi, "UPDATE t_siswa SET nis = '$nis', nama = '$nama', jenis_kelamin = '$jenis_kelamin', tanggal_lahir = '$tanggal_lahir', alamat = '$alamat' WHERE id = $id");

    if ($update) {
        $_SESSION['pesan'] = "Data siswa berhasil diperbarui!";
        header("Location: kelola_siswa.php");
        exit();
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Form Edit Data Siswa</h4>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php } ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" class="form-control" maxlength="15" value="<?= htmlspecialchars($siswa['nis']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($siswa['nama']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="L" <?= ($siswa['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="P" <?= ($siswa['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="<?= htmlspecialchars($siswa['tanggal_lahir']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($siswa['alamat']); ?></textarea>
                        </div>
                        
                        <label class="form-label">Status Aktif</label>
                        <select name="status_aktif" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="kelola_siswa.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>