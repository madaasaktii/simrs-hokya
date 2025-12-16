<?php 
session_start();
if($_SESSION['status'] != "login"){ header("location:login.php?pesan=belum_login"); exit; }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Admin Poli - Realtime</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .blink_me { animation: blinker 1s linear infinite; }
        @keyframes blinker { 50% { opacity: 0; } }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fa-solid fa-laptop-medical me-2"></i>Dashboard Admin</h4>
            <div>
                <a href="pendaftaran.php" class="btn btn-light btn-sm" target="_blank">Form Daftar</a>
                <a href="layar_antrian.php" class="btn btn-warning btn-sm" target="_blank">Layar TV</a>
                <a href="riwayat_pasien.php" class="btn btn-success btn-sm ms-2">Arsip Pasien</a>
                <a href="logout.php" class="btn btn-danger btn-sm ms-2">Logout</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>Poli</th>
                        <th>Dokter</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-pasien">
                   <tr><td colspan="6" class="text-center">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        
        // Fungsi load tabel
        function loadTabel() {
            $("#tabel-pasien").load("load_tabel.php");
        }

        // Panggil sekali saat dibuka
        loadTabel();

        // Ulangi setiap 2 detik
        setInterval(function(){
            loadTabel();
        }, 2000); 
    });
</script>

</body>
</html>