<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Deteksi nama tabel jenis/kategori pelanggaran di database
$nama_tabel = 't_pelanggaran';
$check_table = mysqli_query($koneksi, "SHOW TABLES LIKE '$nama_tabel'");
if (!$check_table || mysqli_num_rows($check_table) == 0) {$nama_tabel = 'pelanggaran';
}

// Cek apakah tabel kosong. Jika kosong, isi otomatis data sampel sesuai laporan
$check_empty = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM $nama_tabel");
$data_count = mysqli_fetch_assoc($check_empty);

if (($data_count['total'] ?? 0) == 0) {
    // Ambil daftar kolom asli tabel untuk menyusun query insert dinamis
    $col_query = mysqli_query($koneksi, "SHOW COLUMNS FROM $nama_tabel");
    $fields = [];
    if ($col_query) {
        while ($col = mysqli_fetch_assoc($col_query)) {
            if (strtolower($col['Extra']) === 'auto_increment') continue;
            $fields[] =$col['Field'];
        }
    }

    $sample_data = [
        ['Kedisiplinan 1', 'PG-001', 'Terlambat datang ke sekolah #001', 2, 'Deskripsi pelanggaran nomor 1.'],
        ['Kerapian 1', 'PG-002', 'Tidak memakai atribut lengkap #002', 3, 'Deskripsi pelanggaran nomor 2.'],
        ['Kehadiran 1', 'PG-003', 'Tidak memakai seragam sesuai ketentuan #003', 4, 'Deskripsi pelanggaran nomor 3.'],
        ['Ketertiban 1', 'PG-004', 'Meninggalkan kelas tanpa izin #004', 5, 'Deskripsi pelanggaran nomor 4.'],
        ['Perlengkapan 1', 'PG-005', 'Tidak mengerjakan tugas #005', 6, 'Deskripsi pelanggaran nomor 5.'],
        ['Sikap 1', 'PG-006', 'Rambut tidak sesuai aturan #006', 7, 'Deskripsi pelanggaran nomor 6.'],
        ['Kebersihan 1', 'PG-007', 'Membawa barang terlarang #007', 8, 'Deskripsi pelanggaran nomor 7.'],
        ['Keamanan 1', 'PG-008', 'Berkata tidak sopan #008', 9, 'Deskripsi pelanggaran nomor 8.'],
        ['Akademik 1', 'PG-009', 'Membuang sampah sembarangan #009', 10, 'Deskripsi pelanggaran nomor 9.']
    ];

    foreach ($sample_data as $row) {$cols = implode("`, `", array_slice($fields, 0, count($row)));
        $vals = "'" . implode("', '", array_map(function($v) use ($koneksi) { return mysqli_real_escape_string($koneksi, $v); },$row)) . "'";
        mysqli_query($koneksi, "INSERT INTO `$nama_tabel` (`$cols`) VALUES ($vals)");
    }
}

// Proses Tambah Data
if (isset($_POST['tambah'])) {
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);$kode = mysqli_real_escape_string($koneksi,$_POST['kode']);
    $nama_pelanggaran = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggaran']);$poin = (int)$_POST['poin'];$deskripsi = mysqli_real_escape_string($koneksi,$_POST['deskripsi']);

    $query = "INSERT INTO $nama_tabel (kategori, kode, nama_pelanggaran, poin, deskripsi) 
              VALUES ('$kategori', '$kode', '$nama_pelanggaran', '$poin', '$deskripsi')";
    if (mysqli_query($koneksi, $query)) {$_SESSION['pesan'] = 'Jenis pelanggaran berhasil ditambahkan!';
        header("Location: kelola_jenis_pelanggaran.php");
        exit();
    } else {
        $error_msg = addslashes(mysqli_error($koneksi));
        echo "<script>alert('Gagal menambah data: $error_msg');</script>";
    }
}

// Proses Edit Data
if (isset($_POST['edit'])) {$id = mysqli_real_escape_string($koneksi,$_POST['id']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);$kode = mysqli_real_escape_string($koneksi,$_POST['kode']);
    $nama_pelanggaran = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggaran']);$poin = (int)$_POST['poin'];$deskripsi = mysqli_real_escape_string($koneksi,$_POST['deskripsi']);

    // Cek primary key yang digunakan (id_pelanggaran atau id)
    $pk = 'id_pelanggaran';
    $col_check = mysqli_query($koneksi, "SHOW COLUMNS FROM $nama_tabel LIKE 'id_pelanggaran'");
    if (!$col_check || mysqli_num_rows($col_check) == 0) {$pk = 'id';
    }

    $query = "UPDATE $nama_tabel SET 
                kategori='$kategori', 
                kode='$kode', 
                nama_pelanggaran='$nama_pelanggaran', 
                poin='$poin', 
                deskripsi='$deskripsi' 
              WHERE $pk='$id'";
              
    if (mysqli_query($koneksi, $query)) {$_SESSION['pesan'] = 'Jenis pelanggaran berhasil diperbarui!';
        header("Location: kelola_jenis_pelanggaran.php");
        exit();
    } else {
        $error_msg = addslashes(mysqli_error($koneksi));
        echo "<script>alert('Gagal memperbarui data: $error_msg');</script>";
    }
}

// Proses Hapus Data
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);$pk = 'id_pelanggaran';
    $col_check = mysqli_query($koneksi, "SHOW COLUMNS FROM $nama_tabel LIKE 'id_pelanggaran'");
    if (!$col_check || mysqli_num_rows($col_check) == 0) {$pk = 'id';
    }

    $query = "DELETE FROM $nama_tabel WHERE $pk='$id'";
    if (mysqli_query($koneksi, $query)) {$_SESSION['pesan'] = 'Jenis pelanggaran berhasil dihapus!';
        header("Location: kelola_jenis_pelanggaran.php");
        exit();
    } else {
        $error_msg = addslashes(mysqli_error($koneksi));
        echo "<script>alert('Gagal menghapus data: $error_msg');</script>";
    }
}

// Ambil Seluruh Data
$query_data = mysqli_query($koneksi, "SELECT * FROM $nama_tabel");
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
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link text-white">Kelola Siswa</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link text-white">Kelola Guru</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link text-white">Kelola Kelas</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link text-white">Kelola Tahun Ajaran</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link text-white">Kelola Wali Kelas</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link text-white">Kelola Kategori Pelanggaran</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link text-white">Kelola Jenis Pelanggaran</a>
                </li>
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
            <h2>Kelola Jenis Pelanggaran</h2>
            <p>Halaman pengelolaan master data jenis pelanggaran dan Poin.</p>

            <div class="mb-3">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Pelanggaran</button>
            </div>

            <?php if (isset($_SESSION['pesan'])) { ?>
                <div class="alert alert-success mt-3">
                    <?php
                    echo $_SESSION['pesan'];
                    unset($_SESSION['pesan']);
                    ?>
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
                            while ($row = mysqli_fetch_assoc($query_data)) : 
                                $id_row =$row['id_pelanggaran'] ?? $row['id'] ?? $no;
                                $kat = $row['kategori'] ?? $row['nama_kategori'] ?? 'Umum';
                                $kd  =$row['kode'] ?? 'PG-' . sprintf("%03d", $no);$nm  = $row['nama_pelanggaran'] ?? $row['nama'] ?? '-';
                                $pn  =$row['poin'] ?? 0;
                                $desk=$row['deskripsi'] ?? '-';
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($kat); ?></td>
                            <td><?= htmlspecialchars($kd); ?></td>
                            <td><?= htmlspecialchars($nm); ?></td>
                            <td><?= htmlspecialchars($pn); ?></td>
                            <td><?= htmlspecialchars($desk); ?></td>
                            <td>
                                <span class="badge bg-success">Aktif</span>
                            </td>
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
                                            <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
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

<!-- INISIALISASI DATATABLE -->
<script>
    $(document).ready(function() {$('#tabelPelanggaran').DataTable({
            "pagingType": "simple_numbers",
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data pelanggaran tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data yang tersedia",
                "infoFiltered": "(difilter dari total _MAX_ data)",
                "search": "Cari Pelanggaran:",
                "paginate": {
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            },
            "pageLength": 10,
            "order": [[0, "asc"]]
        });
    });
</script>

</body>
</html>