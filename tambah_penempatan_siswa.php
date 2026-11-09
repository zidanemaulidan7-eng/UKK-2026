<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Proses simpan data saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tahun_ajaran_id = (int) $_POST['tahun_ajaran_id'];
    $kelas_id        = (int) $_POST['kelas_id'];
    $siswa_id        = (int) $_POST['siswa_id'];

    // Validasi: Cek apakah siswa ini sudah pernah ditempatkan sebelumnya
    $cek_siswa = mysqli_query($koneksi, "SELECT * FROM t_kelas_siswa WHERE siswa_id = $siswa_id");
    
    if (mysqli_num_rows($cek_siswa) > 0) {
        $error = "Gagal! Siswa ini sudah ditempatkan di kelas lain.";
    } else {
        // Simpan penempatan siswa (sesuaikan nama tabel Anda, misal: t_kelas_siswa atau t_penempatan_siswa)
        $query_insert = "INSERT INTO t_kelas_siswa (tahun_ajaran_id, kelas_id, siswa_id) 
                         VALUES ('$tahun_ajaran_id', '$kelas_id', '$siswa_id')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            $_SESSION['pesan'] = "Penempatan siswa berhasil ditambahkan!";
            header("Location: penempatan_siswa.php");
            exit();
        } else {
            $error = "Gagal menyimpan ke database: " . mysqli_error($koneksi);
        }
    }
}

// 1. Ambil HANYA Tahun Ajaran yang AKTIF
$query_tahun = mysqli_query($koneksi, "SELECT * FROM t_tahun_ajaran WHERE status_aktif = 1");

// 2. Ambil Data Kelas (bisa disesuaikan jika ingin memunculkan semua kelas aktif)
$query_kelas = mysqli_query($koneksi, "SELECT * FROM t_kelas");

// 3. Ambil HANYA Siswa yang BELUM ditempatkan (belum ada di tabel penempatan/kelas_siswa)
$query_siswa = mysqli_query($koneksi, "
    SELECT * FROM t_siswa 
    WHERE id NOT IN (SELECT siswa_id FROM t_kelas_siswa)
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penempatan Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Form Penempatan Siswa</h4>
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

                        <!-- Kelas -->
                        <div class="mb-3">
                            <label class="form-label">Pilih Kelas</label>
                            <select name="kelas_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Kelas --</option>
                                <?php while ($k = mysqli_fetch_assoc($query_kelas)) { ?>
                                    <option value="<?= $k['id']; ?>"><?= htmlspecialchars($k['nama']); ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Siswa (Hanya yang BELUM ditempatkan) -->
                        <div class="mb-3">
                            <label class="form-label">Pilih Siswa</label>
                            <select name="siswa_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Siswa yang Belum Ditempatkan --</option>
                                <?php while ($s = mysqli_fetch_assoc($query_siswa)) { ?>
                                    <option value="<?= $s['id']; ?>">
                                        <?= htmlspecialchars($s['nis'] ?? '') ?> - <?= htmlspecialchars($s['nama']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <div class="form-text text-muted">Siswa yang sudah dimasukkan ke dalam kelas tidak akan muncul dalam daftar ini.</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="penempatan_siswa.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Penempatan</button>
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