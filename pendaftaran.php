<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendaftaran Pasien - SIMRS</title>

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body { background-color: #f8f9fa; }

    .card-header {
      background: linear-gradient(to right, #0d6efd, #0043a8);
      color: white;
      border-bottom: 0;
    }

    .card { border-radius: 18px; }
    .form-control, .form-select { border-radius: 12px; }
    .section-title { font-weight: 800; }

    /* HP optimization */
    @media (max-width: 576px) {
      .card-header h3 { font-size: 1.1rem; }
      .card-header p  { font-size: .9rem; }

      label.form-label {
        font-weight: 600;
        font-size: .95rem;
      }

      .form-control, .form-select {
        padding-top: .7rem;
        padding-bottom: .7rem;
        font-size: 1rem;
        border-radius: 12px;
      }

      /* tombol sticky biar gampang diklik */
      .btn-submit {
        position: sticky;
        bottom: 12px;
        z-index: 10;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0,0,0,.15);
      }
    }
  </style>

  <style>
  /* --- KODE KOTAK BUBBLE --- */
  .hero-box {
    background: rgba(255, 255, 255, 0.85); /* Warna Putih Transparan */
    padding: 30px;            /* Jarak teks dari pinggir kotak */
    border-radius: 20px;      /* Membuat sudut tumpul/membulat */
    box-shadow: 0 4px 15px rgba(0,0,0,0.1); /* Efek bayangan */
    max-width: 90%;           /* Lebar maksimal 90% layar HP */
    margin: 0 auto;           /* Posisi tengah otomatis */
    backdrop-filter: blur(5px); /* Efek kaca buram di belakangnya */
  }

  /* Warna Teks di dalam kotak */
  .hero-box h2 {
    color: #0056b3 !important; /* Biru Tua Jelas */
    font-weight: 800;
  }
  
  .hero-box p {
    color: #333 !important;    /* Hitam/Abu Gelap Jelas */
    font-weight: 600;
  }
</style>
</head>

<body>

  <div class="container py-4 py-md-5">
    <div class="row justify-content-center">
      <div class="col-lg-7 col-xl-6">

        <div class="card shadow border-0">
          <div class="card-header text-center py-4 px-3">
            <h3 class="h5 h-md-4 fw-bold mb-1">
              <i class="fa-solid fa-hospital-user me-2"></i>Pendaftaran Pasien
            </h3>
            <p class="small mb-0">Isi data untuk membuat janji berobat</p>
          </div>

          <div class="card-body p-3 p-md-4">

            <form action="proses_daftar.php" method="post" autocomplete="on">

              <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge text-bg-primary">1</span>
                <h5 class="text-primary mb-0 section-title">Data Diri Pasien</h5>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">NIK (KTP)</label>
                  <input
                    type="text"
                    name="nik"
                    class="form-control"
                    inputmode="numeric"
                    pattern="\d{16}"
                    maxlength="16"
                    placeholder="16 digit NIK"
                    required
                  >
                  <div class="form-text">Contoh: 357xxxxxxxxxxxxx (16 digit)</div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Nama Lengkap</label>
                  <input type="text" name="nama" class="form-control" required placeholder="Nama sesuai identitas">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Tempat Lahir</label>
                  <input type="text" name="tempat_lahir" class="form-control" required placeholder="Contoh: Surabaya">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Tanggal Lahir</label>
                  <input type="date" name="tgl_lahir" class="form-control" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Jenis Kelamin</label>
                  <select name="jenis_kelamin" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Nomor HP / WA</label>
                  <input type="tel" name="no_hp" class="form-control" required placeholder="08xxxxxxxxxx">
                </div>

                <div class="col-12">
                  <label class="form-label">Alamat Lengkap</label>
                  <textarea name="alamat" class="form-control" rows="2" required placeholder="Tuliskan alamat lengkap"></textarea>
                </div>
              </div>

              <hr class="my-4">

              <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge text-bg-primary">2</span>
                <h5 class="text-primary mb-0 section-title">Rencana Kunjungan</h5>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-bold">Poli Tujuan</label>
                  <select name="poli" id="poli" class="form-select" required>
                    <option value="" selected>-- Pilih Poli --</option>
                    <option value="Poli Anak">Poli Anak</option>
                    <option value="Poli Jantung">Poli Jantung</option>
                    <option value="Poli Syaraf">Poli Syaraf</option>
                    <option value="Poli Penyakit Dalam">Poli Penyakit Dalam</option>
                    <option value="Poli Gigi">Poli Gigi</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Rencana Tanggal Kunjungan</label>
                  <input type="date" name="tgl_kunjungan" class="form-control" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Cara Bayar</label>
                  <select name="cara_bayar" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="Umum">Umum / Tunai</option>
                    <option value="BPJS">BPJS Kesehatan</option>
                    <option value="Asuransi">Asuransi Lain</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Nomor BPJS (Jika Ada)</label>
                  <input type="text" name="no_bpjs" class="form-control" placeholder="Kosongkan jika Umum">
                </div>
              </div>

              <div class="alert alert-info mt-4">
                <small><i class="fa-solid fa-circle-info me-1"></i> <b>Catatan:</b> Nomor antrian dan jadwal dokter akan ditentukan oleh petugas saat Anda datang ke rumah sakit.</small>
              </div>

              <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary btn-lg fw-bold w-100 btn-submit">
                  DAFTAR SEKARANG
                </button>
                <a href="index.html" class="btn btn-outline-secondary w-100">Batal</a>
              </div>

            </form>

          </div>
        </div>

        <div class="text-center text-secondary small mt-3">
          © SIMRS Demo • Pendaftaran Pasien
        </div>

      </div>
    </div>
  </div>

<div id="reminderBox"></div>

<script src="assets/js/pwa-notif.js"></script>
<script>
  // mount UI pengingat di halaman pendaftaran
  initReminderUI({
    mountId: "reminderBox",
    poli: "",
    queue: ""
  });
</script>

</body>
</html>