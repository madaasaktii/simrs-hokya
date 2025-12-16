<?php
include 'koneksi.php';
$id = $_GET['id'];

// Ambil data detail pasien berdasarkan ID yang diklik
$query = mysqli_query($koneksi, "SELECT * FROM pasien WHERE id='$id'");
$data = mysqli_fetch_array($query);

if(!$data){
    echo "Data tidak ditemukan.";
    exit;
}
?>

<div class="alert alert-primary d-flex align-items-center">
    <i class="fa-solid fa-user-circle fa-2x me-3"></i>
    <div>
        <h5 class="mb-0 fw-bold"><?= $data['nama'] ?></h5>
        <small>NIK: <?= $data['nik'] ?> | Usia: <?= date('Y') - substr($data['tgl_lahir'],0,4) ?> Tahun</small>
    </div>
</div>

<form action="proses_dokter.php" method="post">
    <input type="hidden" name="id" value="<?= $data['id'] ?>">

    <div class="mb-3">
        <label class="form-label fw-bold">Keluhan / Poli</label>
        <input type="text" class="form-control bg-light" value="<?= $data['poli'] ?>" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold text-primary">Diagnosa Dokter</label>
        <textarea name="diagnosa" class="form-control" rows="4" required placeholder="Tulis diagnosa lengkap..."></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold text-primary">Resep Obat</label>
        <textarea name="resep" class="form-control" rows="4" required placeholder="Tulis resep obat..."></textarea>
    </div>

    <hr>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-success btn-lg fw-bold" onclick="return confirm('Selesaikan pemeriksaan pasien ini?')">
            <i class="fa-solid fa-check-circle me-2"></i> SIMPAN & SELESAI
        </button>
    </div>
</form>