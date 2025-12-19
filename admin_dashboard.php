<?php
session_start();
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
  header("location:index.php?pesan=belum_login");
  exit;
}
$user_display = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard | SIMRS (Satu)</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/admin-dashboard.css" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-dashboard bg-light">

<header class="header sticky-top shadow-sm bg-white">
  <div class="container-fluid px-4 py-2 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
      <img src="assets/img/logo-baru.svg" style="height:42px" alt="logo">
      <strong class="d-none d-md-inline">SIMRS (Satu)</strong>
    </div>
    <div class="d-flex align-items-center gap-3">
      <div class="text-end d-none d-md-block">
        <small class="text-muted">Signed in as</small>
        <div class="fw-semibold"><?= $user_display ?></div>
      </div>
      <a href="logout.php" class="btn btn-outline-danger btn-sm">
        <i class="bi bi-box-arrow-right"></i>
      </a>
    </div>
  </div>
</header>

<div class="container-fluid">
  <div class="row">

    <aside class="col-lg-2 col-md-3 sidebar p-3">
      <ul class="nav nav-pills flex-column gap-1">
        <li class="nav-item"><a class="nav-link active" href="#overview"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="#monitoring"><i class="bi bi-activity me-2"></i>Monitoring</a></li>
        <li class="nav-item"><a class="nav-link" href="riwayat_pasien.php"><i class="bi bi-people me-2"></i>Manajemen Pasien</a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-person-badge me-2"></i>SDM</a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-cash-coin me-2"></i>Billing</a></li>
        <li class="nav-item"><a class="nav-link" href="laporan.php"><i class="bi bi-file-earmark-text me-2"></i>Laporan</a></li>
      </ul>
    </aside>

    <main class="col-lg-10 col-md-9 px-4 py-3">

      <section id="overview" class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h4 class="fw-bold">Dashboard Overview</h4>
          <small class="text-muted">Last updated: <span id="last-updated">just now</span></small>
        </div>

        <div class="row g-3">
          <?php
          $stats = [
            ['Pasien RJ','count-rj','bi-hospital','primary'],
            ['Pasien RI','count-ri','bi-bed','success'],
            ['Pasien IGD','count-igd','bi-heart-pulse','danger'],
            ['BOR','bor-percent','bi-bar-chart','warning']
          ];
          foreach($stats as $s):
          ?>
          <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm border-start border-4 border-<?= $s[3] ?>">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="icon bg-<?= $s[3] ?>">
                  <i class="bi <?= $s[2] ?>"></i>
                </div>
                <div>
                  <small class="text-muted"><?= $s[0] ?></small>
                  <h5 id="<?= $s[1] ?>" class="mb-0">0</h5>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </section>

      <section id="monitoring" class="mb-4">
        <div class="card shadow-sm">
          <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="fw-semibold mb-0">
                <i class="bi bi-activity me-2 text-primary"></i>
                Monitoring Operasional
              </h5>
              <span class="badge bg-success">Live</span>
            </div>

            <div class="row g-3">

             <div class="row g-3">

  <div class="col-md-6">
    <div class="border rounded p-3 h-100">
      <div class="d-flex justify-content-between mb-2">
        <h6 class="fw-semibold mb-0">Antrian Aktif Poli</h6>
        <a href="#" id="btn-refresh" class="btn btn-sm btn-outline-primary">Refresh</a>
      </div>

      <div id="queue-container">
        <div class="p-4 text-muted small">Memuat antrian...</div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="border rounded p-3 h-100">
      <div class="d-flex justify-content-between mb-2">
        <h6 class="fw-semibold mb-0">Beban Dokter</h6>
      </div>

      <ul class="list-group list-group-flush small" id="bebanDokterList">
        <li class="list-group-item text-muted text-center">Memuat data...</li>
      </ul>
    </div>
  </div>

</div>

          </div>
        </div>
      </section>

      <section class="mt-3">
        <div class="row" id="patient-container">
          <div class="col-12 text-center p-4 text-muted"><em>Memuat pasien yang sedang dipanggil...</em></div>
        </div>
      </section>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/admin-dashboard.js"></script>

<script>
function loadMonitoringAntrian(){
  $('#monitoring-antrian').load('get_monitoring_list_antrian.php');
}
loadMonitoringAntrian();
setInterval(loadMonitoringAntrian, 3000);

function loadBebanDokter(){
  fetch('get_beban_dokter.php')
    .then(res => res.json())
    .then(data => {
      const list = document.getElementById('bebanDokterList');
      list.innerHTML = '';

      if(data.length === 0){
        list.innerHTML = '<li class="list-group-item text-muted">Belum ada data</li>';
        return;
      }

      data.forEach(d => {
        list.innerHTML += `
          <li class="list-group-item d-flex justify-content-between">
            ${d.dokter} · ${d.poli}
            <span>${d.total_pasien} pasien</span>
          </li>
        `;
      });
    });
}
loadBebanDokter();
setInterval(loadBebanDokter, 5000);
</script>

</body>
</html>