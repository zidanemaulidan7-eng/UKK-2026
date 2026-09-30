
<?php
require_once "cek_session.php";
http_response_code(403);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Akses Ditolak</title>
</head>
<body>

    <h1>Akses Ditolak!</h1>

    <p>
        Maaf, akun
        <?php echo htmlspecialchars($_SESSION['role']); ?>
        tidak memiliki izin untuk membuka halaman ini.
    </p>

    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="dashboard_admin.php">Kembali ke Dashboard Admin</a>
    <?php else: ?>
        <a href="dashboard_guru.php">Kembali ke Dashboard Guru</a>
    <?php endif; ?>

</body>
</html>