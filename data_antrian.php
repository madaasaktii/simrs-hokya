<?php
include 'koneksi.php';

// Cari pasien yang statusnya 'Dipanggil'
$query = mysqli_query($koneksi, "SELECT * FROM pasien WHERE status_antrian='Dipanggil' ORDER BY waktu_daftar DESC LIMIT 1");
$data = mysqli_fetch_array($query);

if ($data) { ?>
    <div class="kotak-antrian text-center p-5 w-75 mx-auto">
        <div class="label-nomor mb-2">NOMOR ANTRIAN</div>
        <div class="nomor-besar mb-4"><?= $data['id'] ?></div>
        <hr class="my-4">
        <div class="nama-pasien mb-3"><?= strtoupper($data['nama']) ?></div>
        <div class="poli-tujuan">POLI <?= strtoupper($data['poli']) ?></div>
        
        <div class="mt-4 fs-3 blink_me">
            SILAKAN MENUJU RUANG PERIKSA
        </div>
    </div>

<?php } else { ?>
    <div class="kotak-antrian text-center p-5 w-75 mx-auto">
        <div style="font-size: 8rem; color: #ddd;"><i class="bi bi-clock-history"></i></div>
        <h2 class="text-muted mt-3" style="font-size: 3rem;">Belum ada panggilan...</h2>
        <p class="fs-4 text-muted">Silakan tunggu nomor antrian Anda dipanggil.</p>
    </div>
<?php } ?>