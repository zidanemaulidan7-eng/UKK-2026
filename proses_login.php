
<?php
session_start();
include 'config/koneksi.php';

// Pastikan diakses melalui metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = mysqli_real_escape_string(
    $koneksi,
    $_POST['email'] ?? ''
);

$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo "<script>
        alert('Email dan Password tidak boleh kosong!');
        window.location='login.php';
    </script>";
    exit;
}

$sql = "SELECT * FROM t_users WHERE email = '$email'";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}

if (mysqli_num_rows($hasil) > 0) {
    $data = mysqli_fetch_assoc($hasil);

    // Verifikasi password sesuai format akun yang tersimpan
    $password_benar = password_get_info($data['password'])['algo']
        ? password_verify($password, $data['password'])
        : ($password === $data['password']);

    if ($password_benar) {
        $role = strtolower(trim($data['role']));

        if (!in_array($role, ['admin', 'guru'], true)) {
            echo "<script>
                alert('Role akun tidak dikenali!');
                window.location='login.php';
            </script>";
            exit;
        }

        session_regenerate_id(true);

        $_SESSION['login'] = true;
        $_SESSION['id_user'] = $data['id'];
        $_SESSION['nama_lengkap'] = $data['name'];
        $_SESSION['name'] = $data['name'];
        $_SESSION['role'] = $role;

        // Arahkan berdasarkan role akun
        if ($role === 'admin') {
            header("Location: dashboard.php");
            exit;
        } elseif ($role === 'guru') {
            header("Location: dashboard_guru.php");
            exit;
        }
    } else {
        echo "<script>
            alert('Password salah!');
            window.location='login.php';
        </script>";
        exit;
    }
} else {
    echo "<script>
        alert('Email tidak ditemukan!');
        window.location='login.php';
    </script>";
    exit;
}
?>
