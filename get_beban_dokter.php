<?php
include 'koneksi.php';
header('Content-Type: application/json');

$tanggal = date('Y-m-d');

$sql = "SELECT COALESCE(dokter_tujuan,'(Tanpa Dokter)') AS dokter, p.name AS poli, COUNT(*) AS total_pasien
        FROM antrian a
        LEFT JOIN poli p ON a.kode_poli = p.code
        WHERE hari = ?
        GROUP BY dokter_tujuan, p.name
        ORDER BY total_pasien DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $tanggal);
$stmt->execute();
$res = $stmt->get_result();
$out = [];
while($r = $res->fetch_assoc()) $out[] = $r;

echo json_encode($out);
?>