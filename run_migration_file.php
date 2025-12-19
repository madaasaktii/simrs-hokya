<?php
// Run a single migration .sql file passed as first arg, or default to create_perawat_table.sql
$path = isset($argv[1]) ? $argv[1] : (__DIR__ . '/migrations/create_perawat_table.sql');
if (!file_exists($path)) { echo "Migration file not found: $path\n"; exit(1); }
$sql = file_get_contents($path);
include 'koneksi.php';
if (!$sql) { echo "No SQL in file: $path\n"; exit(1); }
if ($conn->multi_query($sql)) {
    do { while ($res = $conn->store_result()) { $res->free(); }} while ($conn->more_results() && $conn->next_result());
    echo "Migration executed: $path\n";
} else {
    echo "Migration error: " . $conn->error . "\n";
}
?>