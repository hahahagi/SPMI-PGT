CREATE DATABASE db_spmi;

CREATE TABLE users (
 id_user INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL, 
 nama_lengkap VARCHAR(100) NOT NULL,
 role ENUM('admin', 'kaprodi', 'keuangan', 'akademik', 'penelitian', 'pengabdian') NOT NULL
);
CREATE TABLE permintaan (
 id_permintaan INT AUTO_INCREMENT PRIMARY KEY,
 judul VARCHAR(200) NOT NULL,
 deskripsi TEXT,
 tujuan_role ENUM('kaprodi', 'keuangan', 'akademik', 'penelitian', 'pengabdian') NOT NULL,
 tanggal_deadline DATE, STATUS ENUM('pending', 'selesai') DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE pengumpulan (
 id_pengumpulan INT AUTO_INCREMENT PRIMARY KEY,
 id_permintaan INT NOT NULL,
 id_user INT NOT NULL,
 file_path VARCHAR(255) NOT NULL,
 tanggal_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
 status_verifikasi ENUM('menunggu', 'diterima', 'revisi') DEFAULT 'menunggu',
 catatan_admin TEXT, FOREIGN KEY (id_permintaan) REFERENCES permintaan(id_permintaan) ON
DELETE CASCADE, FOREIGN KEY (id_user) REFERENCES users(id_user) ON
DELETE CASCADE
);

-- DATA DUMMY (Password: 123)
INSERT INTO users (username, password, nama_lengkap, role) VALUES 
('admin', MD5('123'), 'Administrator SPMI', 'admin'),
('kaprodi', MD5('123'), 'Kaprodi Teknik Mesin', 'kaprodi'),
('keuangan', MD5('123'), 'Staff Keuangan', 'keuangan');