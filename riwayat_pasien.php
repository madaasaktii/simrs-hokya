<?php 
session_start();
include 'koneksi.php';

// Cek Login (Security)
if($_SESSION['status'] != "login"){
    header("location:login.php?pesan=belum_login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat & EMR Pasien</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f0f2f5; }
        .card-header { background: white; border-bottom: 1px solid #e3e6f0; }
        .table th { background-color: #4e73df; color: white; text-align: center; vertical-align: middle; }
        .table td { vertical-align: middle; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="admin_poli.php"><i class="fa-solid fa-hospital me-2"></i>SIMRS HOKYA</a>
            <div class="ms-auto">
                <a href="admin_poli.php" class="btn btn-light btn-sm fw-bold text-primary me-2">Kembali ke Antrian</a>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary"><i class="fa-solid fa-file-medical me-2"></i>Data Kunjungan Pasien</h5>
                
                <form action="" method="get" class="d-flex">
                    <input type="text" name="cari" class="form-control form-control-sm me-2" placeholder="Cari Nama / NIK...">
                    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                </form>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="15%">Tanggal</th>
                                <th width="10%">Antrian</th>
                                <th>Identitas Pasien</th>
                                <th width="15%">Poli Tujuan</th>
                                <th width="10%">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Logika Pencarian
                            if(isset($_GET['cari'])){
                                $cari = $_GET['cari'];
                                $query = mysqli_query($koneksi, "SELECT * FROM pasien WHERE nama LIKE '%$cari%' OR nik LIKE '%$cari%' ORDER BY tanggal_daftar DESC, no_antrian DESC");
                            } else {
                                $query = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY tanggal_daftar DESC, no_antrian DESC");
                            }

                            while($data = mysqli_fetch_array($query)){
                            ?>
                            
                            <tr>
                                <td class="text-center"><?= $data['tanggal_daftar']; ?></td>
                                <td class="text-center fw-bold text-primary" style="font-size: 1.2em;"><?= $data['no_antrian']; ?></td>
                                <td>
                                    <strong><?= $data['nama']; ?></strong><br>
                                    <small class="text-muted"><i class="fa-solid fa-id-card me-1"></i> NIK: <?= $data['nik']; ?></small>
                                </td>
                                <td class="text-center"><?= $data['poli']; ?></td>
                                <td class="text-center">
                                    <?php if($data['status_antrian'] == 'Selesai'){ ?>
                                        <span class="badge bg-success">Selesai</span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modal<?= $data['id']; ?>">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="modal<?= $data['id']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Rekam Medis: <?= $data['nama']; ?></h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-5 border-end">
                                                    <h6 class="text-primary fw-bold">Biodata</h6>
                                                    <p class="mb-1"><strong>NIK:</strong> <?= $data['nik']; ?></p>
                                                    <p class="mb-1"><strong>Tgl Lahir:</strong> <?= $data['tgl_lahir']; ?></p>
                                                    <p class="mb-1"><strong>Alamat:</strong> <?= $data['alamat']; ?></p>
                                                    <p class="mb-1"><strong>No HP:</strong> <?= $data['no_hp']; ?></p>
                                                    <hr>
                                                    <p class="mb-1"><strong>Cara Bayar:</strong> <?= $data['cara_bayar']; ?></p>
                                                    <p class="mb-1"><strong>No BPJS:</strong> <?= $data['no_bpjs'] ? $data['no_bpjs'] : '-'; ?></p>
                                                </div>
                                                <div class="col-md-7">
                                                    <h6 class="text-primary fw-bold">Pemeriksaan Dokter</h6>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label small text-muted">Diagnosa</label>
                                                        <textarea class="form-control bg-light" rows="3" readonly><?= $data['diagnosa'] ? $data['diagnosa'] : 'Belum diisi dokter'; ?></textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label small text-muted">Resep Obat</label>
                                                        <textarea class="form-control bg-light" rows="3" readonly><?= $data['resep'] ? $data['resep'] : '-'; ?></textarea>
                                                    </div>

                                                    <div class="alert alert-info py-2 small">
                                                        <i class="fa-solid fa-calendar-check me-1"></i> Berkunjung pada: <?= $data['tgl_kunjungan']; ?> (Poli <?= $data['poli']; ?>)
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>