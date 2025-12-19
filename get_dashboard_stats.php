<?php
header('Content-Type: application/json');
include 'koneksi.php';

$result = [
  'total_patients' => 0,
  'dipanggil' => 0,
  'menunggu' => 0,
  'igd' => 0,
  'bor_percent' => null,
  'last_updated' => date('Y-m-d H:i:s')
];

$hari = date('Y-m-d');

function safe_count($conn, $sql, $params=null){
  $stmt = $conn->prepare($sql);
  if($params){
    $stmt->bind_param(...$params);
  }
  $stmt->execute();
  $res = $stmt->get_result();
  $r = $res->fetch_row();
  return (int)$r[0];
}

// total patients today
$res = $conn->prepare("SELECT COUNT(*) FROM antrian WHERE hari = ?");
$res->bind_param('s', $hari);
$res->execute();
$r = $res->get_result()->fetch_row();
$result['total_patients'] = (int)$r[0];

// dipanggil = called or ongoing
$res = $conn->prepare("SELECT COUNT(*) FROM antrian WHERE hari = ? AND status IN ('called','ongoing')");
$res->bind_param('s', $hari);
$res->execute();
$r = $res->get_result()->fetch_row();
$result['dipanggil'] = (int)$r[0];

// menunggu
$res = $conn->prepare("SELECT COUNT(*) FROM antrian WHERE hari = ? AND status = 'waiting'");
$res->bind_param('s', $hari);
$res->execute();
$r = $res->get_result()->fetch_row();
$result['menunggu'] = (int)$r[0];

// igd - not available in this combined module; keep 0
$result['igd'] = 0;

echo json_encode($result);
?>