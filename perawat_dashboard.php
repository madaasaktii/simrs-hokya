<?php
session_start();
// Proteksi halaman
if (!isset($_SESSION['status']) || $_SESSION['role'] != "perawat") {
    header("location:login.php?pesan=belum_login");
    exit();
}

// Include koneksi
include 'koneksi.php';

// Inisialisasi variabel untuk navigasi sidebar
$page = isset($_GET['p']) ? $_GET['p'] : 'dashboard';

// Mapping nama poli berdasarkan kode
$poli_names = [
    'A' => 'Anak',
    'J' => 'Jantung', 
    'S' => 'Syaraf',
    'P' => 'Penyakit Dalam',
    'G' => 'Gigi'
];

// Fungsi untuk menghitung antrean berdasarkan status
function hitungAntrean($conn, $status, $hari_ini = false) {
    $query = "SELECT COUNT(*) as total FROM antrian WHERE status = ?";
    $params = [$status];
    $types = 's';
    
    if ($hari_ini) {
        $today = date('Y-m-d');
        $query .= " AND hari = ?";
        $params[] = $today;
        $types .= 's';
    }
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Hitung data untuk dashboard
$antrean_mendatang = hitungAntrean($conn, 'waiting', false);
$dipanggil = hitungAntrean($conn, 'called', false);
$sedang_diperiksa = hitungAntrean($conn, 'ongoing', false);
$selesai_hari_ini = hitungAntrean($conn, 'done', true);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Perawat - RS HOKYA SEHAT</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f0f4f8; font-family: 'Poppins', sans-serif; }
        .sidebar { background: #1977cc; color: white; min-height: 100vh; position: fixed; width: inherit; }
        .nav-link { color: rgba(255,255,255,0.8); padding: 15px 20px; }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,0.15); border-left: 4px solid #fff; }
        .main-content { margin-left: 16.666667%; padding: 30px; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .bg-gradient-blue { background: linear-gradient(45deg, #1977cc, #59b2ff); color: white; }
        .bg-gradient-orange { background: linear-gradient(45deg, #ff9800, #ff6f00); color: white; }
        
        /* Auto refresh indicator */
        .refresh-indicator {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
            background: rgba(25, 119, 204, 0.9);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            display: none;
            animation: fadeInOut 0.5s;
        }
        
        @keyframes fadeInOut {
            0%, 100% { opacity: 0; }
            50% { opacity: 1; }
        }
    </style>
</head>
<body>

<!-- Auto Refresh Indicator -->
<div class="refresh-indicator" id="refreshIndicator">
    <i class="bi bi-arrow-clockwise"></i> Memperbarui data...
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-0">
            <div class="p-4 text-center border-bottom border-white border-opacity-25">
                <h5 class="fw-bold mb-0 text-white">HOKYA SEHAT</h5>
            </div>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a href="perawat_dashboard.php?p=dashboard" class="nav-link <?php echo $page == 'dashboard' ? 'active' : ''; ?>">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="perawat_dashboard.php?p=antrean" class="nav-link <?php echo $page == 'antrean' ? 'active' : ''; ?>">
                        <i class="bi bi-people me-2"></i> Antrean Poli
                    </a>
                </li>
                <li class="nav-item">
                    <a href="perawat_dashboard.php?p=selesai" class="nav-link <?php echo $page == 'selesai' ? 'active' : ''; ?>">
                        <i class="bi bi-person-check me-2"></i> Pasien Selesai
                    </a>
                </li>
                <li class="nav-item mt-5">
                    <a href="logout.php" class="nav-link text-warning"><i class="bi bi-box-arrow-left me-2"></i> Keluar (Logout)</a>
                </li>
            </ul>
        </div>

        <div class="col-md-10 main-content">
            <?php
            // Tampilkan pesan sukses/gagal dari parameter GET
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == 'anamnesis_berhasil') {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                    echo 'Data anamnesis berhasil disimpan dan pasien diteruskan ke dokter!';
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                } elseif ($_GET['pesan'] == 'anamnesis_gagal') {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                    echo 'Gagal menyimpan data anamnesis. Error: ' . (isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '');
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                }
            }
            ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-dark">Dashboard Perawat</h3>
                <div class="badge bg-white text-dark shadow-sm p-2 px-3 border-0" style="border-radius: 10px;">
                    <i class="bi bi-person-circle me-2 text-primary"></i> Perawat <?php echo htmlspecialchars($_SESSION['username'] ?? 'Siti'); ?>
                </div>
            </div>

            <?php if ($page == 'dashboard') : ?>
                <div class="row g-4 mb-4 text-center">
                    <div class="col-md-3">
                        <div class="card card-custom p-4">
                            <h6 class="text-muted small">MENUNGGU</h6>
                            <h2 class="fw-bold text-primary"><?php echo $antrean_mendatang; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom p-4 bg-gradient-orange">
                            <h6 class="small opacity-75">DIPANGGIL</h6>
                            <h2 class="fw-bold text-white"><?php echo $dipanggil; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom p-4 bg-gradient-blue">
                            <h6 class="small opacity-75">SEDANG DIPERIKSA</h6>
                            <h2 class="fw-bold text-white"><?php echo $sedang_diperiksa; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom p-4">
                            <h6 class="text-muted small">SELESAI HARI INI</h6>
                            <h2 class="fw-bold text-success"><?php echo $selesai_hari_ini; ?></h2>
                        </div>
                    </div>
                </div>

                <!-- List untuk Antrean Mendatang (waiting) -->
                <div class="card card-custom mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold mb-0">Antrean Pasien (Tunggu Dipanggil)</h5>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No. Antrean</th>
                                    <th>Data Pasien</th>
                                    <th>Poliklinik</th>
                                    <th>Dokter</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_menunggu = "SELECT * FROM antrian WHERE status = 'waiting' ORDER BY created_at ASC";
                                $result_menunggu = $conn->query($query_menunggu);
                                if ($result_menunggu && $result_menunggu->num_rows > 0) {
                                    while ($row = $result_menunggu->fetch_assoc()) {
                                        $no_antrean = strtoupper($row['kode_poli']) . str_pad($row['nomor'], 3, '0', STR_PAD_LEFT);
                                        $poli_name = $poli_names[$row['kode_poli']] ?? $row['kode_poli'];
                                        $pasien_id = $row['pasien_id'] ?? 'N/A';
                                        echo "<tr>
                                            <td class='ps-4'><span class='badge bg-primary'>$no_antrean</span></td>
                                            <td><strong>" . htmlspecialchars($row['nama_pasien']) . "</strong><br><small class='text-muted'>ID: $pasien_id</small></td>
                                            <td>$poli_name</td>
                                            <td>" . htmlspecialchars($row['dokter_tujuan'] ?? '-') . "</td>
                                            <td><span class='badge bg-warning text-dark'>Menunggu</span></td>
                                            <td class='text-center'>
                                                <button class='btn btn-primary btn-sm rounded-pill px-3 btn-panggil' data-id='{$row['id']}' data-nama='" . htmlspecialchars($row['nama_pasien']) . "'>
                                                    <i class='bi bi-megaphone me-1'></i> Panggil & Periksa
                                                </button>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center text-muted'>Tidak ada antrean menunggu saat ini.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- List untuk Dipanggil (called) - Sedang Anamnesis -->
                <div class="card card-custom mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold mb-0">Pasien Dipanggil (Sedang Anamnesis)</h5>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No. Antrean</th>
                                    <th>Data Pasien</th>
                                    <th>Poliklinik</th>
                                    <th>Dokter</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_dipanggil = "SELECT * FROM antrian WHERE status = 'called' ORDER BY created_at ASC";
                                $result_dipanggil = $conn->query($query_dipanggil);
                                if ($result_dipanggil && $result_dipanggil->num_rows > 0) {
                                    while ($row = $result_dipanggil->fetch_assoc()) {
                                        $no_antrean = strtoupper($row['kode_poli']) . str_pad($row['nomor'], 3, '0', STR_PAD_LEFT);
                                        $poli_name = $poli_names[$row['kode_poli']] ?? $row['kode_poli'];
                                        $pasien_id = $row['pasien_id'] ?? 'N/A';
                                        echo "<tr>
                                            <td class='ps-4'><span class='badge bg-warning'>$no_antrean</span></td>
                                            <td><strong>" . htmlspecialchars($row['nama_pasien']) . "</strong><br><small class='text-muted'>ID: $pasien_id</small></td>
                                            <td>$poli_name</td>
                                            <td>" . htmlspecialchars($row['dokter_tujuan'] ?? '-') . "</td>
                                            <td><span class='badge bg-warning text-dark'>Dipanggil</span></td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center text-muted'>Tidak ada pasien dipanggil saat ini.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- List untuk Sedang Diperiksa (ongoing) -->
                <div class="card card-custom mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold mb-0">Pasien Sedang Diperiksa Dokter</h5>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No. Antrean</th>
                                    <th>Data Pasien</th>
                                    <th>Poliklinik</th>
                                    <th>Dokter</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_sedang = "SELECT * FROM antrian WHERE status = 'ongoing' ORDER BY created_at ASC";
                                $result_sedang = $conn->query($query_sedang);
                                if ($result_sedang && $result_sedang->num_rows > 0) {
                                    while ($row = $result_sedang->fetch_assoc()) {
                                        $no_antrean = strtoupper($row['kode_poli']) . str_pad($row['nomor'], 3, '0', STR_PAD_LEFT);
                                        $poli_name = $poli_names[$row['kode_poli']] ?? $row['kode_poli'];
                                        $pasien_id = $row['pasien_id'] ?? 'N/A';
                                        echo "<tr>
                                            <td class='ps-4'><span class='badge bg-info'>$no_antrean</span></td>
                                            <td><strong>" . htmlspecialchars($row['nama_pasien']) . "</strong><br><small class='text-muted'>ID: $pasien_id</small></td>
                                            <td>$poli_name</td>
                                            <td>" . htmlspecialchars($row['dokter_tujuan'] ?? '-') . "</td>
                                            <td><span class='badge bg-info text-white'>Sedang Diperiksa</span></td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center text-muted'>Tidak ada pasien sedang diperiksa saat ini.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- List untuk Selesai Hari Ini (done) -->
                <div class="card card-custom mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold mb-0">Pasien Selesai Hari Ini</h5>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No. Antrean</th>
                                    <th>Data Pasien</th>
                                    <th>Poliklinik</th>
                                    <th>Dokter</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $today = date('Y-m-d');
                                $stmt_selesai_today = $conn->prepare("SELECT * FROM antrian WHERE status = 'done' AND hari = ? ORDER BY created_at ASC");
                                $stmt_selesai_today->bind_param('s', $today);
                                $stmt_selesai_today->execute();
                                $result_selesai_today = $stmt_selesai_today->get_result();
                                
                                if ($result_selesai_today && $result_selesai_today->num_rows > 0) {
                                    while ($row = $result_selesai_today->fetch_assoc()) {
                                        $no_antrean = strtoupper($row['kode_poli']) . str_pad($row['nomor'], 3, '0', STR_PAD_LEFT);
                                        $poli_name = $poli_names[$row['kode_poli']] ?? $row['kode_poli'];
                                        $pasien_id = $row['pasien_id'] ?? 'N/A';
                                        echo "<tr>
                                            <td class='ps-4'><span class='badge bg-success'>$no_antrean</span></td>
                                            <td><strong>" . htmlspecialchars($row['nama_pasien']) . "</strong><br><small class='text-muted'>ID: $pasien_id</small></td>
                                            <td>$poli_name</td>
                                            <td>" . htmlspecialchars($row['dokter_tujuan'] ?? '-') . "</td>
                                            <td><span class='badge bg-success text-white'>Selesai</span></td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center text-muted'>Tidak ada pasien selesai hari ini.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($page == 'antrean') : ?>
                <div class="card card-custom p-4">
                    <h5 class="fw-bold"><i class="bi bi-people me-2"></i> Manajemen Antrean Seluruh Poli</h5>
                    <p class="text-muted">Menampilkan semua antrean aktif di RS Hokya Sehat secara realtime.</p>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>No. Antrean</th>
                                    <th>Nama Pasien</th>
                                    <th>Poliklinik</th>
                                    <th>Dokter</th>
                                    <th>Waktu Daftar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query untuk semua antrean
                                $query_all = "SELECT * FROM antrian ORDER BY created_at DESC";
                                $result_all = $conn->query($query_all);
                                if ($result_all && $result_all->num_rows > 0) {
                                    while ($row = $result_all->fetch_assoc()) {
                                        $no_antrean = strtoupper($row['kode_poli']) . str_pad($row['nomor'], 3, '0', STR_PAD_LEFT);
                                        $poli_name = $poli_names[$row['kode_poli']] ?? $row['kode_poli'];
                                        
                                        $status_badge = '';
                                        $status_text = '';
                                        if ($row['status'] == 'waiting') {
                                            $status_badge = 'bg-warning text-dark';
                                            $status_text = 'Menunggu';
                                        } elseif ($row['status'] == 'called') {
                                            $status_badge = 'bg-primary text-white';
                                            $status_text = 'Dipanggil';
                                        } elseif ($row['status'] == 'ongoing') {
                                            $status_badge = 'bg-info text-white';
                                            $status_text = 'Sedang Diperiksa';
                                        } elseif ($row['status'] == 'done') {
                                            $status_badge = 'bg-success text-white';
                                            $status_text = 'Selesai';
                                        } elseif ($row['status'] == 'cancelled') {
                                            $status_badge = 'bg-secondary text-white';
                                            $status_text = 'Dibatalkan';
                                        }
                                        
                                        echo "<tr>
                                            <td>$no_antrean</td>
                                            <td>" . htmlspecialchars($row['nama_pasien']) . "</td>
                                            <td>$poli_name</td>
                                            <td>" . htmlspecialchars($row['dokter_tujuan'] ?? '-') . "</td>
                                            <td>{$row['created_at']}</td>
                                            <td><span class='badge $status_badge'>$status_text</span></td>
                                            <td>";
                                        
                                        if ($row['status'] == 'waiting') {
                                            echo "<button class='btn btn-sm btn-primary btn-panggil' data-id='{$row['id']}' data-nama='" . htmlspecialchars($row['nama_pasien']) . "'>Panggil</button> ";
                                        }
                                        
                                        echo "</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center text-muted'>Tidak ada antrean saat ini.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($page == 'selesai') : ?>
                <div class="card card-custom p-4">
                    <h5 class="fw-bold text-success"><i class="bi bi-person-check me-2"></i> Data Pasien Selesai</h5>
                    <p class="text-muted">Daftar pasien yang sudah melalui tahap anamnesis dan pemeriksaan dokter.</p>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Antrean</th>
                                    <th>Nama Pasien</th>
                                    <th>Poliklinik</th>
                                    <th>Dokter</th>
                                    <th>Waktu Daftar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query untuk pasien selesai
                                $query_selesai = "SELECT * FROM antrian WHERE status = 'done' ORDER BY created_at DESC";
                                $result_selesai = $conn->query($query_selesai);
                                $no = 1;
                                if ($result_selesai && $result_selesai->num_rows > 0) {
                                    while ($row = $result_selesai->fetch_assoc()) {
                                        $no_antrean = strtoupper($row['kode_poli']) . str_pad($row['nomor'], 3, '0', STR_PAD_LEFT);
                                        $poli_name = $poli_names[$row['kode_poli']] ?? $row['kode_poli'];
                                        echo "<tr>
                                            <td>$no</td>
                                            <td>$no_antrean</td>
                                            <td>" . htmlspecialchars($row['nama_pasien']) . "</td>
                                            <td>$poli_name</td>
                                            <td>" . htmlspecialchars($row['dokter_tujuan'] ?? '-') . "</td>
                                            <td>{$row['created_at']}</td>
                                            <td><span class='badge bg-success text-white'>Selesai</span></td>
                                        </tr>";
                                        $no++;
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center text-muted'>Tidak ada pasien selesai saat ini.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAnamnesis" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-clipboard2-pulse text-primary me-2"></i>Anamnesis Awal Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="simpan_anamnesis.php" method="POST">
                    <input type="hidden" name="id_antrian" id="id_antrian">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Pasien</label>
                            <input type="text" class="form-control bg-light" id="nama_pasien" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Berat Badan (kg)</label>
                            <input type="number" name="berat_badan" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Tinggi Badan (cm)</label>
                            <input type="number" name="tinggi_badan" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Tekanan Darah (mmHg)</label>
                            <input type="text" name="tekanan_darah" class="form-control" placeholder="120/80">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Suhu Tubuh (°C)</label>
                            <input type="text" name="suhu_tubuh" class="form-control" placeholder="36.5">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Nadi (bpm)</label>
                            <input type="number" name="nadi" class="form-control" placeholder="80">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Keluhan Utama</label>
                            <textarea name="keluhan" class="form-control" rows="3" placeholder="Deskripsikan keluhan pasien..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-0 pt-3">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan & Teruskan ke Dokter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto refresh halaman setiap 5 detik (hanya jika tidak ada modal yang terbuka)
    let autoRefreshInterval;
    
    function startAutoRefresh() {
        autoRefreshInterval = setInterval(function() {
            // Cek apakah ada modal yang sedang terbuka
            const modalOpen = document.querySelector('.modal.show');
            
            if (!modalOpen) {
                // Tampilkan indikator refresh
                const indicator = document.getElementById('refreshIndicator');
                indicator.style.display = 'block';
                
                // Reload halaman setelah 500ms
                setTimeout(function() {
                    location.reload();
                }, 500);
            }
        }, 5000); // 5 detik
    }
    
    // Mulai auto refresh saat halaman dimuat
    startAutoRefresh();
    
    // Hentikan auto refresh ketika modal dibuka
    document.getElementById('modalAnamnesis').addEventListener('show.bs.modal', function() {
        clearInterval(autoRefreshInterval);
    });
    
    // Mulai lagi auto refresh ketika modal ditutup
    document.getElementById('modalAnamnesis').addEventListener('hidden.bs.modal', function() {
        startAutoRefresh();
    });

    // Handle tombol Panggil & Periksa
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-panggil') || e.target.closest('.btn-panggil')) {
            const btn = e.target.classList.contains('btn-panggil') ? e.target : e.target.closest('.btn-panggil');
            const id = btn.dataset.id;
            const nama = btn.dataset.nama;
            
            // Ubah status menjadi 'called' terlebih dahulu
            fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id + '&status=called'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Jika berhasil ubah status, buka modal anamnesis
                    document.getElementById('id_antrian').value = id;
                    document.getElementById('nama_pasien').value = nama;
                    
                    const modal = new bootstrap.Modal(document.getElementById('modalAnamnesis'));
                    modal.show();
                    
                    // Reload halaman setelah modal ditutup untuk refresh data
                    document.getElementById('modalAnamnesis').addEventListener('hidden.bs.modal', function () {
                        location.reload();
                    }, { once: true });
                } else {
                    alert('Gagal memanggil pasien: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memanggil pasien');
            });
        }
    });
</script>
</body>
</html>
<?php
// Tutup koneksi
$conn->close();
?>