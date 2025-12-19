<?php
include 'koneksi.php';

// Ensure columns diagnosa & resep exist (attempt alter to be safe)
$conn->query("ALTER TABLE antrian ADD COLUMN IF NOT EXISTS diagnosa TEXT NULL;");
$conn->query("ALTER TABLE antrian ADD COLUMN IF NOT EXISTS resep TEXT NULL;");

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$diagnosa = isset($_POST['diagnosa']) ? $conn->real_escape_string($_POST['diagnosa']) : '';
$resep = isset($_POST['resep']) ? $conn->real_escape_string($_POST['resep']) : '';

if(!$id){
    echo "<script>alert('ID pasien tidak ditemukan'); window.location.href='dokter_dashboard.php';</script>";
    exit;
}

$query = "UPDATE antrian SET diagnosa = '$diagnosa', resep = '$resep', status = 'done', updated_at = NOW() WHERE id = $id";
if($conn->query($query)){
    echo "<script>alert('Data Medis Tersimpan! Pasien telah diselesaikan.'); window.location.href='dokter_dashboard.php';</script>";
} else {
    echo "Gagal: " . $conn->error;
}
?>