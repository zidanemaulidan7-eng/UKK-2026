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

// Ambil akun yang role-nya guru untuk pilihan select
$sql_user = "SELECT id, name, email FROM t_users WHERE role = 'guru' ORDER BY name ASC";
$query_user = mysqli_query($koneksi, $sql_user);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Guru - <?= htmlspecialchars($guru['nama']); ?></title>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Data Guru</h4>
                </div>
                <div class="card-body">
                    <form action="proses_guru.php" method="POST">
                        <!-- Kirim aksi edit dan id guru -->
                        <input type="hidden" name="aksi" value="edit">
                        <input type="hidden" name="id" value="<?= $guru['id']; ?>">

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">NIP</label>
                            <div class="col-sm-9">
                                <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($guru['nip']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Nama Guru</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($guru['nama']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($guru['email'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Akun Guru</label>
                            <div class="col-sm-9">
                                <select name="user_id" class="form-control" required>
                                    <option value="">-- Pilih Akun Guru --</option>
                                    <?php while ($user = mysqli_fetch_assoc($query_user)) { ?>
                                        <option value="<?= $user['id']; ?>" <?= ($guru['user_id'] == $user['id']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($user['name']); ?> - <?= htmlspecialchars($user['email']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Status Aktif</label>
                            <div class="col-sm-9">
                                <select name="status_aktif" class="form-control" required>
                                    <option value="1" <?= ($guru['status_aktif'] == 1) ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="0" <?= ($guru['status_aktif'] == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary">Update Data</button>
                                <a href="kelola_guru.php" class="btn btn-secondary">Cancel</a>
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