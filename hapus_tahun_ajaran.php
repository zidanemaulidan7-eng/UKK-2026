<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Buat query untuk menghapus data berdasarkan ID
    $stmt = mysqli_prepare($koneksi, "DELETE FROM t_tahun_ajaran WHERE id = ?");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan'] = "Data tahun ajaran berhasil dihapus!";
        } else {
            $_SESSION['pesan'] = "Gagal menghapus data: " . mysqli_error($koneksi);
        }

        mysqli_stmt_close($stmt);
    }
}

// Alihkan kembali ke halaman kelola tahun ajaran
header("Location: kelola_tahun_ajaran.php");
exit();
?>