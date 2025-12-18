-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2025 at 01:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `antrian`
--

CREATE TABLE `antrian` (
  `id` int(11) NOT NULL,
  `nomor` int(11) NOT NULL,
  `kode_poli` char(1) NOT NULL,
  `pasien_id` int(11) DEFAULT NULL,
  `nama_pasien` varchar(200) DEFAULT NULL,
  `dokter_tujuan` varchar(100) DEFAULT NULL,
  `status` enum('waiting','called','ongoing','done','cancelled') DEFAULT 'waiting',
  `hari` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `antrian`
--

INSERT INTO `antrian` (`id`, `nomor`, `kode_poli`, `pasien_id`, `nama_pasien`, `dokter_tujuan`, `status`, `hari`, `created_at`, `updated_at`) VALUES
(1, 1, 'A', NULL, 'Test User', 'Dr. Anita', 'waiting', '2025-12-17', '2025-12-18 04:54:23', '2025-12-18 04:54:23');

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran_pasien`
--

CREATE TABLE `pendaftaran_pasien` (
  `id` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `rencana_kunjungan` date NOT NULL,
  `poli` varchar(100) NOT NULL,
  `cara_bayar` enum('Umum','BPJS','Asuransi') NOT NULL,
  `no_bpjs` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendaftaran_pasien`
--

INSERT INTO `pendaftaran_pasien` (`id`, `nik`, `nama`, `tempat_lahir`, `tgl_lahir`, `jenis_kelamin`, `no_hp`, `alamat`, `rencana_kunjungan`, `poli`, `cara_bayar`, `no_bpjs`, `created_at`) VALUES
(0, '3213134123', 'sfsfss', 'swwew', '2025-12-19', 'L', '21312312', 'rqweqe', '2025-12-18', 'Poli Gigi', '', '', '2025-12-18 11:09:52');

-- --------------------------------------------------------

--
-- Table structure for table `perawat`
--

CREATE TABLE `perawat` (
  `id` int(11) NOT NULL,
  `perawat` varchar(150) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perawat`
--

INSERT INTO `perawat` (`id`, `perawat`, `username`, `created_at`) VALUES
(1, 'Perawat Siti', NULL, '2025-12-17 23:49:25'),
(2, 'Perawat Rina', NULL, '2025-12-17 23:49:25');

-- --------------------------------------------------------

--
-- Table structure for table `poli`
--

CREATE TABLE `poli` (
  `id` int(11) NOT NULL,
  `code` char(1) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poli`
--

INSERT INTO `poli` (`id`, `code`, `name`, `created_at`) VALUES
(1, 'A', 'Anak', '2025-12-17 21:53:22'),
(2, 'J', 'Jantung', '2025-12-17 21:53:22'),
(3, 'S', 'Syaraf', '2025-12-17 21:53:22'),
(4, 'P', 'Penyakit Dalam', '2025-12-17 21:53:22'),
(5, 'G', 'Gigi', '2025-12-17 21:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `poli_anak`
--

CREATE TABLE `poli_anak` (
  `id` int(11) NOT NULL,
  `dokter` varchar(100) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poli_anak`
--

INSERT INTO `poli_anak` (`id`, `dokter`, `note`, `created_at`) VALUES
(1, 'Dr. Anita', NULL, '2025-12-17 21:53:22'),
(2, 'Dr. Budi', NULL, '2025-12-17 21:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `poli_gigi`
--

CREATE TABLE `poli_gigi` (
  `id` int(11) NOT NULL,
  `dokter` varchar(100) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poli_gigi`
--

INSERT INTO `poli_gigi` (`id`, `dokter`, `note`, `created_at`) VALUES
(1, 'Dr. Gigi A', NULL, '2025-12-17 21:53:22'),
(2, 'Dr. Gigi B', NULL, '2025-12-17 21:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `poli_jantung`
--

CREATE TABLE `poli_jantung` (
  `id` int(11) NOT NULL,
  `dokter` varchar(100) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poli_jantung`
--

INSERT INTO `poli_jantung` (`id`, `dokter`, `note`, `created_at`) VALUES
(1, 'Dr. Cardi', NULL, '2025-12-17 21:53:22'),
(2, 'Dr. Hartono', NULL, '2025-12-17 21:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `poli_penyakit_dalam`
--

CREATE TABLE `poli_penyakit_dalam` (
  `id` int(11) NOT NULL,
  `dokter` varchar(100) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poli_penyakit_dalam`
--

INSERT INTO `poli_penyakit_dalam` (`id`, `dokter`, `note`, `created_at`) VALUES
(1, 'Dr. Santi', NULL, '2025-12-17 21:53:22'),
(2, 'Dr. Riza', NULL, '2025-12-17 21:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `poli_syaraf`
--

CREATE TABLE `poli_syaraf` (
  `id` int(11) NOT NULL,
  `dokter` varchar(100) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poli_syaraf`
--

INSERT INTO `poli_syaraf` (`id`, `dokter`, `note`, `created_at`) VALUES
(1, 'Dr. Neuro', NULL, '2025-12-17 21:53:22'),
(2, 'Dr. Syafiq', NULL, '2025-12-17 21:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(150) NOT NULL,
  `role` enum('admin','dokter','perawat') NOT NULL DEFAULT 'dokter',
  `poli_code` char(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `role`, `poli_code`, `created_at`) VALUES
(1, 'perawat.siti', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Perawat Siti', 'perawat', NULL, '2025-12-17 23:50:22'),
(2, 'perawat.rina', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Perawat Rina', 'perawat', NULL, '2025-12-17 23:50:22'),
(3, 'admin', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Administrator', 'admin', NULL, '2025-12-17 23:50:22'),
(4, 'perawat', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Perawat Default', 'perawat', NULL, '2025-12-17 23:50:22'),
(5, 'dr.anita', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Dr. Anita', 'dokter', 'A', '2025-12-17 23:50:22'),
(6, 'dr.budi', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Dr. Budi', 'dokter', 'A', '2025-12-17 23:50:22'),
(7, 'dr.cardi', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Dr. Cardi', 'dokter', 'J', '2025-12-17 23:50:22'),
(8, 'dr.hartono', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Dr. Hartono', 'dokter', 'J', '2025-12-17 23:50:22'),
(9, 'dr.santi', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Dr. Santi', 'dokter', 'P', '2025-12-17 23:50:23'),
(10, 'dr.riza', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Dr. Riza', 'dokter', 'P', '2025-12-17 23:50:23'),
(11, 'dr.gigi.a', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Dr. Gigi A', 'dokter', 'G', '2025-12-17 23:50:23'),
(12, 'dr.gigi.b', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Dr. Gigi B', 'dokter', 'G', '2025-12-17 23:50:23'),
(13, 'dr.neuro', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Dr. Neuro', 'dokter', 'S', '2025-12-17 23:50:23'),
(14, 'dr.syafiq', '$2y$10$tt8ORLemf2nR0mJOlTz3nu7AHzcVbF3DImghn/TMfzycWLxoO1aTW', 'Dr. Syafiq', 'dokter', 'S', '2025-12-17 23:50:23');

-- --------------------------------------------------------

--
-- Table structure for table `users_password_backup`
--

CREATE TABLE `users_password_backup` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `old_password` varchar(255) NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_password_backup`
--

INSERT INTO `users_password_backup` (`id`, `user_id`, `username`, `old_password`, `changed_at`) VALUES
(1, 1, 'perawat.siti', '$2y$10$yl29Cr9Ht.auh3E8GbkOmO4kOM.yCDQ2wHRFCNEiy0pPAHJcs9nCy', '2025-12-18 00:04:17'),
(2, 2, 'perawat.rina', '$2y$10$LSy25QqWBrQUtYafUif7c.XXWE0WiFl0uGdqd14tTiDbgrNMZbZPy', '2025-12-18 00:04:17'),
(3, 3, 'admin', '$2y$10$1d7mZRApywAmuWpxMdz.7.VeGQ2CtZmN1u0TVZWeNKksfjB6YChG.', '2025-12-18 00:04:17'),
(4, 4, 'perawat', '$2y$10$WQ7PshASWk/tIiblBilcYOLVFkguc1NOribVwKkAHkXSMUnIjzygC', '2025-12-18 00:04:17'),
(5, 5, 'dr.anita', '$2y$10$MJYEMNRkFKF9PFlfiVYUyeWMnzMkuyWcZqk/AUGjKEKZNsczY5fw2', '2025-12-18 00:04:17'),
(6, 6, 'dr.budi', '$2y$10$Yhmp3MppRn5GV59DWKt3SOzBs7AHtloVQ1c4Damhq.ZByJP3AgN2G', '2025-12-18 00:04:17'),
(7, 7, 'dr.cardi', '$2y$10$kJCczTd4vtjMAvO9w4oVd.dg.rWyTVlqEtjSVENFArRAVKp.S5rpe', '2025-12-18 00:04:17'),
(8, 8, 'dr.hartono', '$2y$10$qlLwBawlh3ryN5rnnGxmC.HaoFDkMYVNEQjQy3NIpzh3jRCuJdK42', '2025-12-18 00:04:17'),
(9, 9, 'dr.santi', '$2y$10$mXsVzWRviEM8OZsJMJ93guh55AU09MgmSQ5rbqXBifQkL70NPn3T.', '2025-12-18 00:04:17'),
(10, 10, 'dr.riza', '$2y$10$fQffV2QM/xyBdjICvZ90We3joFdKx6X62UKbWjoVCcqUXuH6SZ9kG', '2025-12-18 00:04:17'),
(11, 11, 'dr.gigi.a', '$2y$10$UmjifTzQwFvnMJpEMAJys.22jF6AlLu2ADmACYdY3igg8i7dEtfP.', '2025-12-18 00:04:17'),
(12, 12, 'dr.gigi.b', '$2y$10$/6/s1V/swowpBeQGmhpYb.VtYffX.euS3X4C6a.3.8IUBGTLlT3Uy', '2025-12-18 00:04:17'),
(13, 13, 'dr.neuro', '$2y$10$PBTELQpwYk7fPE9JZdCmIuXQkCfOWNMhvPn6px38PsDPfJ0elQ/LK', '2025-12-18 00:04:17'),
(14, 14, 'dr.syafiq', '$2y$10$ZOIYTywCpE1zIxy4EekfmO8YlAje0GNkIECWymZi/wekocmaisILm', '2025-12-18 00:04:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `antrian`
--
ALTER TABLE `antrian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_poli_hari_nomor` (`kode_poli`,`hari`,`nomor`),
  ADD KEY `idx_poli_hari` (`kode_poli`,`hari`,`nomor`);

--
-- Indexes for table `perawat`
--
ALTER TABLE `perawat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poli`
--
ALTER TABLE `poli`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `poli_anak`
--
ALTER TABLE `poli_anak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poli_gigi`
--
ALTER TABLE `poli_gigi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poli_jantung`
--
ALTER TABLE `poli_jantung`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poli_penyakit_dalam`
--
ALTER TABLE `poli_penyakit_dalam`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poli_syaraf`
--
ALTER TABLE `poli_syaraf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users_password_backup`
--
ALTER TABLE `users_password_backup`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `antrian`
--
ALTER TABLE `antrian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `perawat`
--
ALTER TABLE `perawat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `poli`
--
ALTER TABLE `poli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `poli_anak`
--
ALTER TABLE `poli_anak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `poli_gigi`
--
ALTER TABLE `poli_gigi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `poli_jantung`
--
ALTER TABLE `poli_jantung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `poli_penyakit_dalam`
--
ALTER TABLE `poli_penyakit_dalam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `poli_syaraf`
--
ALTER TABLE `poli_syaraf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users_password_backup`
--
ALTER TABLE `users_password_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `antrian`
--
ALTER TABLE `antrian`
  ADD CONSTRAINT `fk_antrian_poli` FOREIGN KEY (`kode_poli`) REFERENCES `poli` (`code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
