<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil semua data tahun ajaran (DIURUTKAN ASCENDING: 1, 2, 3...)
$query = mysqli_query($koneksi, "
    SELECT * FROM t_tahun_ajaran
    ORDER BY id ASC
");

if (!$query) {
    die("<div class='alert alert-danger m-4'><strong>DATABASE ERROR [DB_ERR_TAHUN_AJARAN]:</strong> Gagal mengambil data tahun ajaran: " . mysqli_error($koneksi) . "</div>");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tahun Ajaran</title>
    
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
                    <a href="kelola_tahun_ajaran.php" class="nav-link active">Kelola Tahun Ajaran</a>
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
            <h2>Kelola Tahun Ajaran</h2>
            <p>Halaman pengelolaan data tahun ajaran sekolah.</p>

            <div class="mb-3">
                <a href="tambah_tahun_ajaran.php" class="btn btn-primary btn-sm">+ Tambah Tahun Ajaran</a>
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

            <h4 class="mb-3">Data Tahun Ajaran</h4>

            <div class="table-responsive">
                <!-- Berikan ID tabelTahunAjaran untuk DataTables -->
                <table id="tabelTahunAjaran" class="table table-bordered table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($row = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']); ?></td>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <td><?= htmlspecialchars($row['tanggal_mulai']); ?></td>
                                <td><?= htmlspecialchars($row['tanggal_selesai']); ?></td>
                                <td>
                                    <?php 
                                    if (isset($row['status_aktif']) && $row['status_aktif'] == 1) {
                                        echo '<span class="badge bg-success">Aktif</span>';
                                    } else {
                                        echo '<span class="badge bg-danger">Tidak Aktif</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="edit_tahun_ajaran.php?id=<?= (int) $row['id']; ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
                                    <a href="hapus_tahun_ajaran.php?id=<?= (int) $row['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
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
    $(document).ready(function() {
        $('#tabelTahunAjaran').DataTable({
            "pagingType": "simple_numbers",
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tahun ajaran tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data yang tersedia",
                "infoFiltered": "(difilter dari total _MAX_ data)",
                "search": "Cari Tahun Ajaran:",
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