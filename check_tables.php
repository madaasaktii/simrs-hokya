<?php
include 'koneksi.php';
$tables = ['users','perawat'];
foreach($tables as $t){
  $r = $conn->query("SHOW TABLES LIKE '" . $conn->real_escape_string($t) . "'");
  echo $t . ': ' . ($r ? $r->num_rows : 'ERROR') . "\n";
}
?>