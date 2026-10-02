
<?php
    session_start();

if (!isset($_SESSION['login']) || !isset($_SESSION['true'])) {
    header("Location: login.php");
    exit;
}
?>