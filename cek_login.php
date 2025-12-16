<?php 
// Mengaktifkan session php
session_start();
 
// Menangkap data yang dikirim dari form
$username = $_POST['username'];
$password = $_POST['password'];
 
// Cek username dan password (Manual dulu untuk tugas kuliah)
// Username: admin
// Password: admin123
if($username == "admin" && $password == "admin123"){
 
    // Jika benar, buat session login
    $_SESSION['username'] = $username;
    $_SESSION['status'] = "login";
 
    // Alihkan ke halaman admin
    header("location:admin_poli.php");
 
}else{
    // Jika salah, kembalikan ke login dengan pesan gagal
    header("location:login.php?pesan=gagal");
}
?>