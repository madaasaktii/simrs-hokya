<?php
include 'koneksi.php';
$res = $conn->query("SELECT id, username, name, role, poli_code, created_at FROM users ORDER BY id LIMIT 50");
while($r=$res->fetch_assoc()){
  echo $r['id']." | ".$r['username']." | ".$r['name']." | ".$r['role']." | ".$r['poli_code']."\n";
}
?>