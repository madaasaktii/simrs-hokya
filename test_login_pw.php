<?php
include 'koneksi.php';
function check($username, $pwd){
  $stmt = $GLOBALS['conn']->prepare("SELECT password FROM users WHERE username = ? LIMIT 1");
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $res = $stmt->get_result()->fetch_assoc();
  if ($res){
    echo $username . ': ' . (password_verify($pwd, $res['password']) ? "OK" : "BAD") . "\n";
  } else echo $username . ": NOUSER\n";
}
check('dr.anita','pw123');
check('perawat.siti','pw123');
check('admin','pw123');
?>