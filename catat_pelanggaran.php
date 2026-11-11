
<?php
session_start();
include 'config/koneksi.php';

// ==========================================
// CEK SESSION GURU
// ==========================================

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}

$pesan = '';
$jenis_pesan = 'danger';

// ==========================================
// PROSES SIMPAN PELANGGARAN
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $siswa_id = intval($_POST['siswa_id'] ?? 0);
    $pelanggaran_id = intval($_POST['pelanggaran_id'] ?? 0);
    $tanggal = $_POST['tanggal'] ?? date('Y-m-d');

    if ($siswa_id <= 0 || $pelanggaran_id <= 0 || empty($tanggal)) {

        $pesan = "Semua data wajib diisi!";

    } else {

        // 1. CEK DATA SISWA

        $stmt_siswa = mysqli_prepare($koneksi, "
            SELECT id FROM t_siswa WHERE id = ?
        ");

        mysqli_stmt_bind_param($stmt_siswa, "i", $siswa_id);
        mysqli_stmt_execute($stmt_siswa);

        $hasil_siswa = mysqli_stmt_get_result($stmt_siswa);

        if (mysqli_num_rows($hasil_siswa) == 0) {

            $pesan = "Data siswa tidak ditemukan!";

        } else {

            // 2. AMBIL KATEGORI PELANGGARAN

            $stmt_kategori = mysqli_prepare($koneksi, "
                SELECT nama_kategori, poin
                FROM t_kategori_pelanggaran
                WHERE id_kategori = ?
            ");

            mysqli_stmt_bind_param(
                $stmt_kategori,
                "i",
                $pelanggaran_id
            );

            mysqli_stmt_execute($stmt_kategori);

            $hasil_kategori = mysqli_stmt_get_result($stmt_kategori);

            $kategori = mysqli_fetch_assoc($hasil_kategori);

            if (!$kategori) {

                $pesan = "Kategori pelanggaran tidak ditemukan!";

            } else {

                $nama_pelanggaran = trim($kategori['nama_kategori']);
                $poin = intval($kategori['poin']);

                // 3. CEK DUPLIKAT PELANGGARAN SISWA

                $stmt_cek = mysqli_prepare($koneksi, "
                    SELECT id
                    FROM t_pelanggaran
                    WHERE id_t_siswa = ?
                    AND LOWER(TRIM(pelanggaran)) = LOWER(TRIM(?))
                    LIMIT 1
                ");

                mysqli_stmt_bind_param(
                    $stmt_cek,
                    "is",
                    $siswa_id,
                    $nama_pelanggaran
                );

                mysqli_stmt_execute($stmt_cek);

                $hasil_cek = mysqli_stmt_get_result($stmt_cek);

                if (mysqli_num_rows($hasil_cek) > 0) {

                    $pesan = "Pelanggaran jenis ini sudah pernah dicatat untuk siswa tersebut!";

                } else {

                    // 4. SIMPAN KE TABEL T_PELANGGARAN

                    $sql = "
                        INSERT INTO t_pelanggaran
                        (id_t_siswa, pelanggaran, poin, tanggal)
                        VALUES (?, ?, ?, ?)
                    ";

                    $stmt_simpan = mysqli_prepare($koneksi, $sql);

                    if ($stmt_simpan) {

                        mysqli_stmt_bind_param(
                            $stmt_simpan,
                            "isis",
                            $siswa_id,
                            $nama_pelanggaran,
                            $poin,
                            $tanggal
                        );

                        if (mysqli_stmt_execute($stmt_simpan)) {

                            echo "<script>
                                alert('Data pelanggaran berhasil dicatat!');
                                window.location.href='riwayat.php';
                            </script>";

                            exit;

                        } else {

                            $pesan = "Gagal menyimpan data: " .
                                mysqli_stmt_error($stmt_simpan);

                        }

                    } else {

                        $pesan = "Query gagal: " . mysqli_error($koneksi);

                    }
                }
            }
        }
    }
}

// ==========================================
// AMBIL DATA SISWA
// ==========================================

$query_siswa = mysqli_query($koneksi, "
    SELECT id, nama, nis
    FROM t_siswa
    ORDER BY nama ASC
");

// ==========================================
// AMBIL KATEGORI TANPA DUPLIKAT
// ==========================================

$query_jp = mysqli_query($koneksi, "
    SELECT
        MIN(id_kategori) AS id_kategori,
        MIN(nama_kategori) AS nama_kategori,
        MAX(poin) AS poin
    FROM t_kategori_pelanggaran
    GROUP BY LOWER(TRIM(nama_kategori))
    ORDER BY MIN(nama_kategori) ASC
");

if (!$query_jp) {
    die("Gagal mengambil kategori: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catat Pelanggaran - Guru</title>

    <!-- BOOTSTRAP CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SELECT2 CSS -->

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

    <style>

        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #343a40;
            color: #fff;
            position: fixed;
            z-index: 100;
        }

        .sidebar .brand {
            padding: 1.5rem 1rem;
            font-size: 1.25rem;
            font-weight: bold;
            border-bottom: 1px solid #4f5962;
        }

        .sidebar a {
            color: #c2c7d0;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #0d6efd;
            color: #fff;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .select2-container {
            width: 100% !important;
        }

        @media(max-width: 768px) {

            .sidebar {
                width: 100%;
                min-height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }

        }

    </style>

</head>

<body>

    <!-- SIDEBAR -->

    <div class="sidebar">

        <div class="brand">
            Pelanggaran Siswa
        </div>

        <div class="nav flex-column mt-2">

            <a href="dashboard_guru.php">
                Dashboard
            </a>

            <a href="catat_pelanggaran.php" class="active">
                Catat Pelanggaran
            </a>

            <a href="tindakan.php">
                Tindakan
            </a>

            <a href="riwayat.php">
                Riwayat
            </a>

            <a href="rekap_poin.php">
                Rekap Poin
            </a>

            <hr class="dropdown-divider my-3 border-secondary">

            <a href="logout.php" class="text-danger">
                Logout
            </a>

        </div>

    </div>


    <!-- KONTEN UTAMA -->

    <div class="content">

        <h2>Catat Pelanggaran Siswa</h2>

        <p class="text-muted">
            Masukkan data pelanggaran yang dilakukan oleh siswa.
        </p>


        <!-- PESAN ERROR -->

        <?php if (!empty($pesan)): ?>

            <div class="alert alert-<?php echo $jenis_pesan; ?>">

                <?php echo htmlspecialchars($pesan); ?>

            </div>

        <?php endif; ?>


        <div class="card p-4 shadow-sm border-0 col-md-8">

            <form action="" method="POST">

                <!-- TAHUN AJARAN -->

                <div class="mb-3">

                    <label class="form-label fw-bold text-primary">
                        Tahun Ajaran Aktif
                    </label>

                    <input
                        type="text"
                        class="form-control bg-light fw-bold"
                        value="2025/2026"
                        readonly
                    >

                </div>


                <!-- PILIH SISWA -->

                <div class="mb-3">

                    <label class="form-label">
                        Cari / Pilih Siswa (Ketik Nama)
                    </label>

                    <select
                        name="siswa_id"
                        id="selectSiswa"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Ketik atau Pilih Nama Siswa --
                        </option>

                        <?php

                        if ($query_siswa) {

                            while ($siswa = mysqli_fetch_assoc($query_siswa)) {

                        ?>

                            <option value="<?php echo $siswa['id']; ?>">

                                <?php echo htmlspecialchars($siswa['nama']); ?>

                                (NIS: <?php echo htmlspecialchars($siswa['nis'] ?? '-'); ?>)

                            </option>

                        <?php

                            }

                        }

                        ?>

                    </select>

                </div>


                <!-- TANGGAL PELANGGARAN -->

                <div class="mb-3">

                    <label class="form-label">
                        Tanggal Pelanggaran
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        class="form-control"
                        value="<?php echo date('Y-m-d'); ?>"
                        required
                    >

                </div>


                <!-- JENIS PELANGGARAN -->

                <div class="mb-3">

                    <label class="form-label">
                        Jenis Pelanggaran
                    </label>

                    <select
                        name="pelanggaran_id"
                        id="selectJenisPelanggaran"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Pilih Jenis Pelanggaran --
                        </option>

                        <?php

                        while ($jp = mysqli_fetch_assoc($query_jp)) {

                        ?>

                            <option value="<?php echo $jp['id_kategori']; ?>">

                                <?php echo htmlspecialchars($jp['nama_kategori']); ?>

                                (Poin: <?php echo $jp['poin']; ?>)

                            </option>

                        <?php

                        }

                        ?>

                    </select>

                    <small class="text-muted">
                        Setiap jenis pelanggaran hanya ditampilkan satu kali.
                    </small>

                </div>


                <!-- TOMBOL -->

                <button type="submit" class="btn btn-primary">
                    Simpan Pelanggaran
                </button>

                <a href="dashboard_guru.php" class="btn btn-secondary">
                    Batal
                </a>

            </form>

        </div>

    </div>


    <!-- JAVASCRIPT -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

        $(document).ready(function() {

            $('#selectSiswa').select2({

                theme: 'bootstrap-5',

                placeholder: '-- Ketik atau Pilih Nama Siswa --',

                allowClear: true

            });


            $('#selectJenisPelanggaran').select2({

                theme: 'bootstrap-5',

                placeholder: '-- Pilih Jenis Pelanggaran --',

                allowClear: true

            });

        });

    </script>

</body>

</html>
