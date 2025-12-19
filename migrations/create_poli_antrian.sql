-- Migration: create poli table, per-poli metadata tables and antrian

CREATE TABLE IF NOT EXISTS poli (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code CHAR(1) NOT NULL UNIQUE,
  name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Standardized poli codes for this combined implementation:
-- A = Anak, J = Jantung, S = Syaraf, P = Penyakit Dalam, G = Gigi
INSERT IGNORE INTO poli (code, name) VALUES
('A','Anak'), ('J','Jantung'), ('S','Syaraf'), ('P','Penyakit Dalam'), ('G','Gigi');

CREATE TABLE IF NOT EXISTS antrian (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nomor INT NOT NULL,
  kode_poli CHAR(1) NOT NULL,
  pasien_id INT NULL,
  nama_pasien VARCHAR(200) DEFAULT NULL,
  dokter_tujuan VARCHAR(100) DEFAULT NULL,
  status ENUM('waiting','called','ongoing','done','cancelled') DEFAULT 'waiting',
  hari DATE NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_poli_hari (kode_poli, hari, nomor),
  CONSTRAINT fk_antrian_poli FOREIGN KEY (kode_poli) REFERENCES poli(code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE antrian ADD UNIQUE IF NOT EXISTS uq_poli_hari_nomor (kode_poli, hari, nomor);

-- Create per-poli metadata tables (doctors / schedules). These store additional data per specialty.
CREATE TABLE IF NOT EXISTS poli_anak (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dokter VARCHAR(100) NOT NULL,
  note VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS poli_jantung (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dokter VARCHAR(100) NOT NULL,
  note VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS poli_penyakit_dalam (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dokter VARCHAR(100) NOT NULL,
  note VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS poli_gigi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dokter VARCHAR(100) NOT NULL,
  note VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS poli_syaraf (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dokter VARCHAR(100) NOT NULL,
  note VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed some sample doctors per poli (INSERT IGNORE will avoid duplicates when re-running)
INSERT IGNORE INTO poli_anak (dokter) VALUES ('Dr. Anita'), ('Dr. Budi');
INSERT IGNORE INTO poli_jantung (dokter) VALUES ('Dr. Cardi'), ('Dr. Hartono');
INSERT IGNORE INTO poli_penyakit_dalam (dokter) VALUES ('Dr. Santi'), ('Dr. Riza');
INSERT IGNORE INTO poli_gigi (dokter) VALUES ('Dr. Gigi A'), ('Dr. Gigi B');
INSERT IGNORE INTO poli_syaraf (dokter) VALUES ('Dr. Neuro'), ('Dr. Syafiq');
