<?php
// Sertakan file cek sesi login dan koneksi database
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Periksa apakah parameter ID siswa tersedia di URL dan berupa angka
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Buat query untuk menghapus data siswa berdasarkan ID
    $query = "DELETE FROM t_siswa WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            // Jika berhasil dihapus, arahkan kembali ke halaman kelola siswa
            header("Location: kelola_siswa.php?pesan=sukses_hapus");
            exit();
        } else {
            echo "Gagal menghapus data siswa: " . mysqli_error($koneksi);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Gagal menyiapkan query: " . mysqli_error($koneksi);
    }
} else {
    // Jika tidak ada ID atau ID tidak valid, alihkan kembali ke halaman kelola siswa
    header("Location: kelola_siswa.php");
    exit();
}
?>