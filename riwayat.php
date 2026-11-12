
<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}

// Ambil hanya pelanggaran yang sudah diproses
$query_riwayat = mysqli_query($koneksi, "
    SELECT
        p.id,
        p.tanggal,
        p.pelanggaran,
        p.poin,
        p.status_tindakan,
        s.nama AS nama_siswa,
        s.nis
    FROM t_pelanggaran p
    LEFT JOIN t_siswa s ON p.id_t_siswa = s.id
    WHERE p.status_tindakan IN ('Sudah Ditindak', 'Selesai')
    ORDER BY p.id DESC
");

if (!$query_riwayat) {
    die("Query riwayat gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pelanggaran - Guru</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #343a40;
            color: #fff;
            position: fixed;
            z-index: 100;
        }

        .sidebar .brand {
            padding: 1.5rem 1rem;
            font-size: 1.25rem;
            font-weight: bold;
            border-bottom: 1px solid #4f5962;
        }

        .sidebar a {
            color: #c2c7d0;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #0d6efd;
            color: #fff;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        @media(max-width: 768px) {
            .sidebar {
                width: 180px;
            }

            .content {
                margin-left: 180px;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<div class="sidebar">
    <div class="brand">Pelanggaran Siswa</div>

    <div class="nav flex-column mt-2">
        <a href="dashboard_guru.php">Dashboard</a>
        <a href="catat_pelanggaran.php">Catat Pelanggaran</a>
        <a href="tindakan.php">Tindakan</a>
        <a href="riwayat.php" class="active">Riwayat</a>
        <a href="rekap_poin.php">Rekap Poin</a>
        <a href="pelanggaran.php">Laporan</a>
        <hr class="dropdown-divider my-3 border-secondary">
        <a href="logout.php" class="text-danger">Logout</a>
    </div>
</div>

<div class="content">

    <h2>Riwayat Pelanggaran Siswa</h2>

    <p class="text-muted">
        Catatan historis seluruh pelanggaran dan sanksi yang telah diproses.
    </p>

    <div class="card p-4 shadow-sm border-0">

        <div class="table-responsive">
            <table id="tabelRiwayat"
                   class="table table-bordered table-striped align-middle mb-0"
                   style="width:100%">

                <thead class="table-light">
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Pelanggaran</th>
                        <th class="text-center">Poin</th>
                        <th>Status Tindakan</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                $no = 1;

                if (mysqli_num_rows($query_riwayat) > 0):
                    while ($row = mysqli_fetch_assoc($query_riwayat)):

                        $nama = !empty($row['nama_siswa'])
                            ? $row['nama_siswa']
                            : 'Siswa Tidak Ditemukan';

                        $pelanggaran = $row['pelanggaran'] ?? '-';
                        $tanggal = $row['tanggal'] ?? '-';
                        $poin = $row['poin'] ?? 0;
                        $status = $row['status_tindakan'];
                ?>
                    <tr>
                        <td><?= $no++; ?></td>

                        <td>
                            <?= htmlspecialchars($tanggal); ?>
                        </td>

                        <td>
                            <b><?= htmlspecialchars($nama); ?></b>
                        </td>

                        <td>
                            <?= htmlspecialchars($pelanggaran); ?>
                        </td>

                        <td class="text-center">
                            <span class="badge bg-danger">
                                <?= htmlspecialchars($poin); ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-success">
                                <?= htmlspecialchars($status); ?>
                            </span>
                        </td>
                    </tr>

                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Belum ada riwayat tindakan yang selesai diproses.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tabelRiwayat').DataTable({
        language: {
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data riwayat tidak ditemukan",
            info: "Menampilkan halaman _PAGE_ dari _PAGES_",
            infoEmpty: "Tidak ada data",
            search: "Cari Riwayat:",
            paginate: {
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        pageLength: 10,
        order: [[1, "desc"]]
    });
});
</script>

</body>
</html>
