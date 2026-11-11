
<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}

// Pastikan kolom status tersedia
$cek_kolom = mysqli_query($koneksi, "
    SHOW COLUMNS FROM t_pelanggaran LIKE 'status_tindakan'
");

if ($cek_kolom && mysqli_num_rows($cek_kolom) == 0) {
    mysqli_query($koneksi, "
        ALTER TABLE t_pelanggaran
        ADD status_tindakan VARCHAR(100) NOT NULL
        DEFAULT 'Perlu Tindakan'
    ");
}

// Proses tindakan hanya satu kali
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proses_tindakan'])) {
    $id = intval($_POST['id_pelanggaran']);

    $stmt = mysqli_prepare($koneksi, "
        UPDATE t_pelanggaran
        SET status_tindakan = 'Sudah Ditindak'
        WHERE id = ?
        AND (status_tindakan = 'Perlu Tindakan'
             OR status_tindakan = ''
             OR status_tindakan IS NULL)
    ");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['pesan_tindakan'] = 'Tindakan berhasil diproses!';
        } else {
            $_SESSION['pesan_tindakan'] = 'Data sudah diproses atau tidak ditemukan!';
        }

        mysqli_stmt_close($stmt);
    }

    header("Location: tindakan.php");
    exit;
}

// Ambil data pelanggaran beserta nama siswa
$query = mysqli_query($koneksi, "
    SELECT
        p.id,
        p.id_t_siswa,
        p.tanggal,
        p.pelanggaran,
        p.poin,
        p.status_tindakan,
        s.nama AS nama_siswa,
        s.nis
    FROM t_pelanggaran p
    LEFT JOIN t_siswa s ON p.id_t_siswa = s.id
    ORDER BY p.id DESC
");

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tindakan & Sanksi - Guru</title>

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
        <a href="tindakan.php" class="active">Tindakan</a>
        <a href="riwayat.php">Riwayat</a>
        <a href="rekap_poin.php">Rekap Poin</a>
        <hr class="dropdown-divider my-3 border-secondary">
        <a href="logout.php" class="text-danger">Logout</a>
    </div>
</div>

<div class="content">

    <h2>Daftar Tindakan & Sanksi</h2>
    <p class="text-muted">
        Kelola tindak lanjut dan sanksi untuk siswa yang melanggar.
    </p>

    <?php if (isset($_SESSION['pesan_tindakan'])): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($_SESSION['pesan_tindakan']); ?>
        </div>
        <?php unset($_SESSION['pesan_tindakan']); ?>
    <?php endif; ?>

    <div class="card p-4 shadow-sm border-0">

        <div class="table-responsive">
            <table id="tabelTindakan"
                   class="table table-bordered table-striped align-middle"
                   style="width:100%">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Siswa</th>
                        <th>Pelanggaran</th>
                        <th class="text-center">Poin</th>
                        <th>Status Tindakan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                $no = 1;

                if (mysqli_num_rows($query) > 0):
                    while ($row = mysqli_fetch_assoc($query)):

                        $nama = $row['nama_siswa'] ?? '';
                        $pelanggaran = $row['pelanggaran'] ?? '-';
                        $poin = $row['poin'] ?? 0;
                        $status = $row['status_tindakan'] ?? 'Perlu Tindakan';

                        if (empty($nama)) {
                            $nama = 'Siswa Tidak Ditemukan';
                        }

                        if (empty($status)) {
                            $status = 'Perlu Tindakan';
                        }

                        $selesai = in_array(
                            $status,
                            ['Sudah Ditindak', 'Selesai']
                        );

                        $badge = $selesai
                            ? 'bg-success'
                            : 'bg-warning text-dark';
                ?>
                    <tr>
                        <td><?= $no++; ?></td>

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
                            <span class="badge <?= $badge; ?>">
                                <?= htmlspecialchars($status); ?>
                            </span>
                        </td>

                        <td class="text-center">
                            <?php if ($selesai): ?>
                                <button class="btn btn-secondary btn-sm" disabled>
                                    Selesai
                                </button>
                            <?php else: ?>
                                <form method="POST"
                                      onsubmit="return confirm('Proses tindakan untuk siswa ini?')">

                                    <input type="hidden"
                                           name="id_pelanggaran"
                                           value="<?= $row['id']; ?>">

                                    <button type="submit"
                                            name="proses_tindakan"
                                            class="btn btn-primary btn-sm">
                                        Proses
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>

                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Belum ada data pelanggaran.
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
    $('#tabelTindakan').DataTable({
        language: {
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tindakan tidak ditemukan",
            info: "Menampilkan halaman _PAGE_ dari _PAGES_",
            infoEmpty: "Tidak ada data",
            search: "Cari Tindakan:",
            paginate: {
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        pageLength: 10,
        order: [[0, "asc"]]
    });
});
</script>

</body>
</html>
