<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Tentukan nama tabel
$nama_tabel = 't_pelanggaran';
$check_table = mysqli_query($koneksi, "SHOW TABLES LIKE '$nama_tabel'");
if (!$check_table || mysqli_num_rows($check_table) == 0) {
    $nama_tabel = 'pelanggaran';
}

// Cek Primary Key & Daftar Kolom yang ada di Database secara otomatis
$pk_col = 'id';
$columns = [];
$col_query = mysqli_query($koneksi, "SHOW COLUMNS FROM `$nama_tabel`");
if ($col_query) {
    while ($col = mysqli_fetch_assoc($col_query)) {
        $columns[] = $col['Field'];
        if ($col['Key'] === 'PRI') {
            $pk_col = $col['Field'];
        }
    }
}

// Deteksi nama kolom secara fleksibel agar tidak error
$has_kategori = in_array('kategori', $columns);
$has_kode     = in_array('kode', $columns);
$col_nama     = in_array('nama_pelanggaran', $columns) ? 'nama_pelanggaran' : (in_array('nama', $columns) ? 'nama' : 'nama_pelanggaran');
$has_poin     = in_array('poin', $columns);
$has_desk     = in_array('deskripsi', $columns);

// PROSES TAMBAH DATA
if (isset($_POST['tambah'])) {
    $kode      = $has_kode ? mysqli_real_escape_string($koneksi, $_POST['kode']) : '';
    $nama_pel  = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggaran']);
    $poin      = $has_poin ? (int)$_POST['poin'] : 0;
    $deskripsi = $has_desk ? mysqli_real_escape_string($koneksi, $_POST['deskripsi']) : '';

    $fields = [];
    $values = [];

    if ($has_kategori && isset($_POST['kategori'])) {
        $fields[] = '`kategori`';
        $values[] = "'" . mysqli_real_escape_string($koneksi, $_POST['kategori']) . "'";
    }
    if ($has_kode) {
        $fields[] = '`kode`';
        $values[] = "'$kode'";
    }
    $fields[] = "`$col_nama`";
    $values[] = "'$nama_pel'";
    
    if ($has_poin) {
        $fields[] = '`poin`';
        $values[] = "'$poin'";
    }
    if ($has_desk) {
        $fields[] = '`deskripsi`';
        $values[] = "'$deskripsi'";
    }

    $query = "INSERT INTO `$nama_tabel` (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ")";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan'] = 'Jenis pelanggaran berhasil disimpan!';
    } else {
        $_SESSION['pesan_error'] = 'Gagal menyimpan: ' . mysqli_error($koneksi);
    }
    header("Location: kelola_jenis_pelanggaran.php");
    exit();
}

// PROSES EDIT DATA
if (isset($_POST['edit'])) {
    $id        = mysqli_real_escape_string($koneksi, $_POST['id']);
    $kode      = $has_kode ? mysqli_real_escape_string($koneksi, $_POST['kode']) : '';
    $nama_pel  = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggaran']);
    $poin      = $has_poin ? (int)$_POST['poin'] : 0;
    $deskripsi = $has_desk ? mysqli_real_escape_string($koneksi, $_POST['deskripsi']) : '';

    $updates = [];
    if ($has_kategori && isset($_POST['kategori'])) {
        $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
        $updates[] = "`kategori` = '$kategori'";
    }
    if ($has_kode) {
        $updates[] = "`kode` = '$kode'";
    }
    $updates[] = "`$col_nama` = '$nama_pel'";
    if ($has_poin) {
        $updates[] = "`poin` = '$poin'";
    }
    if ($has_desk) {
        $updates[] = "`deskripsi` = '$deskripsi'";
    }

    $query = "UPDATE `$nama_tabel` SET " . implode(', ', $updates) . " WHERE `$pk_col` = '$id'";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan'] = 'Jenis pelanggaran berhasil diperbarui!';
    } else {
        $_SESSION['pesan_error'] = 'Gagal memperbarui: ' . mysqli_error($koneksi);
    }
    header("Location: kelola_jenis_pelanggaran.php");
    exit();
}

// PROSES HAPUS DATA
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $query = "DELETE FROM `$nama_tabel` WHERE `$pk_col` = '$id'";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan'] = 'Jenis pelanggaran berhasil dihapus!';
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
    
    <style>
        /* Sidebar agar tetap diam di samping layar */
        .sidebar-sticky {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        /* Memperkecil padding link sidebar agar muat sempurna tanpa scroll internal */
        .sidebar-sticky .nav-link {
            padding-top: 0.35rem !important;
            padding-bottom: 0.35rem !important;
            font-size: 0.95rem;
        }
        .sidebar-sticky .nav-item {
            margin-bottom: 0.15rem !important;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark p-3 sidebar-sticky">
            <h4 class="text-white mb-3 fs-5">Pelanggaran Siswa</h4>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link text-white">Dashboard</a></li>
                <li class="nav-item"><a href="kelola_siswa.php" class="nav-link text-white">Kelola Siswa</a></li>
                <li class="nav-item"><a href="kelola_guru.php" class="nav-link text-white">Kelola Guru</a></li>
                <li class="nav-item"><a href="kelola_kelas.php" class="nav-link text-white">Kelola Kelas</a></li>
                <li class="nav-item"><a href="kelola_tahun_ajaran.php" class="nav-link text-white">Kelola Tahun Ajaran</a></li>
                <li class="nav-item"><a href="kelola_wali_kelas.php" class="nav-link text-white">Kelola Wali Kelas</a></li>
                <li class="nav-item"><a href="kelola_kategori_pelanggaran.php" class="nav-link text-white">Kelola Kategori Pelanggaran</a></li>
                <li class="nav-item">
                    <!-- Menu Kelola Jenis Pelanggaran aktif (Tombol Biru) -->
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link btn btn-primary text-white text-start">Kelola Jenis Pelanggaran</a>
                </li>
                <li class="nav-item"><a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a></li>
                <li class="nav-item"><a href="tentang_saya.php" class="nav-link text-white">About Me</a></li>
                <hr class="text-secondary my-2">
                <li class="nav-item"><a href="logout.php" class="nav-link text-danger">Logout</a></li>
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
                            <?php if ($has_kategori): ?><th>Kategori</th><?php endif; ?>
                            <?php if ($has_kode): ?><th>Kode</th><?php endif; ?>
                            <th>Nama Pelanggaran</th>
                            <?php if ($has_poin): ?><th width="60">Poin</th><?php endif; ?>
                            <?php if ($has_desk): ?><th>Deskripsi</th><?php endif; ?>
                            <th width="80">Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($query_data && mysqli_num_rows($query_data) > 0) :
                            while ($row = mysqli_fetch_assoc($query_data)) :
                                $id_row = $row[$pk_col];
                                $kat    = $has_kategori ? ($row['kategori'] ?? 'Umum') : '';
                                $kd     = $has_kode ? ($row['kode'] ?? ('PG-' . sprintf("%03d", $no))) : '';
                                $nm     = $row[$col_nama] ?? '-';
                                $pn     = $has_poin ? ($row['poin'] ?? 0) : 0;
                                $desk   = $has_desk ? ($row['deskripsi'] ?? '-') : '';
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <?php if ($has_kategori): ?><td><?= htmlspecialchars($kat); ?></td><?php endif; ?>
                            <?php if ($has_kode): ?><td><?= htmlspecialchars($kd); ?></td><?php endif; ?>
                            <td><?= htmlspecialchars($nm); ?></td>
                            <?php if ($has_poin): ?><td><?= htmlspecialchars($pn); ?></td><?php endif; ?>
                            <?php if ($has_desk): ?><td><?= htmlspecialchars($desk); ?></td><?php endif; ?>
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
                                            <?php if ($has_kategori): ?>
                                            <div class="mb-3">
                                                <label class="form-label">Kategori</label>
                                                <input type="text" class="form-control" name="kategori" value="<?= htmlspecialchars($kat); ?>" required>
                                            </div>
                                            <?php endif; ?>
                                            <?php if ($has_kode): ?>
                                            <div class="mb-3">
                                                <label class="form-label">Kode Pelanggaran</label>
                                                <input type="text" class="form-control" name="kode" value="<?= htmlspecialchars($kd); ?>" required>
                                            </div>
                                            <?php endif; ?>
                                            <div class="mb-3">
                                                <label class="form-label">Nama Pelanggaran</label>
                                                <input type="text" class="form-control" name="nama_pelanggaran" value="<?= htmlspecialchars($nm); ?>" required>
                                            </div>
                                            <?php if ($has_poin): ?>
                                            <div class="mb-3">
                                                <label class="form-label">Poin</label>
                                                <input type="number" class="form-control" name="poin" value="<?= htmlspecialchars($pn); ?>" required>
                                            </div>
                                            <?php endif; ?>
                                            <?php if ($has_desk): ?>
                                            <div class="mb-3">
                                                <label class="form-label">Deskripsi</label>
                                                <textarea class="form-control" name="deskripsi" rows="3"><?= htmlspecialchars($desk); ?></textarea>
                                            </div>
                                            <?php endif; ?>
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
                    <?php if ($has_kategori): ?>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" class="form-control" name="kategori" required placeholder="Contoh: Kedisiplinan 1">
                    </div>
                    <?php endif; ?>
                    <?php if ($has_kode): ?>
                    <div class="mb-3">
                        <label class="form-label">Kode Pelanggaran</label>
                        <input type="text" class="form-control" name="kode" required placeholder="Contoh: PG-010">
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggaran</label>
                        <input type="text" class="form-control" name="nama_pelanggaran" required placeholder="Nama jenis pelanggaran">
                    </div>
                    <?php if ($has_poin): ?>
                    <div class="mb-3">
                        <label class="form-label">Poin</label>
                        <input type="number" class="form-control" name="poin" required placeholder="Contoh: 5">
                    </div>
                    <?php endif; ?>
                    <?php if ($has_desk): ?>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" placeholder="Keterangan singkat pelanggaran"></textarea>
                    </div>
                    <?php endif; ?>
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
    $(document).ready(function() {
        $('#tabelPelanggaran').DataTable({
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