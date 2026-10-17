<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan hanya admin
if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

// Cek apakah ada aksi yang dikirim melalui POST atau GET
$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

if ($aksi == 'tambah') {
    $nip          = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama         = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email        = mysqli_real_escape_string($koneksi, $_POST['email']);
    $user_id      = mysqli_real_escape_string($koneksi, $_POST['user_id']);
    $status_aktif = mysqli_real_escape_string($koneksi, $_POST['status_aktif']);

    $sql = "INSERT INTO t_guru (nip, nama, email, user_id, status_aktif) 
            VALUES ('$nip', '$nama', '$email', '$user_id', '$status_aktif')";
    
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['pesan'] = "Data guru berhasil ditambahkan!";
    } else {
        $_SESSION['pesan'] = "Gagal menambahkan data: " . mysqli_error($koneksi);
    }
    
    header("Location: kelola_guru.php");
    exit();

} elseif ($aksi == 'edit') {
    $id           = mysqli_real_escape_string($koneksi, $_POST['id']);
    $nip          = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama         = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email        = mysqli_real_escape_string($koneksi, $_POST['email']);
    $user_id      = mysqli_real_escape_string($koneksi, $_POST['user_id']);
    $status_aktif = mysqli_real_escape_string($koneksi, $_POST['status_aktif']);

    $sql = "UPDATE t_guru SET 
                nip = '$nip', 
                nama = '$nama', 
                email = '$email', 
                user_id = '$user_id', 
                status_aktif = '$status_aktif' 
            WHERE id = '$id'";

    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['pesan'] = "Data guru berhasil diperbarui!";
    } else {
        $_SESSION['pesan'] = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }

    header("Location: kelola_guru.php");
    exit();

} elseif ($aksi == 'hapus') {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    $sql = "DELETE FROM t_guru WHERE id = '$id'";

    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['pesan'] = "Data guru berhasil dihapus!";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus data: " . mysqli_error($koneksi);
    }

    header("Location: kelola_guru.php");
    exit();
} else {
    header("Location: kelola_guru.php");
    exit();
}