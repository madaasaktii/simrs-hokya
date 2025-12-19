-- Migration: create perawat table
CREATE TABLE IF NOT EXISTS `perawat` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `perawat` VARCHAR(150) NOT NULL,
  `username` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- sample perawat entries
INSERT INTO `perawat` (`perawat`, `username`) VALUES
('Perawat Siti', NULL),
('Perawat Rina', NULL)
ON DUPLICATE KEY UPDATE perawat = VALUES(perawat);