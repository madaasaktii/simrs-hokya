<?php
include 'koneksi.php';
$username = 'dr.anita';
$pwd = 'dokter123';
$stmt = $conn->prepare("SELECT password FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
if ($res){
  echo password_verify($pwd, $res['password']) ? "OK\n" : "BAD\n";
} else echo "NOUSER\n";
?>