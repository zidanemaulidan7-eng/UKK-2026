<?php

session_start();

include 'config/koneksi.php';

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pelanggaran Siswa</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #595657; /* Latar belakang merah menyala */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-card {
            background-color: #ffffff;
            width: 320px;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .logo-container {
            margin-bottom: 25px;
            display: flex;
            justify-content: center;
        }

        /* Styling logo berbentuk icon S/kupu-kupu */
        .logo-svg {
            width: 50px;
            height: 50px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            color: #333333;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            background-color: #dcdcdc; /* Warna input abu-abu */
            border: none;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
            color: #333;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #00ff3c; /* Warna hijau terang */
            border: none;
            border-radius: 10px;
            color: #000000;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.2s;
        }

        .btn-login:hover {
            background-color: #00e035;
        }

        .pesan-error {
            color: red;
            font-size: 12px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <!-- Logo Icon S / Abstrak -->
        <div class="logo-container">
            <svg class="logo-svg" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M30 20 C20 20 20 40 50 50 C80 60 80 80 70 80 M70 20 C80 20 80 40 50 50 C20 60 20 80 30 80" stroke="#000000" stroke-width="8" stroke-linecap="round"/>
            </svg>
        </div>

        <?php
        if (isset($_SESSION['pesan_error'])) {
            echo "<p class='pesan-error'>" . $_SESSION['pesan_error'] . "</p>";
            unset($_SESSION['pesan_error']);
        }
        ?>

        <form action="proses_login.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="Admin" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" name="login" class="btn-login">LOGIN</button>
        </form>
    </div>

</body>

</html>