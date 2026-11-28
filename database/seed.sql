-- =====================================================================
-- SEED DATA — SISTEM PELAPORAN KERUSAKAN FASILITAS KAMPUS
-- Jalankan setelah database/schema.sql:
--   mysql -u root pelaporan_fasilitas < database/seed.sql
--
-- KREDENSIAL AKUN UJI COBA (password sama untuk kedua akun):
--   Admin    : admin@kampus.ac.id    / password123
--   Petugas  : petugas@kampus.ac.id  / password123
--
-- Catatan:
-- - Mahasiswa TIDAK di-seed di sini karena registrasi mandiri dilakukan
--   melalui halaman register.php (role otomatis Mahasiswa).
-- - Seluruh data sampel fasilitas memakai created_by = 2 (Petugas).
-- - Seed ini idempotent: aman dijalankan ulang berkat ON DUPLICATE KEY UPDATE.
-- =====================================================================

USE pelaporan_fasilitas;

-- ---------------------------------------------------------------------
-- 1. Roles
-- ---------------------------------------------------------------------
INSERT INTO roles (id_role, nama_role) VALUES
  (1, 'Mahasiswa'),
  (2, 'Petugas'),
  (3, 'Admin')
ON DUPLICATE KEY UPDATE
  nama_role = VALUES(nama_role);

-- ---------------------------------------------------------------------
-- 2. Akun staf bawaan
--    Hash di bawah adalah password_hash('password123', PASSWORD_DEFAULT).
--    Admin   : admin@kampus.ac.id   / password123
--    Petugas : petugas@kampus.ac.id / password123
-- ---------------------------------------------------------------------
INSERT INTO users
  (id_user, id_role, nama, email, password, nim_nip, no_hp, status_akun)
VALUES
  (1, 3, 'Administrator Sistem', 'admin@kampus.ac.id',
   '$2y$10$dQuYOQ7igy3TrIblcQ1PNe8bNVbuLEOjJS3n8wf960FTjAGbeBILu',
   '198501012010011001', '081200000001', 'aktif'),
  (2, 2, 'Budi Santoso', 'petugas@kampus.ac.id',
   '$2y$10$bvJeuF9D4j5IYHHMn891U.f1Cnhs942UzIJEigCbHQJ6bQ7tCc7O2',
   '199205122018031002', '081200000002', 'aktif')
ON DUPLICATE KEY UPDATE
  id_role     = VALUES(id_role),
  nama        = VALUES(nama),
  email       = VALUES(email),
  password    = VALUES(password),
  nim_nip     = VALUES(nim_nip),
  no_hp       = VALUES(no_hp),
  status_akun = VALUES(status_akun);

-- ---------------------------------------------------------------------
-- 3. Kategori fasilitas (selaras assets/js/data/facilities.js)
-- ---------------------------------------------------------------------
INSERT INTO categories
  (id_kategori, nama_kategori, deskripsi, status_aktif)
VALUES
  (1, 'Elektronik', 'Perangkat elektronik penunjang perkuliahan', 1),
  (2, 'Perabot', 'Meja, kursi, lemari, dan furnitur ruangan', 1),
  (3, 'Sanitasi', 'Fasilitas air dan toilet', 1),
  (4, 'Jaringan', 'Infrastruktur jaringan dan internet kampus', 1)
ON DUPLICATE KEY UPDATE
  nama_kategori = VALUES(nama_kategori),
  deskripsi     = VALUES(deskripsi),
  status_aktif  = VALUES(status_aktif);

-- ---------------------------------------------------------------------
-- 4. Jenis fasilitas + ikon Material Symbols
-- ---------------------------------------------------------------------
INSERT INTO facility_types
  (id_jenis, id_kategori, nama_fasilitas, deskripsi, icon, status_aktif)
VALUES
  (1, 1, 'AC', 'Pendingin ruangan kelas dan laboratorium', 'ac_unit', 1),
  (2, 1, 'Proyektor', 'Sarana presentasi dan seminar', 'videocam', 1),
  (3, 1, 'Komputer', 'Perangkat praktikum komputer', 'desktop_windows', 1),
  (4, 2, 'Meja Kuliah Kursi Gandeng', 'Furnitur ruang kuliah', 'chair', 1),
  (5, 3, 'Kran Air Wastafel', 'Sanitasi fasilitas umum', 'water_damage', 1),
  (6, 4, 'Access Point WiFi', 'Infrastruktur jaringan kampus', 'wifi', 1)
ON DUPLICATE KEY UPDATE
  id_kategori    = VALUES(id_kategori),
  nama_fasilitas = VALUES(nama_fasilitas),
  deskripsi      = VALUES(deskripsi),
  icon           = VALUES(icon),
  status_aktif   = VALUES(status_aktif);

-- ---------------------------------------------------------------------
-- 5. Lokasi kampus
-- ---------------------------------------------------------------------
INSERT INTO locations
  (id_lokasi, gedung, lantai, nama_ruangan, keterangan, status_aktif)
VALUES
  (1, 'Gedung A', '1', 'Ruang 101', 'Ruang Kuliah', 1),
  (2, 'Gedung A', '2', 'Ruang 201', 'Ruang Kuliah', 1),
  (3, 'Gedung A', '4', 'Lab Komputer 401', 'Lab Riset', 1),
  (4, 'Gedung B', '1', 'Ruang Seminar 102', 'Seminar Hall', 1),
  (5, 'Gedung C', '1', 'Toilet Lantai 1', 'Fasilitas Umum', 1),
  (6, 'Gedung Perpustakaan Pusat', '2', 'Lantai 2', 'Infrastruktur TIK', 1)
ON DUPLICATE KEY UPDATE
  gedung       = VALUES(gedung),
  lantai       = VALUES(lantai),
  nama_ruangan = VALUES(nama_ruangan),
  keterangan   = VALUES(keterangan),
  status_aktif = VALUES(status_aktif);

-- ---------------------------------------------------------------------
-- 6. Barang fisik (created_by = 2 / akun Petugas)
-- ---------------------------------------------------------------------
INSERT INTO facility_items
  (id_barang, id_jenis, id_lokasi, kode_barang, nama_label, kondisi, status_aktif, created_by)
VALUES
  (1, 1, 1, 'AC-001',  'AC Split 2 PK',              'baik',   1, 2),
  (2, 2, 4, 'PRO-001', 'Proyektor Epson EB-X400',    'ringan', 1, 2),
  (3, 3, 3, 'KOM-001', 'Komputer Laboratorium',      'baik',   1, 2),
  (4, 4, 2, 'FUR-001', 'Meja Kuliah Kursi Gandeng',  'baik',   1, 2),
  (5, 5, 5, 'SAN-001', 'Kran Air Wastafel Sensor',   'berat',  1, 2),
  (6, 6, 6, 'NET-002', 'Access Point WiFi Aruba',    'baik',   1, 2)
ON DUPLICATE KEY UPDATE
  id_jenis     = VALUES(id_jenis),
  id_lokasi    = VALUES(id_lokasi),
  kode_barang  = VALUES(kode_barang),
  nama_label   = VALUES(nama_label),
  kondisi      = VALUES(kondisi),
  status_aktif = VALUES(status_aktif),
  created_by   = VALUES(created_by);
