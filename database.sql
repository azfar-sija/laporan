-- =============================================================
-- laporan_fasilitas_db : Sistem Pelaporan Kerusakan Fasilitas
--
-- 3 tabel yang saling berelasi (sudah 3NF):
--   kategori_fasilitas  (id_kategori PK)
--        1 ---< banyak
--   fasilitas           (id_fasilitas PK, id_kategori FK)
--        1 ---< banyak
--   laporan_kerusakan   (id_laporan PK, id_fasilitas FK)
--
-- Cara impor: phpMyAdmin / DBeaver -> jalankan seluruh isi file ini.
-- Aman dijalankan ulang: database dibuat ulang dan data kembali ke contoh awal.
-- =============================================================

DROP DATABASE IF EXISTS laporan_fasilitas_db;
CREATE DATABASE laporan_fasilitas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE laporan_fasilitas_db;

-- 1) Kategori fasilitas (tabel master)
CREATE TABLE kategori_fasilitas (
    id_kategori   INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL UNIQUE
);

-- 2) Fasilitas: satu fasilitas fisik di satu lokasi, milik satu kategori
CREATE TABLE fasilitas (
    id_fasilitas   INT AUTO_INCREMENT PRIMARY KEY,
    nama_fasilitas VARCHAR(100) NOT NULL,
    lokasi         VARCHAR(100) NOT NULL,
    id_kategori    INT NOT NULL,
    UNIQUE (nama_fasilitas, lokasi),
    FOREIGN KEY (id_kategori) REFERENCES kategori_fasilitas (id_kategori)
        ON UPDATE CASCADE ON DELETE RESTRICT
);

-- 3) Laporan kerusakan: satu fasilitas bisa punya banyak laporan
CREATE TABLE laporan_kerusakan (
    id_laporan      INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelapor    VARCHAR(100) NOT NULL,
    id_fasilitas    INT NOT NULL,
    deskripsi       TEXT NOT NULL,
    prioritas       ENUM('Rendah', 'Sedang', 'Tinggi') NOT NULL DEFAULT 'Sedang',
    status          ENUM('Menunggu', 'Diproses', 'Selesai') NOT NULL DEFAULT 'Menunggu',
    tanggal_laporan DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_fasilitas) REFERENCES fasilitas (id_fasilitas)
        ON UPDATE CASCADE ON DELETE RESTRICT
);

INSERT INTO kategori_fasilitas (id_kategori, nama_kategori) VALUES
    (1, 'Elektronik'),
    (2, 'Furnitur'),
    (3, 'Kebersihan & Sanitasi'),
    (4, 'Bangunan');

-- Fasilitas (ID 1-26 dipakai laporan contoh, sisanya belum pernah dilaporkan)
INSERT INTO fasilitas (id_fasilitas, nama_fasilitas, lokasi, id_kategori) VALUES
    (1, 'Proyektor Epson', 'Kelas XI RPL 1', 1),
    (2, 'Kursi Siswa', 'Perpustakaan', 2),
    (3, 'AC Panasonic', 'Lab Komputer 2', 1),
    (4, 'Wastafel', 'Toilet Lantai 2', 3),
    (5, 'Papan Tulis', 'Kelas X TKJ 2', 2),
    (6, 'Switch Jaringan', 'Lab Jaringan', 1),
    (7, 'Pintu Kelas', 'Kelas XII RPL 2', 4),
    (8, 'Printer Canon', 'Ruang Guru', 1),
    (9, 'Lampu TL', 'Kelas XI TKJ 1', 1),
    (10, 'Meja Guru', 'Kelas X RPL 3', 2),
    (11, 'Komputer PC-07', 'Lab Komputer 1', 1),
    (12, 'Kipas Angin', 'Kelas XII TKJ 1', 1),
    (13, 'Atap Koridor', 'Koridor Gedung B', 4),
    (14, 'Tempat Sampah', 'Kantin', 3),
    (15, 'Speaker Aktif', 'Aula', 1),
    (16, 'Rak Buku', 'Perpustakaan', 2),
    (17, 'Smart TV', 'Ruang Rapat', 1),
    (18, 'Keran Wudhu', 'Musala', 3),
    (19, 'Jendela', 'Kelas XI RPL 2', 4),
    (20, 'Mouse Komputer', 'Lab Komputer 1', 1),
    (21, 'Proyektor Hitachi', 'Lab Komputer 1', 1),
    (22, 'AC Daikin', 'Ruang Guru', 1),
    (23, 'Kursi Guru', 'Ruang Guru', 2),
    (24, 'Papan Tulis', 'Kelas XI RPL 1', 2),
    (25, 'Toilet Siswa', 'Toilet Lantai 1', 3),
    (26, 'Pintu Lab', 'Lab Jaringan', 4);

-- Laporan contoh: ID 1-20 (asli) + ID 21-23 (laporan ulang pada fasilitas yang sama)
INSERT INTO laporan_kerusakan
    (nama_pelapor, id_fasilitas, deskripsi, prioritas, status, tanggal_laporan)
VALUES
    ('Andi Pratama', 1, 'Gambar buram dan berkedip saat dipakai presentasi', 'Tinggi', 'Selesai', '2026-10-01 07:45:00'),
    ('Sari Wulandari', 2, 'Kaki kursi patah satu', 'Sedang', 'Selesai', '2026-10-02 09:10:00'),
    ('Budi Santoso', 3, 'AC menyala tetapi tidak dingin', 'Tinggi', 'Selesai', '2026-10-03 10:30:00'),
    ('Rina Kusuma', 4, 'Kran bocor, air menetes terus', 'Sedang', 'Selesai', '2026-10-05 08:15:00'),
    ('Pak Hadi', 5, 'Permukaan papan tergores, spidol sulit dihapus', 'Rendah', 'Selesai', '2026-10-06 13:00:00'),
    ('Dimas Arya', 6, 'Lampu port 3 sampai 8 mati, PC tidak dapat IP', 'Tinggi', 'Diproses', '2026-10-08 07:30:00'),
    ('Nabila Putri', 7, 'Engsel lepas, pintu tidak bisa ditutup rapat', 'Sedang', 'Diproses', '2026-10-09 11:20:00'),
    ('Bu Ratna', 8, 'Kertas sering macet di tengah', 'Sedang', 'Diproses', '2026-10-12 09:45:00'),
    ('Fajar Nugroho', 9, 'Dua lampu mati, kelas gelap saat mendung', 'Sedang', 'Diproses', '2026-10-13 07:50:00'),
    ('Dewi Lestari', 10, 'Laci macet dan tidak bisa dikunci', 'Rendah', 'Menunggu', '2026-10-14 10:05:00'),
    ('Rizky Ramadhan', 11, 'Tidak mau menyala, lampu power tidak hidup', 'Tinggi', 'Menunggu', '2026-10-15 08:40:00'),
    ('Aulia Rahma', 12, 'Berbunyi keras saat kecepatan maksimal', 'Rendah', 'Menunggu', '2026-10-15 12:15:00'),
    ('Pak Joko', 13, 'Bocor saat hujan, lantai licin', 'Tinggi', 'Menunggu', '2026-10-16 14:00:00'),
    ('Salsa Amelia', 14, 'Tutup rusak, sampah berserakan', 'Rendah', 'Menunggu', '2026-10-17 09:00:00'),
    ('Yoga Saputra', 15, 'Suara kresek saat volume dinaikkan', 'Sedang', 'Menunggu', '2026-10-18 10:30:00'),
    ('Citra Maharani', 16, 'Papan rak melengkung, buku hampir jatuh', 'Sedang', 'Menunggu', '2026-10-19 08:20:00'),
    ('Bu Sinta', 17, 'Tidak bisa tersambung ke HDMI laptop', 'Tinggi', 'Menunggu', '2026-10-19 13:10:00'),
    ('Ilham Maulana', 18, 'Air keluar sangat kecil', 'Sedang', 'Menunggu', '2026-10-20 11:45:00'),
    ('Putri Anjani', 19, 'Kaca retak di pojok kanan bawah', 'Tinggi', 'Menunggu', '2026-10-21 07:35:00'),
    ('Kevin Wijaya', 20, 'Klik kiri kadang tidak merespons', 'Rendah', 'Menunggu', '2026-10-21 09:25:00'),
    ('Rina Kusuma', 3, 'AC kembali tidak dingin setelah sebulan diperbaiki', 'Sedang', 'Menunggu', '2026-11-10 08:05:00'),
    ('Andi Pratama', 1, 'Lampu proyektor mulai redup lagi', 'Sedang', 'Menunggu', '2026-11-12 07:55:00'),
    ('Rizky Ramadhan', 11, 'Sudah menyala tetapi layar tidak menampilkan gambar', 'Sedang', 'Diproses', '2026-11-16 09:30:00');

-- Cek hasil: kategori 4, fasilitas 26, laporan 23
SELECT (SELECT COUNT(*) FROM kategori_fasilitas) AS kategori,
       (SELECT COUNT(*) FROM fasilitas)          AS fasilitas,
       (SELECT COUNT(*) FROM laporan_kerusakan)  AS laporan;
