<?php
include 'koneksi.php';

// AKTIFKAN ERROR UNTUK DEBUGGING
error_reporting(E_ALL);
ini_set('display_errors', 1);

$nik = isset($_GET['nik']) ? trim($_GET['nik']) : '';

// DEBUG: Tampilkan NIK yang diterima
echo "<!-- DEBUG: NIK dari URL = '$nik' -->\n";

if (empty($nik)) {
  die("Data tidak ditemukan. NIK kosong.");
}

// Ambil data pendaftaran berdasarkan NIK (pakai mysqli_query dulu untuk debug)
$nik_escaped = mysqli_real_escape_string($koneksi, $nik);
$query = "SELECT * FROM pendaftaran_pasien WHERE nik = '$nik_escaped' ORDER BY created_at DESC LIMIT 1";

echo "<!-- DEBUG: Query = $query -->\n";

$result = mysqli_query($koneksi, $query);

if (!$result) {
  die("Error query: " . mysqli_error($koneksi));
}

if (mysqli_num_rows($result) === 0) {
  // DEBUG: Cek apakah ada data di tabel
  $count_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pendaftaran_pasien");
  $count = mysqli_fetch_assoc($count_query);
  
  die("Data tidak ditemukan untuk NIK: $nik. Total data di tabel: " . $count['total']);
}

$p = mysqli_fetch_assoc($result);

// Helper aman tampil
function e($str) { return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sukses Daftar - SIMRS</title>

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- PWA manifest (opsional tapi bagus) -->
  <link rel="manifest" href="manifest.json">

  <style>
    body { background:#f6f8fb; }
    .ticket-badge{
      font-size:48px; font-weight:800; line-height:1;
      letter-spacing:1px;
    }
    .small-muted{ font-size:13px; color:#6c757d; }
  </style>
</head>

<body>
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="card shadow border-0">
          <div class="card-header bg-primary text-white py-3">
            <div class="d-flex align-items-center gap-2">
              <i class="fa-solid fa-circle-check"></i>
              <div>
                <div class="fw-bold">Pendaftaran Berhasil</div>
                <div class="small">Simpan halaman ini untuk bukti daftar.</div>
              </div>
            </div>
          </div>

          <div class="card-body p-4">
            <!-- Hidden input buat reminder ambil tanggal -->
            <input type="hidden" id="tgl_kunjungan" value="<?= e($p['rencana_kunjungan']) ?>">

            <div class="text-center mb-3">
              <div class="small-muted">Nomor Identitas (NIK)</div>
              <div class="ticket-badge text-primary"><?= e($p['nik']) ?></div>
              <div class="badge bg-success text-white px-3 py-2 mt-2">
                Terdaftar
              </div>
            </div>

            <hr>

            <div class="row g-3">
              <div class="col-md-6">
                <div class="small-muted">Nama Pasien</div>
                <div class="fw-semibold"><?= e($p['nama']) ?></div>
              </div>
              <div class="col-md-6">
                <div class="small-muted">Tanggal Daftar</div>
                <div class="fw-semibold"><?= e($p['created_at']) ?></div>
              </div>

              <div class="col-md-6">
                <div class="small-muted">Tempat, Tanggal Lahir</div>
                <div class="fw-semibold"><?= e($p['tempat_lahir']) ?>, <?= e($p['tgl_lahir']) ?></div>
              </div>
              <div class="col-md-6">
                <div class="small-muted">Jenis Kelamin</div>
                <div class="fw-semibold"><?= $p['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></div>
              </div>

              <div class="col-md-6">
                <div class="small-muted">No. HP</div>
                <div class="fw-semibold"><?= e($p['no_hp']) ?></div>
              </div>
              <div class="col-md-6">
                <div class="small-muted">Alamat</div>
                <div class="fw-semibold"><?= e($p['alamat']) ?></div>
              </div>

              <div class="col-md-6">
                <div class="small-muted">Poli Tujuan</div>
                <div class="fw-semibold"><?= e($p['poli']) ?></div>
              </div>
              <div class="col-md-6">
                <div class="small-muted">Rencana Kunjungan</div>
                <div class="fw-semibold"><?= e($p['rencana_kunjungan']) ?></div>
              </div>

              <div class="col-md-6">
                <div class="small-muted">Cara Bayar</div>
                <div class="fw-semibold"><?= e($p['cara_bayar']) ?></div>
              </div>
              <?php if(!empty($p['no_bpjs'])): ?>
              <div class="col-md-6">
                <div class="small-muted">No. BPJS</div>
                <div class="fw-semibold"><?= e($p['no_bpjs']) ?></div>
              </div>
              <?php endif; ?>
            </div>

            <div class="alert alert-info mt-4 mb-0">
              <b>Tips:</b> Screenshot NIK dan data pendaftaran ini. Tunjukkan saat datang ke rumah sakit pada tanggal kunjungan.
            </div>

            <!-- BOX REMINDER / NOTIF -->
            <div id="reminderBox"></div>

            <div class="d-grid gap-2 mt-3">
              <a href="index.html" class="btn btn-outline-secondary">
                Kembali ke Beranda
              </a>
              <a href="pendaftaran.php" class="btn btn-light">
                Daftar Lagi
              </a>
            </div>
          </div>
        </div>

        <div class="text-center mt-3 small-muted">
          © <?= date('Y') ?> SIMRS Pasien
        </div>

      </div>
    </div>
  </div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- PWA + Notif (pastikan file ini ada) -->
  <script src="assets/js/pwa-notif.js"></script>
  <script>
    // daftar service worker (opsional tapi bagus)
    if ('serviceWorker' in navigator) navigator.serviceWorker.register('/sw.js');

    // mount UI reminder dan auto isi poli dari data DB
    initReminderUI({
      mountId: "reminderBox",
      poli: "<?= e($p['poli']) ?>",
      queue: "<?= e($p['nik']) ?>",
      dateInputId: "tgl_kunjungan"
    });
  </script>
</body>
</html>