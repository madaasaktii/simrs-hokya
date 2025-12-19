<?php
include 'koneksi.php';
$fh = fopen(__DIR__ . '/migrations/created_users.csv', 'w');
fputcsv($fh, ['username','password','role','name','poli_code']);
$res = $conn->query("SELECT username, role, name, poli_code FROM users WHERE role IN ('dokter','perawat','admin') ORDER BY role, username");
while($r = $res->fetch_assoc()){
  $pwd = ($r['role'] === 'admin') ? 'admin123' : 'dokter123';
  fputcsv($fh, [$r['username'], $pwd, $r['role'], $r['name'], $r['poli_code']]);
}
fclose($fh);
echo "CSV generated: migrations/created_users.csv\n";
?>