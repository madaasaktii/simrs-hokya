<?php
session_start();
include 'koneksi.php';

$role = isset($_POST['role']) ? $_POST['role'] : '';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (!$username || !$password || !$role) {
    header('Location: index.php?pesan=gagal');
    exit;
}

// Check users table
$stmt = $conn->prepare("SELECT id, username, password, name, role FROM users WHERE username = ? AND role = ? LIMIT 1");
$stmt->bind_param('ss', $username, $role);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows === 1){
    $u = $res->fetch_assoc();
    if (password_verify($password, $u['password'])){
        $_SESSION['username'] = $u['username'];
        $_SESSION['name'] = $u['name'];
        $_SESSION['role'] = $u['role'];
        $_SESSION['status'] = 'login';
        // redirect based on role
        if ($u['role'] === 'admin') header('Location: admin_dashboard.php');
        else if ($u['role'] === 'dokter') header('Location: dokter_dashboard.php');
        else if ($u['role'] === 'perawat') header('Location: perawat_dashboard.php');
        exit;
    }
}

// fallback to old hardcoded admin/perawat for compatibility
if ($role === 'admin' && $username === 'admin' && $password === 'admin123') {
    $_SESSION['username'] = $username; $_SESSION['role'] = 'admin'; $_SESSION['status'] = 'login'; header('Location: admin_dashboard.php'); exit;
}
if ($role === 'perawat' && $username === 'perawat' && $password === 'perawat123') {
    $_SESSION['username'] = $username; $_SESSION['role'] = 'perawat'; $_SESSION['status'] = 'login'; header('Location: perawat_dashboard.php'); exit;
}

// failed
header('Location: index.php?pesan=gagal');
exit;
?>