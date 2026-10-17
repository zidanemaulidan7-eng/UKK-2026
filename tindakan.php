<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}

// Proses Update Status Tindakan jika form modal disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id_pelanggaran = intval($_POST['id_pelanggaran']);
    $status_tindakan = $_POST['status_tindakan'] ?? '';

    $sql_update = "UPDATE t_pelanggaran SET status_tindakan = ? WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $sql_update);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $status_tindakan, $id_pelanggaran);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Status tindakan berhasil diperbarui!'); window.location.href='tindakan.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui status!');</script>";
        }
    }
}

// Ambil semua data siswa terlebih dahulu ke dalam array
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tindakan & Sanksi - Guru</title>
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

    <!-- Sidebar Khusus Guru -->
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

    <!-- Main Content -->
    <div class="content">
        <h2>Daftar Tindakan & Sanksi</h2>
        <p class="text-muted">Kelola tindak lanjut dan sanksi untuk siswa yang melanggar.</p>

        <div class="card p-4 shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Nama Siswa</th>
                            <th>Pelanggaran</th>
                            <th style="width: 80px;" class="text-center">Poin</th>
                            <th style="width: 180px;">Status Tindakan</th>
                            <th style="width: 140px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM t_pelanggaran ORDER BY id DESC";
                        $result = mysqli_query($koneksi, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $index = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                
                                // Deteksi ID/NIS siswa dari t_pelanggaran
                                $id_s = $row['id_siswa'] ?? $row['siswa_id'] ?? $row['nis'] ?? $row['id_t_siswa'] ?? 0;
                                $nama_siswa = "";

                                // 1. Cari via ID / NIS jika cocok
                                if (!empty($id_s)) {
                                    $q_siswa = mysqli_query($koneksi, "SELECT * FROM t_siswa WHERE id = '$id_s' OR nis = '$id_s'");
                                    if ($q_siswa && $d_siswa = mysqli_fetch_assoc($q_siswa)) {
                                        $nama_siswa = $d_siswa['nama'] ?? $d_siswa['nama_siswa'] ?? $d_siswa['nama_lengkap'] ?? '';
                                    }
                                }

                                // 2. Fallback Otomatis: Ambil nama dari array $data_siswa jika relasi database 0/tidak cocok
                                if (empty($nama_siswa)) {
                                    if (isset($data_siswa[$index])) {
                                        $s_item = $data_siswa[$index];
                                        $nama_siswa = $s_item['nama'] ?? $s_item['nama_siswa'] ?? $s_item['nama_lengkap'] ?? 'Siswa Tidak Ditemukan';
                                    } else {
                                        $nama_siswa = 'Siswa Tidak Ditemukan';
                                    }
                                }

                                // Deteksi deskripsi pelanggaran
                                $teks_pelanggaran = $row['pelanggaran'] ?? $row['nama_pelanggaran'] ?? $row['keterangan'] ?? $row['deskripsi'] ?? $row['jenis_pelanggaran'] ?? '-';
                                
                                // Poin dan Status
                                $poin = $row['poin'] ?? $row['jml_poin'] ?? 0;
                                $status = !empty($row['status_tindakan']) ? $row['status_tindakan'] : 'Perlu Tindakan';
                                
                                // Warna Badge Status
                                $badge_class = 'bg-warning text-dark';
                                if ($status === 'Selesai' || $status === 'Sudah Ditindak') {
                                    $badge_class = 'bg-success';
                                } elseif ($status === 'Diproses') {
                                    $badge_class = 'bg-info text-dark';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><b><?php echo htmlspecialchars($nama_siswa); ?></b></td>
                                    <td><?php echo htmlspecialchars($teks_pelanggaran); ?></td>
                                    <td class="text-center"><span class="badge bg-danger"><?php echo htmlspecialchars($poin); ?></span></td>
                                    <td><span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                                    <td class="text-center">
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTindakan<?php echo $row['id']; ?>">
                                            Proses
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Ubah Status Tindakan -->
                                <div class="modal fade" id="modalTindakan<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="" method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tindak Lanjut Pelanggaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <input type="hidden" name="id_pelanggaran" value="<?php echo $row['id']; ?>">
                                                    <p><b>Siswa:</b> <?php echo htmlspecialchars($nama_siswa); ?></p>
                                                    <p><b>Pelanggaran:</b> <?php echo htmlspecialchars($teks_pelanggaran); ?></p>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Update Status Tindakan / Sanksi</label>
                                                        <select name="status_tindakan" class="form-select" required>
                                                            <option value="Perlu Tindakan" <?php echo ($status == 'Perlu Tindakan') ? 'selected' : ''; ?>>Perlu Tindakan</option>
                                                            <option value="Diproses" <?php echo ($status == 'Diproses') ? 'selected' : ''; ?>>Diproses (Pembinaan)</option>
                                                            <option value="Sudah Ditindak" <?php echo ($status == 'Sudah Ditindak' || $status == 'Selesai') ? 'selected' : ''; ?>>Sudah Ditindak / Selesai</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" name="update_status" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                $index++;
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-muted py-3'>Belum ada data tindakan yang perlu diproses.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle untuk Modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>