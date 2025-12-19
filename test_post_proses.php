<?php
// Test script: simulate a POST to proses_daftar.php
$_POST = ['kode_poli' => 'A', 'nama' => 'Test User', 'dokter' => 'Dr. Anita'];
include __DIR__ . '/proses_daftar.php';
?>