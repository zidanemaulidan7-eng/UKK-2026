<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data pelanggaran + kategori
$sql = "SELECT 
            t_pelanggaran.*,
            t_pelanggaran_kategori.nama AS nama_kategori
        FROM t_pelanggaran
        LEFT JOIN t_pelanggaran_kategori
        ON t_pelanggaran.pelanggaran_kategori_id = t_pelanggaran_kategori.id
        ORDER BY t_pelanggaran.id DESC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("<div class='alert alert-danger m-4'><strong>DATABASE ERROR [DB_ERR_PELANGGARAN]:</strong> Gagal mengambil data pelanggaran: " . mysqli_error($koneksi) . "</div>");
}

// Ambil data kategori
$sql_kategori = "SELECT * FROM t_pelanggaran_kategori
                 ORDER BY nama ASC";

$query_kategori = mysqli_query($koneksi, $sql_kategori);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Kelola Pelanggaran</title>
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
                    <a href="kelola_siswa.php" class="nav-link text-white">
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
                    <a href="pelanggaran.php" class="nav-link active">Laporan</a>
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
            <h2>Kelola Pelanggaran</h2>

            <?php if (isset($_SESSION['pesan'])) { ?>
                <div class="alert alert-success mt-3">
                    <?php
                    echo $_SESSION['pesan'];
                    unset($_SESSION['pesan']);
                    ?>
                </div>
            <?php } ?>

            <hr>

            <h4 class="mb-3">Tambah Pelanggaran</h4>

            <form action="proses_pelanggaran.php" method="POST">
                <input type="hidden" name="aksi" value="tambah">

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Kategori</label>
                    <div class="col-sm-6">
                        <select name="pelanggaran_kategori_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php while ($kategori = mysqli_fetch_assoc($query_kategori)) { ?>
                                <option value="<?php echo $kategori['id']; ?>">
                                    <?php echo htmlspecialchars($kategori['nama']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Kode</label>
                    <div class="col-sm-6">
                        <input type="text" name="kode" class="form-control" placeholder="PG-001" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Nama Pelanggaran</label>
                    <div class="col-sm-6">
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Poin</label>
                    <div class="col-sm-6">
                        <input type="number" name="poin" class="form-control" min="0" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Deskripsi</label>
                    <div class="col-sm-6">
                        <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
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
                        <button type="submit" class="btn btn-primary">Tambah Pelanggaran</button>
                    </div>
                </div>
            </form>

            <hr class="my-4">

            <h4 class="mb-3">Data Pelanggaran</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Kode</th>
                            <th>Nama Pelanggaran</th>
                            <th>Poin</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;

                        if (mysqli_num_rows($query) > 0) {
                            while ($pelanggaran = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($pelanggaran['nama_kategori'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($pelanggaran['kode']); ?></td>
                                <td><?php echo htmlspecialchars($pelanggaran['nama']); ?></td>
                                <td><?php echo htmlspecialchars($pelanggaran['poin']); ?></td>
                                <td><?php echo htmlspecialchars($pelanggaran['deskripsi']); ?></td>
                                <td>
                                    <?php if ($pelanggaran['status_aktif'] == 1) { ?>
                                        Aktif
                                    <?php } else { ?>
                                        Tidak Aktif
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="edit_pelanggaran.php?id=<?php echo $pelanggaran['id']; ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
                                    <a href="proses_pelanggaran.php?aksi=hapus&id=<?php echo $pelanggaran['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus pelanggaran ini?');">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data pelanggaran.</td>
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