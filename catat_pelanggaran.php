<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}

// Proses simpan data pelanggaran jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siswa_id = $_POST['siswa_id'] ?? '';
    $pelanggaran = $_POST['pelanggaran'] ?? '';
    $poin = $_POST['poin'] ?? 0;
    $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
    $guru_id = $_SESSION['id_user'] ?? 1;

    // Menggunakan 'id_siswa' sebagai kolom relasi ke tabel siswa
    $sql = "INSERT INTO t_pelanggaran (id_siswa, user_id, pelanggaran, poin, tanggal) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "iisis", $siswa_id, $guru_id, $pelanggaran, $poin, $tanggal);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Data pelanggaran berhasil dicatat!'); window.location.href='riwayat.php';</script>";
    } else {
        echo "<script>alert('Gagal mencatat pelanggaran: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Catat Pelanggaran - Guru</title>
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
                    <label class="form-label">Pilih Siswa</label>
                    <select name="siswa_id" class="form-select" required>
                        <option value="">-- Pilih Siswa --</option>
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
                    <label class="form-label">Jenis Pelanggaran</label>
                    <textarea name="pelanggaran" class="form-control" rows="3" placeholder="Deskripsi pelanggaran..." required></textarea>
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
</body>
</html>