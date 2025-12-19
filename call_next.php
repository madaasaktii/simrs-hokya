<?php
include 'koneksi.php';
header('Content-Type: application/json');
$kode = isset($_POST['poli']) ? strtoupper(trim($_POST['poli'])) : '';
$valid = ['A','J','S','P','G'];
if (!in_array($kode, $valid)) { http_response_code(400); echo json_encode(['error'=>'Invalid poli']); exit; }
$hari = date('Y-m-d');

try {
    $conn->begin_transaction();
    // pick the next waiting nomor
    $stmt = $conn->prepare("SELECT id, nomor FROM antrian WHERE kode_poli = ? AND hari = ? AND status = 'waiting' ORDER BY nomor ASC LIMIT 1 FOR UPDATE");
    $stmt->bind_param('ss', $kode, $hari);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if (!$row) { $conn->rollback(); echo json_encode(['success'=>false,'message'=>'No waiting patients']); exit; }
    $id = (int)$row['id'];
    $nomor = (int)$row['nomor'];
    $upd = $conn->prepare("UPDATE antrian SET status = 'called', updated_at = NOW() WHERE id = ?");
    $upd->bind_param('i', $id);
    if (!$upd->execute()) throw new Exception($upd->error);
    $conn->commit();
    $display = $kode . str_pad($nomor,3,'0',STR_PAD_LEFT);
    echo json_encode(['success'=>true,'id'=>$id,'display'=>$display,'nomor'=>$nomor]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success'=>false,'error'=>$e->getMessage()]);
}
?>