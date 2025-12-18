// =========================
// PWA + Notifikasi Reminder
// =========================

function isHttpsOrLocalhost() {
  return location.protocol === "https:" || location.hostname === "localhost";
}

async function registerServiceWorker() {
  if (!("serviceWorker" in navigator)) return false;
  try {
    await navigator.serviceWorker.register("/sw.js");
    return true;
  } catch (e) {
    console.warn("SW register gagal:", e);
    return false;
  }
}

async function requestNotifPermission() {
  if (!("Notification" in window)) {
    alert("Browser ini tidak support notifikasi.");
    return false;
  }

  if (!isHttpsOrLocalhost()) {
    alert("Notifikasi butuh HTTPS. Aktifkan SSL dulu ya (InfinityFree + SSL).");
    return false;
  }

  const permission = await Notification.requestPermission();
  if (permission !== "granted") {
    alert("Izin notifikasi ditolak. Aktifkan dari setting browser kalau mau.");
    return false;
  }
  return true;
}

/**
 * Simpan reminder lokal.
 * @param {Object} data
 *  - name: nama pasien
 *  - poli: poli
 *  - queue: no antrian
 *  - timeISO: waktu kunjungan ISO (contoh: 2025-12-17T14:30)
 *  - minutesBefore: berapa menit sebelum kunjungan
 */
function saveReminder(data) {
  localStorage.setItem("simrs_reminder", JSON.stringify(data));
}

/**
 * Ambil reminder lokal
 */
function getReminder() {
  try {
    return JSON.parse(localStorage.getItem("simrs_reminder"));
  } catch {
    return null;
  }
}

function clearReminder() {
  localStorage.removeItem("simrs_reminder");
}

function showNotif(title, body) {
  try {
    new Notification(title, {
      body,
      icon: "/assets/img/favicon.png"
    });
  } catch (e) {
    console.warn("Notif gagal:", e);
  }
}

/**
 * Jadwalkan notif (jalan paling aman saat halaman/PWA masih terbuka)
 */
function scheduleReminder() {
  const r = getReminder();
  if (!r) return;

  const target = new Date(r.timeISO);
  const remindAt = new Date(target.getTime() - (r.minutesBefore * 60 * 1000));

  const now = new Date();
  const ms = remindAt.getTime() - now.getTime();

  // Kalau sudah lewat, jangan spam
  if (ms <= 0) {
    // Kalau target kunjungan masih di depan, notif sekarang aja
    if (target.getTime() > now.getTime()) {
      showNotif("Pengingat Jadwal RS", `Jadwal kamu sebentar lagi (${r.minutesBefore} menit). Poli ${r.poli}, antrian #${r.queue}.`);
    }
    return;
  }

  // Set timeout untuk notif
  setTimeout(() => {
    showNotif("Pengingat Jadwal RS", `Dalam ${r.minutesBefore} menit kamu akan periksa. Poli ${r.poli}, antrian #${r.queue}.`);
  }, ms);
}

/**
 * Helper: bikin UI kecil di halaman untuk aktifkan pengingat
 * Panggil initReminderUI() dari halaman pendaftaran / halaman sukses
 */
async function initReminderUI(options = {}) {
  // options default
  const cfg = {
    mountId: options.mountId || "reminderBox",
    name: options.name || "",
    poli: options.poli || "",
    queue: options.queue || "",
    dateInputId: options.dateInputId || "tgl_kunjungan",
    timeInputId: options.timeInputId || null // optional
  };

  const mount = document.getElementById(cfg.mountId);
  if (!mount) return;

  mount.innerHTML = `
    <div class="card border-0 shadow-sm mt-3">
      <div class="card-body">
        <div class="d-flex align-items-start gap-2">
          <div style="font-size:22px">🔔</div>
          <div class="w-100">
            <div class="fw-bold">Pengingat Jadwal</div>
            <div class="text-muted" style="font-size:14px">
              Notif akan muncul <b>kalau web/PWA masih kebuka</b>. (Versi demo)
            </div>

            <div class="row g-2 mt-2">
              <div class="col-6 col-md-4">
                <label class="form-label small mb-1">Ingatkan</label>
                <select id="remindMinutes" class="form-select form-select-sm">
                  <option value="60">60 menit sebelum</option>
                  <option value="30" selected>30 menit sebelum</option>
                  <option value="15">15 menit sebelum</option>
                  <option value="10">10 menit sebelum</option>
                  <option value="5">5 menit sebelum</option>
                </select>
              </div>

              <div class="col-6 col-md-4">
                <label class="form-label small mb-1">Jam (opsional)</label>
                <input id="remindTime" type="time" class="form-control form-control-sm" value="09:00">
              </div>

              <div class="col-12 col-md-4 d-flex align-items-end">
                <button id="btnEnableReminder" class="btn btn-primary btn-sm w-100">
                  Aktifkan Notifikasi
                </button>
              </div>
            </div>

            <div class="d-flex gap-2 mt-2">
              <button id="btnTestNotif" class="btn btn-outline-secondary btn-sm">Test Notif</button>
              <button id="btnClearReminder" class="btn btn-outline-danger btn-sm">Hapus Pengingat</button>
            </div>

            <div id="reminderStatus" class="mt-2 text-muted" style="font-size:13px"></div>
          </div>
        </div>
      </div>
    </div>
  `;

  // register SW
  await registerServiceWorker();

  // handlers
  const statusEl = document.getElementById("reminderStatus");

  document.getElementById("btnTestNotif").addEventListener("click", async () => {
    const ok = await requestNotifPermission();
    if (!ok) return;
    showNotif("Test Notifikasi SIMRS", "Notif berhasil 🎉");
  });

  document.getElementById("btnClearReminder").addEventListener("click", () => {
    clearReminder();
    statusEl.textContent = "Pengingat dihapus.";
  });

  document.getElementById("btnEnableReminder").addEventListener("click", async () => {
    const ok = await requestNotifPermission();
    if (!ok) return;

    // Ambil tanggal dari input pendaftaran jika ada
    let dateStr = "";
    const dateInput = document.getElementById(cfg.dateInputId);
    if (dateInput) dateStr = dateInput.value; // yyyy-mm-dd

    const timeStr = document.getElementById("remindTime").value || "09:00";
    const minutesBefore = parseInt(document.getElementById("remindMinutes").value, 10);

    if (!dateStr) {
      alert("Isi dulu tanggal kunjungan ya.");
      return;
    }

    // Gabungkan jadi ISO lokal
    const timeISO = `${dateStr}T${timeStr}`;

    saveReminder({
      name: cfg.name,
      poli: cfg.poli,
      queue: cfg.queue,
      timeISO,
      minutesBefore
    });

    scheduleReminder();

    statusEl.innerHTML = `Pengingat aktif ✅<br>Jadwal: <b>${dateStr} ${timeStr}</b> | ${minutesBefore} menit sebelum`;
  });

  // auto schedule jika ada reminder lama
  const old = getReminder();
  if (old) {
    statusEl.innerHTML = `Pengingat tersimpan ✅<br>Jadwal: <b>${old.timeISO.replace("T"," ")}</b> | ${old.minutesBefore} menit sebelum`;
    scheduleReminder();
  }
}

// expose ke global biar bisa dipanggil dari halaman
window.initReminderUI = initReminderUI;
