<?php
include 'koneksi.php';

// 1. Tangkap data dari form
$nik            = $_POST['nik'];
$nama           = $_POST['nama'];
$tempat_lahir   = $_POST['tempat_lahir'];
$tgl_lahir      = $_POST['tgl_lahir'];
$jenis_kelamin  = $_POST['jenis_kelamin'];
$no_hp          = $_POST['no_hp'];
$alamat         = $_POST['alamat'];
$tgl_kunjungan  = $_POST['tgl_kunjungan'];
$poli           = $_POST['poli'];
$dokter_tujuan  = $_POST['dokter_tujuan']; // <--- INI VARIABEL BARU
$cara_bayar     = $_POST['cara_bayar'];
$no_bpjs        = $_POST['no_bpjs'];

// 2. SETTING DATA OTOMATIS
$status_antrian = "Menunggu";
$tanggal_daftar = date('Y-m-d'); 

// 3. LOGIKA NOMOR ANTRIAN (RESET PER HARI & PER POLI)
// Kita tambahkan "AND poli = '$poli'" agar menghitungnya terpisah masing-masing poli
$cari_max = mysqli_query($koneksi, "SELECT max(no_antrian) as maxKode FROM pasien WHERE tanggal_daftar = '$tanggal_daftar' AND poli = '$poli'");
$data_max = mysqli_fetch_array($cari_max);

// Ambil angka terbesarnya
$kode_terbesar = $data_max['maxKode'];

// Tambah 1
$no_antrian = $kode_terbesar + 1;

// 4. SIMPAN KE DATABASE
// Tambahkan '$dokter_tujuan' ke dalam query
$query = "INSERT INTO pasien (
            nik, nama, tempat_lahir, tgl_lahir, jenis_kelamin, 
            no_hp, alamat, tgl_kunjungan, poli, dokter_tujuan, 
            cara_bayar, no_bpjs, status_antrian, no_antrian, tanggal_daftar
          ) VALUES (
            '$nik', '$nama', '$tempat_lahir', '$tgl_lahir', '$jenis_kelamin', 
            '$no_hp', '$alamat', '$tgl_kunjungan', '$poli', '$dokter_tujuan',
            '$cara_bayar', '$no_bpjs', '$status_antrian', '$no_antrian', '$tanggal_daftar'
          )";

if (mysqli_query($koneksi, $query)) {
    echo "<script>
            alert('Janji Temu Berhasil! \\nDokter: $dokter_tujuan \\nNomor Antrian: $no_antrian');
            window.location.href = 'index.html'; 
          </script>";
} else {
    echo "Gagal menyimpan data: " . mysqli_error($koneksi);
}
?>