<?php
// =========================
// KONFIGURASI FONNTE
// =========================

// Dapatkan token dari: https://fonnte.com/dashboard
define('FONNTE_TOKEN', 'H58qE3sNA6ySp1gNKdJ1'); // Ganti dengan token Fonnte Anda
define('FONNTE_URL', 'https://api.fonnte.com/send');

/**
 * Kirim WhatsApp via Fonnte
 * 
 * @param string $target Nomor HP (format: 628xxx atau 08xxx)
 * @param string $message Pesan yang akan dikirim
 * @return array Status pengiriman
 */
function kirimWhatsApp($target, $message) {
    // Format nomor: hapus leading 0, tambah 62
    $target = preg_replace('/^0/', '62', $target);
    $target = preg_replace('/[^0-9]/', '', $target); // Hapus karakter non-angka
    
    // Data yang akan dikirim
    $data = [
        'target' => $target,
        'message' => $message,
        'countryCode' => '62' // Kode negara Indonesia
    ];
    
    // Setup CURL
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => FONNTE_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Authorization: ' . FONNTE_TOKEN,
            'Content-Type: application/json'
        ],
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    
    if ($err) {
        error_log("FONNTE ERROR: " . $err);
        return [
            'status' => false,
            'message' => 'Gagal mengirim: ' . $err
        ];
    }
    
    $result = json_decode($response, true);
    
    return [
        'status' => isset($result['status']) && $result['status'] == true,
        'message' => $result['reason'] ?? 'Pesan terkirim',
        'data' => $result
    ];
}

/**
 * Template pesan pendaftaran
 */
function templatePesanPendaftaran($data) {
    $nomor_antrian = $data['nomor_antrian'];
    $nama = $data['nama'];
    $poli = $data['poli'];
    $tgl = date('d/m/Y', strtotime($data['tgl_kunjungan']));
    $cara_bayar = $data['cara_bayar'];
    
    $pesan = "🏥 *PENDAFTARAN BERHASIL*\n";
    $pesan .= "RS HOKYA - Surabaya\n\n";
    $pesan .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $pesan .= "Halo *{$nama}*,\n";
    $pesan .= "Pendaftaran Anda telah berhasil!\n\n";
    $pesan .= "📋 *Detail Pendaftaran:*\n";
    $pesan .= "🎫 Nomor Antrian: *{$nomor_antrian}*\n";
    $pesan .= "🏥 Poli: {$poli}\n";
    $pesan .= "📅 Tanggal Kunjungan: {$tgl}\n";
    $pesan .= "💳 Pembayaran: {$cara_bayar}\n\n";
    $pesan .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $pesan .= "⏰ *Jam Operasional:*\n";
    $pesan .= "Senin - Jumat: 08.00 - 16.00\n";
    $pesan .= "Sabtu: 08.00 - 12.00\n\n";
    $pesan .= "💡 *Tips:*\n";
    $pesan .= "• Datang 30 menit lebih awal\n";
    $pesan .= "• Bawa KTP & Kartu BPJS (jika ada)\n";
    $pesan .= "• Simpan nomor antrian ini\n\n";
    $pesan .= "📍 Jl. Rumah Sakit No. 123, Surabaya\n";
    $pesan .= "📞 (031) 555-777\n\n";
    $pesan .= "_Terima kasih telah memilih RS Hokya_";
    
    return $pesan;
}

/**
 * Template reminder H-1 (opsional - bisa dijadwalkan manual)
 */
function templateReminderH1($data) {
    $nomor_antrian = $data['nomor_antrian'];
    $nama = $data['nama'];
    $poli = $data['poli'];
    $tgl = date('d/m/Y', strtotime($data['tgl_kunjungan']));
    
    $pesan = "⏰ *PENGINGAT KUNJUNGAN*\n";
    $pesan .= "RS HOKYA - Surabaya\n\n";
    $pesan .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $pesan .= "Halo *{$nama}*,\n\n";
    $pesan .= "Mengingatkan jadwal kunjungan Anda:\n\n";
    $pesan .= "🎫 Nomor Antrian: *{$nomor_antrian}*\n";
    $pesan .= "🏥 Poli: {$poli}\n";
    $pesan .= "📅 Besok, {$tgl}\n";
    $pesan .= "⏰ Senin-Jumat: 08.00-16.00 | Sabtu: 08.00-12.00\n\n";
    $pesan .= "Jangan lupa bawa KTP & kartu BPJS!\n\n";
    $pesan .= "Sampai jumpa besok 😊";
    
    return $pesan;
}

/**
 * Template reminder hari H pagi (opsional)
 */
function templateReminderHariIni($data) {
    $nomor_antrian = $data['nomor_antrian'];
    $nama = $data['nama'];
    $poli = $data['poli'];
    
    $pesan = "⏰ *PENGINGAT HARI INI*\n";
    $pesan .= "RS HOKYA - Surabaya\n\n";
    $pesan .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $pesan .= "Halo *{$nama}*,\n\n";
    $pesan .= "Hari ini jadwal kunjungan Anda!\n\n";
    $pesan .= "🎫 Nomor Antrian: *{$nomor_antrian}*\n";
    $pesan .= "🏥 Poli: {$poli}\n";
    $pesan .= "⏰ Jam Buka: 08.00 WIB\n\n";
    $pesan .= "📍 Jl. Rumah Sakit No. 123, Surabaya\n\n";
    $pesan .= "Kami tunggu kedatangan Anda! 🏥";
    
    return $pesan;
}
?>