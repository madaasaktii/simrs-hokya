<?php
include 'koneksi.php';
$new_password = password_hash('pw123', PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = 'dr.neuro'");
$stmt->bind_param("s", $new_password);
$stmt->execute();
echo "Password reset to 'pw123' hashed.";
?>