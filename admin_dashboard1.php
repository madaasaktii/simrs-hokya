<?php
// Koneksi ke database
include 'koneksi.php';

// Daftar poli yang ada
$poli = ['Poli Gigi', 'Poli Syaraf', 'Poli Penyakit Dalam', 'Poli Jantung', 'Poli Anak'];
$poli_count = [];

foreach ($poli as $p) {
    // Query untuk menghitung jumlah pasien per poli
    $sql = "SELECT COUNT(*) AS count FROM pendaftaran_pasien WHERE poli = '$p'";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $poli_count[$p] = $row['count']; // Simpan jumlah pasien per poli
    } else {
        $poli_count[$p] = 0; // Jika query gagal, set 0
    }
}
?>

<main class="col-lg-10 col-md-9 px-4 py-3">
    <section id="overview" class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="fw-bold">Dashboard Overview</h4>
            <small class="text-muted">Last updated: <span id="last-updated">just now</span></small>
        </div>

        <div class="row g-3">
            <?php
            // Menampilkan statistik per poli
            foreach ($poli_count as $poli_name => $count):
            ?>
            <div class="col-sm-6 col-lg-3">
                <div class="card stat-card shadow-sm border-start border-4 border-primary">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon bg-primary">
                            <i class="bi bi-hospital"></i> <!-- Anda bisa menyesuaikan ikon sesuai kebutuhan -->
                        </div>
                        <div>
                            <small class="text-muted"><?php echo htmlspecialchars($poli_name); ?></small>
                            <h5 class="mb-0"><?php echo $count; ?></h5> <!-- Menampilkan jumlah pasien -->
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
