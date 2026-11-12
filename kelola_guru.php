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
        ORDER BY t_guru.id ASC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Query data guru gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Guru</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        /* Mengunci sidebar agar diam total dan tidak bisa di-scroll / muncul scrollbar */
        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 16.666667%; /* Lebar standar col-lg-2 */
            height: 100vh;
            z-index: 100;
            overflow: hidden; /* Mencegah pergerakan atau scroll terpisah */
            background-color: #212529;
            padding: 15px;
        }

        /* Membuat jarak dan ukuran menu ringkas agar pas di berbagai tinggi layar */
        .sidebar-fixed h4 {
            font-size: 1.1rem;
            margin-bottom: 0.8rem !important;
            color: #fff;
            border-bottom: 1px solid #373b3e;
            padding-bottom: 0.5rem;
        }
        .sidebar-fixed .nav-item {
            margin-bottom: 2px !important;
        }
        .sidebar-fixed .nav-link {
            color: #adb5bd;
            padding: 6px 10px;
            font-size: 0.88rem;
            border-radius: 0.375rem;
        }
        .sidebar-fixed .nav-link:hover, .sidebar-fixed .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
        .sidebar-fixed .nav-link.logout {
            color: #dc3545;
        }
        .sidebar-fixed .nav-link.logout:hover {
            background-color: #dc3545;
            color: #fff;
        }
        .sidebar-fixed hr {
            margin: 0.5rem 0;
            border-color: #373b3e;
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
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="sidebar-fixed">
            <h4 class="mb-3">Pelanggaran Siswa</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="kelola_siswa.php" class="nav-link">Kelola Siswa</a></li>
                <li class="nav-item"><a href="kelola_guru.php" class="nav-link active">Kelola Guru</a></li>
                <li class="nav-item"><a href="kelola_kelas.php" class="nav-link">Kelola Kelas</a></li>
                <li class="nav-item"><a href="kelola_tahun_ajaran.php" class="nav-link">Kelola Tahun Ajaran</a></li>
                <li class="nav-item"><a href="kelola_wali_kelas.php" class="nav-link">Kelola Wali Kelas</a></li>
                <li class="nav-item"><a href="kelola_kategori_pelanggaran.php" class="nav-link">Kelola Kategori Pelanggaran</a></li>
                <li class="nav-item"><a href="kelola_jenis_pelanggaran.php" class="nav-link">Kelola Jenis Pelanggaran</a></li>
                <li class="nav-item"><a href="penempatan_siswa.php" class="nav-link">Penempatan Siswa</a></li>
                <li class="nav-item"><a href="tentang_saya.php" class="nav-link">About Me</a></li>
            </ul>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="logout.php" class="nav-link logout">Logout</a></li>
            </ul>
        </div>

        <!-- KONTEN UTAMA -->
        <main class="p-4 main-content">
            <h2>Kelola Guru</h2>

            <?php if (isset($_SESSION['pesan'])) { ?>
                <div class="alert alert-success mt-3">
                    <?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
                </div>
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
                    <label class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-6">
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Konfirmasi Password</label>
                    <div class="col-sm-6">
                        <input type="password" name="konfirmasi_password" class="form-control" required>
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
                <table id="tabelGuru" class="table table-bordered table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($query && mysqli_num_rows($query) > 0) {
                            while ($guru = mysqli_fetch_assoc($query)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($guru['id']); ?></td>
                                <td><?= htmlspecialchars($guru['nip']); ?></td>
                                <td><?= htmlspecialchars($guru['nama']); ?></td>
                                <td><?= htmlspecialchars($guru['email'] ?? $guru['email_user'] ?? '-'); ?></td>
                                <td>
                                    <?= ($guru['status_aktif'] == 1) ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>'; ?>
                                </td>
                                <td>
                                    <a href="edit_guru.php?id=<?= $guru['id']; ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
                                    <a href="proses_guru.php?aksi=hapus&id=<?= $guru['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus data guru ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- JQUERY & BOOTSTRAP JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabelGuru').DataTable({
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data guru tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data yang tersedia",
                "search": "Cari Guru:",
                "paginate": {
                    "first": "Pertama", "last": "Terakhir", "next": "Selanjutnya", "previous": "Sebelumnya"
                }
            },
            "pageLength": 10,
            "order": [[0, "asc"]]
        });
    });
</script>

</body>
</html>