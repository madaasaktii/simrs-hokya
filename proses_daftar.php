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

/**
 * Mapping nama poli ke kode poli
 */
function getKodePoli($namaPoli) {
  $mapping = [
    'Poli Anak' => 'A',
    'Poli Jantung' => 'J',
    'Poli Syaraf' => 'S',
    'Poli Penyakit Dalam' => 'P',
    'Poli Gigi' => 'G'
  ];
  
  return isset($mapping[$namaPoli]) ? $mapping[$namaPoli] : null;
}

/**
 * Generate nomor antrian berikutnya untuk poli & tanggal tertentu
 */
function getNextNomorAntrian($conn, $kode_poli, $tanggal) {
  $query = "SELECT MAX(nomor) as max_nomor 
            FROM antrian 
            WHERE kode_poli = ? AND hari = ?";
  
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $kode_poli, $tanggal);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  
  $nextNomor = ($row['max_nomor'] ?? 0) + 1;
  return $nextNomor;
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
$insert = $conn->prepare("
  INSERT INTO pendaftaran_pasien (
    nik, nama, tempat_lahir, tgl_lahir, jenis_kelamin,
    no_hp, alamat, rencana_kunjungan, poli, cara_bayar,
    no_bpjs
  ) VALUES (?,?,?,?,?,?,?,?,?,?,?)
");

if (!$insert) {
  die("Error prepare statement: " . $conn->error);
}

$insert->bind_param(
  "sssssssssss",
  $nik, $nama, $tempat_lahir, $tgl_lahir, $jenis_kelamin,
  $no_hp, $alamat, $rencana_kunjungan, $poli,
  $cara_bayar, $no_bpjs
);

/* =========================
   4. Execute & Buat Antrian
========================= */
if ($insert->execute()) {
  $insert->close();
  
  // ============================================
  // FIX: Ambil ID dari database berdasarkan NIK
  // (karena AUTO_INCREMENT bermasalah, insert_id return 0)
  // ============================================
  $queryGetId = "SELECT id FROM pendaftaran_pasien WHERE nik = ? ORDER BY created_at DESC LIMIT 1";
  $stmtGetId = $conn->prepare($queryGetId);
  $stmtGetId->bind_param("s", $nik);
  $stmtGetId->execute();
  $resultGetId = $stmtGetId->get_result();
  
  if ($resultGetId->num_rows > 0) {
    $rowId = $resultGetId->fetch_assoc();
    $pasien_id = $rowId['id'];
  } else {
    die("Error: Data pasien tidak ditemukan setelah insert.");
  }
  $stmtGetId->close();
  
  // ============================================
  // BUAT ANTRIAN OTOMATIS
  // ============================================
  $kode_poli = getKodePoli($poli);
  
  if ($kode_poli) {
    // Dapatkan nomor antrian berikutnya
    $nomor_antrian = getNextNomorAntrian($conn, $kode_poli, $rencana_kunjungan);
    
    // Insert ke tabel antrian
    $insertAntrian = $conn->prepare("
      INSERT INTO antrian (nomor, kode_poli, pasien_id, nama_pasien, status, hari)
      VALUES (?, ?, ?, ?, 'waiting', ?)
    ");
    
    $insertAntrian->bind_param(
      "isiss",
      $nomor_antrian,
      $kode_poli,
      $pasien_id,
      $nama,
      $rencana_kunjungan
    );
    
    if ($insertAntrian->execute()) {
      $antrian_id = $conn->insert_id;
      
      // Jika antrian_id juga 0, ambil manual
      if ($antrian_id == 0) {
        $queryGetAntrianId = "SELECT id FROM antrian 
                              WHERE kode_poli = ? AND nomor = ? AND hari = ? 
                              ORDER BY created_at DESC LIMIT 1";
        $stmtGetAntrianId = $conn->prepare($queryGetAntrianId);
        $stmtGetAntrianId->bind_param("sis", $kode_poli, $nomor_antrian, $rencana_kunjungan);
        $stmtGetAntrianId->execute();
        $resultAntrianId = $stmtGetAntrianId->get_result();
        
        if ($resultAntrianId->num_rows > 0) {
          $rowAntrianId = $resultAntrianId->fetch_assoc();
          $antrian_id = $rowAntrianId['id'];
        }
        $stmtGetAntrianId->close();
      }
      
      $insertAntrian->close();
      
      // ============================================
      // KIRIM WHATSAPP (OPSIONAL)
      // ============================================
      if (file_exists('whatsapp_config.php')) {
        include 'whatsapp_config.php';
        
        $dataPassien = [
          'nomor_antrian' => $kode_poli . '-' . str_pad($nomor_antrian, 3, '0', STR_PAD_LEFT),
          'nik' => $nik,
          'nama' => $nama,
          'poli' => $poli,
          'tgl_kunjungan' => $rencana_kunjungan,
          'cara_bayar' => $cara_bayar,
          'no_bpjs' => $no_bpjs
        ];
        
        $pesan = templatePesanPendaftaran($dataPassien);
        $hasilWA = kirimWhatsApp($no_hp, $pesan);
        
        // Log hasil
        if ($hasilWA['status']) {
          error_log("WhatsApp berhasil dikirim ke: " . $no_hp);
        } else {
          error_log("WhatsApp gagal: " . json_encode($hasilWA));
        }
        
        $wa_param = "&wa=" . ($hasilWA['status'] ? '1' : '0');
      } else {
        $wa_param = "";
      }
      
      $conn->close();
      
      // Redirect dengan ID antrian
      header("Location: sukses.php?antrian_id=" . $antrian_id . $wa_param);
      exit;
    } else {
      error_log("Gagal membuat antrian: " . $insertAntrian->error);
      $insertAntrian->close();
    }
  }
  
  // Fallback jika gagal buat antrian, tetap redirect tapi pakai NIK
  $conn->close();
  header("Location: sukses.php?nik=" . urlencode($nik));
  exit;
  
} else {
  die("Gagal menyimpan data: " . $insert->error);
}
?>