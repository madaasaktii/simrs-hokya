<?php
include 'koneksi.php';

// Ambil pasien status 'Dipanggil'
$query = mysqli_query($koneksi, "SELECT * FROM pasien WHERE status_antrian = 'Dipanggil' ORDER BY waktu_panggil DESC");

if(mysqli_num_rows($query) == 0){
    echo '<div class="p-4 text-center text-muted small">
            <em>Tidak ada pasien antri di poli ini.</em>
          </div>';
}

while($row = mysqli_fetch_array($query)){
?>
    <div class="list-group-item list-group-item-action p-3 pasien-item">
        <div class="d-flex w-100 justify-content-between align-items-center">
            <h6 class="mb-1 fw-bold text-primary"><?= $row['nama'] ?></h6>
            <small class="badge bg-warning text-dark"><?= $row['no_antrian'] ?></small>
        </div>
        <p class="mb-1 small text-muted">Poli: <?= $row['poli'] ?></p>
        
        <button class="btn btn-sm btn-outline-primary w-100 mt-2 btn-periksa" data-id="<?= $row['id'] ?>">
            <i class="fa-solid fa-arrow-right me-1"></i> Periksa Pasien
        </button>
    </div>
<?php } ?>