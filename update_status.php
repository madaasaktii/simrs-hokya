<?php
header('Content-Type: application/json');
include 'koneksi.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';

// Validasi status sesuai enum di database
$valid_statuses = ['waiting', 'called', 'ongoing', 'done', 'cancelled'];
if (!in_array($status, $valid_statuses)) {
    echo json_encode(['success' => false, 'error' => 'Status tidak valid']);
    exit;
}

$stmt = $conn->prepare("UPDATE antrian SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}