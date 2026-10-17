<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

$aksi = $_POST['aksi'] ?? '';

if ($aksi == 'tambah') {
    $nis           = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nisn          = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $nama          = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $status_aktif  = mysqli_real_escape_string($koneksi, $_POST['status_aktif']);

    $sql = "INSERT INTO t_siswa (nis, nisn, nama, jenis_kelamin, tanggal_lahir, alamat, status_aktif) 
            VALUES ('$nis', '$nisn', '$nama', '$jenis_kelamin', '$tanggal_lahir', '$alamat', '$status_aktif')";
    
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['pesan'] = "Data siswa berhasil ditambahkan!";
    } else {
        $_SESSION['pesan'] = "Gagal menambahkan data: " . mysqli_error($koneksi);
    }

    header("Location: kelola_siswa.php");
    exit();

} elseif ($aksi == 'edit') {
    $id            = mysqli_real_escape_string($koneksi, $_POST['id']);
    $nis           = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nisn          = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $nama          = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $status_aktif  = mysqli_real_escape_string($koneksi, $_POST['status_aktif']);

    $sql = "UPDATE t_siswa SET 
                nis = '$nis', 
                nisn = '$nisn', 
                nama = '$nama', 
                jenis_kelamin = '$jenis_kelamin', 
                tanggal_lahir = '$tanggal_lahir', 
                alamat = '$alamat', 
                status_aktif = '$status_aktif' 
            WHERE id = '$id'";

    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['pesan'] = "Data siswa berhasil diperbarui!";
    } else {
        $_SESSION['pesan'] = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }

    header("Location: kelola_siswa.php");
    exit();
}