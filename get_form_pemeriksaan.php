<?php
include 'koneksi.php';

$id_antrian = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch data antrian dan anamnesis
$query_antrian = "SELECT * FROM antrian WHERE id = '$id_antrian'";
$result_antrian = mysqli_query($conn, $query_antrian);
$row_antrian = mysqli_fetch_assoc($result_antrian);

if (!$row_antrian) {
    echo '<p class="text-danger">Data antrian tidak ditemukan.</p>';
    exit;
}

$query_anamnesis = "SELECT * FROM anamnesis WHERE id_antrian = '$id_antrian'";
$result_anamnesis = mysqli_query($conn, $query_anamnesis);
$row_anamnesis = mysqli_fetch_assoc($result_anamnesis);

if ($row_anamnesis) {
    echo '<form id="form-pemeriksaan">';
    echo '<h6 class="mb-3"><strong>Pasien: ' . htmlspecialchars($row_antrian['nama_pasien']) . '</strong></h6>';
    echo '<hr>';
    echo '<h6>Data Anamnesis dari Perawat</h6>';
    echo '<div class="row mb-3">';
    echo '<div class="col-md-6"><label>Berat Badan</label><input class="form-control" value="' . htmlspecialchars($row_anamnesis['berat_badan']) . ' kg" readonly></div>';
    echo '<div class="col-md-6"><label>Tinggi Badan</label><input class="form-control" value="' . htmlspecialchars($row_anamnesis['tinggi_badan']) . ' cm" readonly></div>';
    echo '</div>';
    echo '<div class="row mb-3">';
    echo '<div class="col-md-6"><label>Tekanan Darah</label><input class="form-control" value="' . htmlspecialchars($row_anamnesis['tekanan_darah']) . '" readonly></div>';
    echo '<div class="col-md-6"><label>Suhu Tubuh</label><input class="form-control" value="' . htmlspecialchars($row_anamnesis['suhu_tubuh']) . ' °C" readonly></div>';
    echo '</div>';
    echo '<div class="row mb-3">';
    echo '<div class="col-md-6"><label>Nadi</label><input class="form-control" value="' . htmlspecialchars($row_anamnesis['nadi']) . ' bpm" readonly></div>';
    echo '</div>';
    echo '<div class="mb-3"><label>Keluhan</label><textarea class="form-control" readonly>' . htmlspecialchars($row_anamnesis['keluhan']) . '</textarea></div>';

    echo '<hr>';
    echo '<h6>Input Pemeriksaan Dokter</h6>';
    echo '<div class="mb-3"><label>Diagnosis <span class="text-danger">*</span></label><textarea name="diagnosis" class="form-control" rows="3" placeholder="Tuliskan diagnosis..." required></textarea></div>';
    echo '<div class="mb-3"><label>Resep Obat</label><textarea name="resep" class="form-control" rows="3" placeholder="Tuliskan resep obat..."></textarea></div>';
    echo '<div class="mb-3"><label>Catatan Lain</label><textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea></div>';

    echo '<input type="hidden" name="id_antrian" value="' . $id_antrian . '">';
    echo '<button type="submit" class="btn btn-success w-100"><i class="bi bi-check-circle me-2"></i>Simpan Pemeriksaan</button>';
    echo '</form>';
} else {
    echo '<div class="alert alert-warning">Data anamnesis belum diisi oleh perawat. Pasien harus melalui anamnesis terlebih dahulu.</div>';
}
?>