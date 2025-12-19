<?php
// Seed doctor user accounts from per-poli tables into users table
include 'koneksi.php';

function make_username($name){
    // basic slug: lower, replace non-alnum with dot, trim dots
    $u = mb_strtolower($name, 'UTF-8');
    $u = preg_replace('/[^a-z0-9]+/u', '.', $u);
    $u = trim($u, '.');
    $u = substr($u, 0, 30);
    return $u;
}

$default_password = 'dokter123';
$created = [];

$logfile = __DIR__ . '/migrations/created_users.csv';
$need_header = !file_exists($logfile);
$fh = fopen($logfile, 'a');
if ($need_header) fputcsv($fh, ['username','password','role','name','poli_code']);

// create admin and perawat default accounts
$defaults = [
  ['username'=>'admin','password'=>'admin123','name'=>'Administrator','role'=>'admin', 'poli'=>null],
  // keep compatibility default perawat account, password aligned to default
  ['username'=>'perawat','password'=>$default_password,'name'=>'Perawat Default','role'=>'perawat','poli'=>null],
];

// --- Seed perawat table entries (if table exists)
$checkPerawat = $conn->query("SHOW TABLES LIKE 'perawat'");
if($checkPerawat && $checkPerawat->num_rows > 0){
  $resP = $conn->query("SELECT DISTINCT perawat FROM `perawat` WHERE perawat IS NOT NULL AND perawat <> ''");
  while($prow = $resP->fetch_assoc()){
    $name = trim($prow['perawat']);
    $username = make_username($name);
    // skip if a user with same name & role already exists
    $chk = $conn->prepare("SELECT id FROM users WHERE name = ? AND role = 'perawat' LIMIT 1"); $chk->bind_param('s', $name); $chk->execute(); $chkRes = $chk->get_result(); if($chkRes && $chkRes->num_rows>0) continue;
    // ensure unique username
    $base = $username; $i = 1;
    while(true){
      $q = $conn->prepare("SELECT id FROM users WHERE username = ?"); $q->bind_param('s', $username); $q->execute(); $r = $q->get_result();
      if($r->num_rows == 0) break;
      $username = $base . $i; $i++;
    }
    $hash = password_hash($default_password, PASSWORD_DEFAULT);
    $ins = $conn->prepare("INSERT INTO users (username, password, name, role) VALUES (?, ?, ?, 'perawat')");
    $ins->bind_param('sss', $username, $hash, $name);
    if($ins->execute()){
      $created[] = [$username, $default_password, 'perawat', $name];
      fputcsv($fh, [$username, $default_password, 'perawat', $name, '']);
    }
  }
}
foreach($defaults as $d){
  $u = $d['username'];
  $q = $conn->prepare("SELECT id FROM users WHERE username = ?"); $q->bind_param('s', $u); $q->execute(); $r = $q->get_result();
  if($r->num_rows == 0){
    $hash = password_hash($d['password'], PASSWORD_DEFAULT);
    $ins = $conn->prepare("INSERT INTO users (username, password, name, role, poli_code) VALUES (?, ?, ?, ?, ?)");
    $ins->bind_param('sssss', $u, $hash, $d['name'], $d['role'], $d['poli']);
    if($ins->execute()){
      $created[] = [$u, $d['password'], $d['role']];
      fputcsv($fh, [$u, $d['password'], $d['role'], $d['name'], $d['poli']]);
    }
  }
}

// Map poli codes to per-poli table names
$map = [ 'A' => 'poli_anak', 'J' => 'poli_jantung', 'P' => 'poli_penyakit_dalam', 'G' => 'poli_gigi', 'S' => 'poli_syaraf' ];
foreach($map as $code => $table){
  // if table exists
  $check = $conn->query("SHOW TABLES LIKE '".$conn->real_escape_string($table)."'");
  if(!$check || $check->num_rows == 0) continue;

  $res = $conn->query("SELECT DISTINCT dokter FROM `".$conn->real_escape_string($table)."` WHERE dokter IS NOT NULL AND dokter <> ''");
  while($row = $res->fetch_assoc()){
    $name = trim($row['dokter']);
    $username = make_username($name);
    // skip if user with same name & role already exists
    $chk = $conn->prepare("SELECT id FROM users WHERE name = ? AND role = 'dokter' AND poli_code = ? LIMIT 1"); $chk->bind_param('ss', $name, $code); $chk->execute(); $chkRes = $chk->get_result(); if($chkRes && $chkRes->num_rows>0) continue;
    // ensure unique username
    $base = $username; $i=1;
    while(true){
      $q = $conn->prepare("SELECT id FROM users WHERE username = ?"); $q->bind_param('s', $username); $q->execute(); $r = $q->get_result();
      if($r->num_rows == 0) break;
      $username = $base . $i; $i++;
    }
    $hash = password_hash($default_password, PASSWORD_DEFAULT);
    $ins = $conn->prepare("INSERT INTO users (username, password, name, role, poli_code) VALUES (?, ?, ?, 'dokter', ?)");
    $ins->bind_param('ssss', $username, $hash, $name, $code);
    if($ins->execute()){
      $created[] = [$username, $default_password, 'dokter', $name, $code];
      fputcsv($fh, [$username, $default_password, 'dokter', $name, $code]);
    }
  }
}

// report
if(count($created)==0){
  echo "No users created (already exist)\n";
} else {
  echo "Created users:\n";
  foreach($created as $c){
    echo implode(' | ', $c) . "\n";
  }
}

// close logfile
if (is_resource($fh)) fclose($fh);

?>