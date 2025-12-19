-- Seed sample data for testing
INSERT INTO antrian (nomor, kode_poli, nama_pasien, dokter_tujuan, status, hari) VALUES
(1,'A','Budi','Dr. Anita', 'waiting', CURDATE()),
(2,'A','Siti','Dr. Budi', 'waiting', CURDATE()),
(1,'J','Andi','Dr. Cardi', 'waiting', CURDATE()),
(1,'G','Rina','Dr. Gigi A', 'waiting', CURDATE());

-- Sample entries in per-poli tables (if migration did not insert)
INSERT IGNORE INTO poli_anak (dokter) VALUES ('Dr. Anita'), ('Dr. Budi');
INSERT IGNORE INTO poli_jantung (dokter) VALUES ('Dr. Cardi');
INSERT IGNORE INTO poli_gigi (dokter) VALUES ('Dr. Gigi A');
