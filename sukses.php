<?php
include 'koneksi.php';

// AKTIFKAN ERROR UNTUK DEBUGGING
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek parameter
$antrian_id = isset($_GET['antrian_id']) ? intval($_GET['antrian_id']) : 0;
$nik = isset($_GET['nik']) ? trim($_GET['nik']) : '';

$dataAntrian = null;
$dataPasien = null;

// Prioritas 1: Cari berdasarkan antrian_id
if ($antrian_id > 0) {
  $query = "SELECT 
              a.id as antrian_id,
              a.nomor,
              a.kode_poli,
              a.status,
              a.hari,
              p.id,
              p.nik,
              p.nama,
              p.tempat_lahir,
              p.tgl_lahir,
              p.jenis_kelamin,
              p.no_hp,
              p.alamat,
              p.rencana_kunjungan,
              p.poli,
              p.cara_bayar,
              p.no_bpjs,
              p.created_at
            FROM antrian a
            INNER JOIN pendaftaran_pasien p ON a.pasien_id = p.id
            WHERE a.id = ?
            LIMIT 1";
  
  $stmt = $koneksi->prepare($query);
  $stmt->bind_param("i", $antrian_id);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $dataAntrian = [
      'nomor' => $row['nomor'],
      'kode_poli' => $row['kode_poli'],
      'status' => $row['status'],
      'hari' => $row['hari']
    ];
    $dataPasien = $row;
  }
  $stmt->close();
}

// Prioritas 2: Cari berdasarkan NIK dan ambil antrian terbarunya
if (!$dataPasien && !empty($nik)) {
  // Ambil data pasien dan antrian bersamaan
  $query = "SELECT 
              a.id as antrian_id,
              a.nomor,
              a.kode_poli,
              a.status,
              a.hari,
              p.id,
              p.nik,
              p.nama,
              p.tempat_lahir,
              p.tgl_lahir,
              p.jenis_kelamin,
              p.no_hp,
              p.alamat,
              p.rencana_kunjungan,
              p.poli,
              p.cara_bayar,
              p.no_bpjs,
              p.created_at
            FROM pendaftaran_pasien p
            LEFT JOIN antrian a ON p.id = a.pasien_id
            WHERE p.nik = ?
            ORDER BY a.created_at DESC
            LIMIT 1";
  
  $stmt = $koneksi->prepare($query);
  $stmt->bind_param("s", $nik);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $dataPasien = $row;
    
    // Jika ada data antrian
    if (!empty($row['nomor'])) {
      $dataAntrian = [
        'nomor' => $row['nomor'],
        'kode_poli' => $row['kode_poli'],
        'status' => $row['status'],
        'hari' => $row['hari']
      ];
    }
  }
  $stmt->close();
}

// Jika tidak ada data sama sekali
if (!$dataPasien) {
  die("Data tidak ditemukan. Pastikan link yang Anda akses benar.");
}

$p = $dataPasien;

// Helper aman tampil
function e($str) { 
  return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8'); 
}

// Cek apakah WhatsApp berhasil dikirim
$wa_sent = isset($_GET['wa']) && $_GET['wa'] == '1';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sukses Daftar - SIMRS</title>

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="manifest" href="manifest.json">

  <style>
    body { background:#f6f8fb; }
    .ticket-badge{
      font-size:clamp(32px, 10vw, 56px);
      font-weight:800;
      line-height:1.2;
      letter-spacing:2px;
    }
    .small-muted{ font-size:13px; color:#6c757d; }
    
    /* Animasi nomor antrian */
    @keyframes bounceIn {
      0% { transform: scale(0.3); opacity: 0; }
      50% { transform: scale(1.05); }
      70% { transform: scale(0.9); }
      100% { transform: scale(1); opacity: 1; }
    }
    
    .ticket-badge {
      animation: bounceIn 0.6s ease-out;
    }
    
    .antrian-box {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 2rem;
      border-radius: 15px;
      margin-bottom: 1.5rem;
    }

    /* Style untuk ticket download */
    #downloadTicket {
      display: none;
      position: fixed;
      top: -9999px;
      left: -9999px;
    }

    .download-ticket {
      width: 400px;
      background: white;
      padding: 30px;
      font-family: Arial, sans-serif;
    }

    .download-ticket .header {
      text-align: center;
      border-bottom: 3px solid #667eea;
      padding-bottom: 20px;
      margin-bottom: 20px;
    }

    .download-ticket .logo {
      font-size: 24px;
      font-weight: bold;
      color: #667eea;
      margin-bottom: 5px;
    }

    .download-ticket .subtitle {
      font-size: 12px;
      color: #666;
    }

    .download-ticket .queue-number {
      text-align: center;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 30px;
      border-radius: 10px;
      margin: 20px 0;
    }

    .download-ticket .queue-label {
      font-size: 14px;
      opacity: 0.9;
      margin-bottom: 10px;
    }

    .download-ticket .queue-num {
      font-size: 48px;
      font-weight: 800;
      letter-spacing: 3px;
    }

    .download-ticket .info-row {
      display: flex;
      justify-content: space-between;
      padding: 8px 0;
      border-bottom: 1px solid #eee;
      font-size: 13px;
    }

    .download-ticket .info-label {
      color: #666;
      font-weight: 600;
    }

    .download-ticket .info-value {
      color: #333;
      text-align: right;
    }

    .download-ticket .footer {
      text-align: center;
      margin-top: 20px;
      padding-top: 15px;
      border-top: 2px dashed #ccc;
      font-size: 11px;
      color: #999;
    }

    .download-ticket .barcode {
      text-align: center;
      margin: 15px 0;
      font-family: 'Courier New', monospace;
      font-size: 10px;
      letter-spacing: 2px;
    }
  </style>

  <!-- Library html2canvas untuk screenshot -->
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
</head>

<body>
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="card shadow border-0">
          <div class="card-header bg-success text-white py-3">
            <div class="d-flex align-items-center gap-2">
              <i class="fa-solid fa-circle-check fa-lg"></i>
              <div>
                <div class="fw-bold">Pendaftaran Berhasil!</div>
                <div class="small">Simpan nomor antrian Anda</div>
              </div>
            </div>
          </div>

          <div class="card-body p-4">
            <?php if($wa_sent): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="fa-brands fa-whatsapp me-1"></i>
              <strong>Notifikasi terkirim!</strong> Cek WhatsApp Anda untuk detail lengkap.
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if($dataAntrian): ?>
            <!-- TAMPILKAN NOMOR ANTRIAN DARI KOLOM NOMOR -->
            <div class="antrian-box text-center">
              <div class="small mb-2" style="opacity: 0.9;">Nomor Antrian Anda</div>
              <div class="ticket-badge">
                <?= e($dataAntrian['kode_poli']) ?>-<?= str_pad($dataAntrian['nomor'], 3, '0', STR_PAD_LEFT) ?>
              </div>
              <div class="mt-2" style="opacity: 0.85;">
                <i class="fa-solid fa-calendar me-1"></i>
                <?= date('d/m/Y', strtotime($dataAntrian['hari'])) ?>
              </div>
            </div>

            <!-- TOMBOL DOWNLOAD -->
            <div class="d-grid gap-2 mb-3">
              <button onclick="downloadTicket()" class="btn btn-outline-primary">
                <i class="fa-solid fa-download me-2"></i>
                Unduh Nomor Antrian
              </button>
            </div>

            <?php else: ?>
            <!-- JIKA TIDAK ADA ANTRIAN -->
            <div class="alert alert-danger">
              <i class="fa-solid fa-exclamation-triangle me-2"></i>
              <strong>Data antrian tidak ditemukan!</strong><br>
              Silakan hubungi bagian pendaftaran.
            </div>
            <?php endif; ?>

            <hr>

            <div class="row g-3">
              <div class="col-md-6">
                <div class="small-muted">NIK</div>
                <div class="fw-semibold"><?= e($p['nik']) ?></div>
              </div>
              <div class="col-md-6">
                <div class="small-muted">Nama Pasien</div>
                <div class="fw-semibold"><?= e($p['nama']) ?></div>
              </div>

              <div class="col-md-6">
                <div class="small-muted">Tempat, Tanggal Lahir</div>
                <div class="fw-semibold"><?= e($p['tempat_lahir']) ?>, <?= date('d/m/Y', strtotime($p['tgl_lahir'])) ?></div>
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
                <div class="fw-semibold text-primary">
                  <i class="fa-solid fa-hospital me-1"></i><?= e($p['poli']) ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="small-muted">Rencana Kunjungan</div>
                <div class="fw-semibold">
                  <i class="fa-solid fa-calendar me-1"></i><?= date('d/m/Y', strtotime($p['rencana_kunjungan'])) ?>
                </div>
              </div>

              <div class="col-md-6">
                <div class="small-muted">Cara Bayar</div>
                <div class="fw-semibold">
                  <?php 
                    if (!empty($p['cara_bayar'])) {
                      echo e($p['cara_bayar']);
                    } else {
                      echo '<span class="text-muted">Belum diisi</span>';
                    }
                  ?>
                </div>
              </div>
              <?php if(!empty($p['no_bpjs'])): ?>
              <div class="col-md-6">
                <div class="small-muted">No. BPJS</div>
                <div class="fw-semibold"><?= e($p['no_bpjs']) ?></div>
              </div>
              <?php endif; ?>
              
              <div class="col-md-6">
                <div class="small-muted">Tanggal Daftar</div>
                <div class="fw-semibold"><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></div>
              </div>
            </div>

            <?php if($dataAntrian): ?>
            <div class="alert alert-warning mt-4 mb-0">
              <i class="fa-solid fa-lightbulb me-1"></i>
              <b>Penting:</b> Simpan nomor antrian 
              <strong><?= e($dataAntrian['kode_poli']) ?>-<?= str_pad($dataAntrian['nomor'], 3, '0', STR_PAD_LEFT) ?></strong>
              . Tunjukkan saat datang ke rumah sakit.
            </div>

            <!-- INFO PENTING SEBELUM KUNJUNGAN -->
            <div class="card border-0 shadow-sm mt-3" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
              <div class="card-body p-3">
                <h6 class="mb-2 fw-bold" style="color: #1565c0;">
                  <i class="fa-solid fa-circle-info me-1"></i> Informasi Penting
                </h6>
                
                <div class="row g-2">
                  <div class="col-md-6">
                    <div class="d-flex gap-2 mb-2">
                      <i class="fa-solid fa-clock" style="color: #ff6f00; font-size: 18px;"></i>
                      <div style="font-size: 13px;">
                        <strong>Jam Buka Poli</strong><br>
                        <span class="text-muted">Senin - Jumat: 08.00 - 16.00</span><br>
                        <span class="text-muted">Sabtu: 08.00 - 12.00</span>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="d-flex gap-2 mb-2">
                      <i class="fa-solid fa-location-dot" style="color: #d32f2f; font-size: 18px;"></i>
                      <div style="font-size: 13px;">
                        <strong>Lokasi</strong><br>
                        <span class="text-muted">Jl. Rumah Sakit No. 123</span><br>
                        <span class="text-muted">Surabaya, Jawa Timur</span>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-12">
                    <div class="alert alert-info mb-0 p-2" style="font-size: 12px; background: rgba(255,255,255,0.7); border: 1px solid #0288d1;">
                      <i class="fa-solid fa-circle-exclamation me-1"></i>
                      <strong>Tips:</strong> Datang 30 menit lebih awal untuk proses administrasi. Bawa KTP & kartu BPJS (jika ada).
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="row g-2 mt-3">
              <div class="col-6">
                <a href="https://maps.google.com/?q=RS+Hokya+Surabaya" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                  <i class="fa-solid fa-map-location-dot me-1"></i>
                  Lihat Peta
                </a>
              </div>
              <div class="col-6">
                <a href="https://wa.me/6281234567890?text=Halo,%20saya%20butuh%20info%20tentang%20jadwal%20kunjungan" target="_blank" class="btn btn-outline-success btn-sm w-100">
                  <i class="fa-brands fa-whatsapp me-1"></i>
                  Hubungi CS
                </a>
              </div>
            </div>
            <?php endif; ?>

            <div class="d-grid gap-2 mt-3">
              <a href="index.html" class="btn btn-primary">
                <i class="fa-solid fa-home me-1"></i> Kembali ke Beranda
              </a>
              <a href="pendaftaran.php" class="btn btn-outline-secondary">
                <i class="fa-solid fa-plus me-1"></i> Daftar Lagi
              </a>
            </div>
          </div>
        </div>

        <div class="text-center mt-3 small-muted">
          © <?= date('Y') ?> SIMRS Hokya - Melayani dengan Sepenuh Hati
        </div>

      </div>
    </div>
  </div>

  <!-- HIDDEN TICKET UNTUK DOWNLOAD -->
  <div id="downloadTicket">
    <div class="download-ticket">
      <div class="header">
        <div class="logo">🏥 RS HOKYA</div>
        <div class="subtitle">Sistem Informasi Manajemen Rumah Sakit</div>
      </div>

      <div class="queue-number">
        <div class="queue-label">NOMOR ANTRIAN</div>
        <div class="queue-num">
          <?php if($dataAntrian): ?>
            <?= e($dataAntrian['kode_poli']) ?>-<?= str_pad($dataAntrian['nomor'], 3, '0', STR_PAD_LEFT) ?>
          <?php else: ?>
            -
          <?php endif; ?>
        </div>
      </div>

      <div style="margin: 20px 0;">
        <div class="info-row">
          <span class="info-label">Nama Pasien</span>
          <span class="info-value"><?= e($p['nama']) ?></span>
        </div>
        <div class="info-row">
          <span class="info-label">NIK</span>
          <span class="info-value"><?= e($p['nik']) ?></span>
        </div>
        <div class="info-row">
          <span class="info-label">Poli Tujuan</span>
          <span class="info-value"><?= e($p['poli']) ?></span>
        </div>
        <div class="info-row">
          <span class="info-label">Tanggal Kunjungan</span>
          <span class="info-value"><?= date('d/m/Y', strtotime($p['rencana_kunjungan'])) ?></span>
        </div>
        <div class="info-row">
          <span class="info-label">Cara Bayar</span>
          <span class="info-value">
            <?= !empty($p['cara_bayar']) ? e($p['cara_bayar']) : 'Umum' ?>
          </span>
        </div>
      </div>

      <div class="barcode">
        |||| || ||| |||| | || ||| |||| ||
      </div>

      <div class="footer">
        <div style="margin-bottom: 5px;">📍 Jl. Rumah Sakit No. 123, Surabaya</div>
        <div>📞 (031) xxx-xxxx | 📧 info@rshokya.id</div>
        <div style="margin-top: 10px; font-style: italic;">
          Dicetak: <?= date('d/m/Y H:i') ?>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    // Fungsi download ticket
    function downloadTicket() {
      const ticketElement = document.getElementById('downloadTicket');
      const btn = event.target.closest('button');
      
      // Tampilkan loading
      const originalHTML = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Membuat file...';
      btn.disabled = true;

      // Pindahkan element ke posisi visible sementara
      ticketElement.style.display = 'block';
      ticketElement.style.position = 'fixed';
      ticketElement.style.top = '50%';
      ticketElement.style.left = '50%';
      ticketElement.style.transform = 'translate(-50%, -50%)';
      ticketElement.style.zIndex = '-1';

      html2canvas(ticketElement, {
        backgroundColor: '#ffffff',
        scale: 2,
        logging: false
      }).then(canvas => {
        // Sembunyikan lagi
        ticketElement.style.display = 'none';
        ticketElement.style.position = 'fixed';
        ticketElement.style.top = '-9999px';
        ticketElement.style.left = '-9999px';
        ticketElement.style.transform = 'none';

        // Convert to image
        const link = document.createElement('a');
        const queueNum = '<?= $dataAntrian ? e($dataAntrian["kode_poli"]) . "-" . str_pad($dataAntrian["nomor"], 3, "0", STR_PAD_LEFT) : "tiket" ?>';
        link.download = `Antrian_${queueNum}_<?= date("Ymd") ?>.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();

        // Reset button
        btn.innerHTML = originalHTML;
        btn.disabled = false;

        // Show success message
        alert('✅ Nomor antrian berhasil diunduh!\n\nSimpan file ini dan tunjukkan saat datang ke rumah sakit.');
      }).catch(err => {
        console.error('Error generating ticket:', err);
        alert('Gagal membuat file. Silakan screenshot halaman ini.');
        
        // Sembunyikan element
        ticketElement.style.display = 'none';
        ticketElement.style.position = 'fixed';
        ticketElement.style.top = '-9999px';
        ticketElement.style.left = '-9999px';
        
        // Reset button
        btn.innerHTML = originalHTML;
        btn.disabled = false;
      });
    }
  </script>
</body>
</html>