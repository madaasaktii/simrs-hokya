document.addEventListener('DOMContentLoaded', function () {
  const borCanvas = document.getElementById('borChart');
  let borChart = null;

  function animateCount(id, end) {
    const el = document.getElementById(id);
    if (!el) return;
    let start = 0;
    const duration = 700;
    const stepTime = Math.max(20, Math.floor(duration / Math.max(1, end)));
    const timer = setInterval(function () {
      start += Math.ceil(end / (duration / stepTime));
      if (start >= end) { el.textContent = end; clearInterval(timer); }
      else el.textContent = start;
    }, stepTime);
  }

  function getCssVar(name){ return getComputedStyle(document.documentElement).getPropertyValue(name).trim() || '#1977cc'; }

  function renderBorChart(value){
    const accent = getCssVar('--accent-color');
    const data = [value || 0, 100 - (value || 0)];
    if(!borCanvas) return;
    if(borChart) { borChart.data.datasets[0].data = data; borChart.update(); return; }
    borChart = new Chart(borCanvas, { type: 'doughnut', data: { labels: ['Terisi', 'Kosong'], datasets: [{ data: data, backgroundColor: [accent, 'rgba(0,0,0,0.04)'] }] }, options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{position:'bottom'}} } });
  }

  async function fetchStats(){
    try{ const res = await fetch('get_dashboard_stats.php'); if(!res.ok) throw new Error('Network error'); const json = await res.json(); animateCount('count-rj', json.total_patients - json.igd); animateCount('count-ri', 0); animateCount('count-igd', json.igd || 0); document.getElementById('bor-percent').textContent = (json.bor_percent !== null) ? json.bor_percent + '%' : 'N/A'; document.getElementById('last-updated').textContent = json.last_updated || new Date().toLocaleString(); renderBorChart(json.bor_percent || 0); }catch(e){ console.error('Failed to load stats', e); } }

  async function fetchQueue(){ try{ const res = await fetch('get_list_antrian_html.php'); const html = await res.text(); const container = document.getElementById('queue-container'); if(container) container.innerHTML = html; }catch(e){ console.error('Failed to load queue', e); } }

  async function fetchPatients(){ try{ const res = await fetch('get_pasien_dokter.php'); const html = await res.text(); const container = document.getElementById('patient-container'); if(container) container.innerHTML = html; }catch(e){ console.error('Failed to load patients', e); } }

  async function fetchAll(){ await Promise.all([fetchStats(), fetchQueue(), fetchPatients()]); }

  fetchAll();
  const btn = document.getElementById('btn-refresh'); if (btn) btn.addEventListener('click', function () { fetchAll(); });
  document.querySelectorAll('.slide-fade').forEach(function (el) { setTimeout(function () { el.classList.add('show'); }, 120); });
});