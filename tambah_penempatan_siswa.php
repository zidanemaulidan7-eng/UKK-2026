<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Proses simpan data jika form disubmit
if (isset($_POST['submit'])) {
    $siswa_id         = (int)$_POST['siswa_id'];
    $kelas_id         = (int)$_POST['kelas_id'];
    $tahun_ajaran_id  = (int)$_POST['tahun_ajaran_id'];
    $status_aktif     = (int)$_POST['status_aktif'];

    // Validasi sederhana
    if (empty($siswa_id) || empty($kelas_id) || empty($tahun_ajaran_id)) {
        $error = "Semua field yang wajib harus diisi!";
    } else {
        $query_insert = "INSERT INTO t_penempatan_siswa (siswa_id, kelas_id, tahun_ajaran_id, status_aktif) VALUES ('$siswa_id', '$kelas_id', '$tahun_ajaran_id', '$status_aktif')";
        $sql_insert = mysqli_query($koneksi, $query_insert);

        if ($sql_insert) {
            $_SESSION['pesan'] = "Data penempatan siswa berhasil ditambahkan!";
            header("Location: penempatan_siswa.php");
            exit;
        } else {
            $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
        }
    }
}

// Ambil data untuk dropdown (Siswa, Kelas, Tahun Ajaran)
$siswa = mysqli_query($koneksi, "SELECT id, nis, nama FROM t_siswa ORDER BY nama ASC");
$kelas = mysqli_query($koneksi, "SELECT id, nama FROM t_kelas ORDER BY nama ASC");
$tahun_ajaran = mysqli_query($koneksi, "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Tambah Penempatan Siswa</title>
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
                    <a href="kelola_wali_kelas.php" class="nav-link text-white">Kelola Wali Kelas</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="penempatan_siswa.php" class="nav-link active">Penempatan Siswa</a>
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
            <h2>Tambah Penempatan Siswa</h2>
            <p>Form untuk menambahkan penempatan siswa ke kelas dan tahun ajaran tertentu.</p>
            <hr>

            <?php if (isset($error)) { ?>
                <div class="alert alert-danger">
                    <?= $error; ?>
                </div>
            <?php } ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="siswa_id" class="form-label">Pilih Siswa</label>
                    <select name="siswa_id" id="siswa_id" class="form-control" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>
                            <option value="<?= $s['id']; ?>"><?= htmlspecialchars($s['nis'] . ' - ' . $s['nama']); ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="kelas_id" class="form-label">Pilih Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                            <option value="<?= $k['id']; ?>"><?= htmlspecialchars($k['nama']); ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tahun_ajaran_id" class="form-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control" required>
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        <?php while ($ta = mysqli_fetch_assoc($tahun_ajaran)) { ?>
                            <option value="<?= $ta['id']; ?>"><?= htmlspecialchars($ta['nama']); ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status_aktif" class="form-label">Status Penempatan</label>
                    <select name="status_aktif" id="status_aktif" class="form-control" required>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-primary btn-sm">Simpan</button>
                <a href="penempatan_siswa.php" class="btn btn-secondary btn-sm">Kembali</a>
            </form>
        </main>
    </div>
</div>

</body>
</html>