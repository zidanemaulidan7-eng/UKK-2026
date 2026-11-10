<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil semua data siswa (diurutkan secara ASCENDING: 1, 2, 3...)
$sql = "SELECT * FROM t_siswa ORDER BY id ASC";
$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Siswa</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        /* Mengunci sidebar agar diam total dan tidak bisa di-scroll */
        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 16.666667%; /* Lebar standar col-lg-2 */
            height: 100vh;
            z-index: 100;
            overflow: hidden; /* Mencegah pergerakan/scroll pada sidebar */
            padding: 15px;
        }

        /* Membuat jarak dan ukuran menu lebih ringkas agar pas di berbagai tinggi layar */
        .sidebar-fixed h4 {
            font-size: 1.1rem;
            margin-bottom: 0.8rem !important;
        }
        .sidebar-fixed .nav-item {
            margin-bottom: 2px !important;
        }
        .sidebar-fixed .nav-link {
            padding: 6px 10px;
            font-size: 0.88rem;
        }
        .sidebar-fixed hr {
            margin: 0.5rem 0;
        }

        /* Memberikan jarak margin kiri pada konten utama agar tidak tertutup sidebar */
        .main-content {
            margin-left: 16.666667%;
            width: 83.333333%;
        }

        /* Responsif untuk layar medium / tablet */
        @media (max-width: 991.98px) {
            .sidebar-fixed {
                width: 25%; /* Lebar col-md-3 */
            }
            .main-content {
                margin-left: 25%;
                width: 75%;
            }
        }

        /* Responsif untuk layar kecil / HP */
        @media (max-width: 767.98px) {
            .sidebar-fixed {
                position: relative;
                width: 100%;
                height: auto;
                overflow: visible;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark sidebar-fixed">
            <h4 class="text-white">
                Pelanggaran Siswa
            </h4>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link text-white">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_siswa.php" class="nav-link active">Kelola Siswa</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_guru.php" class="nav-link text-white">Kelola Guru</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_kelas.php" class="nav-link text-white">Kelola Kelas</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_tahun_ajaran.php" class="nav-link text-white">Kelola Tahun Ajaran</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_wali_kelas.php" class="nav-link text-white">Kelola Wali Kelas</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link text-white">Kelola Kategori Pelanggaran</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link text-white">Kelola Jenis Pelanggaran</a>
                </li>
                <li class="nav-item">
                    <a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a>
                </li>
                <li class="nav-item">
                    <a href="pelanggaran.php" class="nav-link text-white">Laporan</a>
                </li>
                <li class="nav-item">
                    <a href="tentang_saya.php" class="nav-link text-white">About Me</a>
                </li>
                <hr class="text-secondary">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </li>
            </ul>
        </div>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4 main-content">
            <h2>Kelola Siswa</h2>
            <p>Halaman untuk mengelola data siswa.</p>

            <!-- Tombol Tambah Siswa -->
            <div class="mb-3">
                <a href="tambah_siswa.php" class="btn btn-primary btn-sm">+ Tambah Siswa</a>
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

            <h4 class="mb-3">Data Siswa</h4>

            <div class="table-responsive">
                <table id="tabelSiswa" class="table table-bordered table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
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
                            $no = 1;
                            while ($siswa = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
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
                                    if (isset($siswa['status_aktif']) && $siswa['status_aktif'] == 1) {
                                        echo '<span class="badge bg-success">Aktif</span>';
                                    } else {
                                        echo '<span class="badge bg-danger">Tidak Aktif</span>';
                                    }
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
        $('#tabelSiswa').DataTable({
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data siswa tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data yang tersedia",
                "infoFiltered": "(difilter dari total _MAX_ data)",
                "search": "Cari Siswa:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
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