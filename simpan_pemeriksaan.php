<?php
header('Content-Type: application/json');
include 'koneksi.php';

$id_antrian = isset($_POST['id_antrian']) ? intval($_POST['id_antrian']) : 0;
$diagnosis = mysqli_real_escape_string($conn, $_POST['diagnosis']);
$resep = mysqli_real_escape_string($conn, $_POST['resep']);
$catatan = mysqli_real_escape_string($conn, $_POST['catatan']);
$durasi = isset($_POST['durasi_pemeriksaan']) ? intval($_POST['durasi_pemeriksaan']) : 0;

// Fetch nama pasien dan data anamnesis
$query_antrian = "SELECT nama_pasien FROM antrian WHERE id = '$id_antrian'";
$result = mysqli_query($conn, $query_antrian);
$row_antrian = mysqli_fetch_assoc($result);

if (!$row_antrian) {
    echo json_encode(['success' => false, 'error' => 'Data antrian tidak ditemukan']);
    exit;
}

$query_anamnesis = "SELECT * FROM anamnesis WHERE id_antrian = '$id_antrian'";
$result = mysqli_query($conn, $query_anamnesis);
$row_anamnesis = mysqli_fetch_assoc($result);

if (!$row_anamnesis) {
    echo json_encode(['success' => false, 'error' => 'Data anamnesis tidak ditemukan']);
    exit;
}

// Simpan ke tabel pemeriksaan
$insert = "INSERT INTO pemeriksaan (id_antrian, nama_pasien, tinggi_badan, berat_badan, tekanan_darah, suhu_tubuh, nadi, keluhan, diagnosis, resep, catatan, durasi_pemeriksaan) 
           VALUES ('$id_antrian', '{$row_antrian['nama_pasien']}', '{$row_anamnesis['tinggi_badan']}', '{$row_anamnesis['berat_badan']}', '{$row_anamnesis['tekanan_darah']}', '{$row_anamnesis['suhu_tubuh']}', '{$row_anamnesis['nadi']}', '{$row_anamnesis['keluhan']}', '$diagnosis', '$resep', '$catatan', '$durasi')";

if (mysqli_query($conn, $insert)) {
    // Update status ke 'done' (sesuai enum di SQL)
    mysqli_query($conn, "UPDATE antrian SET status = 'done' WHERE id = '$id_antrian'");
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>