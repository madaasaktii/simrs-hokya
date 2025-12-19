<?php
include 'koneksi.php';

$q = $conn->query("SELECT id, kode_poli, nomor, CONCAT(kode_poli, LPAD(nomor,3,'0')) AS no, nama_pasien, status FROM antrian ORDER BY created_at ASC LIMIT 5");

if($q->num_rows == 0){
  echo '<li class="list-group-item text-muted">Belum ada antrian</li>';
  exit;
}

while($d = $q->fetch_assoc()){
  $badge = 'secondary';
  if($d['status']=='waiting') $badge='warning';
  if($d['status']=='called' || $d['status']=='ongoing') $badge='success';
  if($d['status']=='done') $badge='secondary';

  // get poli name
  $p = $conn->query("SELECT name FROM poli WHERE code = '".$conn->real_escape_string($d['kode_poli'])."'");
  $pn = ($p && $p->num_rows>0) ? $p->fetch_assoc()['name'] : $d['kode_poli'];

  echo '<li class="list-group-item d-flex justify-content-between align-items-center">'
      . $d['no'] . ' · ' . htmlspecialchars($pn)
      . '<span class="badge bg-' . $badge . '">'.htmlspecialchars($d['status']).'</span>'
      . '</li>';
}
?>