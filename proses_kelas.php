<?php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

// Cek parameter aksi dari form (POST) atau URL (GET)
$aksi = $_GET['aksi'] ?? $_POST['aksi'] ?? '';

if ($aksi == 'edit') {
    // Ambil data dari form edit sesuai dengan name di edit_kelas.php
    $id          = $_POST['id'] ?? '';
    $nama        = $_POST['nama'] ?? '';
    $tingkat     = $_POST['tingkat'] ?? '';
    $jurusan     = $_POST['jurusan'] ?? '';
    $status_aktif = $_POST['status_aktif'] ?? '';

    // Validasi ketat: pastikan id tidak kosong
    if (empty($id)) {
        die("Error: ID tidak valid atau tidak terkirim dari form.");
    }

    // Amankan data dari SQL Injection
    $id_esc           = mysqli_real_escape_string($koneksi, $id);
    $nama_esc         = mysqli_real_escape_string($koneksi, $nama);
    $tingkat_esc      = mysqli_real_escape_string($koneksi, $tingkat);
    $jurusan_esc      = mysqli_real_escape_string($koneksi, $jurusan);
    $status_aktif_esc = mysqli_real_escape_string($koneksi, $status_aktif);

    // Query untuk memperbarui data ke tabel t_kelas
    $query = "UPDATE t_kelas SET 
                nama         = '$nama_esc', 
                tingkat      = '$tingkat_esc', 
                jurusan      = '$jurusan_esc', 
                status_aktif = '$status_aktif_esc' 
              WHERE id       = '$id_esc'";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: kelola_kelas.php?pesan=update_sukses");
        exit();
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($koneksi);
    }

} elseif ($aksi == 'hapus') {
    // Ambil ID dari URL untuk proses hapus
    $id = $_GET['id'] ?? '';

    if (!empty($id)) {
        $id_esc = mysqli_real_escape_string($koneksi, $id);
        $query = "DELETE FROM t_kelas WHERE id = '$id_esc'";
        $result = mysqli_query($koneksi, $query);

        if ($result) {
            header("Location: kelola_kelas.php?pesan=hapus_sukses");
            exit();
        } else {
            echo "Gagal menghapus data: " . mysqli_error($koneksi);
        }
    } else {
        echo "ID tidak ditemukan.";
    }

} else {
    header("Location: kelola_kelas.php");
    exit();
}
?>