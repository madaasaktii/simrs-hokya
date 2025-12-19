<?php
include 'koneksi.php';
header('Content-Type: application/json');
$res = $conn->query("SELECT code, name FROM poli ORDER BY name");
$out = [];
while ($r = $res->fetch_assoc()) $out[] = $r;
echo json_encode($out);
?>