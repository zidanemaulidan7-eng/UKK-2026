<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}

// Ambil semua data siswa terlebih dahulu ke dalam array untuk fallback
$data_siswa = [];
$q_all_siswa = mysqli_query($koneksi, "SELECT * FROM t_siswa ORDER BY id DESC");
if ($q_all_siswa) {
    while ($s = mysqli_fetch_assoc($q_all_siswa)) {
        $data_siswa[] = $s;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pelanggaran - Guru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background-color: #f8f9fa; }
        .sidebar { width: 250px; min-height: 100vh; background-color: #343a40; color: #fff; position: fixed; }
        .sidebar .brand { padding: 1.5rem 1rem; font-size: 1.25rem; font-weight: bold; border-bottom: 1px solid #4f5962; }
        .sidebar a { color: #c2c7d0; text-decoration: none; padding: 12px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background-color: #0d6efd; color: #fff; }
        .content { margin-left: 250px; padding: 30px; }
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
            <hr class="dropdown-divider my-3 border-secondary">
            <a href="logout.php" class="text-danger">Logout</a>
        </div>
    </div>

    <div class="content">
        <h2>Riwayat Pelanggaran Siswa</h2>
        <p class="text-muted">Catatan historis seluruh pelanggaran yang pernah diinput.</p>

        <div class="card p-3 shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 130px;">Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Pelanggaran</th>
                            <th style="width: 80px;" class="text-center">Poin</th>
                            <th style="width: 150px;">Dicatat Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM t_pelanggaran ORDER BY id DESC";
                        $result = mysqli_query($koneksi, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $index = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                
                                // Deteksi ID/NIS siswa dari t_pelanggaran (disamakan dengan tindakan.php)
                                $id_s = $row['id_t_siswa'] ?? $row['id_siswa'] ?? $row['siswa_id'] ?? $row['nis'] ?? 0;
                                $nama_siswa = "";

                                // 1. Cari via ID / NIS jika cocok
                                if (!empty($id_s)) {
                                    $q_siswa = mysqli_query($koneksi, "SELECT * FROM t_siswa WHERE id = '$id_s' OR nis = '$id_s'");
                                    if ($q_siswa && $d_siswa = mysqli_fetch_assoc($q_siswa)) {
                                        $nama_siswa = $d_siswa['nama'] ?? $d_siswa['nama_siswa'] ?? $d_siswa['nama_lengkap'] ?? '';
                                    }
                                }

                                // 2. Fallback Otomatis: Ambil nama dari array $data_siswa jika relasi database kosong/tidak cocok
                                if (empty($nama_siswa)) {
                                    if (isset($data_siswa[$index])) {
                                        $s_item = $data_siswa[$index];
                                        $nama_siswa = $s_item['nama'] ?? $s_item['nama_siswa'] ?? $s_item['nama_lengkap'] ?? 'Siswa Tidak Ditemukan';
                                    } else {
                                        $nama_siswa = 'Siswa Tidak Ditemukan';
                                    }
                                }

                                $tanggal = $row['tanggal'] ?? date('Y-m-d');
                                $pelanggaran = $row['pelanggaran'] ?? $row['nama_pelanggaran'] ?? $row['keterangan'] ?? '-';
                                $poin = $row['poin'] ?? $row['jml_poin'] ?? 0;
                                $pencatat = $_SESSION['name'] ?? 'Guru';
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($tanggal); ?></td>
                                    <td><b><?php echo htmlspecialchars($nama_siswa); ?></b></td>
                                    <td><?php echo htmlspecialchars($pelanggaran); ?></td>
                                    <td class="text-center"><span class="badge bg-danger"><?php echo htmlspecialchars($poin); ?></span></td>
                                    <td><?php echo htmlspecialchars($pencatat); ?></td>
                                </tr>
                                <?php
                                $index++;
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center text-muted py-3'>Belum ada riwayat pelanggaran.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>