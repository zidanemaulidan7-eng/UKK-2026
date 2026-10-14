<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan hanya admin yang bisa menghapus
if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    $sql = "DELETE FROM t_guru WHERE id = '$id'";
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['pesan'] = "Data guru berhasil dihapus!";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus data guru: " . mysqli_error($koneksi);
    }
}

header("Location: kelola_guru.php");
exit();
?>