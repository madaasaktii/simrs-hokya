-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Des 2025 pada 11.24
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simrs`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pasien`
--

CREATE TABLE `pasien` (
  `id` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tgl_kunjungan` date DEFAULT NULL,
  `poli` varchar(50) DEFAULT NULL,
  `dokter_tujuan` varchar(100) DEFAULT NULL,
  `cara_bayar` varchar(20) DEFAULT NULL,
  `no_bpjs` varchar(25) DEFAULT NULL,
  `status_antrian` enum('Menunggu','Dipanggil','Selesai') DEFAULT 'Menunggu',
  `waktu_panggil` datetime DEFAULT NULL,
  `diagnosa` text DEFAULT NULL,
  `resep` text DEFAULT NULL,
  `no_antrian` int(11) NOT NULL,
  `waktu_daftar` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_daftar` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pasien`
--

INSERT INTO `pasien` (`id`, `nik`, `nama`, `tempat_lahir`, `tgl_lahir`, `jenis_kelamin`, `no_hp`, `alamat`, `tgl_kunjungan`, `poli`, `dokter_tujuan`, `cara_bayar`, `no_bpjs`, `status_antrian`, `waktu_panggil`, `diagnosa`, `resep`, `no_antrian`, `waktu_daftar`, `tanggal_daftar`) VALUES
(1, '5151221011', 'mada', 'gresik', '2004-01-01', 'L', '00000000000000', 'jalan zamrud', '2025-12-16', 'Gigi', NULL, 'Umum', '', 'Selesai', NULL, NULL, NULL, 0, '2025-12-15 07:34:29', '2025-12-15'),
(2, '1111111111111111', 'sakti', 'gresik', '2003-12-31', 'L', '111111111111', 'jalan mirah delima', '2025-12-16', 'Umum', NULL, 'Umum', '', 'Selesai', NULL, NULL, NULL, 0, '2025-12-15 08:09:12', '2025-12-15'),
(3, '1', 'bowo', 'gresik', '2003-12-30', 'L', '22222222222', 'jalan mutiara', '2025-12-16', 'Umum', NULL, 'Umum', '', 'Selesai', NULL, NULL, NULL, 0, '2025-12-15 08:13:03', '2025-12-15'),
(4, '11111111111111', 'mada', 'gresik', '2003-12-31', 'L', '111111111111', 'jalan merah delima', '2025-12-16', 'Umum', NULL, 'Umum', '', 'Selesai', NULL, NULL, NULL, 0, '2025-12-15 14:42:23', '2025-12-15'),
(5, '111111111111111', 'mada', 'gresik', '2003-12-31', 'L', '111111111111', 'jalan merah delima', '2025-12-16', 'Umum', NULL, 'Umum', '', 'Selesai', NULL, NULL, NULL, 0, '2025-12-15 14:46:08', '2025-12-15'),
(6, '111111111111', 'mada', 'gresik', '2024-02-01', 'L', '22222222222', 'jalan mutiara', '2025-12-16', 'Anak', NULL, 'Umum', '', 'Selesai', NULL, NULL, NULL, 0, '2025-12-15 15:02:05', '2025-12-15'),
(7, '1111111111111111', 'IPF FKK', 'gresik', '2024-01-15', 'L', '22222222222', 'jalan bambe', '2025-12-17', 'Anak', NULL, 'Umum', '', 'Selesai', NULL, NULL, NULL, 0, '2025-12-15 15:19:41', '2025-12-15'),
(8, '1111111111111111', 'mada', 'gresik', '2002-01-14', 'L', '22222222222', 'jalan permata', '2025-12-16', 'Umum', NULL, 'Umum', '', 'Selesai', NULL, NULL, NULL, 1, '2025-12-15 15:33:49', '2025-12-15'),
(9, '11111111111111', 'IPF FKK', 'gresik', '2023-02-02', 'L', '111111111111', 'jalan raya cangkir', '2025-12-16', 'Anak', NULL, 'Umum', '', 'Selesai', NULL, NULL, NULL, 2, '2025-12-15 15:34:56', '2025-12-15'),
(10, '11111111111111', 'IPF FKK', 'gresik', '2023-02-02', 'L', '111111111111', 'jalan raya cangkir', '2025-12-16', 'Anak', NULL, 'Umum', '', 'Selesai', NULL, NULL, NULL, 3, '2025-12-15 15:37:18', '2025-12-15'),
(11, '11111111111111111', 'mada', 'gresik', '2003-01-04', 'L', '111111111111', 'jalan pirus ', '2025-12-16', 'Umum', 'dr. Budi Santoso', 'Umum', '', 'Selesai', NULL, NULL, NULL, 4, '2025-12-15 15:58:30', '2025-12-15'),
(12, '111111111111111', 'mada', 'gresik', '2006-03-02', 'L', '111111111111', 'jalan kalimaya', '2025-12-16', 'Umum', 'dr. Budi Santoso', 'Umum', '', 'Selesai', NULL, NULL, NULL, 5, '2025-12-15 16:06:19', '2025-12-15'),
(13, '111111111111111111', 'IPF FKK', 'gresik', '2023-04-02', 'P', '333333333333333', 'jalan intan', '2025-12-16', 'Anak', 'dr. Rina Kartika, Sp.A', 'Umum', '', 'Selesai', NULL, NULL, NULL, 4, '2025-12-15 16:18:26', '2025-12-15'),
(14, '1111111111111111111', 'xixi', 'solo', '1994-05-19', 'P', '444444444444444', 'jalan giok', '2025-12-16', 'Umum', 'dr. Budi Santoso', 'Umum', '', 'Selesai', NULL, NULL, NULL, 6, '2025-12-15 16:21:44', '2025-12-15'),
(15, '22222222222222222', 'haha', 'tuban', '1995-09-09', 'L', '555555555555555', 'jalan ngasem', '2025-12-16', 'Umum', 'dr. Siti Aminah', 'Umum', '', 'Selesai', NULL, NULL, NULL, 7, '2025-12-15 16:23:32', '2025-12-15'),
(16, '55555555555555', 'joko', 'pati', '1990-03-03', 'L', '666666666666666', 'jalan maospati', '2025-12-16', 'Jantung', 'dr. Tirta, Sp.JP', 'Umum', '', 'Selesai', NULL, NULL, NULL, 1, '2025-12-15 16:42:17', '2025-12-15'),
(17, '1111111111111111', 'mada', 'gresik', '2005-06-06', 'L', '666666666666666', 'jalan zamrud', '2025-12-16', 'Gigi', 'drg. Ratna Sari', 'Umum', '', 'Selesai', NULL, NULL, NULL, 1, '2025-12-15 16:57:53', '2025-12-15'),
(18, '111111111111111111', 'cindy', 'malang', '2022-03-03', 'P', '111111111111', 'lawang', '2025-12-16', 'Anak', 'dr. Rina Kartika, Sp.A', 'Umum', '', 'Selesai', NULL, 'panas karena gigi tumbuh', 'paracetamol', 5, '2025-12-15 16:58:54', '2025-12-15'),
(19, '1111111111111', 'yanto', 'ambon', '1982-11-02', 'L', '111111111111', 'jalan gelap', '2025-12-16', 'Jantung', 'dr. Tirta, Sp.JP', 'Umum', '', 'Selesai', '2025-12-16 00:13:40', 'nyeri di dada', 'asamefenamat', 2, '2025-12-15 17:12:32', '2025-12-15'),
(20, '1111111111111111', 'yanti', 'maluku', '1988-05-03', 'L', '22222222222', 'jalan merauke', '2025-12-16', 'Kandungan', 'dr. Boyke, Sp.OG', 'Umum', '', 'Selesai', '2025-12-16 00:14:45', 'panas karena gigi tumbuh', 'paracetamol', 1, '2025-12-15 17:14:36', '2025-12-15'),
(21, '1', 'diki', 'palembang', '2002-12-18', 'L', '111111111111', 'jalan sapi', '2025-12-16', 'Gigi', 'drg. Ratna Sari', 'Umum', '', 'Selesai', '2025-12-16 00:24:50', 'lobang, perlu tambal', 'asamefenamat dan antibiotik', 2, '2025-12-15 17:24:24', '2025-12-15'),
(22, '1111111111111111', 'mada', 'gresik', '2001-02-02', 'L', '111111111111', 'jalam tekkim', '2025-12-17', 'Gigi', 'drg. Andi Pratama', 'Umum', '', 'Dipanggil', '2025-12-16 11:48:51', NULL, NULL, 1, '2025-12-16 04:48:13', '2025-12-16');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
