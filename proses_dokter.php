<?php
include 'koneksi.php';

$id = $_POST['id'];
$diagnosa = $_POST['diagnosa'];
$resep = $_POST['resep'];

// 1. Simpan Diagnosa & Resep
// 2. Ubah Status jadi 'Selesai'
$query = "UPDATE pasien SET 
            diagnosa = '$diagnosa', 
            resep = '$resep', 
            status_antrian = 'Selesai' 
          WHERE id = '$id'";

if(mysqli_query($koneksi, $query)){
    echo "<script>
            alert('Data Medis Tersimpan! Pasien telah diselesaikan.');
            window.location.href='dokter_dashboard.php';
          </script>";
} else {
    echo "Gagal: " . mysqli_error($koneksi);
}
?>