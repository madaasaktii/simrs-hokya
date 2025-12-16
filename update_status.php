<?php
include 'koneksi.php';

$id = $_GET['id'];
$status = $_GET['status'];

if($status == 'Dipanggil'){
    // Jika dipanggil, catat WAKTU SEKARANG (NOW)
    $query = "UPDATE pasien SET status_antrian='$status', waktu_panggil=NOW() WHERE id='$id'";
} else {
    // Jika Selesai, tidak perlu catat waktu
    $query = "UPDATE pasien SET status_antrian='$status' WHERE id='$id'";
}

mysqli_query($koneksi, $query);

// Kembali ke halaman sebelumnya
header("location:admin_poli.php");
?>