<?php
include 'koneksi.php';

// Ambil data pasien (Urutkan dari yang terbaru ID-nya biar admin tau ada yang baru daftar)
// Atau urutkan ASC biar antrian tetap urut 1, 2, 3
$query = mysqli_query($koneksi, "SELECT * FROM pasien WHERE status_antrian != 'Selesai' ORDER BY no_antrian ASC");

while ($row = mysqli_fetch_array($query)) {
    
    // LOGIKA HURUF
    $p = $row['poli'];
    $huruf = "U"; 
    if(stripos($p, "Gigi") !== false) $huruf = "G";
    if(stripos($p, "Anak") !== false) $huruf = "A";
    if(stripos($p, "Kandungan") !== false) $huruf = "K";
    if(stripos($p, "Jantung") !== false) $huruf = "J";
    
    $nomor_final = $huruf . "-" . $row['no_antrian'];

    // Warna baris
    $bg_class = ($row['status_antrian'] == 'Dipanggil') ? 'table-warning' : '';
    ?>
    
    <tr class="<?= $bg_class ?>">
        <td class="fw-bold text-center fs-5"><?= $nomor_final ?></td>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['poli'] ?></td>
        <td class="text-primary fw-bold"><i class="bi bi-person-badge-fill me-1"></i> <?= $row['dokter_tujuan'] ?></td>
        <td class="text-center">
            <?php if($row['status_antrian'] == 'Dipanggil'): ?>
                <span class="badge bg-danger fs-6 blink_me">SEDANG DIPANGGIL</span>
            <?php else: ?>
                <span class="badge bg-secondary">Menunggu</span>
            <?php endif; ?>
        </td>
        <td class="text-center">
            <a href="update_status.php?id=<?= $row['id'] ?>&status=Dipanggil" class="btn btn-success btn-sm mb-1">
                <i class="fa-solid fa-bullhorn"></i> Panggil
            </a>
            <a href="update_status.php?id=<?= $row['id'] ?>&status=Selesai" class="btn btn-secondary btn-sm mb-1" onclick="return confirm('Pasien sudah selesai diperiksa?')">
                <i class="fa-solid fa-check"></i> Selesai
            </a>
        </td>
    </tr>
    <?php
}

if(mysqli_num_rows($query) == 0): ?>
    <tr><td colspan="6" class="text-center text-muted">Belum ada pasien antri.</td></tr>
<?php endif; ?>