<?php
include 'koneksi.php';

// Ambil pasien yang sedang dipanggil atau sedang berjalan
$query = $conn->query("SELECT id, kode_poli, nomor, CONCAT(kode_poli, LPAD(nomor,3,'0')) AS display, nama_pasien, dokter_tujuan, status FROM antrian WHERE status IN ('called','ongoing') ORDER BY updated_at DESC");

if($query->num_rows == 0){
    echo '<div class="col-12 text-center py-5 text-muted"><h4>Tidak ada pasien aktif di ruangan.</h4></div>';
    exit;
}

while($data = $query->fetch_assoc()){
    // get poli name
    $p = $conn->query("SELECT name FROM poli WHERE code='".$conn->real_escape_string($data['kode_poli'])."'");
    $pn = ($p && $p->num_rows>0) ? $p->fetch_assoc()['name'] : $data['kode_poli'];
    ?>
    <div class="col-lg-6 mb-4">
        <div class="card card-pasien shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <i class="fa-solid fa-hospital-user me-2"></i> POLI: <strong><?= htmlspecialchars($pn) ?></strong>
            </div>
            <div class="card-body">
                <h5 class="fw-bold text-primary"><?= htmlspecialchars($data['nama_pasien']) ?> (<?= $data['display'] ?>)</h5>
                <p class="text-muted small mb-3">Dokter Tujuan: <?= htmlspecialchars($data['dokter_tujuan']) ?></p>
                
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