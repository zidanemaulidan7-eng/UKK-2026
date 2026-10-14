<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

if ($aksi == 'tambah') {
    $pelanggaran_kategori_id = mysqli_real_escape_string($koneksi, $_POST['pelanggaran_kategori_id']);
    $kode                    = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $nama                    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $poin                    = mysqli_real_escape_string($koneksi, $_POST['poin']);
    $deskripsi               = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $status_aktif            = mysqli_real_escape_string($koneksi, $_POST['status_aktif']);

    $sql = "INSERT INTO t_pelanggaran (pelanggaran_kategori_id, kode, nama, poin, deskripsi, status_aktif) 
            VALUES ('$pelanggaran_kategori_id', '$kode', '$nama', '$poin', '$deskripsi', '$status_aktif')";
    
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['pesan'] = "Data pelanggaran berhasil ditambahkan!";
    } else {
        $_SESSION['pesan'] = "Gagal menambahkan data: " . mysqli_error($koneksi);
    }

    header("Location: pelanggaran.php");
    exit();

} elseif ($aksi == 'edit') {
    $id                      = mysqli_real_escape_string($koneksi, $_POST['id']);
    $pelanggaran_kategori_id = mysqli_real_escape_string($koneksi, $_POST['pelanggaran_kategori_id']);
    $kode                    = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $nama                    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $poin                    = mysqli_real_escape_string($koneksi, $_POST['poin']);
    $deskripsi               = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $status_aktif            = mysqli_real_escape_string($koneksi, $_POST['status_aktif']);

    $sql = "UPDATE t_pelanggaran SET 
                pelanggaran_kategori_id = '$pelanggaran_kategori_id', 
                kode = '$kode', 
                nama = '$nama', 
                poin = '$poin', 
                deskripsi = '$deskripsi', 
                status_aktif = '$status_aktif' 
            WHERE id = '$id'";

    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['pesan'] = "Data pelanggaran berhasil diperbarui!";
    } else {
        $_SESSION['pesan'] = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }

    header("Location: pelanggaran.php");
    exit();

} elseif ($aksi == 'hapus') {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    $sql = "DELETE FROM t_pelanggaran WHERE id = '$id'";
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['pesan'] = "Data pelanggaran berhasil dihapus!";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus data: " . mysqli_error($koneksi);
    }

    header("Location: pelanggaran.php");
    exit();
} else {
    header("Location: pelanggaran.php");
    exit();
}