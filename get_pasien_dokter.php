<?php
include 'koneksi.php';

// Ambil SEMUA pasien yang statusnya 'Dipanggil'
// Urutkan berdasarkan waktu panggil terbaru
$query = mysqli_query($koneksi, "SELECT * FROM pasien WHERE status_antrian = 'Dipanggil' ORDER BY waktu_panggil DESC");

if(mysqli_num_rows($query) == 0){
    echo '<div class="col-12 text-center py-5 text-muted">
            <h4>Tidak ada pasien aktif di ruangan.</h4>
          </div>';
}

while($data = mysqli_fetch_array($query)){
?>
    <div class="col-lg-6 mb-4">
        <div class="card card-pasien shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <i class="fa-solid fa-hospital-user me-2"></i> POLI: <strong><?= $data['poli'] ?></strong>
            </div>
            <div class="card-body">
                <h5 class="fw-bold text-primary"><?= $data['nama'] ?> (<?= $data['no_antrian'] ?>)</h5>
                <p class="text-muted small mb-3">Dokter Tujuan: <?= $data['dokter_tujuan'] ?></p>
                
                <form action="proses_dokter.php" method="post">
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                    <div class="mb-2">
                        <textarea name="diagnosa" class="form-control form-control-sm" rows="2" required placeholder="Diagnosa..."></textarea>
                    </div>
                    <div class="mb-2">
                        <textarea name="resep" class="form-control form-control-sm" rows="2" required placeholder="Resep Obat..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 btn-sm" onclick="return confirm('Selesaikan Pasien ini?')">
                        <i class="fa-solid fa-check"></i> SELESAI & SIMPAN
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php } ?>