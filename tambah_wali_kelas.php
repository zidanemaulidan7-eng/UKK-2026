<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Proses simpan data saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tahun_ajaran_id = (int) $_POST['tahun_ajaran_id'];
    $kelas_id        = (int) $_POST['kelas_id'];
    $guru_id         = (int) $_POST['guru_id'];
    $tanggal_mulai   = mysqli_real_escape_string($koneksi, $_POST['tanggal_mulai']);
    $tanggal_selesai = mysqli_real_escape_string($koneksi, $_POST['tanggal_selesai']);
    $status_aktif    = 1; // Otomatis aktif atau dari form

    // Validasi: Cek apakah kelas ini sudah punya wali kelas di tahun ajaran yang sama
    $cek_kelas = mysqli_query($koneksi, "SELECT * FROM t_wali_kelas WHERE kelas_id = $kelas_id AND tahun_ajaran_id = $tahun_ajaran_id");
    if (mysqli_num_rows($cek_kelas) > 0) {
        $error = "Gagal! Kelas ini sudah memiliki wali kelas pada tahun ajaran tersebut.";
    } else {
        // Simpan data
        $query_insert = "INSERT INTO t_wali_kelas (tahun_ajaran_id, kelas_id, guru_id, tanggal_mulai, tanggal_selesai, status_aktif) 
                         VALUES ('$tahun_ajaran_id', '$kelas_id', '$guru_id', '$tanggal_mulai', '$tanggal_selesai', '$status_aktif')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            $_SESSION['pesan'] = "Data wali kelas berhasil ditambahkan!";
            header("Location: kelola_wali_kelas.php");
            exit();
        } else {
            $error = "Gagal menyimpan ke database: " . mysqli_error($koneksi);
        }
    }
}

// 1. Ambil HANYA Tahun Ajaran yang AKTIF saja
$query_tahun = mysqli_query($koneksi, "SELECT * FROM t_tahun_ajaran WHERE status_aktif = 1");

// 2. Ambil Data Kelas yang belum punya wali kelas
$query_kelas = mysqli_query($koneksi, "
    SELECT * FROM t_kelas 
    WHERE id NOT IN (SELECT kelas_id FROM t_wali_kelas)
");

// 3. Ambil Data Guru yang belum menjadi wali kelas (opsional, jika 1 guru hanya boleh 1 kelas)
$query_guru = mysqli_query($koneksi, "
    SELECT * FROM t_guru 
    WHERE id NOT IN (SELECT guru_id FROM t_wali_kelas)
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Wali Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Form Tambah Wali Kelas</h4>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Ditolak!</strong> <?= $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>

                    <form action="" method="POST">
                        <!-- Tahun Ajaran (Hanya yang Aktif) -->
                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran (Aktif)</label>
                            <select name="tahun_ajaran_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Tahun Ajaran Aktif --</option>
                                <?php while ($ta = mysqli_fetch_assoc($query_tahun)) { ?>
                                    <option value="<?= $ta['id']; ?>"><?= htmlspecialchars($ta['nama']); ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Kelas (Hanya yang belum punya wali kelas) -->
                        <div class="mb-3">
                            <label class="form-label">Pilih Kelas</label>
                            <select name="kelas_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Kelas --</option>
                                <?php while ($k = mysqli_fetch_assoc($query_kelas)) { ?>
                                    <option value="<?= $k['id']; ?>"><?= htmlspecialchars($k['nama']); ?></option>
                                <?php } ?>
                            </select>
                            <div class="form-text text-muted">Kelas yang sudah memiliki wali kelas tidak akan muncul di pilihan ini.</div>
                        </div>

                        <!-- Guru (Hanya guru yang belum jadi wali kelas) -->
                        <div class="mb-3">
                            <label class="form-label">Pilih Guru</label>
                            <select name="guru_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Guru --</option>
                                <?php while ($g = mysqli_fetch_assoc($query_guru)) { ?>
                                    <option value="<?= $g['id']; ?>"><?= htmlspecialchars($g['nama']); ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" required>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="kelola_wali_kelas.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>