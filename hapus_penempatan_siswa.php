<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan parameter id tersedia dan valid
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    // Eksekusi query hapus
    $query = "DELETE FROM t_penempatan_siswa WHERE id = $id";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $_SESSION['pesan'] = "Data penempatan siswa berhasil dihapus!";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    $_SESSION['pesan'] = "ID tidak valid!";
}

// Redirect kembali ke halaman utama
header("Location: penempatan_siswa.php");
exit();
?>