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
      .card-header { background: linear-gradient(to right, #0d6efd, #0043a8); color: white; }
  </style>
</head>

<body>

  <div class="container mt-5 mb-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow border-0">
          <div class="card-header text-center py-4">
            <h3><i class="fa-solid fa-hospital-user me-2"></i>FORMULIR PENDAFTARAN</h3>
            <p class="mb-0">Pilih Dokter & Jadwal Sesuai Kebutuhan Anda</p>
          </div>
          <div class="card-body p-4">
            
            <form action="proses_daftar.php" method="post">
              
              <h5 class="text-primary mb-3 fw-bold">1. Data Diri Pasien</h5>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">NIK (KTP)</label>
                  <input type="number" name="nik" class="form-control" required placeholder="Masukan 16 digit NIK">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Nama Lengkap</label>
                  <input type="text" name="nama" class="form-control" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Tempat Lahir</label>
                  <input type="text" name="tempat_lahir" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Tanggal Lahir</label>
                  <input type="date" name="tgl_lahir" class="form-control" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Jenis Kelamin</label>
                  <select name="jenis_kelamin" class="form-select" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Nomor HP / WA</label>
                  <input type="text" name="no_hp" class="form-control" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="2" required></textarea>
              </div>

              <hr class="my-4">
              <h5 class="text-primary mb-3 fw-bold">2. Pilih Jadwal Dokter</h5>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-bold">Poli Tujuan</label>
                  <select name="poli" id="poli" class="form-select" required onchange="tampilkanDokter()">
                    <option value="">-- Pilih Poli Dahulu --</option>
                    <option value="Umum">Poli Umum</option>
                    <option value="Gigi">Poli Gigi</option>
                    <option value="Anak">Poli Anak</option>
                    <option value="Kandungan">Poli Kandungan</option>
                    <option value="Jantung">Poli Jantung</option>
                  </select>
                </div>
                
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-bold">Pilih Dokter & Jam Praktek</label>
                  <select name="dokter_tujuan" id="dokter" class="form-select" required>
                    <option value="">-- Dokter Tidak Tersedia --</option>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Rencana Tanggal Periksa</label>
                  <input type="date" name="tgl_kunjungan" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Cara Bayar</label>
                  <select name="cara_bayar" class="form-select" required>
                    <option value="Umum">Umum / Tunai</option>
                    <option value="BPJS">BPJS Kesehatan</option>
                    <option value="Asuransi">Asuransi Lain</option>
                  </select>
                </div>
              </div>

              <div class="mb-3">
                  <label class="form-label">Nomor BPJS (Jika Ada)</label>
                  <input type="text" name="no_bpjs" class="form-control" placeholder="Kosongkan jika Umum">
              </div>

              <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary btn-lg fw-bold">BUAT JANJI SEKARANG</button>
                <a href="index.html" class="btn btn-outline-secondary">Batal</a>
              </div>

            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function tampilkanDokter() {
        var poli = document.getElementById("poli").value;
        var listDokter = document.getElementById("dokter");

        // Kosongkan pilihan dokter dulu
        listDokter.innerHTML = "";

        // Data Dokter dan Jadwal (Bisa ditambah sesuai keinginan)
        // Format: "Nama Dokter|Jam Praktek"
        var dataDokter = {
            "Umum": [
                "dr. Budi Santoso (08:00 - 14:00)|dr. Budi Santoso",
                "dr. Siti Aminah (14:00 - 20:00)|dr. Siti Aminah"
            ],
            "Gigi": [
                "drg. Ratna Sari (09:00 - 13:00)|drg. Ratna Sari",
                "drg. Andi Pratama (16:00 - 20:00)|drg. Andi Pratama"
            ],
            "Anak": [
                "dr. Rina Kartika, Sp.A (08:00 - 12:00)|dr. Rina Kartika, Sp.A",
                "dr. Joko Widodo, Sp.A (15:00 - 19:00)|dr. Joko Widodo, Sp.A"
            ],
            "Kandungan": [
                "dr. Boyke, Sp.OG (10:00 - 14:00)|dr. Boyke, Sp.OG"
            ],
            "Jantung": [
                "dr. Tirta, Sp.JP (09:00 - 15:00)|dr. Tirta, Sp.JP"
            ]
        };

        // Jika poli dipilih, masukkan daftar dokter ke dropdown
        if (dataDokter[poli]) {
            dataDokter[poli].forEach(function(item) {
                var parts = item.split("|"); // Pisahkan Teks Tampil dan Value Database
                var option = document.createElement("option");
                option.text = parts[0];  // Teks yang muncul (ada jamnya)
                option.value = parts[1]; // Yang disimpan ke database (namanya saja)
                listDokter.add(option);
            });
        } else {
            var option = document.createElement("option");
            option.text = "-- Pilih Poli Dahulu --";
            listDokter.add(option);
        }
    }
  </script>

</body>
</html>