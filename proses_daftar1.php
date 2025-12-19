<?php
// Include koneksi database
include 'koneksi.php';

// Ambil data dari form
$nik = $_POST['nik'];
$nama = $_POST['nama'];
$tempat_lahir = $_POST['tempat_lahir'];
$tgl_lahir = $_POST['tgl_lahir'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$no_hp = $_POST['no_hp'];
$alamat = $_POST['alamat'];
$rencana_kunjungan = $_POST['rencana_kunjungan'];
$poli = $_POST['poli'];
$cara_bayar = $_POST['cara_bayar'];
$no_bpjs = $_POST['no_bpjs'];

// Cek kode poli untuk menentukan nomor antrian
$kode_poli = '';
switch ($poli) {
    case 'Poli Anak':
        $kode_poli = 'A';
        break;
    case 'Poli Jantung':
        $kode_poli = 'J';
        break;
    case 'Poli Syaraf':
        $kode_poli = 'S';
        break;
    case 'Poli Penyakit Dalam':
        $kode_poli = 'P';
        break;
    case 'Poli Gigi':
        $kode_poli = 'G';
        break;
    default:
        $kode_poli = 'X';  // Kode default jika poli tidak dikenali
}

// Masukkan data pasien ke tabel pendaftaran_pasien
$sql_pendaftaran = "INSERT INTO pendaftaran_pasien (nik, nama, tempat_lahir, tgl_lahir, jenis_kelamin, no_hp, alamat, rencana_kunjungan, poli, cara_bayar, no_bpjs)
                    VALUES ('$nik', '$nama', '$tempat_lahir', '$tgl_lahir', '$jenis_kelamin', '$no_hp', '$alamat', '$rencana_kunjungan', '$poli', '$cara_bayar', '$no_bpjs')";

if ($conn->query($sql_pendaftaran) === TRUE) {
    // Ambil nomor antrian berikutnya untuk poli yang dipilih
    $sql_antrian = "SELECT MAX(nomor_antrian) AS max_antrian FROM antrian WHERE kode_poli = '$kode_poli'";
    $result = $conn->query($sql_antrian);
    $row = $result->fetch_assoc();
    $nomor_antrian = $row['max_antrian'] + 1;

    // Masukkan data antrian ke tabel antrian
    $sql_insert_antrian = "INSERT INTO antrian (kode_poli, nomor_antrian, nama_pasien, poli, dokter)
                           VALUES ('$kode_poli', '$nomor_antrian', '$nama', '$poli', 'Dokter Poli $poli')";

    if ($conn->query($sql_insert_antrian) === TRUE) {
        // Redirect ke halaman cetak antrian
        header("Location: cetak_antrian.php?antrian=$kode_poli$nomor_antrian&nama=$nama");
    } else {
        echo "Error: " . $sql_insert_antrian . "<br>" . $conn->error;
    }
} else {
    echo "Error: " . $sql_pendaftaran . "<br>" . $conn->error;
}

// Menutup koneksi
$conn->close();
?>
