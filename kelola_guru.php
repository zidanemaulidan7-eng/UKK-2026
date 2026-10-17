<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

// Ambil data guru beserta email akun user
$sql = "SELECT 
            t_guru.*, 
            t_users.email AS email_user 
        FROM t_guru 
        LEFT JOIN t_users ON t_guru.user_id = t_users.id 
        ORDER BY t_guru.id DESC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Query data guru gagal: " . mysqli_error($koneksi));
}

// Ambil akun yang role-nya guru
$sql_user = "SELECT id, name, email 
             FROM t_users 
             WHERE role = 'guru' 
             ORDER BY name ASC";

$query_user = mysqli_query($koneksi, $sql_user);

if (!$query_user) {
    die("Query akun guru gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Kelola Guru</title>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h4 class="text-white mb-4">
                Pelanggaran Siswa
            </h4>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link text-white">
                        Kelola Siswa
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link active">Kelola Guru</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link text-white">Kelola Kelas</a>
                </li>
                <!-- Menu Kelola Tahun Ajaran yang disamakan -->
                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link text-white">Kelola Tahun Ajaran</a>
                    <!-- Menu Kelola Wali Kelas ditambahkan di sini -->
                <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link text-white">Kelola Wali Kelas</a>
                <li class="nav-item mb-2">
                    <a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a>
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
            <h2>Kelola Guru</h2>

            <?php if (isset($_SESSION['pesan'])) { ?>
                <div class="alert alert-success mt-3">
                    <?= htmlspecialchars($_SESSION['pesan']); ?>
                </div>
                <?php unset($_SESSION['pesan']); ?>
            <?php } ?>

            <hr>

            <h4 class="mb-3">Tambah Data Guru</h4>

            <form action="proses_guru.php" method="POST">
                <input type="hidden" name="aksi" value="tambah">

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">NIP</label>
                    <div class="col-sm-6">
                        <input type="text" name="nip" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Nama Guru</label>
                    <div class="col-sm-6">
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-6">
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Akun Guru</label>
                    <div class="col-sm-6">
                        <select name="user_id" class="form-control" required>
                            <option value="">-- Pilih Akun Guru --</option>
                            <?php while ($user = mysqli_fetch_assoc($query_user)) { ?>
                                <option value="<?= $user['id']; ?>">
                                    <?= htmlspecialchars($user['name']); ?> (<?= htmlspecialchars($user['email']); ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Status Aktif</label>
                    <div class="col-sm-6">
                        <select name="status_aktif" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-6 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Tambah Guru</button>
                    </div>
                </div>
            </form>

            <hr class="my-4">

            <h4 class="mb-3">Data Guru</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>User ID</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($guru = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($guru['id']); ?></td>
                                <td><?php echo htmlspecialchars($guru['nip']); ?></td>
                                <td><?php echo htmlspecialchars($guru['nama']); ?></td>
                                <td><?php echo htmlspecialchars($guru['email'] ?? $guru['email_user'] ?? '-'); ?></td>
                                <td>
                                    <?php 
                                    echo (isset($guru['status_aktif']) && $guru['status_aktif'] == 1) ? "Aktif" : "Tidak Aktif"; 
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($guru['user_id']); ?></td>
                                <td>
                                    <a href="edit_guru.php?id=<?php echo $guru['id']; ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
                                    <a href="hapus_guru.php?id=<?php echo $guru['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus data guru ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data guru.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

</body>
</html>