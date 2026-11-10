<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}

// Ambil data riwayat pelanggaran dari database
$query_riwayat = mysqli_query($koneksi, "
    SELECT p.*, s.nama AS nama_siswa, s.nis 
    FROM t_pelanggaran p 
    LEFT JOIN t_siswa s ON p.id_t_siswa = s.id 
    ORDER BY p.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pelanggaran - Guru</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        body { min-height: 100vh; background-color: #f8f9fa; }
        /* Sidebar agar tetap diam di samping layar tanpa scroll internal */
        .sidebar-sticky {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            background-color: #343a40;
            color: #fff;
            width: 250px;
            flex-shrink: 0;
            z-index: 100;
        }
        .sidebar-sticky .brand {
            padding: 1.5rem 1rem;
            font-size: 1.25rem;
            font-weight: bold;
            border-bottom: 1px solid #4f5962;
        }
        .sidebar-sticky .nav-link {
            color: #c2c7d0;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
            border-radius: 0.375rem;
            margin-bottom: 0.2rem;
        }
        .sidebar-sticky .nav-link:hover, .sidebar-sticky .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
        }
        .content {
            flex-grow: 1;
            padding: 30px;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- SIDEBAR -->
    <div class="sidebar-sticky p-0">
        <div class="brand">Pelanggaran Siswa</div>
        <div class="nav flex-column p-3">
            <a href="dashboard_guru.php" class="nav-link">Dashboard</a>
            <a href="catat_pelanggaran.php" class="nav-link">Catat Pelanggaran</a>
            <a href="tindakan.php" class="nav-link">Tindakan</a>
            <a href="riwayat.php" class="nav-link btn btn-primary text-white text-start active">Riwayat</a>
            <a href="rekap_poin.php" class="nav-link">Rekap Poin</a>
            <hr class="text-secondary my-3">
            <a href="logout.php" class="nav-link text-danger">Logout</a>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="content">
        <h2>Riwayat Pelanggaran Siswa</h2>
        <p class="text-muted">Catatan historis seluruh pelanggaran yang pernah diinput.</p>

        <div class="card p-4 shadow-sm border-0">
            <div class="table-responsive">
                <table id="tabelRiwayat" class="table table-bordered table-striped align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Pelanggaran</th>
                            <th style="width: 80px;" class="text-center">Poin</th>
                            <th>Status Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($query_riwayat && mysqli_num_rows($query_riwayat) > 0) {
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($query_riwayat)) {
                                $nama_s = $row['nama_siswa'] ?? 'Siswa Tidak Ditemukan';
                                $pelanggaran = $row['pelanggaran'] ?? '-';
                                $poin = $row['poin'] ?? 0;
                                $tanggal = $row['tanggal'] ?? date('Y-m-d');
                                $status = $row['status_tindakan'] ?? 'Perlu Tindakan';

                                // Warna badge status
                                $badge_bg = 'bg-warning text-dark';
                                if ($status === 'Sudah Ditindak' || $status === 'Selesai') {
                                    $badge_bg = 'bg-success';
                                } elseif ($status === 'Diproses') {
                                    $badge_bg = 'bg-info text-dark';
                                }
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($tanggal); ?></td>
                            <td><b><?= htmlspecialchars($nama_s); ?></b></td>
                            <td><?= htmlspecialchars($pelanggaran); ?></td>
                            <td class="text-center"><span class="badge bg-danger"><?= htmlspecialchars($poin); ?></span></td>
                            <td><span class="badge <?= $badge_bg; ?>"><?= htmlspecialchars($status); ?></span></td>
                        </tr>
                        <?php 
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery & Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabelRiwayat').DataTable({
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data riwayat tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "search": "Cari Riwayat:",
                "paginate": { "next": "Selanjutnya", "previous": "Sebelumnya" }
            },
            "pageLength": 10,
            "order": [[1, "desc"]]
        });
    });
</script>

</body>
</html>