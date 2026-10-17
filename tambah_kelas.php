<?php
session_start();
include 'includes/cek_session.php';

if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tambah Kelas</title>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Tambah Data Kelas</h4>
                </div>
                <div class="card-body">
                    <form action="proses_kelas.php" method="POST">
                        <input type="hidden" name="aksi" value="tambah">

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Nama Kelas</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama" class="form-control" placeholder="Contoh: XII RPL 1" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Tingkat</label>
                            <div class="col-sm-9">
                                <input type="text" name="tingkat" class="form-control" placeholder="Contoh: XII" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Jurusan</label>
                            <div class="col-sm-9">
                                <input type="text" name="jurusan" class="form-control" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Status Aktif</label>
                            <div class="col-sm-9">
                                <select name="status_aktif" class="form-control" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary">Simpan Kelas</button>
                                <a href="kelola_kelas.php" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>