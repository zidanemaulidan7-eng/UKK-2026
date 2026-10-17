<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Periksa apakah parameter ID tersedia di URL dan berupa angka
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Buat query untuk menghapus data berdasarkan ID
    $stmt = mysqli_prepare($koneksi, "DELETE FROM t_wali_kelas WHERE id = ?");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan'] = "Data wali kelas berhasil dihapus!";
        } else {
            $_SESSION['pesan'] = "Gagal menghapus data: " . mysqli_error($koneksi);
        }

        mysqli_stmt_close($stmt);
    }
}

// Alihkan kembali ke halaman kelola wali kelas
header("Location: kelola_wali_kelas.php");
exit();
?>