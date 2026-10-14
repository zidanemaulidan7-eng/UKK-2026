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
    <title>Edit Pelanggaran</title>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
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
                                <button type="submit" class="btn btn-warning">Update Data</button>
                                <a href="pelanggaran.php" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>