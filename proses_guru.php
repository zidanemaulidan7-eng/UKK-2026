<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

if ($aksi === 'tambah') {
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];
    $status_aktif = (int)$_POST['status_aktif'];

    if ($password !== $konfirmasi_password) {
        $_SESSION['pesan'] = "Password dan Konfirmasi Password tidak cocok!";
        header("Location: kelola_guru.php");
        exit();
    }

    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'guru';

    // 1. Masukkan data ke tabel t_users terlebih dahulu
    $sql_user = "INSERT INTO t_users (name, email, password, role) VALUES ('$nama', '$email', '$hashed_password', '$role')";
    if (mysqli_query($koneksi, $sql_user)) {
        $user_id = mysqli_insert_id($koneksi); // Ambil ID user yang baru saja dibuat

        // 2. Masukkan data ke tabel t_guru
        $sql_guru = "INSERT INTO t_guru (user_id, nip, nama, email, status_aktif) VALUES ('$user_id', '$nip', '$nama', '$email', '$status_aktif')";
        if (mysqli_query($koneksi, $sql_guru)) {
            $_SESSION['pesan'] = "Data guru berhasil ditambahkan!";
        } else {
            $_SESSION['pesan'] = "Gagal menambah data guru: " . mysqli_error($koneksi);
        }
    } else {
        $_SESSION['pesan'] = "Gagal membuat akun user: " . mysqli_error($koneksi);
    }

    header("Location: kelola_guru.php");
    exit();
} 
else if ($aksi === 'edit') {
    // Tangani proses edit jika ada
    $id = (int)$_POST['id'];
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $status_aktif = (int)$_POST['status_aktif'];

    $sql = "UPDATE t_guru SET nip='$nip', nama='$nama', email='$email', status_aktif='$status_aktif' WHERE id='$id'";
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['pesan'] = "Data guru berhasil diperbarui!";
    } else {
        $_SESSION['pesan'] = "Gagal memperbarui data guru: " . mysqli_error($koneksi);
    }

    header("Location: kelola_guru.php");
    exit();
}
else if ($aksi === 'hapus') {
    $id = (int)$_GET['id'];
    
    // Ambil user_id terlebih dahulu agar akun di t_users juga bisa dihapus jika perlu
    $q = mysqli_query($koneksi, "SELECT user_id FROM t_guru WHERE id = '$id'");
    $data = mysqli_fetch_assoc($q);
    
    if ($data) {
        $user_id = $data['user_id'];
        // Hapus dari t_guru (atau jika cascade aktif, hapus dari t_users)
        mysqli_query($koneksi, "DELETE FROM t_guru WHERE id = '$id'");
        if ($user_id) {
            mysqli_query($koneksi, "DELETE FROM t_users WHERE id = '$user_id'");
        }
        $_SESSION['pesan'] = "Data guru berhasil dihapus!";
    }

    header("Location: kelola_guru.php");
    exit();
}
?>