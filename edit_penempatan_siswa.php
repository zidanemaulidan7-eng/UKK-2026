
<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Validasi ID
if (
    !isset($_GET['id']) ||
    !ctype_digit($_GET['id']) ||
    (int) $_GET['id'] < 1
) {
    header("Location: penempatan_siswa.php");
    exit();
}

$id = (int) $_GET['id'];
$error = "";

// Ambil data penempatan dari tabel yang benar
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_kelas_siswa WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    $_SESSION['pesan'] = "Data penempatan siswa tidak ditemukan.";
    header("Location: penempatan_siswa.php");
    exit();
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siswa_id = (int) ($_POST['siswa_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);
    $status_input = $_POST['status_aktif'] ?? '';

    if (
        $siswa_id < 1 ||
        $kelas_id < 1 ||
        $tahun_ajaran_id < 1 ||
        !in_array($status_input, ['0', '1'], true)
    ) {
        $error = "Semua field wajib diisi dengan benar.";
    } else {
        $status_aktif = (int) $status_input;

        $stmt_update = mysqli_prepare(
            $koneksi,
            "UPDATE t_kelas_siswa
             SET siswa_id = ?,
                 kelas_id = ?,
                 tahun_ajaran_id = ?,
                 status_aktif = ?
             WHERE id = ?"
        );

        if (!$stmt_update) {
            $error = "Query update gagal: " . mysqli_error($koneksi);
        } else {
            mysqli_stmt_bind_param(
                $stmt_update,
                "iiiii",
                $siswa_id,
                $kelas_id,
                $tahun_ajaran_id,
                $status_aktif,
                $id
            );

            if (mysqli_stmt_execute($stmt_update)) {
                mysqli_stmt_close($stmt_update);

                $_SESSION['pesan'] =
                    "Penempatan siswa berhasil diperbarui!";

                header("Location: penempatan_siswa.php");
                exit();
            } else {
                $error = "Gagal memperbarui data: "
                    . mysqli_stmt_error($stmt_update);

                mysqli_stmt_close($stmt_update);
            }
        }
    }

    // Pertahankan pilihan jika validasi gagal
    $row['siswa_id'] = $siswa_id;
    $row['kelas_id'] = $kelas_id;
    $row['tahun_ajaran_id'] = $tahun_ajaran_id;

    if (in_array($status_input, ['0', '1'], true)) {
        $row['status_aktif'] = (int) $status_input;
    }
}

// Ambil pilihan siswa, kelas, dan tahun ajaran
$query_siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama FROM t_siswa ORDER BY nama ASC"
);

$query_kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_kelas ORDER BY nama ASC"
);

$query_ta = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC"
);

if (!$query_siswa || !$query_kelas || !$query_ta) {
    die("Gagal mengambil data dropdown: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Penempatan Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>

<div class="container py-4">
    <h2>Edit Penempatan Siswa</h2>
    <p>Form untuk mengubah penempatan kelas siswa.</p>

    <?php if ($error !== '') { ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error); ?>
        </div>
    <?php } ?>

    <form method="POST"
          action="edit_penempatan_siswa.php?id=<?= $id; ?>">

        <div class="mb-3">
            <label class="form-label">Pilih Siswa</label>
            <select name="siswa_id" class="form-select" required>
                <option value="">-- Pilih Siswa --</option>

                <?php while ($s = mysqli_fetch_assoc($query_siswa)) { ?>
                    <option value="<?= (int) $s['id']; ?>"
                        <?= ((int) $row['siswa_id'] === (int) $s['id'])
                            ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($s['nis'] . ' - ' . $s['nama']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Pilih Kelas</label>
            <select name="kelas_id" class="form-select" required>
                <option value="">-- Pilih Kelas --</option>

                <?php while ($k = mysqli_fetch_assoc($query_kelas)) { ?>
                    <option value="<?= (int) $k['id']; ?>"
                        <?= ((int) $row['kelas_id'] === (int) $k['id'])
                            ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($k['nama']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tahun Ajaran</label>
            <select name="tahun_ajaran_id" class="form-select" required>
                <option value="">-- Pilih Tahun Ajaran --</option>

                <?php while ($ta = mysqli_fetch_assoc($query_ta)) { ?>
                    <option value="<?= (int) $ta['id']; ?>"
                        <?= ((int) $row['tahun_ajaran_id'] === (int) $ta['id'])
                            ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($ta['nama']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status_aktif" class="form-select" required>
                <option value="1"
                    <?= ((int) $row['status_aktif'] === 1)
                        ? 'selected' : ''; ?>>
                    Aktif
                </option>

                <option value="0"
                    <?= ((int) $row['status_aktif'] === 0)
                        ? 'selected' : ''; ?>>
                    Tidak Aktif
                </option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            Update Penempatan
        </button>

        <a href="penempatan_siswa.php" class="btn btn-secondary">
            Kembali
        </a>
    </form>
</div>

</body>
</html>
