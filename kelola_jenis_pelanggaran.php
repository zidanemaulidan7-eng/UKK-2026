<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Tentukan nama tabel
$nama_tabel = 't_pelanggaran';
$check_table = mysqli_query($koneksi, "SHOW TABLES LIKE '$nama_tabel'");
if (!$check_table || mysqli_num_rows($check_table) == 0) {$nama_tabel = 'pelanggaran';
}

// Cek Primary Key
$pk_col = 'id';
$col_query = mysqli_query($koneksi, "SHOW COLUMNS FROM `$nama_tabel`");
if ($col_query) {
    while ($col = mysqli_fetch_assoc($col_query)) {
        if ($col['Key'] === 'PRI') {
            $pk_col =$col['Field'];
            break;
        }
    }
}

// PROSES TAMBAH DATA
if (isset($_POST['tambah'])) {
    $kategori   = mysqli_real_escape_string($koneksi, $_POST['kategori']);$kode       = mysqli_real_escape_string($koneksi,$_POST['kode']);
    $nama_pel   = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggaran']);$poin       = (int)$_POST['poin'];$deskripsi  = mysqli_real_escape_string($koneksi,$_POST['deskripsi']);

    $query = "INSERT INTO `$nama_tabel` (`kategori`, `kode`, `nama_pelanggaran`, `poin`, `deskripsi`) 
              VALUES ('$kategori', '$kode', '$nama_pel', '$poin', '$deskripsi')";

    if (mysqli_query($koneksi, $query)) {$_SESSION['pesan'] = 'Jenis pelanggaran berhasil disimpan!';
    } else {
        $_SESSION['pesan_error'] = 'Gagal menyimpan: ' . mysqli_error($koneksi);
    }
    header("Location: kelola_jenis_pelanggaran.php");
    exit();
}

// PROSES EDIT DATA
if (isset($_POST['edit'])) {$id         = mysqli_real_escape_string($koneksi,$_POST['id']);
    $kategori   = mysqli_real_escape_string($koneksi, $_POST['kategori']);$kode       = mysqli_real_escape_string($koneksi,$_POST['kode']);
    $nama_pel   = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggaran']);$poin       = (int)$_POST['poin'];$deskripsi  = mysqli_real_escape_string($koneksi,$_POST['deskripsi']);

    $query = "UPDATE `$nama_tabel` SET 
                `kategori` = '$kategori',
                `kode` = '$kode',
                `nama_pelanggaran` = '$nama_pel',
                `poin` = '$poin',
                `deskripsi` = '$deskripsi'
              WHERE `$pk_col` = '$id'";

    if (mysqli_query($koneksi, $query)) {$_SESSION['pesan'] = 'Jenis pelanggaran berhasil diperbarui!';
    } else {
        $_SESSION['pesan_error'] = 'Gagal memperbarui: ' . mysqli_error($koneksi);
    }
    header("Location: kelola_jenis_pelanggaran.php");
    exit();
}

// PROSES HAPUS DATA
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);$query = "DELETE FROM `$nama_tabel` WHERE `$pk_col` = '$id'";

    if (mysqli_query($koneksi, $query)) {$_SESSION['pesan'] = 'Jenis pelanggaran berhasil dihapus!';
    } else {
        $_SESSION['pesan_error'] = 'Gagal menghapus: ' . mysqli_error($koneksi);
    }
    header("Location: kelola_jenis_pelanggaran.php");
    exit();
}

// AMBIL DATA PELANGGARAN
$query_data = mysqli_query($koneksi, "SELECT * FROM `$nama_tabel` ORDER BY `$pk_col` ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jenis Pelanggaran</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white">Dashboard</a></li>
                <li class="nav-item mb-2"><a href="kelola_siswa.php" class="nav-link text-white">Kelola Siswa</a></li>
                <li class="nav-item mb-2"><a href="kelola_guru.php" class="nav-link text-white">Kelola Guru</a></li>
                <li class="nav-item mb-2"><a href="kelola_kelas.php" class="nav-link text-white">Kelola Kelas</a></li>
                <li class="nav-item mb-2"><a href="kelola_tahun_ajaran.php" class="nav-link text-white">Kelola Tahun Ajaran</a></li>
                <li class="nav-item mb-2"><a href="kelola_wali_kelas.php" class="nav-link text-white">Kelola Wali Kelas</a></li>
                <li class="nav-item mb-2"><a href="kelola_kategori_pelanggaran.php" class="nav-link text-white">Kelola Kategori Pelanggaran</a></li>
                <li class="nav-item mb-2"><a href="kelola_jenis_pelanggaran.php" class="nav-link active">Kelola Jenis Pelanggaran</a></li>
                <li class="nav-item mb-2"><a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a></li>
                <li class="nav-item mb-2"><a href="pelanggaran.php" class="nav-link text-white">Laporan</a></li>
                <li class="nav-item mb-2"><a href="tentang_saya.php" class="nav-link text-white">About Me</a></li>
                <hr class="text-secondary">
                <li class="nav-item mb-2"><a href="logout.php" class="nav-link text-danger">Logout</a></li>
            </ul>
        </div>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">
            <h2>Kelola Jenis Pelanggaran</h2>
            <p>Halaman pengelolaan master data jenis pelanggaran dan Poin.</p>

            <div class="mb-3">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Pelanggaran</button>
            </div>

            <!-- PESAN NOTIFIKASI -->
            <?php if (isset($_SESSION['pesan'])) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['pesan_error'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['pesan_error']; unset($_SESSION['pesan_error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php } ?>

            <hr class="my-4">

            <h4 class="mb-3">Data Pelanggaran</h4>

            <div class="table-responsive">
                <table id="tabelPelanggaran" class="table table-bordered table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th width="40">No</th>
                            <th>Kategori</th>
                            <th>Kode</th>
                            <th>Nama Pelanggaran</th>
                            <th width="60">Poin</th>
                            <th>Deskripsi</th>
                            <th width="80">Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($query_data && mysqli_num_rows($query_data) > 0) :
                            while ($row = mysqli_fetch_assoc($query_data)) :$id_row  = $row[$pk_col];
                                $kat     =$row['kategori'] ?? 'Umum';
                                $kd      =$row['kode'] ?? ('PG-' . sprintf("%03d", $no));$nm      = $row['nama_pelanggaran'] ?? $row['nama'] ?? '-';
                                $pn      =$row['poin'] ?? 0;
                                $desk    =$row['deskripsi'] ?? '-';
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($kat); ?></td>
                            <td><?= htmlspecialchars($kd); ?></td>
                            <td><?= htmlspecialchars($nm); ?></td>
                            <td><?= htmlspecialchars($pn); ?></td>
                            <td><?= htmlspecialchars($desk); ?></td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $id_row; ?>">Edit</button>
                                <a href="kelola_jenis_pelanggaran.php?hapus=<?= $id_row; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Yakin ingin menghapus pelanggaran ini?')">
                                   Hapus
                                </a>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal<?= $id_row; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Pelanggaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $id_row; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Kategori</label>
                                                <input type="text" class="form-control" name="kategori" value="<?= htmlspecialchars($kat); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kode Pelanggaran</label>
                                                <input type="text" class="form-control" name="kode" value="<?= htmlspecialchars($kd); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama Pelanggaran</label>
                                                <input type="text" class="form-control" name="nama_pelanggaran" value="<?= htmlspecialchars($nm); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Poin</label>
                                                <input type="number" class="form-control" name="poin" value="<?= htmlspecialchars($pn); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Deskripsi</label>
                                                <textarea class="form-control" name="deskripsi" rows="3"><?= htmlspecialchars($desk); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php 
                            endwhile;
                        endif; 
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Pelanggaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" class="form-control" name="kategori" required placeholder="Contoh: Kedisiplinan 1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Pelanggaran</label>
                        <input type="text" class="form-control" name="kode" required placeholder="Contoh: PG-010">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggaran</label>
                        <input type="text" class="form-control" name="nama_pelanggaran" required placeholder="Nama jenis pelanggaran">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Poin</label>
                        <input type="number" class="form-control" name="poin" required placeholder="Contoh: 5">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" placeholder="Keterangan singkat pelanggaran"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JQUERY & BOOTSTRAP JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DATATABLES JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {$('#tabelPelanggaran').DataTable({
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data pelanggaran tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "search": "Cari Pelanggaran:",
                "paginate": { "next": "Selanjutnya", "previous": "Sebelumnya" }
            },
            "pageLength": 10
        });
    });
</script>

</body>
</html>