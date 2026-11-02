<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

// Ambil ID dari URL
$id = $_GET['id'] ?? '';

if (!empty($id)) {
    // Amankan dari SQL Injection
    $id_esc = mysqli_real_escape_string($koneksi, $id);

    // Query untuk menghapus data dari tabel t_kelas
    $query = "DELETE FROM t_kelas WHERE id = '$id_esc'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // Redirect kembali ke halaman kelola kelas dengan pesan sukses
        header("Location: kelola_kelas.php?pesan=hapus_sukses");
        exit();
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    echo "ID tidak ditemukan.";
}
?>