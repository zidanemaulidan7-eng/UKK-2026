<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan parameter id tersedia di URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Query untuk menghapus data berdasarkan ID
    $query = "DELETE FROM t_penempatan_siswa WHERE id = $id";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $_SESSION['pesan'] = "Data penempatan siswa berhasil dihapus!";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus data: " . mysqli_error($koneksi);
    }
}

// Redirect kembali ke halaman utama penempatan siswa
header("Location: penempatan_siswa.php");
exit;
?>