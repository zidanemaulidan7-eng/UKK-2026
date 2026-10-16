<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data penempatan siswa menggunakan tabel t_kelas_siswa yang sudah ada di database
$sql = "
    SELECT 
        ks.id,
        s.nama AS nama_siswa,
        s.nis,
        k.nama AS nama_kelas,
        ta.nama AS tahun_ajaran,
        ks.status_aktif
    FROM t_kelas_siswa ks
    LEFT JOIN t_siswa s ON ks.siswa_id = s.id
    LEFT JOIN t_kelas k ON ks.kelas_id = k.id
    LEFT JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
    ORDER BY ks.id DESC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("<div class='alert alert-danger m-4'><strong>DATABASE ERROR:</strong> Gagal mengambil data penempatan siswa: " . mysqli_error($koneksi) . "</div>");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Kelola Penempatan Siswa</title>
    <style>
        @media print {
            /* Sembunyikan sidebar, tombol tambah, aksi, dan elemen non-cetak */
            .col-md-3, .col-lg-2, .btn, .mb-3, hr, th:last-child, td:last-child {
                display: none !important;
            }
            /* Lebarkan konten utama agar memenuhi halaman kertas */
            main {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
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
                    <a href="penempatan_siswa.php" class="nav-link active">Penempatan Siswa</a>
                </li>
                <!-- Menu Cetak / Export di Sidebar -->
                <li class="nav-item mb-2">
                    <a href="#" onclick="window.print()" class="nav-link text-white">Cetak / Export</a>
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
            <h2>Kelola Penempatan Siswa</h2>
            <p>Halaman untuk mengatur penempatan kelas siswa pada tahun ajaran tertentu.</p>

            <?php if (isset($_SESSION['pesan'])) { ?>
                <div class="alert alert-success mt-3">
                    <?php
                    echo $_SESSION['pesan'];
                    unset($_SESSION['pesan']);
                    ?>
                </div>
            <?php } ?>

            <div class="mb-3 d-flex gap-2">
                <a href="tambah_penempatan_siswa.php" class="btn btn-primary btn-sm">+ Tambah Penempatan Siswa</a>
                <button onclick="window.print()" class="btn btn-success btn-sm">🖨️ Cetak / Print</button>
                <button onclick="exportExcel()" class="btn btn-warning btn-sm">📊 Export ke Excel</button>
            </div>

            <hr>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;

                        if (mysqli_num_rows($query) > 0) {
                            while ($row = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nis'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['nama_siswa'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['nama_kelas'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['tahun_ajaran'] ?? '-') ?></td>
                                <td>
                                    <?= ($row['status_aktif'] == 1) ? 'Aktif' : 'Tidak Aktif' ?>
                                </td>
                                <td>
                                    <a href="edit_penempatan_siswa.php?id=<?= (int)$row['id'] ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
                                    <a href="hapus_penempatan_siswa.php?id=<?= (int)$row['id'] ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus penempatan siswa ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data penempatan siswa.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script>
function exportExcel() {
    let table = document.querySelector("table");
    let html = table.outerHTML;
    let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    let downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    downloadLink.href = url;
    downloadLink.download = 'Data_Penempatan_Siswa.xls';
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>

</body>
</html>