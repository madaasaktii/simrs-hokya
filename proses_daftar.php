<?php
include 'koneksi.php';

/* =========================
   Helper
========================= */
function post($key) {
  return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

/* =========================
   1. Ambil data form
========================= */
$nik           = post('nik');
$nama          = post('nama');
$tempat_lahir  = post('tempat_lahir');
$tgl_lahir     = post('tgl_lahir');
$jenis_kelamin = post('jenis_kelamin');
$no_hp         = post('no_hp');
$alamat        = post('alamat');
$tgl_kunjungan = post('tgl_kunjungan');
$poli          = post('poli');
$dokter_tujuan = post('dokter_tujuan');
$cara_bayar    = post('cara_bayar');
$no_bpjs       = post('no_bpjs');

/* =========================
   2. Validasi dasar
========================= */
if (!preg_match('/^\d{16}$/', $nik)) {
  die("NIK harus 16 digit angka.");
}

$wajib = [
  $nama, $tempat_lahir, $tgl_lahir, $jenis_kelamin,
  $no_hp, $alamat, $tgl_kunjungan, $poli,
  $dokter_tujuan, $cara_bayar
];

foreach ($wajib as $v) {
  if ($v === '') {
    die("Data wajib belum lengkap.");
  }
}

/* =========================
   3. Data otomatis
========================= */
$status_antrian = "Menunggu";
$tanggal_daftar = date('Y-m-d');

/* =========================
   4. Hitung nomor antrian
   (reset per hari & poli)
========================= */
$q = $koneksi->prepare("
  SELECT COALESCE(MAX(no_antrian), 0) AS max_no
  FROM pasien
  WHERE tanggal_daftar = ? AND poli = ?
");
$q->bind_param("ss", $tanggal_daftar, $poli);
$q->execute();
$res = $q->get_result()->fetch_assoc();
$no_antrian = ((int)$res['max_no']) + 1;
$q->close();

/* =========================
   5. Insert data pasien
========================= */
$insert = $koneksi->prepare("
  INSERT INTO pasien (
    nik, nama, tempat_lahir, tgl_lahir, jenis_kelamin,
    no_hp, alamat, tgl_kunjungan, poli, dokter_tujuan,
    cara_bayar, no_bpjs, status_antrian, no_antrian, tanggal_daftar
  ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$insert->bind_param(
  "sssssssssssssis",
  $nik, $nama, $tempat_lahir, $tgl_lahir, $jenis_kelamin,
  $no_hp, $alamat, $tgl_kunjungan, $poli, $dokter_tujuan,
  $cara_bayar, $no_bpjs, $status_antrian, $no_antrian, $tanggal_daftar
);

/* =========================
   6. Redirect ke sukses.php
========================= */
if ($insert->execute()) {
  $id = $koneksi->insert_id;   // ID pasien terakhir
  header("Location: sukses.php?id=" . $id);
  exit;
} else {
  die("Gagal menyimpan data: " . $insert->error);
}

$insert->close();
$koneksi->close();
