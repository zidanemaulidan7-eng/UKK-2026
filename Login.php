
<?php
session_start();

// Jika sudah login, arahkan sesuai role
if (isset($_SESSION['id_user'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: dashboard_admin.php");
    } elseif ($_SESSION['role'] === 'guru') {
        header("Location: dashboard_guru.php");
    } else {
        header("Location: Login.php");
    }
    exit;
}

$pesan_error = $_SESSION['pesan_error'] ?? '';
unset($_SESSION['pesan_error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Informasi Sekolah</title>
</head>
<body>

    <h1>Sistem pelanggaran siswa</h1>
    <h2>Halaman Login</h2>

    <hr>

    <?php if ($pesan_error !== ''): ?>
        <p>
            <?php echo htmlspecialchars($pesan_error); ?>
        </p>
    <?php endif; ?>

    <form action="proses_login.php" method="POST">

        <table>
            <tr>
                <td>
                    <label for="email">Email</label>
                </td>
                <td>:</td>
                <td>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Masukkan email"
                        required
                        autofocus
                    >
                </td>
            </tr>

            <tr>
                <td>
                    <label for="password">Password</label>
                </td>
                <td>:</td>
                <td>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <button type="submit">Login</button>
                    <button type="reset">Reset</button>
                </td>
            </tr>
        </table>

    </form>

    <hr>

    <p>&copy; 2026 Sistem Informasi Sekolah</p>

</body>
</html>