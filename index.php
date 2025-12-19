<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Halaman Utama | SIMRS (Satu)</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .option-box {
      height: 150px;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f1f1f1;
      border-radius: 8px;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      transition: transform 0.3s;
    }

    .option-box:hover {
      transform: scale(1.05);
    }

    .option-box a {
      text-decoration: none;
      font-size: 1.5rem;
      font-weight: bold;
      color: #333;
    }

    .container {
      max-width: 600px;
      margin-top: 50px;
    }

    .title {
      text-align: center;
      margin-bottom: 30px;
    }
  </style>
</head>
<body class="bg-light">

<div class="container">
  <div class="title">
    <h1>Selamat Datang di SIMRS</h1>
    <p>Pilih salah satu opsi di bawah ini untuk melanjutkan.</p>
  </div>

  <div class="row">
    <!-- Kotak Login Staff -->
    <div class="col-md-6 mb-4">
      <div class="option-box" onclick="window.location.href='login_staff.php'">
        <a href="#">Login sebagai Staff</a>
      </div>
    </div>

    <!-- Kotak Pendaftaran Pasien -->
    <div class="col-md-6 mb-4">
      <div class="option-box" onclick="window.location.href='pendaftaran.php'">
        <a href="#">Pendaftaran Pasien</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
