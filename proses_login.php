
<?php
session_start();
require_once "config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['pesan_error'] = "Email dan password wajib diisi.";
    header("Location: login.php");
    exit;
}

$sql = "SELECT id, name, email, password, role
        FROM users
        WHERE email = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data || !password_verify($password, $data['password'])) {
    $_SESSION['pesan_error'] = "Email atau password salah.";
    header("Location: login.php");
    exit;
}

$role = strtolower(trim($data['role']));

if (!in_array($role, ['admin', 'guru'], true)) {
    $_SESSION['pesan_error'] = "Role pengguna tidak valid.";
    header("Location: login.php");
    exit;
}

session_regenerate_id(true);

$_SESSION['id_user'] = $data['id'];
$_SESSION['nama_user'] = $data['name'];
$_SESSION['email'] = $data['email'];
$_SESSION['role'] = $role;

$stmt->close();
$conn->close();

if ($role === 'admin') {
    header("Location: dashboard_admin.php");
} else {
    header("Location: dashboard_guru.php");
}
exit;
?>