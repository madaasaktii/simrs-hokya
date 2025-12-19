<?php
session_start();
include 'koneksi.php';

// Asumsi $kode_poli_dokter dari session
$kode_poli_dokter = isset($_SESSION['poli_code']) ? $conn->real_escape_string($_SESSION['poli_code']) : '';

// Ambil pasien status 'called' atau 'ongoing' per poli dokter (sesuai enum di SQL)
// 'called' = sudah lewat anamnesis, siap diperiksa dokter
// 'ongoing' = sedang diperiksa dokter
$query = "SELECT id, kode_poli, nomor, CONCAT(kode_poli, LPAD(nomor,3,'0')) AS no, nama_pasien, status 
          FROM antrian 
          WHERE status IN ('called', 'ongoing') AND kode_poli = '$kode_poli_dokter' 
          ORDER BY created_at ASC";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo '<div class="p-4 text-center text-danger small"><em>Error memuat antrian: ' . mysqli_error($conn) . '</em></div>';
    exit;
}

if(mysqli_num_rows($result) == 0){
    echo '<div class="p-4 text-center text-muted small"><em>Tidak ada pasien antri di poli ini.</em></div>';
    exit;
}

while($row = mysqli_fetch_assoc($result)){
    $p = mysqli_query($conn, "SELECT name FROM poli WHERE code='" . $conn->real_escape_string($row['kode_poli']) . "'");
    $pn = ($p && mysqli_num_rows($p) > 0) ? mysqli_fetch_assoc($p)['name'] : $row['kode_poli'];
    
    // Tentukan badge status
    $statusBadge = '';
    if ($row['status'] == 'called') {
        $statusBadge = '<span class="badge bg-warning text-dark">Dipanggil</span>';
    } elseif ($row['status'] == 'ongoing') {
        $statusBadge = '<span class="badge bg-info text-white">Dipanggil</span>';
    }
    ?>
    <div class="list-group-item list-group-item-action p-3 pasien-item" data-id="<?php echo $row['id']; ?>">
        <div class="d-flex w-100 justify-content-between align-items-center">
            <h6 class="mb-1 fw-bold text-primary"><?php echo htmlspecialchars($row['nama_pasien']); ?></h6>
            <small class="badge bg-primary"><?php echo htmlspecialchars($row['no']); ?></small>
        </div>
        <p class="mb-1 small text-muted">Poli: <?php echo htmlspecialchars($pn); ?></p>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <?php echo $statusBadge; ?>
            <?php if ($row['status'] == 'called'): ?>
                <button class="btn btn-sm btn-outline-primary btn-periksa" data-id="<?php echo $row['id']; ?>">
                    <i class="bi bi-arrow-right me-1"></i> Periksa Pasien
                </button>
            <?php else: ?>
                <small class="text-muted fst-italic">Sedang diperiksa...</small>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>