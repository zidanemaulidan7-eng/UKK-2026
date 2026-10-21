<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Coba jalankan hapus data
    $query = "DELETE FROM t_tahun_ajaran WHERE id = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Tahun ajaran berhasil dihapus!'); window.location='kelola_tahun_ajaran.php';</script>";
        exit();
    } else {
        // Tangkap pesan error foreign key agar tidak fatal error
        $error_msg = mysqli_error($koneksi);
        echo "<script>alert('Gagal menghapus! Tahun ajaran ini masih digunakan oleh data pelanggaran siswa.'); window.location='kelola_tahun_ajaran.php';</script>";
        exit();
    }
} else {
    header("Location: kelola_tahun_ajaran.php");
    exit();
}
?>