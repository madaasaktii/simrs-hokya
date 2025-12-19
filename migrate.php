<?php
// Simple migration runner - run from browser or CLI. Edit DB in koneksi.php or ensure koneksi matches.
$path = __DIR__ . '/migrations/create_poli_antrian.sql';
$sql = file_get_contents($path);
include 'koneksi.php';
if (!$sql) { echo "No migration file found"; exit; }
if ($conn->multi_query($sql)) {
    do { /* flush */ } while ($conn->more_results() && $conn->next_result());
    echo "Migration executed.";
} else {
    echo "Migration error: " . $conn->error;
}
?>