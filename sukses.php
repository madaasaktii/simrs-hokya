<?php
include 'koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  die("Data tidak ditemukan.");
}

// Ambil data pendaftaran
$q = mysqli_query($koneksi, "SELECT * FROM pasien WHERE id = $id LIMIT 1");
if (!$q || mysqli_num_rows($q) === 0) {
  die("Data tidak ditemukan.");
}
$p = mysqli_fetch_assoc($q);

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
            <input type="hidden" id="tgl_kunjungan" value="<?= e($p['tgl_kunjungan']) ?>">

            <div class="text-center mb-3">
              <div class="small-muted">Nomor Antrian</div>
              <div class="ticket-badge text-primary"><?= e($p['no_antrian']) ?></div>
              <div class="badge bg-warning text-dark px-3 py-2 mt-2">
                Status: <?= e($p['status_antrian']) ?>
              </div>
            </div>

            <hr>

            <div class="row g-3">
              <div class="col-md-6">
                <div class="small-muted">Nama Pasien</div>
                <div class="fw-semibold"><?= e($p['nama']) ?></div>
              </div>
              <div class="col-md-6">
                <div class="small-muted">NIK</div>
                <div class="fw-semibold"><?= e($p['nik']) ?></div>
              </div>

              <div class="col-md-6">
                <div class="small-muted">Poli</div>
                <div class="fw-semibold"><?= e($p['poli']) ?></div>
              </div>
              <div class="col-md-6">
                <div class="small-muted">Dokter</div>
                <div class="fw-semibold"><?= e($p['dokter_tujuan']) ?></div>
              </div>

              <div class="col-md-6">
                <div class="small-muted">Tanggal Periksa</div>
                <div class="fw-semibold"><?= e($p['tgl_kunjungan']) ?></div>
              </div>
              <div class="col-md-6">
                <div class="small-muted">Cara Bayar</div>
                <div class="fw-semibold"><?= e($p['cara_bayar']) ?></div>
              </div>
            </div>

            <div class="alert alert-info mt-4 mb-0">
              <b>Tips:</b> Screenshot nomor antrian ini ya. Nanti tinggal tunjukin saat datang.
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

    // mount UI reminder dan auto isi poli+antrian dari data DB
    initReminderUI({
      mountId: "reminderBox",
      poli: "<?= e($p['poli']) ?>",
      queue: "<?= e($p['no_antrian']) ?>",
      dateInputId: "tgl_kunjungan"
    });
  </script>
</body>
</html>
