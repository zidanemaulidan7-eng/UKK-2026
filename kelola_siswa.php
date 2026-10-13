<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil semua data siswa
$sql = "SELECT * FROM t_siswa ORDER BY id DESC";
$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Kelola Siswa</title>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h4 class="text-white mb-4">
                Website
            </h4>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link active">
                        kelola Siswa
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link text-white">kelola Guru</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link text-white">kelola Kelas</a>
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
            <h2>Kelola Siswa</h2>

            <?php if (isset($_SESSION['pesan'])) { ?>
                <div class="alert alert-success mt-3">
                    <?php
                    echo $_SESSION['pesan'];
                    unset($_SESSION['pesan']);
                    ?>
                </div>
            <?php } ?>

            <hr>

            <h4 class="mb-3">Tambah Data Siswa</h4>

            <form action="menu1.php" method="POST">
                <input type="hidden" name="aksi" value="tambah">

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">NIS</label>
                    <div class="col-sm-6">
                        <input type="text" name="nis" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">NISN</label>
                    <div class="col-sm-6">
                        <input type="text" name="nisn" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-6">
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                    <div class="col-sm-6">
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                    <div class="col-sm-6">
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-6">
                        <textarea name="alamat" class="form-control" rows="3" required></textarea>
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
                        <button type="submit" class="btn btn-primary">Tambah Siswa</button>
                    </div>
                </div>
            </form>

            <hr class="my-4">

            <h4 class="mb-3">Data Siswa</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($siswa = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($siswa['id']); ?></td>
                                <td><?php echo htmlspecialchars($siswa['nis']); ?></td>
                                <td><?php echo htmlspecialchars($siswa['nisn']); ?></td>
                                <td><?php echo htmlspecialchars($siswa['nama']); ?></td>
                                <td>
                                    <?php 
                                    echo ($siswa['jenis_kelamin'] == 'L') ? "Laki-laki" : "Perempuan"; 
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($siswa['tanggal_lahir']); ?></td>
                                <td><?php echo htmlspecialchars($siswa['alamat']); ?></td>
                                <td>
                                    <?php 
                                    echo ($siswa['status_aktif'] == 1) ? "Aktif" : "Tidak Aktif"; 
                                    ?>
                                </td>
                                <td>
                                    <a href="edit_siswa.php?id=<?php echo $siswa['id']; ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
                                    <a href="hapus_siswa.php?id=<?php echo $siswa['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus data siswa ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                            <tr>
                                <td colspan="9" class="text-center">Belum ada data siswa.</td>
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