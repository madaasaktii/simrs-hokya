<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Halaman Utama | SIMRS HOKYA</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #42a5f5 0%, #478ed1 100%);
      min-height: 100vh;
      padding: 20px 0;
    }

    .option-box {
      height: 180px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
      cursor: pointer;
      transition: all 0.3s ease;
      padding: 20px;
    }

    .option-box:hover {
      transform: translateY(-10px);
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
    }

    .option-box i {
      font-size: 3rem;
      margin-bottom: 15px;
      color: #1976d2;
    }

    .option-box a {
      text-decoration: none;
      font-size: 1.3rem;
      font-weight: 600;
      color: #333;
      text-align: center;
    }

    .option-box:hover a {
      color: #1976d2;
    }

    .option-box.staff {
      border-top: 5px solid #1976d2;
    }

    .option-box.staff i {
      color: #1976d2;
    }

    .option-box.pasien {
      border-top: 5px solid #38ef7d;
    }

    .option-box.pasien i {
      color: #38ef7d;
    }

    .option-box.layar {
      border-top: 5px solid #ff9800;
    }

    .option-box.layar i {
      color: #ff9800;
    }

    .container {
      max-width: 900px;
      margin-top: 50px;
    }

    .title {
      text-align: center;
      margin-bottom: 50px;
      color: white;
    }

    .title h1 {
      font-weight: 700;
      font-size: 2.5rem;
      margin-bottom: 10px;
      text-shadow: 2px 2px 8px rgba(0,0,0,0.2);
    }

    .title p {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    @media (max-width: 768px) {
      .title h1 {
        font-size: 2rem;
      }
      .option-box {
        height: 160px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="title">
    <h1><i class="bi bi-hospital"></i> RS HOKYA SEHAT</h1>
    <p>Sistem Informasi Manajemen Rumah Sakit</p>
  </div>

  <div class="row g-4">
    <!-- Kotak Login Staff -->
    <div class="col-md-4 mb-4">
      <div class="option-box staff" onclick="window.location.href='login_staff.php'">
        <i class="bi bi-person-badge"></i>
        <a href="login_staff.php">Login Staff</a>
      </div>
    </div>

    <!-- Kotak Pendaftaran Pasien -->
    <div class="col-md-4 mb-4">
      <div class="option-box pasien" onclick="window.location.href='pendaftaran.php'">
        <i class="bi bi-person-plus"></i>
        <a href="pendaftaran.php">Pendaftaran Pasien</a>
      </div>
    </div>

    <!-- Kotak Layar Antrian -->
    <div class="col-md-4 mb-4">
      <div class="option-box layar" onclick="window.location.href='layar_antrian.php'">
        <i class="bi bi-display"></i>
        <a href="layar_antrian.php">Layar Antrian</a>
      </div>
    </div>
  </div>

  <div class="text-center mt-5">
    <p class="text-white opacity-75">
      <i class="bi bi-clock"></i> Buka setiap hari | <i class="bi bi-telephone"></i> Hubungi: (021) 1234-5678
    </p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>