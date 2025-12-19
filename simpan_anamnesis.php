<?php
session_start();
// Proteksi: Pastikan hanya perawat yang akses
if (!isset($_SESSION['status']) || $_SESSION['role'] != "perawat") {
    header("location:login.php?pesan=belum_login");
    exit();
}

// Koneksi database
include 'koneksi.php';

// Ambil data dari form POST
$id_antrian = mysqli_real_escape_string($conn, $_POST['id_antrian']);
$berat_badan = mysqli_real_escape_string($conn, $_POST['berat_badan']);
$tinggi_badan = mysqli_real_escape_string($conn, $_POST['tinggi_badan']);
$tekanan_darah = mysqli_real_escape_string($conn, $_POST['tekanan_darah']);
$suhu_tubuh = mysqli_real_escape_string($conn, $_POST['suhu_tubuh']);
$nadi = mysqli_real_escape_string($conn, $_POST['nadi']);
$keluhan = mysqli_real_escape_string($conn, $_POST['keluhan']);

// Fetch data tambahan dari antrian
$fetch_query = "SELECT nama_pasien, kode_poli, nomor FROM antrian WHERE id = '$id_antrian'";
$fetch_result = mysqli_query($conn, $fetch_query);
if (mysqli_num_rows($fetch_result) > 0) {
    $row = mysqli_fetch_assoc($fetch_result);
    $nama_pasien = mysqli_real_escape_string($conn, $row['nama_pasien']);
    $kode_poli = mysqli_real_escape_string($conn, $row['kode_poli']);
    $nomor = $row['nomor'];

    // Simpan data anamnesis ke tabel (FIX: gunakan $nomor bukan $nomor_antrian)
    $insert_query = "INSERT INTO anamnesis (id_antrian, nama_pasien, kode_poli, nomor_antrian, berat_badan, tinggi_badan, tekanan_darah, suhu_tubuh, nadi, keluhan) 
                     VALUES ('$id_antrian', '$nama_pasien', '$kode_poli', '$nomor', '$berat_badan', '$tinggi_badan', '$tekanan_darah', '$suhu_tubuh', '$nadi', '$keluhan')";
    if (mysqli_query($conn, $insert_query)) {
        // FIX: Update status antrian menjadi 'called' (sesuai enum di SQL)
        // Status 'called' = dipanggil, siap diperiksa dokter
        $update_status = "UPDATE antrian SET status = 'called' WHERE id = '$id_antrian'";
        if (mysqli_query($conn, $update_status)) {
            header("location: perawat_dashboard.php?pesan=anamnesis_berhasil");
        } else {
            header("location: perawat_dashboard.php?pesan=anamnesis_gagal&error=" . urlencode(mysqli_error($conn)));
        }
    } else {
        header("location: perawat_dashboard.php?pesan=anamnesis_gagal&error=" . urlencode(mysqli_error($conn)));
    }
} else {
    header("location: perawat_dashboard.php?pesan=anamnesis_gagal&error=ID_antrian_tidak_ditemukan");
}

mysqli_close($conn);
?>