<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Jika tombol Simpan ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tahun_ajaran    = mysqli_real_escape_string($koneksi, $_POST['tahun_ajaran']);
    $tanggal_mulai   = mysqli_real_escape_string($koneksi, $_POST['tanggal_mulai']);
    $tanggal_selesai = mysqli_real_escape_string($koneksi, $_POST['tanggal_selesai']);
    $status_aktif    = (int) $_POST['status_aktif']; // 1 = Aktif, 0 = Tidak Aktif

    // ATURAN KETAT: Jika memilih status Aktif (1)
    if ($status_aktif === 1) {
        // Cek apakah sudah ada tahun ajaran lain yang berstatus aktif
        $cek_aktif = mysqli_query($koneksi, "SELECT * FROM t_tahun_ajaran WHERE status_aktif = 1");
        
        if (mysqli_num_rows($cek_aktif) > 0) {
            // Tolak penyimpanan dan siapkan pesan error
            $error = "Gagal ditolak! Sudah ada tahun ajaran yang berstatus Aktif. Harap nonaktifkan tahun ajaran aktif yang lain terlebih dahulu.";
        }
    }

    // Jika lolos validasi (tidak ada error), lakukan INSERT
    if (!isset($error)) {
        $query = "INSERT INTO t_tahun_ajaran (nama, tanggal_mulai, tanggal_selesai, status_aktif) 
                  VALUES ('$tahun_ajaran', '$tanggal_mulai', '$tanggal_selesai', '$status_aktif')";
        
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['pesan'] = "Tahun ajaran berhasil ditambahkan!";
            header("Location: kelola_tahun_ajaran.php");
            exit();
        } else {
            $error = "Gagal menyimpan ke database: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tahun Ajaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Form Tambah Tahun Ajaran</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Menampilkan Pesan Penolakan -->
                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Ditolak!</strong> <?= $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control" placeholder="Contoh: 2025/2026" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status_aktif" class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="kelola_tahun_ajaran.php" class="btn btn-secondary">Batal</a>
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