<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Layar Antrian Poli</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { 
            background-color: #f0f8ff; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            overflow: hidden; 
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Layout Tengah */
        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        /* Kotak Putih Utama */
        .kotak-antrian { 
            width: 80%;
            max-width: 900px;
            border: 0; 
            border-radius: 30px; 
            background: white; 
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            text-align: center;
            padding: 40px;
            position: relative;
        }

        .header-text { font-size: 2.5rem; font-weight: 800; color: #333; margin-bottom: 20px; letter-spacing: 1px;}
        
        .label-kecil { font-size: 1.5rem; color: #777; text-transform: uppercase; letter-spacing: 2px; }
        
        /* Nomor Antrian Besar */
        .nomor-besar { 
            font-size: 10rem; 
            font-weight: 800; 
            color: #0d6efd; 
            line-height: 1; 
            margin: 10px 0;
            text-shadow: 2px 2px 0px #eee;
        }

        /* Nama Pasien */
        .nama-pasien { 
            font-size: 2.5rem; 
            font-weight: 600; 
            color: #333; 
            margin-bottom: 20px;
        }

        /* Kotak Poli Tujuan */
        .poli-box {
            background: #0d6efd;
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 2.2rem;
            font-weight: bold;
            display: inline-block;
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
        }

        /* Footer Berjalan */
        .footer-running { 
            background: #004085; 
            color: white; 
            padding: 15px; 
            font-size: 1.5rem; 
        }

        /* Animasi loading jika data belum ada */
        .loading-text { font-size: 2rem; color: #aaa; font-style: italic; }

    </style>
</head>
<body>

    <div class="main-container">
        
        <div class="header-text">ANTRIAN POLIKLINIK RS HOKYA</div>

        <div class="kotak-antrian">
            <div class="label-kecil">Nomor Antrian</div>

            <div id="layar_no" class="nomor-besar">---</div>
            
            <hr class="my-4" style="opacity: 0.1;">

            <div class="label-kecil" style="font-size: 1rem;">Pasien</div>
            <div id="layar_nama" class="nama-pasien">Menunggu...</div>

            <div class="mt-2">
                <p class="mb-2 text-muted" style="font-size: 1.2rem;">Silakan Menuju:</p>
                <div id="layar_poli" class="poli-box">
                    -- ISTIRAHAT --
                </div>
            </div>

            <div class="mt-3 text-muted">
                Dokter: <span id="layar_dokter" class="fw-bold">-</span>
            </div>
        </div>

    </div>

    <div class="footer-running">
        <marquee scrollamount="10">Selamat Datang di RS HOKYA SEHAT - Budayakan Antri - Jagalah Kebersihan - Harap Menyiapkan Kartu BPJS / Identitas Saat Dipanggil - Terima Kasih.</marquee>
    </div>

    <script>
        // Fungsi untuk mengambil data JSON dari get_antrian.php
        function updateLayar() {
            fetch('get_antrian.php?v=' + new Date().getTime())
                .then(response => response.json()) // Ubah response jadi JSON
                .then(data => {
                    
                    if(data.status == 'ada') {
                        // Jika ada antrian yang dipanggil:
                        
                        // 1. Update Nomor (Misal: G-1)
                        document.getElementById('layar_no').innerText = data.nomor;
                        
                        // 2. Update Nama Pasien
                        document.getElementById('layar_nama').innerText = data.nama_pasien;
                        
                        // 3. Update Kotak Poli (Huruf Kapital Semua)
                        document.getElementById('layar_poli').innerText = "POLI " + data.kode_poli.toUpperCase();
                        
                        // 4. Update Nama Dokter
                        document.getElementById('layar_dokter').innerText = data.dokter_tujuan;

                    } else {
                        // Jika antrian kosong / istirahat:
                        document.getElementById('layar_no').innerText = "---";
                        document.getElementById('layar_nama').innerText = "Belum ada antrian";
                        document.getElementById('layar_poli').innerText = "-- ISTIRAHAT --";
                        document.getElementById('layar_dokter').innerText = "-";
                    }

                })
                .catch(error => console.error('Gagal mengambil data:', error));
        }

        // Jalankan fungsi updateLayar setiap 3 detik (3000 ms)
        setInterval(updateLayar, 3000);

        // Jalankan sekali saat halaman pertama dibuka agar tidak menunggu 3 detik
        updateLayar();
    </script>

</body>
</html>