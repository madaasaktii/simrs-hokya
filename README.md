# 🏥 SIMRS (Sistem Informasi Manajemen Rumah Sakit) - RS Hokya Sehat

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

**Sistem Antrian dan Manajemen Pasien Terintegrasi**

[Features](#-fitur-utama) • [Installation](#-instalasi) • [Usage](#-penggunaan) • [Screenshots](#-screenshots) • [Documentation](#-dokumentasi)

</div>

---

## 📋 Deskripsi

SIMRS adalah sistem informasi manajemen rumah sakit yang dirancang untuk mengoptimalkan alur pelayanan pasien dari pendaftaran hingga pemeriksaan dokter. Sistem ini menyediakan antarmuka yang berbeda untuk setiap role (Admin, Perawat, Dokter) dengan fitur-fitur yang disesuaikan dengan kebutuhan masing-masing.

### 🎯 Tujuan Proyek

- Mempermudah manajemen antrian pasien di berbagai poliklinik
- Mendigitalisasi proses anamnesis dan pemeriksaan
- Menyediakan layar display antrian real-time untuk pasien
- Meningkatkan efisiensi pelayanan rumah sakit

---

## ✨ Fitur Utama

### 👥 Multi-Role System

#### 🔐 **Admin Dashboard**
- Manajemen user (Dokter, Perawat)
- Monitoring antrian seluruh poliklinik
- Statistik dan laporan harian

#### 💉 **Perawat Dashboard**
- **Manajemen Antrian Pasien**
  - Melihat daftar antrian menunggu
  - Memanggil pasien (status: `waiting` → `called`)
  - Input data anamnesis awal
- **Monitoring Real-time**
  - Antrian menunggu
  - Pasien dipanggil (sedang anamnesis)
  - Pasien sedang diperiksa dokter
  - Pasien selesai hari ini
- **Form Anamnesis Lengkap**
  - Berat badan, tinggi badan
  - Tekanan darah, suhu tubuh, nadi
  - Keluhan utama pasien

#### 🩺 **Dokter Dashboard**
- **Antrian Pasien per Poli**
  - Auto-refresh setiap 3 detik
  - Filter otomatis berdasarkan poli dokter
  - Status real-time (Dipanggil)
- **Form Pemeriksaan Lengkap**
  - View data anamnesis dari perawat
  - Input diagnosis
  - Resep obat
  - Catatan tambahan
  - Timer durasi pemeriksaan otomatis
- **Manajemen Status**
  - Mulai periksa: `called` → `ongoing`
  - Selesai periksa: `ongoing` → `done`

### 🎫 Sistem Antrian

- **5 Poliklinik Tersedia:**
  - 🧒 Poli Anak (A)
  - ❤️ Poli Jantung (J)
  - 🧠 Poli Syaraf (S)
  - 💊 Poli Penyakit Dalam (P)
  - 🦷 Poli Gigi (G)

- **Layar Antrian Public Display**
  - Tampilan modern dan responsif
  - Menampilkan nomor antrian yang sedang dipanggil
  - Auto-refresh real-time
  - Kartu visual per poliklinik dengan warna berbeda

### 📝 Pendaftaran Pasien

- **Form Pendaftaran Lengkap**
  - NIK (16 digit)
  - Data pribadi (nama, tempat/tanggal lahir, gender)
  - Kontak (no. HP, alamat)
  - Pilihan poliklinik
  - Cara bayar (Umum, BPJS, Asuransi)
- **Auto-Generate Nomor Antrian**
  - Format: `[KODE_POLI][NOMOR_3DIGIT]` (contoh: A001, J002)
  - Unique per hari per poli

### 🔄 Alur Sistem
