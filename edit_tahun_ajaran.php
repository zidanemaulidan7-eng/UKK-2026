<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil ID dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelola_tahun_ajaran.php");
    exit();
}

$id = intval($_GET['id']);

// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $status_aktif = $_POST['status_aktif'];

    if (!empty($nama) && !empty($tanggal_mulai) && !empty($tanggal_selesai)) {
        $stmt = mysqli_prepare($koneksi, "UPDATE t_tahun_ajaran SET nama = ?, tanggal_mulai = ?, tanggal_selesai = ?, status_aktif = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssii", $nama, $tanggal_mulai, $tanggal_selesai, $status_aktif, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan'] = "Data tahun ajaran berhasil diubah!";
            header("Location: kelola_tahun_ajaran.php");
            exit();
        } else {
            $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Semua field wajib diisi!";
    }
}

// Ambil data lama berdasarkan ID
$stmt_get = mysqli_prepare($koneksi, "SELECT * FROM t_tahun_ajaran WHERE id = ?");
mysqli_stmt_bind_param($stmt_get, "i", $id);
mysqli_stmt_execute($stmt_get);
$result = mysqli_stmt_get_result($stmt_get);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header("Location: kelola_tahun_ajaran.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Edit Tahun Ajaran</title>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h4 class="text-white mb-4">
                pelanggaran siswa
            </h4>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link text-white">kelola Siswa</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link text-white">kelola Guru</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link text-white">kelola Kelas</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link active">Kelola Tahun Ajaran</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="pelanggaran.php" class="nav-link text-white">Laporan</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="tentang_saya.php" class="nav-link text-white">About Me</a>
                </li>
                <hr class="text-secondary">
                <li class="nav-item mb-2">
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </li>
            </ul>
        </div>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">
            <h2>Edit Tahun Ajaran</h2>
            <p>Form untuk memperbarui data tahun ajaran.</p>

            <?php if (isset($error)) { ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php } ?>

            <hr>

            <form action="" method="POST">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tahun Ajaran</label>
                    <div class="col-sm-6">
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']); ?>" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tanggal Mulai</label>
                    <div class="col-sm-6">
                        <input type="date" name="tanggal_mulai" class="form-control" value="<?= htmlspecialchars($row['tanggal_mulai']); ?>" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tanggal Selesai</label>
                    <div class="col-sm-6">
                        <input type="date" name="tanggal_selesai" class="form-control" value="<?= htmlspecialchars($row['tanggal_selesai']); ?>" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Status Aktif</label>
                    <div class="col-sm-6">
                        <select name="status_aktif" class="form-control" required>
                            <option value="1" <?= ($row['status_aktif'] == 1) ? 'selected' : ''; ?>>Aktif</option>
                            <option value="0" <?= ($row['status_aktif'] == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-6 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Update Tahun Ajaran</button>
                        <a href="kelola_tahun_ajaran.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

</body>
</html>