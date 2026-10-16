
<?php
session_start();
include 'includes/cek_session.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Saya</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f0f2f5;
            font-family: Arial, sans-serif;
        }

        .about-card {
            max-width: 500px;
            margin: 70px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .about-title {
            text-align: center;
            color: #0d6efd;
            margin-bottom: 25px;
        }

        .profile-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .profile-item:last-of-type {
            border-bottom: none;
        }

        .profile-label {
            font-weight: bold;
            display: block;
            color: #555;
            margin-bottom: 5px;
        }

        .back-button {
            width: 100%;
            margin-top: 25px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="about-card">

        <h2 class="about-title">Tentang Saya</h2>

        <div class="profile-item">
            <span class="profile-label">Nama</span>
            <span>Zidan Maulidan</span>
        </div>

        <div class="profile-item">
            <span class="profile-label">Umur</span>
            <span>20 tahun</span>
        </div>

        <div class="profile-item">
            <span class="profile-label">Sekolah</span>
            <span>SMK Muhammadiyah Tasikmalaya</span>
        </div>

        <div class="profile-item">
            <span class="profile-label">Cita-cita</span>
            <span>YouTuber</span>
        </div>

        <a href="dashboard.php"
           class="btn btn-primary back-button">
            ← Kembali ke Dashboard
        </a>

    </div>
</div>

</body>
</html>
