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
      background: linear-gradient(135deg, #42a5f5 0%, #5dbbf7 100%);
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
      color: rgba(255, 255, 255, 0.95);
      font-size: 1.1rem;
      font-weight: 400;
    }
    
    .queue-container {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      gap: 30px;
      flex-wrap: wrap;
      max-width: 1600px;
      margin: 0 auto;
    }
    
    .queue-card {
      background: white;
      border-radius: 20px;
      padding: 35px 30px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.15);
      transition: all 0.3s ease;
      width: 320px;
      min-height: 280px;
      display: flex;
      flex-direction: column;
    }
    
    .queue-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 40px rgba(0,0,0,0.25);
    }
    
    .poli-header {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 25px;
    }
    
    .poli-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.5rem;
    }
    
    .poli-A .poli-icon { background: #1976d2; }
    .poli-J .poli-icon { background: #66bb6a; }
    .poli-S .poli-icon { background: #ffa726; }
    .poli-P .poli-icon { background: #1976d2; }
    .poli-G .poli-icon { background: #66bb6a; }
    
    .poli-name {
      font-size: 1.1rem;
      font-weight: 600;
      color: #333;
    }
    
    .queue-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }
    
    .queue-number {
      font-size: 4.5rem;
      font-weight: 700;
      color: #2C3E50;
      margin-bottom: 15px;
      line-height: 1;
      letter-spacing: -2px;
    }
    
    .poli-A .queue-number { color: #1976d2; }
    .poli-J .queue-number { color: #66bb6a; }
    .poli-S .queue-number { color: #ffa726; }
    .poli-P .queue-number { color: #1976d2; }
    .poli-G .queue-number { color: #66bb6a; }
    
    .queue-patient {
      font-size: 1rem;
      color: #555;
      font-weight: 500;
      margin-bottom: 15px;
    }
    
    .queue-badges {
      display: flex;
      gap: 8px;
      justify-content: center;
      flex-wrap: wrap;
    }
    
    .badge-custom {
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }
    
    .badge-loket {
      background: rgba(25, 118, 210, 0.15);
      color: #1976d2;
    }
    
    .badge-dokter {
      background: rgba(33, 150, 243, 0.15);
      color: #2196F3;
    }
    
    .empty-queue {
      color: #999;
      font-size: 1.1rem;
      font-style: italic;
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
    <p class="subtitle">Sistem Informasi Manajemen Rumah Sakit</p>
  </div>

  <!-- Time Display -->
  <div class="time-display" id="timeDisplay"></div>

  <!-- Queue Container -->
  <div class="queue-container" id="queueContainer">
    <!-- Cards akan di-generate oleh JavaScript -->
  </div>

  <script>
    const poliConfig = {
      'A': { name: 'Poli Anak', icon: 'bi-person-hearts' },
      'J': { name: 'Poli Jantung', icon: 'bi-heart-pulse' },
      'S': { name: 'Poli Syaraf', icon: 'bi-brain' },
      'P': { name: 'Poli Penyakit Dalam', icon: 'bi-capsule' },
      'G': { name: 'Poli Gigi', icon: 'bi-tooth' }
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
        
        // Group queues by poli and get only the first 'called' or 'ongoing' per poli
        const queueByPoli = {};
        
        data.forEach(q => {
          if ((q.status === 'called' || q.status === 'ongoing') && !queueByPoli[q.kode_poli]) {
            queueByPoli[q.kode_poli] = q;
          }
        });
        
        // Render cards for all poli (even if no active queue)
        let html = '';
        Object.keys(poliConfig).forEach(code => {
          const poli = poliConfig[code];
          const queue = queueByPoli[code];
          
          html += `
            <div class="queue-card poli-${code}">
              <div class="poli-header">
                <div class="poli-icon">
                  <i class="bi ${poli.icon}"></i>
                </div>
                <div class="poli-name">${poli.name}</div>
              </div>
              <div class="queue-content">
          `;
          
          if (queue) {
            // Ada antrian aktif - tampilkan nomor dan nama pasien
            html += `
              <div class="queue-number">${queue.display}</div>
              <div class="queue-patient">${queue.nama_pasien || '-'}</div>
            `;
          } else {
            // Tidak ada antrian - tampilkan pesan kosong
            html += `
              <div class="queue-number">${code}-000</div>
              <div class="empty-queue">Tidak ada antrian</div>
            `;
          }
          
          html += `
              </div>
            </div>
          `;
        });
        
        document.getElementById('queueContainer').innerHTML = html;
        
      } catch (error) {
        console.error('Error fetching queues:', error);
        // Even on error, show all poli cards with empty state
        let html = '';
        Object.keys(poliConfig).forEach(code => {
          const poli = poliConfig[code];
          html += `
            <div class="queue-card poli-${code}">
              <div class="poli-header">
                <div class="poli-icon">
                  <i class="bi ${poli.icon}"></i>
                </div>
                <div class="poli-name">${poli.name}</div>
              </div>
              <div class="queue-content">
                <div class="queue-number">${code}-000</div>
                <div class="empty-queue">Tidak ada antrian</div>
              </div>
            </div>
          `;
        });
        document.getElementById('queueContainer').innerHTML = html;
      }
    }

    // Initial load and auto refresh
    fetchAllQueues();
    setInterval(fetchAllQueues, 3000);
  </script>
</body>
</html>