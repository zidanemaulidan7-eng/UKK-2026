<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Dashboard</title>
</head>

<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h4 class="text-white mb-4">
                My Website
            </h4>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link active">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
    <a href="kelola_siswa.php" class="nav-link text-white">Data Siswa</a>
</li>
                </li>
                <li class="nav-item mb-2">
    <a href="kelola_guru.php" class="nav-link text-white">Data Guru</a>
</li>
                    </a>
                </li>
                <li class="nav-item mb-2">
    <a href="kelola_kelas.php" class="nav-link text-white">Kelas</a>
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
        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4">
            <h2>Dashboard</h2>
            <p>
                Selamat datang di halaman dashboard.
            </p>
            <div class="row">
                <!-- CARD DATA SISWA -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                Data Siswa
                            </h5>
                            <h2>
                                120
                            </h2>
                        </div>
                    </div>
                </div>
                <!-- CARD DATA GURU -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                Data Guru
                            </h5>
                            <h2>
                                25
                            </h2>
                        </div>
                    </div>
                </div>
                <!-- CARD KELAS -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                Kelas
                            </h5>
                            <h2>
                             12
                          </h2>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
    <h2>Dashboard</h2>
    <p>
        Selamat datang,
        <?php echo $_SESSION['nama_user']; ?>
    </p>
    <p>
        Anda berhasil login.
    </p>
    <a href="logout.php">Logout</a>
</body>
</html>
<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

$nama = $_SESSION['nama_lengkap'] ?? 'Pengguna';
$role = $_SESSION['role'] ?? '';

?>

<!DOCTYPE html>


<html>
<head>
    <title>Dashboard</title>
</head>
<body>

    <h1>Dashboard Sistem Pelanggaran Siswa</h1>

    <p>Selamat datang, <?php echo htmlspecialchars($nama); ?>!</p>
    <p>Role: <?php echo htmlspecialchars($role); ?></p>

    <hr>

    <?php if ($role === 'admin') { ?>

        <h2>Menu Admin</h2>
        <ul>
            <li><a href="kelola_guru.php">kelola Guru</a></li>
            <li><a href="kelola_kelas.php">kelola Kelas</a></li>
            <li><a href="kelola_siswa.php">kelola Siswa</a></li>
            <li><a href="pelanggaran.php">kelola Pelanggaran</a></li>
            <li><a href="kelola_tahun_ajaran.php">kelola tahun ajaran</a></li>
            <li><a href="kelola_wali_kelas.php">kelola wali kelas</a></li>
            <li><a href="penempatan_siswa.php">penempatan siswa</a></li>
        </ul>

    <?php } else { ?>

        <h2>Menu Pengguna</h2>
        <p>Selamat menggunakan sistem.</p>

    <?php } ?>

    <p><a href="logout.php">Logout</a></p>

</body>
</html>