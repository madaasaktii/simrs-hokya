<?php
session_start();
// Proteksi halaman (Manual Frontend)
if (!isset($_SESSION['status']) || $_SESSION['role'] != "dokter") {
    header("location:login.php?pesan=belum_login");
    exit();
}

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "simrs");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Verifikasi ulang username dari session dan fetch poli_code jika belum ada
$username = isset($_SESSION['username']) ? $conn->real_escape_string($_SESSION['username']) : '';
$kode_poli_dokter = '';
if (!empty($username)) {
    $stmt = $conn->prepare("SELECT poli_code FROM users WHERE username = ? AND role = 'dokter'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $kode_poli_dokter = $row['poli_code'];
        $_SESSION['poli_code'] = $kode_poli_dokter;
    } else {
        echo "<div class='alert alert-danger'>Data user tidak ditemukan atau bukan dokter. Silakan login ulang atau hubungi admin.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Username tidak ditemukan di session. Silakan login ulang.</div>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Dokter | SIMRS HOKYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
        <span class="navbar-brand fw-bold">
            <i class="bi bi-person-badge me-2"></i>DASHBOARD DOKTER
        </span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Keluar</a>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row">

        <!-- ===== LIST ANTRIAN ===== -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold text-primary">
                    <i class="bi bi-list-ul me-2"></i> Antrian Masuk
                </div>
                <div class="card-body p-0">
                    <div id="list-antrian" class="list-group list-group-flush">
                        <div class="text-center py-4 text-muted">Memuat antrian...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== FORM PEMERIKSAAN ===== -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold text-success d-flex justify-content-between">
                    <span>
                        <i class="bi bi-stethoscope me-2"></i> Pemeriksaan Pasien
                    </span>
                    <span class="fw-bold text-danger" id="timer">00:00:00</span>
                </div>

                <div class="card-body" id="area-form">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-person-lines-fill fs-1 mb-3 opacity-50"></i>
                        <h5>Belum ada pasien dipilih</h5>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
let timerInterval;
let seconds = 0;

function loadListAntrian(){
    $('#list-antrian').load('get_list_antrian_html.php');
}
setInterval(loadListAntrian, 3000);
loadListAntrian();

$(document).on('click', '.btn-periksa', function(e){
    e.stopPropagation();
    const idAntrian = $(this).data('id');

    // Update status ke 'ongoing'
    $.post('update_status.php', { id: idAntrian, status: 'ongoing' }, function(response){
        if (response.success) {
            // Start stopwatch dan load form
            resetTimer();
            startTimer();

            // Mark item as active
            $('.pasien-item').removeClass('active');
            $('[data-id="' + idAntrian + '"]').closest('.pasien-item').addClass('active');

            $('#area-form').load('get_form_pemeriksaan.php?id=' + idAntrian);
        } else {
            alert('Gagal periksa pasien: ' + (response.error || 'Unknown error'));
        }
    }, 'json').fail(function(){
        alert('Terjadi kesalahan saat mengubah status pasien');
    });
});

$(document).on('submit', '#form-pemeriksaan', function(e){
    e.preventDefault();
    stopTimer();
    // Tambah durasi ke form
    $(this).append('<input type="hidden" name="durasi_pemeriksaan" value="' + seconds + '">');
    // Submit form ke simpan_pemeriksaan.php
    $.post('simpan_pemeriksaan.php', $(this).serialize(), function(response){
        if(response.success){
            alert('Pemeriksaan disimpan!');
            resetTimer();
            $('#area-form').html('<div class="text-center py-5 text-muted"><h5>Pasien selesai diperiksa.</h5></div>');
            loadListAntrian();
        } else {
            alert('Gagal menyimpan: ' + (response.error || 'Unknown error'));
        }
    }, 'json').fail(function(){
        alert('Terjadi kesalahan saat menyimpan pemeriksaan');
    });
});

function startTimer(){
    timerInterval = setInterval(() => { seconds++; $('#timer').text(formatTime(seconds)); }, 1000);
}
function stopTimer(){ clearInterval(timerInterval); }
function resetTimer(){ stopTimer(); seconds = 0; $('#timer').text('00:00:00'); }
function formatTime(sec){ const h = String(Math.floor(sec / 3600)).padStart(2,'0'); const m = String(Math.floor((sec % 3600) / 60)).padStart(2,'0'); const s = String(sec % 60).padStart(2,'0'); return `${h}:${m}:${s}`; }
</script>

</body>
</html>