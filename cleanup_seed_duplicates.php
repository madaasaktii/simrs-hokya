<?php
include 'koneksi.php';
$res = $conn->query("DELETE FROM users WHERE username LIKE '%1' AND role IN ('dokter','perawat')");
echo "Deleted: " . $conn->affected_rows . "\n";
?>