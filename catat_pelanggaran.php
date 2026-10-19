<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}

// Proses simpan data pelanggaran jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siswa_id    = $_POST['siswa_id'] ?? '';
    $pelanggaran = $_POST['pelanggaran'] ?? '';
    $poin        = $_POST['poin'] ?? 0;
    $tanggal     = $_POST['tanggal'] ?? date('Y-m-d');

    // Query INSERT untuk menyimpan data
    $sql = "INSERT INTO t_pelanggaran (id_t_siswa, pelanggaran, poin, tanggal) VALUES (?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($koneksi, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "isis", $siswa_id, $pelanggaran, $poin, $tanggal);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Data pelanggaran berhasil dicatat!'); window.location.href='riwayat.php';</script>";
            exit;
        } else {
            $err = mysqli_stmt_error($stmt);
            echo "<script>alert('Gagal mencatat pelanggaran: " . addslashes($err) . "');</script>";
        }
    } else {
        echo "<script>alert('Gagal menyiapkan query database: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Catat Pelanggaran - Guru</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS untuk kotak pilihan siswa yang bisa diketik/dicari -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
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
            <a href="catat_pelanggaran.php" class="active">Catat Pelanggaran</a>
            <a href="tindakan.php">Tindakan</a>
            <a href="riwayat.php">Riwayat</a>
            <a href="rekap_poin.php">Rekap Poin</a>
            <hr class="dropdown-divider my-3 border-secondary">
            <a href="logout.php" class="text-danger">Logout</a>
        </div>
    </div>

    <div class="content">
        <h2>Catat Pelanggaran Siswa</h2>
        <p class="text-muted">Masukkan data pelanggaran yang dilakukan oleh siswa.</p>

        <div class="card p-4 shadow-sm border-0 col-md-8">
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Cari / Pilih Siswa (Ketik Nama)</label>
                    <select name="siswa_id" id="selectSiswa" class="form-select" required>
                        <option value="">-- Ketik atau Pilih Nama Siswa --</option>
                        <?php
                        $query_siswa = mysqli_query($koneksi, "SELECT * FROM t_siswa ORDER BY nama ASC");
                        if ($query_siswa && mysqli_num_rows($query_siswa) > 0) {
                            while ($siswa = mysqli_fetch_assoc($query_siswa)) {
                                echo "<option value='" . $siswa['id'] . "'>" . htmlspecialchars($siswa['nama']) . " (ID: " . htmlspecialchars($siswa['id']) . ")</option>";
                            }
                        } else {
                            echo "<option value='' disabled>Belum ada data siswa di database</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Pelanggaran</label>
                    <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Pelanggaran (Ketik Bebas)</label>
                    <textarea name="pelanggaran" class="form-control" rows="3" placeholder="Contoh: Tidur di kelas / Terlambat..." required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Poin Pelanggaran</label>
                    <input type="number" name="poin" class="form-control" placeholder="Contoh: 10" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Pelanggaran</button>
                <a href="dashboard_guru.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

    <!-- jQuery dan Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#selectSiswa').select2({
                theme: 'bootstrap-5',
                placeholder: '-- Ketik atau Pilih Nama Siswa --',
                allowClear: true
            });
        });
    </script>
</body>
</html>