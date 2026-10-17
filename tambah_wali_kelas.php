<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Proses simpan data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tahun_ajaran_id = $_POST['tahun_ajaran_id'];
    $kelas_id = $_POST['kelas_id'];
    $guru_id = $_POST['guru_id'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $status_aktif = $_POST['status_aktif'];

    if (!empty($tahun_ajaran_id) && !empty($kelas_id) && !empty($guru_id) && !empty($tanggal_mulai) && !empty($tanggal_selesai)) {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO t_wali_kelas (tahun_ajaran_id, kelas_id, guru_id, tanggal_mulai, tanggal_selesai, status_aktif) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iiissi", $tahun_ajaran_id, $kelas_id, $guru_id, $tanggal_mulai, $tanggal_selesai, $status_aktif);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan'] = "Data wali kelas berhasil ditambahkan!";
            header("Location: kelola_wali_kelas.php");
            exit();
        } else {
            $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Semua field wajib diisi!";
    }
}

// Ambil data untuk dropdown
$query_ta = mysqli_query($koneksi, "SELECT * FROM t_tahun_ajaran ORDER BY id DESC");
$query_kelas = mysqli_query($koneksi, "SELECT * FROM t_kelas ORDER BY nama ASC");
$query_guru = mysqli_query($koneksi, "SELECT * FROM t_guru ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Tambah Wali Kelas</title>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h4 class="text-white mb-4">
                Pelanggaran Siswa
            </h4>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link text-white">Kelola Siswa</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link text-white">Kelola Guru</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link text-white">Kelola Kelas</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link text-white">Kelola Tahun Ajaran</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link active">Kelola Wali Kelas</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="pelanggaran.php" class="nav-link text-white">Laporan</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="tentang_saya.php" class="nav-link text-white">About Me</a>
                </li>
                <hr class="text-secondary">
                <li class="nav-item mb-2">
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </li>
            </ul>
        </div>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">
            <h2>Tambah Wali Kelas</h2>
            <p>Form untuk menambahkan data penugasan wali kelas baru.</p>

            <?php if (isset($error)) { ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php } ?>

            <hr>

            <form action="" method="POST">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tahun Ajaran</label>
                    <div class="col-sm-6">
                        <select name="tahun_ajaran_id" class="form-control" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            <?php while ($ta = mysqli_fetch_assoc($query_ta)) { ?>
                                <option value="<?= $ta['id']; ?>"><?= htmlspecialchars($ta['nama']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Kelas</label>
                    <div class="col-sm-6">
                        <select name="kelas_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php while ($kls = mysqli_fetch_assoc($query_kelas)) { ?>
                                <option value="<?= $kls['id']; ?>"><?= htmlspecialchars($kls['nama']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Guru (Wali Kelas)</label>
                    <div class="col-sm-6">
                        <select name="guru_id" class="form-control" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php while ($guru = mysqli_fetch_assoc($query_guru)) { ?>
                                <option value="<?= $guru['id']; ?>"><?= htmlspecialchars($guru['nama']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tanggal Mulai</label>
                    <div class="col-sm-6">
                        <input type="date" name="tanggal_mulai" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tanggal Selesai</label>
                    <div class="col-sm-6">
                        <input type="date" name="tanggal_selesai" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Status Aktif</label>
                    <div class="col-sm-6">
                        <select name="status_aktif" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-6 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Simpan Wali Kelas</button>
                        <a href="kelola_wali_kelas.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

</body>
</html>