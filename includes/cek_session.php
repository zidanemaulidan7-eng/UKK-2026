
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user'], $_SESSION['role'])) {
    $_SESSION['pesan_error'] = "Silakan login terlebih dahulu.";
    header("Location: login.php");
    exit;
}
?>