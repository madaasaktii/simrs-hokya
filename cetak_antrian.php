<?php
if (isset($_GET['antrian']) && isset($_GET['nama']) && isset($_GET['poli']) && isset($_GET['waktu'])) {
    $antrian = $_GET['antrian'];
    $nama = $_GET['nama'];
    $poli = $_GET['poli'];
    $waktu = $_GET['waktu'];
} else {
    echo "Data tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Cetak Antrian - SIMRS</title>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <style>
      body {
          background-color: #f2f2f2;
          font-family: 'Arial', sans-serif;
          padding: 50px 0;
      }
      .container {
          max-width: 600px;
          margin: 0 auto;
          background-color: #fff;
          border-radius: 10px;
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
          padding: 30px;
      }
      .header {
          text-align: center;
          margin-bottom: 30px;
      }
      .antrian-box {
          font-size: 4em;
          font-weight: bold;
          color: #0043a8;
          margin: 20px 0;
          text-align: center;
          background: #e2f3fc;
          padding: 20px;
          border-radius: 8px;
      }
      .details {
          font-size: 1.2em;
          margin-top: 20px;
          text-align: left;
      }
      .details span {
          font-weight: bold;
      }
      .footer {
          text-align: center;
          margin-top: 40px;
          font-size: 1.1em;
      }
      .btn-print {
          background-color: #0043a8;
          color: white;
          padding: 10px 20px;
          font-size: 1.1em;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          text-decoration: none;
      }
  </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Nomor Antrian Anda</h1>
        <p>Terima kasih, <?php echo htmlspecialchars($nama); ?>. Berikut adalah nomor antrian Anda:</p>
    </div>

    <div class="antrian-box">
        <?php echo htmlspecialchars($antrian); ?> <!-- Menampilkan nomor antrian -->
    </div>

    <div class="details">
        <p><span>Nama Pasien:</span> <?php echo htmlspecialchars($nama); ?></p>
        <p><span>Nama Poli:</span> <?php echo htmlspecialchars($poli); ?></p>
        <p><span>Waktu Mendaftar:</span> <?php echo htmlspecialchars($waktu); ?></p>
    </div>

    <div class="footer">
        <p>Silakan menunggu di ruang poli terkait.</p>
        <a href="javascript:window.print()" class="btn-print">Cetak Antrian</a>
    </div>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
