<?php
include 'koneksi.php';
header('Content-Type: application/json');
// Supports ?poli=A or ?poli=ALL (default ALL)
$kode = isset($_GET['poli']) ? strtoupper(trim($_GET['poli'])) : 'ALL';
$hari = date('Y-m-d');
$valid = ['A','J','S','P','G'];

if ($kode === 'ALL') {
    $sql = "SELECT id, kode_poli, nomor, CONCAT(kode_poli, LPAD(nomor,3,'0')) AS display, nama_pasien, dokter_tujuan, status FROM antrian WHERE hari = ? ORDER BY kode_poli, nomor";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $hari);
} else {
    if (!in_array($kode, $valid)) { http_response_code(400); echo json_encode(['error'=>'Invalid poli']); exit; }
    $sql = "SELECT id, kode_poli, nomor, CONCAT(kode_poli, LPAD(nomor,3,'0')) AS display, nama_pasien, dokter_tujuan, status FROM antrian WHERE kode_poli = ? AND hari = ? ORDER BY nomor";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $kode, $hari);
}
$stmt->execute();
$res = $stmt->get_result();
out: $out = [];
while ($r = $res->fetch_assoc()) $out[] = $r;
echo json_encode($out);
?>