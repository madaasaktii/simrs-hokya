<?php
// MATIKAN ERROR AGAR JSON BERSIH
error_reporting(0);
ini_set('display_errors', 0);

include 'koneksi.php';

// LOGIKA BARU:
// Ambil pasien yang statusnya 'Dipanggil'
// TAPI urutkan berdasarkan 'waktu_panggil' DARI YANG TERBARU (DESC)
// Jadi meskipun ada 3 orang di dalam ruangan, TV hanya mengambil yang barusan diklik Admin.

$query = mysqli_query($koneksi, "SELECT * FROM pasien 
                                 WHERE status_antrian = 'Dipanggil' 
                                 ORDER BY waktu_panggil DESC 
                                 LIMIT 1");

$data = mysqli_fetch_assoc($query);

// JIKA TIDAK ADA YANG DIPANGGIL (Semua Menunggu / Selesai)
// Maka ambil antrian 'Menunggu' urutan teratas untuk persiapan
if(!$data) {
    $query_next = mysqli_query($koneksi, "SELECT * FROM pasien WHERE status_antrian = 'Menunggu' ORDER BY no_antrian ASC LIMIT 1");
    $data_next = mysqli_fetch_assoc($query_next);
    
    if($data_next){
        // Tampilkan info "Persiapan" atau tetap kosong
        echo json_encode(['status' => 'kosong']); 
    } else {
        echo json_encode(['status' => 'kosong']);
    }
    exit;
}

// LOGIKA HURUF (Sama seperti sebelumnya)
$p = $data['poli'];
$huruf = "U"; 
if(stripos($p, "Gigi") !== false) $huruf = "G";
if(stripos($p, "Anak") !== false) $huruf = "A";
if(stripos($p, "Kandungan") !== false) $huruf = "K";
if(stripos($p, "Jantung") !== false) $huruf = "J";

$nomor_final = $huruf . "-" . $data['no_antrian'];

echo json_encode([
    'status' => 'ada',
    'no_antrian' => $nomor_final,
    'nama' => $data['nama'],
    'poli' => $data['poli'],
    'dokter' => $data['dokter_tujuan']
]);
?>