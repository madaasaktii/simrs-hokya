<?php
// Backup current passwords and update all users' passwords to pw123 (bcrypt)
include 'koneksi.php';
$hash = '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW'; // precomputed hash for 'pw123'

// create backup table
$create = "CREATE TABLE IF NOT EXISTS users_password_backup (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  username VARCHAR(100) NOT NULL,
  old_password VARCHAR(255) NOT NULL,
  changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if (!$conn->query($create)) { echo "Backup table creation failed: " . $conn->error . "\n"; exit(1); }

// insert current passwords into backup
$ins = $conn->prepare("INSERT INTO users_password_backup (user_id, username, old_password) SELECT id, username, password FROM users");
if (!$ins) { echo "Prepare failed: " . $conn->error . "\n"; exit(1); }
if (!$ins->execute()) { echo "Backup insert failed: " . $ins->error . "\n"; exit(1); }

// update all users
$upd = $conn->prepare("UPDATE users SET password = ?");
if (!$upd) { echo "Prepare update failed: " . $conn->error . "\n"; exit(1); }
$upd->bind_param('s', $hash);
if (!$upd->execute()) { echo "Update failed: " . $upd->error . "\n"; exit(1); }

echo "Updated passwords for users - affected rows: " . $conn->affected_rows . "\n";

// optional: regenerate CSV of users with new password (pw123)
$fh = fopen(__DIR__ . '/migrations/created_users_pw123.csv', 'w');
fputcsv($fh, ['username','password','role','name','poli_code']);
$res = $conn->query("SELECT username, role, name, poli_code FROM users ORDER BY id");
while($r = $res->fetch_assoc()){
  $pwd = 'pw123';
  fputcsv($fh, [$r['username'], $pwd, $r['role'], $r['name'], $r['poli_code']]);
}
fclose($fh);

echo "CSV generated: migrations/created_users_pw123.csv\n";
?>