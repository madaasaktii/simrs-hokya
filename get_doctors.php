<?php
include 'koneksi.php';
header('Content-Type: application/json');
$kode = isset($_GET['kode']) ? strtoupper(trim($_GET['kode'])) : '';
if (!$kode) { echo json_encode([]); exit; }

// Prefer doctor list from users table if available
$stmt = $conn->prepare("SELECT username, name as dokter FROM users WHERE role='dokter' AND poli_code = ? ORDER BY name");
$stmt->bind_param('s', $kode);
$stmt->execute();
$res = $stmt->get_result();
$out = [];
if($res && $res->num_rows>0){
    while($r = $res->fetch_assoc()) $out[] = $r;
    echo json_encode($out);
    exit;
}

// Fallback to legacy per-poli table
$map = [
  'A' => 'poli_anak',
  'J' => 'poli_jantung',
  'P' => 'poli_penyakit_dalam',
  'G' => 'poli_gigi',
  'S' => 'poli_syaraf'
];
if (!isset($map[$kode])) { echo json_encode([]); exit; }
$table = $map[$kode];
$res = $conn->query("SELECT dokter FROM `".$conn->real_escape_string($table)."` ORDER BY dokter");
while ($r = $res->fetch_assoc()) $out[] = ['dokter'=>$r['dokter']];

echo json_encode($out);
?>