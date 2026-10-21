<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Deteksi nama tabel di database (t_kategori_pelanggaran atau kategori_pelanggaran)
$nama_tabel = 't_kategori_pelanggaran';
$check_table = mysqli_query($koneksi, "SHOW TABLES LIKE '$nama_tabel'");
if (!$check_table || mysqli_num_rows($check_table) == 0) {$nama_tabel = 'kategori_pelanggaran';
}

// Cek apakah tabel kosong, jika kosong isi data default seperti di laporan.php
$check_empty = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM $nama_tabel");
$data_count = mysqli_fetch_assoc($check_empty);

if (($data_count['total'] ?? 0) == 0) {
    mysqli_query($koneksi, "INSERT INTO `$nama_tabel` (`nama_kategori`, `deskripsi`) VALUES
        ('Kedisiplinan 1', 'Deskripsi kategori untuk pelanggaran kedisiplinan siswa.'),
        ('Kerapian 1', 'Deskripsi kategori untuk pelanggaran kerapian berpakaian dan atribut.'),
        ('Kehadiran 1', 'Deskripsi kategori untuk pelanggaran kehadiran dan ketepatan waktu.'),
        ('Ketertiban 1', 'Deskripsi kategori untuk pelanggaran ketertiban lingkungan sekolah.'),
        ('Perlengkapan 1', 'Deskripsi kategori untuk pelanggaran kelengkapan belajar siswa.'),
        ('Sikap 1', 'Deskripsi kategori untuk pelanggaran sikap dan etika siswa.'),
        ('Kebersihan 1', 'Deskripsi kategori untuk pelanggaran kebersihan kelas dan sekolah.'),
        ('Keamanan 1', 'Deskripsi kategori untuk pelanggaran aturan keamanan sekolah.'),
        ('Akademik 1', 'Deskripsi kategori untuk pelanggaran kegiatan belajar dan tugas.')");
}

// Proses Tambah Data
if (isset($_POST['tambah'])) {
    $nama_kategori = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);$deskripsi = mysqli_real_escape_string($koneksi,$_POST['deskripsi']);

    $query = "INSERT INTO $nama_tabel (nama_kategori, deskripsi) VALUES ('$nama_kategori', '$deskripsi')";
    if (mysqli_query($koneksi, $query)) {$_SESSION['pesan'] = 'Kategori pelanggaran berhasil ditambahkan!';
        header("Location: kelola_kategori_pelanggaran.php");
        exit();
    } else {
        $error_msg = addslashes(mysqli_error($koneksi));
        echo "<script>alert('Gagal menambah data: $error_msg');</script>";
    }
}

// Proses Edit Data
if (isset($_POST['edit'])) {$id = mysqli_real_escape_string($koneksi,$_POST['id_kategori']);
    $nama_kategori = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);$deskripsi = mysqli_real_escape_string($koneksi,$_POST['deskripsi']);

    $query = "UPDATE $nama_tabel SET nama_kategori='$nama_kategori', deskripsi='$deskripsi' WHERE id_kategori='$id'";
    if (mysqli_query($koneksi, $query)) {$_SESSION['pesan'] = 'Kategori pelanggaran berhasil diperbarui!';
        header("Location: kelola_kategori_pelanggaran.php");
        exit();
    } else {
        $error_msg = addslashes(mysqli_error($koneksi));
        echo "<script>alert('Gagal memperbarui data: $error_msg');</script>";
    }
}

// Proses Hapus Data
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);$query = "DELETE FROM $nama_tabel WHERE id_kategori='$id'";
    if (mysqli_query($koneksi, $query)) {$_SESSION['pesan'] = 'Kategori pelanggaran berhasil dihapus!';
        header("Location: kelola_kategori_pelanggaran.php");
        exit();
    } else {
        $error_msg = addslashes(mysqli_error($koneksi));
        echo "<script>alert('Gagal menghapus data: $error_msg');</script>";
    }
}

// Ambil Data Kategori
$query_data = mysqli_query($koneksi, "SELECT * FROM $nama_tabel ORDER BY id_kategori ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori Pelanggaran</title>
    
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
            <h4 class="text-white mb-4">
                Pelanggaran Siswa
            </h4>
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
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link active">Kelola Kategori Pelanggaran</a>
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
            <h2>Kelola Kategori Pelanggaran</h2>
            <p>Halaman pengelolaan data kategori pelanggaran siswa.</p>

            <div class="mb-3">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Kategori Pelanggaran</button>
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

            <h4 class="mb-3">Data Kategori Pelanggaran</h4>

            <div class="table-responsive">
                <table id="tabelKategori" class="table table-bordered table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th width="50">ID</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th width="100">Status</th>
                            <th width="130">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($query_data && mysqli_num_rows($query_data) > 0) :
                            while ($row = mysqli_fetch_assoc($query_data)) : 
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                            <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                            <td>
                                <span class="badge bg-success">Aktif</span>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_kategori']; ?>">Edit</button>
                                <a href="kelola_kategori_pelanggaran.php?hapus=<?= $row['id_kategori']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                   Hapus
                                </a>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal<?= $row['id_kategori']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Kategori Pelanggaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_kategori" value="<?= $row['id_kategori']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Kategori</label>
                                                <input type="text" class="form-control" name="nama_kategori" value="<?= htmlspecialchars($row['nama_kategori']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Deskripsi</label>
                                                <textarea class="form-control" name="deskripsi" rows="3"><?= htmlspecialchars($row['deskripsi']); ?></textarea>
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

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Pelanggaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" name="nama_kategori" required placeholder="Contoh: Kedisiplinan 1, Kerapian 1">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" placeholder="Keterangan mengenai kategori pelanggaran ini"></textarea>
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
    $(document).ready(function() {$('#tabelKategori').DataTable({
            "pagingType": "simple_numbers",
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data kategori pelanggaran tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data yang tersedia",
                "infoFiltered": "(difilter dari total _MAX_ data)",
                "search": "Cari Kategori:",
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