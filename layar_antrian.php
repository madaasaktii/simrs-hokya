<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Layar Antrian - RS Hokya Sehat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
      font-family: 'Poppins', sans-serif; 
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding: 30px 20px;
    }
    
    .header-section {
      text-align: center;
      margin-bottom: 40px;
    }
    
    .hospital-title {
      font-size: 2.5rem;
      font-weight: 700;
      color: white;
      margin-bottom: 10px;
      text-shadow: 2px 2px 8px rgba(0,0,0,0.2);
    }
    
    .subtitle {
      color: rgba(255, 255, 255, 0.9);
      font-size: 1.1rem;
      font-weight: 300;
    }
    
    .queue-container {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      gap: 25px;
      flex-wrap: wrap;
      max-width: 1400px;
      margin: 0 auto;
    }
    
    .queue-card {
      background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
      border-radius: 25px;
      padding: 35px 30px;
      width: 280px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.2);
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
    }
    
    .queue-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 50px rgba(0,0,0,0.3);
    }
    
    .queue-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 8px;
      height: 100%;
      background: linear-gradient(to bottom, #FFB75E, #ED8F03);
    }
    
    .queue-card.poli-A::before { background: linear-gradient(to bottom, #FF6B9D, #C06C84); }
    .queue-card.poli-J::before { background: linear-gradient(to bottom, #F97794, #623AA2); }
    .queue-card.poli-S::before { background: linear-gradient(to bottom, #4776E6, #8E54E9); }
    .queue-card.poli-P::before { background: linear-gradient(to bottom, #11998e, #38ef7d); }
    .queue-card.poli-G::before { background: linear-gradient(to bottom, #FFB75E, #ED8F03); }
    
    .queue-number {
      font-size: 4rem;
      font-weight: 700;
      color: #1977cc;
      margin-bottom: 15px;
      line-height: 1;
    }
    
    .poli-A .queue-number { color: #FF6B9D; }
    .poli-J .queue-number { color: #F97794; }
    .poli-S .queue-number { color: #4776E6; }
    .poli-P .queue-number { color: #11998e; }
    .poli-G .queue-number { color: #FFB75E; }
    
    .queue-patient {
      font-size: 1.1rem;
      color: #333;
      font-weight: 600;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .queue-patient i {
      font-size: 1.3rem;
      opacity: 0.7;
    }
    
    .queue-status {
      display: inline-block;
      padding: 6px 16px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      background: rgba(102, 126, 234, 0.15);
      color: #667eea;
      margin-top: 10px;
    }
    
    .poli-label {
      font-size: 0.85rem;
      color: #666;
      font-weight: 500;
      margin-bottom: 5px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .empty-state {
      text-align: center;
      color: white;
      padding: 60px 20px;
      width: 100%;
    }
    
    .empty-state i {
      font-size: 5rem;
      margin-bottom: 20px;
      opacity: 0.5;
    }
    
    .empty-state h3 {
      font-size: 1.8rem;
      font-weight: 600;
      margin-bottom: 10px;
    }
    
    .time-display {
      position: fixed;
      top: 20px;
      right: 20px;
      background: white;
      padding: 12px 24px;
      border-radius: 30px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      font-size: 0.9rem;
      color: #666;
      font-weight: 500;
      z-index: 99;
    }
    
    .refresh-indicator {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: white;
      padding: 12px 20px;
      border-radius: 30px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.85rem;
      color: #666;
    }
    
    .refresh-icon {
      animation: rotate 2s linear infinite;
    }
    
    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
      .hospital-title { font-size: 1.8rem; }
      .queue-card { width: 100%; max-width: 320px; }
      .time-display { 
        position: static; 
        margin: 0 auto 20px;
        width: fit-content; 
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <div class="header-section">
    <h1 class="hospital-title"><i class="bi bi-hospital"></i> RS HOKYA SEHAT</h1>
    <p class="subtitle">Nomor Antrian Yang Sedang Dipanggil</p>
  </div>

  <!-- Time Display -->
  <div class="time-display" id="timeDisplay"></div>

  <!-- Queue Container -->
  <div class="queue-container" id="queueContainer">
    <div class="empty-state">
      <i class="bi bi-hourglass-split"></i>
      <h3>Memuat data...</h3>
    </div>
  </div>

  <!-- Refresh Indicator -->
  <div class="refresh-indicator">
    <i class="bi bi-arrow-clockwise refresh-icon"></i>
    <span>Auto refresh 3 detik</span>
  </div>

  <script>
    const poliConfig = {
      'A': { name: 'Poli Anak', icon: 'bi-person-hearts' },
      'J': { name: 'Poli Jantung', icon: 'bi-heart-pulse' },
      'S': { name: 'Poli Syaraf', icon: 'bi-brain' },
      'P': { name: 'Poli Penyakit Dalam', icon: 'bi-capsule' },
      'G': { name: 'Poli Gigi', icon: 'bi-tooth' }
    };

    const statusMap = {
      'called': 'Dipanggil',
      'ongoing': 'Sedang Diperiksa'
    };

    // Update time display
    function updateTime() {
      const now = new Date();
      const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      };
      document.getElementById('timeDisplay').textContent = now.toLocaleDateString('id-ID', options);
    }
    setInterval(updateTime, 1000);
    updateTime();

    // Fetch queue data for all poli
    async function fetchAllQueues() {
      try {
        const res = await fetch('get_antrian.php?poli=ALL');
        const data = await res.json();
        
        if (data.error) {
          throw new Error(data.error);
        }
        
        // Filter hanya yang sedang dipanggil atau sedang diperiksa
        const activeQueues = data.filter(q => q.status === 'called' || q.status === 'ongoing');
        
        if (activeQueues.length === 0) {
          document.getElementById('queueContainer').innerHTML = `
            <div class="empty-state">
              <i class="bi bi-inbox"></i>
              <h3>Belum Ada Antrian Aktif</h3>
              <p>Tidak ada pasien yang sedang dipanggil saat ini</p>
            </div>
          `;
          return;
        }
        
        // Render cards
        let html = '';
        activeQueues.forEach(q => {
          const poli = poliConfig[q.kode_poli];
          const statusLabel = statusMap[q.status] || q.status;
          
          html += `
            <div class="queue-card poli-${q.kode_poli}">
              <div class="poli-label">${poli ? poli.name : 'Poli ' + q.kode_poli}</div>
              <div class="queue-number">${q.display}</div>
              <div class="queue-patient">
                <i class="bi bi-person"></i>
                <span>${q.nama_pasien || 'Pasien'}</span>
              </div>
              <div class="queue-status">
                <i class="bi bi-megaphone-fill"></i> ${statusLabel}
              </div>
            </div>
          `;
        });
        
        document.getElementById('queueContainer').innerHTML = html;
        
      } catch (error) {
        console.error('Error fetching queues:', error);
        document.getElementById('queueContainer').innerHTML = `
          <div class="empty-state">
            <i class="bi bi-exclamation-triangle"></i>
            <h3>Terjadi Kesalahan</h3>
            <p>Gagal memuat data antrian: ${error.message}</p>
          </div>
        `;
      }
    }

    // Initial load and auto refresh
    fetchAllQueues();
    setInterval(fetchAllQueues, 3000);
  </script>
</body>
</html>