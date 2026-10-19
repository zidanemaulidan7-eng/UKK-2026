<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}

// Ambil semua data siswa ke dalam array untuk fallback
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
    <title>Rekap Poin Siswa - Guru</title>
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
            <a href="riwayat.php">Riwayat</a>
            <a href="rekap_poin.php" class="active">Rekap Poin</a>
            <hr class="dropdown-divider my-3 border-secondary">
            <a href="logout.php" class="text-danger">Logout</a>
        </div>
    </div>

    <div class="content">
        <h2>Rekap Poin Pelanggaran Siswa</h2>
        <p class="text-muted">Total poin akumulasi dari setiap siswa.</p>

        <div class="card p-3 shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Nama Siswa</th>
                            <th style="width: 150px;">Kelas</th>
                            <th style="width: 100px;" class="text-center">Total Poin</th>
                            <th style="width: 180px;">Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM t_pelanggaran ORDER BY id DESC";
                        $result = mysqli_query($koneksi, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $index = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                
                                // Deteksi ID/NIS siswa dari t_pelanggaran (disamakan dengan file lain)
                                $id_s = $row['id_t_siswa'] ?? $row['id_siswa'] ?? $row['siswa_id'] ?? $row['nis'] ?? 0;
                                $nama_siswa = "";
                                $kelas_siswa = "-";

                                // 1. Cari via ID / NIS jika cocok
                                if (!empty($id_s)) {
                                    $q_siswa = mysqli_query($koneksi, "SELECT * FROM t_siswa WHERE id = '$id_s' OR nis = '$id_s'");
                                    if ($q_siswa && $d_siswa = mysqli_fetch_assoc($q_siswa)) {
                                        $nama_siswa = $d_siswa['nama'] ?? $d_siswa['nama_siswa'] ?? $d_siswa['nama_lengkap'] ?? '';
                                        $kelas_siswa = $d_siswa['kelas'] ?? $d_siswa['nama_kelas'] ?? $d_siswa['rombel'] ?? '-';
                                    }
                                }

                                // 2. Fallback Otomatis dari array $data_siswa
                                if (empty($nama_siswa)) {
                                    if (isset($data_siswa[$index])) {
                                        $s_item = $data_siswa[$index];
                                        $nama_siswa = $s_item['nama'] ?? $s_item['nama_siswa'] ?? $s_item['nama_lengkap'] ?? 'Siswa Tidak Ditemukan';
                                        $kelas_siswa = $s_item['kelas'] ?? $s_item['nama_kelas'] ?? '-';
                                    } else {
                                        $nama_siswa = 'Siswa Tidak Ditemukan';
                                    }
                                }

                                $poin = intval($row['poin'] ?? $row['jml_poin'] ?? 0);
                                
                                // Penentuan Kategori berdasarkan jumlah poin
                                $badge_kategori = 'bg-success';
                                $teks_kategori = 'Ringan';
                                if ($poin >= 50) {
                                    $badge_kategori = 'bg-danger';
                                    $teks_kategori = 'Berat';
                                } elseif ($poin >= 20) {
                                    $badge_kategori = 'bg-warning text-dark';
                                    $teks_kategori = 'Sedang';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><b><?php echo htmlspecialchars($nama_siswa); ?></b></td>
                                    <td><?php echo htmlspecialchars($kelas_siswa); ?></td>
                                    <td class="text-center"><span class="badge bg-dark"><?php echo $poin; ?></span></td>
                                    <td><span class="badge <?php echo $badge_kategori; ?>"><?php echo $teks_kategori; ?></span></td>
                                </tr>
                                <?php
                                $index++;
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center text-muted py-3'>Belum ada rekap poin tersedia.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>