<?php
include 'koneksi.php';

// AKTIFKAN ERROR UNTUK DEBUGGING
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* =========================
   Helper
========================= */
function post($key) {
  return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

/* =========================
   1. Ambil data form
========================= */
$nik              = post('nik');
$nama             = post('nama');
$tempat_lahir     = post('tempat_lahir');
$tgl_lahir        = post('tgl_lahir');
$jenis_kelamin    = post('jenis_kelamin');
$no_hp            = post('no_hp');
$alamat           = post('alamat');
$rencana_kunjungan = post('tgl_kunjungan');
$poli             = post('poli');
$cara_bayar       = post('cara_bayar');
$no_bpjs          = post('no_bpjs');

/* =========================
   2. Validasi dasar
========================= */
if (!preg_match('/^\d{16}$/', $nik)) {
  die("NIK harus 16 digit angka.");
}

$wajib = [
  $nama, $tempat_lahir, $tgl_lahir, $jenis_kelamin,
  $no_hp, $alamat, $rencana_kunjungan, $poli,
  $cara_bayar
];

foreach ($wajib as $v) {
  if ($v === '') {
    die("Data wajib belum lengkap.");
  }
}

/* =========================
   3. Insert data pasien
========================= */
$insert = $koneksi->prepare("
  INSERT INTO pendaftaran_pasien (
    nik, nama, tempat_lahir, tgl_lahir, jenis_kelamin,
    no_hp, alamat, rencana_kunjungan, poli, cara_bayar,
    no_bpjs
  ) VALUES (?,?,?,?,?,?,?,?,?,?,?)
");

if (!$insert) {
  die("Error prepare statement: " . $koneksi->error);
}

$insert->bind_param(
  "sssssssssss",
  $nik, $nama, $tempat_lahir, $tgl_lahir, $jenis_kelamin,
  $no_hp, $alamat, $rencana_kunjungan, $poli,
  $cara_bayar, $no_bpjs
);

/* =========================
   4. Execute dan redirect (pakai NIK, bukan ID)
========================= */
if ($insert->execute()) {
  $insert->close();
  $koneksi->close();
  
  // Redirect pakai NIK sebagai identifier
  header("Location: sukses.php?nik=" . urlencode($nik));
  exit;
} else {
  die("Gagal menyimpan data: " . $insert->error);
}