<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Dokter</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .pasien-item { cursor: pointer; transition: 0.2s; border-left: 4px solid transparent; }
        .pasien-item:hover { background-color: #e9ecef; border-left: 4px solid #0d6efd; }
        .pasien-item.active { background-color: #cfe2ff; border-left: 4px solid #0d6efd; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-primary shadow mb-4">
    <div class="container-fluid px-4">
        <span class="navbar-brand fw-bold"><i class="fa-solid fa-user-doctor me-2"></i>DASHBOARD DOKTER</span>
        <a href="index.html" class="btn btn-outline-light btn-sm">Keluar</a>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row">
        
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold text-primary">
                    <i class="fa-solid fa-list-ul me-2"></i> Antrian Masuk
                </div>
                <div class="card-body p-0">
                    <div id="list-antrian" class="list-group list-group-flush">
                        <div class="text-center py-4 text-muted">Memuat antrian...</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold text-success">
                    <i class="fa-solid fa-stethoscope me-2"></i> Form Pemeriksaan
                </div>
                <div class="card-body" id="area-form">
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-user-nurse fa-3x mb-3 opacity-50"></i>
                        <h5>Belum ada pasien dipilih</h5>
                        <p>Silakan klik nama pasien di sebelah kiri untuk mulai memeriksa.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        
        // 1. Fungsi Auto Refresh LIST Antrian (Kiri)
        function loadListAntrian() {
            // Hanya load daftar namanya saja, bukan formnya
            $("#list-antrian").load("get_list_antrian.php");
        }

        // Jalankan refresh list setiap 3 detik
        setInterval(loadListAntrian, 3000);
        loadListAntrian(); // Load pertama kali

        // 2. Fungsi Klik Pasien -> Buka Form (Kanan)
        // Kita pakai 'on click' ke document karena elemennya dinamis (hasil load)
        $(document).on('click', '.btn-periksa', function(){
            var idPasien = $(this).data('id');
            
            // Tandai yang aktif
            $('.pasien-item').removeClass('active');
            $(this).closest('.pasien-item').addClass('active');

            // Load form pemeriksaan ke kolom kanan
            // Form ini TIDAK akan ke-refresh otomatis, jadi aman buat ngetik
            $("#area-form").html('<div class="text-center py-5">Memuat data medis...</div>');
            $("#area-form").load("get_form_pemeriksaan.php?id=" + idPasien);
        });

    });
</script>

</body>
</html>