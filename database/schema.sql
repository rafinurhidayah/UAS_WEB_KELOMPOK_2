-- =====================================================================
-- SISTEM PELAPORAN KERUSAKAN FASILITAS KAMPUS
-- Schema database MySQL (berdasarkan PRD v2.0, bab 8)
-- Cara pakai: import file ini dulu, lalu seed.sql
-- =====================================================================

DROP DATABASE IF EXISTS pelaporan_fasilitas;
CREATE DATABASE pelaporan_fasilitas
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE pelaporan_fasilitas;

-- ---------------------------------------------------------------------
-- 8.1 roles : jenis hak akses (Mahasiswa, Petugas, Admin)
-- ---------------------------------------------------------------------
CREATE TABLE roles (
  id_role    TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama_role  VARCHAR(30)      NOT NULL,
  PRIMARY KEY (id_role),
  UNIQUE KEY uq_roles_nama (nama_role)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 8.2 users : seluruh pengguna sistem
-- Kolom disesuaikan dengan form register.html
-- (fullName -> nama, nim -> nim_nip, email, phone -> no_hp, password)
-- Password WAJIB disimpan hasil hash (bcrypt / password_hash), bukan teks asli.
-- ---------------------------------------------------------------------
CREATE TABLE users (
  id_user      INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  id_role      TINYINT UNSIGNED NOT NULL,
  nama         VARCHAR(100)     NOT NULL,
  email        VARCHAR(120)     NOT NULL,
  password     VARCHAR(255)     NOT NULL,
  nim_nip      VARCHAR(20)      NOT NULL,
  no_hp        VARCHAR(20)      NULL,
  status_akun  ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  created_at   TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_user),
  UNIQUE KEY uq_users_email (email),
  UNIQUE KEY uq_users_nim_nip (nim_nip),
  KEY idx_users_role (id_role),
  CONSTRAINT fk_users_role FOREIGN KEY (id_role)
    REFERENCES roles (id_role) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 8.3 categories : kategori fasilitas (Elektronik, Perabot, dst.)
-- Cocok dengan tombol filter di index.html
-- ---------------------------------------------------------------------
CREATE TABLE categories (
  id_kategori    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama_kategori  VARCHAR(60)  NOT NULL,
  deskripsi      VARCHAR(255) NULL,
  status_aktif   TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (id_kategori),
  UNIQUE KEY uq_categories_nama (nama_kategori)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 8.4 facility_types : jenis fasilitas (AC, Proyektor, Komputer, ...)
-- Tambahan: kolom icon (nama Material Symbols) agar sesuai tampilan kartu
-- di home.js (field "icon" pada data.js).
-- ---------------------------------------------------------------------
CREATE TABLE facility_types (
  id_jenis        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_kategori     INT UNSIGNED NOT NULL,
  nama_fasilitas  VARCHAR(80)  NOT NULL,
  deskripsi       VARCHAR(255) NULL,
  icon            VARCHAR(40)  NOT NULL DEFAULT 'build',
  status_aktif    TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (id_jenis),
  UNIQUE KEY uq_type_kategori_nama (id_kategori, nama_fasilitas),
  CONSTRAINT fk_types_kategori FOREIGN KEY (id_kategori)
    REFERENCES categories (id_kategori) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 8.5 locations : gedung, lantai, ruangan
-- Tambahan: status_aktif, karena PRD 6.3 meminta lokasi yang tidak
-- dipakai bisa dinonaktifkan (di 8.5 field ini belum tercantum).
-- ---------------------------------------------------------------------
CREATE TABLE locations (
  id_lokasi     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  gedung        VARCHAR(80)  NOT NULL,
  lantai        VARCHAR(20)  NOT NULL,
  nama_ruangan  VARCHAR(80)  NOT NULL,
  keterangan    VARCHAR(255) NULL,
  status_aktif  TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (id_lokasi),
  UNIQUE KEY uq_location (gedung, lantai, nama_ruangan)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 8.6 facility_items : barang fisik (kode inventaris) di lokasi tertentu
-- kondisi memakai nilai yang sama dengan FACILITY_STATUS di data.js
-- (baik / ringan / berat) supaya tidak perlu mapping di front-end.
-- Barang tidak dihapus permanen: nonaktifkan lewat status_aktif.
-- ---------------------------------------------------------------------
CREATE TABLE facility_items (
  id_barang     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_jenis      INT UNSIGNED NOT NULL,
  id_lokasi     INT UNSIGNED NOT NULL,
  kode_barang   VARCHAR(20)  NOT NULL,
  nama_label    VARCHAR(120) NOT NULL,
  kondisi       ENUM('baik','ringan','berat') NOT NULL DEFAULT 'baik',
  status_aktif  TINYINT(1)   NOT NULL DEFAULT 1,
  created_by    INT UNSIGNED NOT NULL,
  created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_barang),
  UNIQUE KEY uq_items_kode (kode_barang),
  KEY idx_items_jenis (id_jenis),
  KEY idx_items_lokasi (id_lokasi),
  CONSTRAINT fk_items_jenis FOREIGN KEY (id_jenis)
    REFERENCES facility_types (id_jenis) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_items_lokasi FOREIGN KEY (id_lokasi)
    REFERENCES locations (id_lokasi) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_items_creator FOREIGN KEY (created_by)
    REFERENCES users (id_user) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 8.7 reports : laporan kerusakan dari mahasiswa
-- kode_laporan dibuat otomatis oleh backend, contoh: LPR-20261126-0001
-- id_petugas boleh NULL sampai ada petugas yang menangani.
-- ---------------------------------------------------------------------
CREATE TABLE reports (
  id_laporan      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  kode_laporan    VARCHAR(25)  NOT NULL,
  id_pelapor      INT UNSIGNED NOT NULL,
  id_barang       INT UNSIGNED NOT NULL,
  id_lokasi       INT UNSIGNED NOT NULL,
  deskripsi       TEXT         NOT NULL,
  prioritas       ENUM('rendah','sedang','tinggi') NOT NULL DEFAULT 'sedang',
  status_laporan  ENUM('Menunggu','Diverifikasi','Diproses','Selesai','Ditolak')
                  NOT NULL DEFAULT 'Menunggu',
  id_petugas      INT UNSIGNED NULL,
  created_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
                               ON UPDATE CURRENT_TIMESTAMP,
  selesai_at      DATETIME     NULL,
  PRIMARY KEY (id_laporan),
  UNIQUE KEY uq_reports_kode (kode_laporan),
  KEY idx_reports_pelapor (id_pelapor),
  KEY idx_reports_barang (id_barang),
  KEY idx_reports_lokasi (id_lokasi),
  KEY idx_reports_petugas (id_petugas),
  KEY idx_reports_status (status_laporan),
  CONSTRAINT fk_reports_pelapor FOREIGN KEY (id_pelapor)
    REFERENCES users (id_user) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_reports_barang FOREIGN KEY (id_barang)
    REFERENCES facility_items (id_barang) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_reports_lokasi FOREIGN KEY (id_lokasi)
    REFERENCES locations (id_lokasi) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_reports_petugas FOREIGN KEY (id_petugas)
    REFERENCES users (id_user) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 8.8 report_photos : foto bukti kerusakan (satu laporan bisa banyak foto)
-- ---------------------------------------------------------------------
CREATE TABLE report_photos (
  id_foto      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_laporan   INT UNSIGNED NOT NULL,
  path_file    VARCHAR(255) NOT NULL,
  uploaded_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_foto),
  KEY idx_photos_laporan (id_laporan),
  CONSTRAINT fk_photos_laporan FOREIGN KEY (id_laporan)
    REFERENCES reports (id_laporan) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 8.9 report_status_history : riwayat setiap perubahan status
-- status_sebelumnya = NULL untuk catatan pertama (laporan baru dibuat).
-- ---------------------------------------------------------------------
CREATE TABLE report_status_history (
  id_riwayat         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_laporan         INT UNSIGNED NOT NULL,
  id_user            INT UNSIGNED NOT NULL,
  status_sebelumnya  ENUM('Menunggu','Diverifikasi','Diproses','Selesai','Ditolak') NULL,
  status_baru        ENUM('Menunggu','Diverifikasi','Diproses','Selesai','Ditolak') NOT NULL,
  catatan            TEXT         NULL,
  created_at         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_riwayat),
  KEY idx_history_laporan (id_laporan),
  KEY idx_history_user (id_user),
  CONSTRAINT fk_history_laporan FOREIGN KEY (id_laporan)
    REFERENCES reports (id_laporan) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_history_user FOREIGN KEY (id_user)
    REFERENCES users (id_user) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- =====================================================================
-- VIEW untuk dashboard & daftar (PRD bab 6.7)
-- =====================================================================

-- Daftar laporan lengkap: dipakai halaman Daftar/Detail Laporan
CREATE OR REPLACE VIEW v_laporan_detail AS
SELECT
  r.id_laporan, r.kode_laporan, r.status_laporan, r.prioritas, r.deskripsi,
  r.created_at, r.updated_at, r.selesai_at,
  u.nama  AS nama_pelapor, u.nim_nip AS nim_pelapor,
  p.nama  AS nama_petugas,
  i.kode_barang, i.nama_label, i.kondisi,
  t.nama_fasilitas, c.nama_kategori,
  l.gedung, l.lantai, l.nama_ruangan
FROM reports r
JOIN users u            ON u.id_user = r.id_pelapor
LEFT JOIN users p       ON p.id_user = r.id_petugas
JOIN facility_items i   ON i.id_barang = r.id_barang
JOIN facility_types t   ON t.id_jenis = i.id_jenis
JOIN categories c       ON c.id_kategori = t.id_kategori
JOIN locations l        ON l.id_lokasi = r.id_lokasi;

-- Daftar barang untuk halaman publik "Daftar Fasilitas" (index.html)
CREATE OR REPLACE VIEW v_fasilitas AS
SELECT
  i.id_barang, i.kode_barang AS code, i.nama_label AS name,
  c.nama_kategori AS category, t.icon, i.kondisi AS status,
  CONCAT(l.gedung, ', ', l.nama_ruangan) AS building,
  t.deskripsi AS `desc`, l.keterangan AS tag
FROM facility_items i
JOIN facility_types t ON t.id_jenis = i.id_jenis
JOIN categories c     ON c.id_kategori = t.id_kategori
JOIN locations l      ON l.id_lokasi = i.id_lokasi
WHERE i.status_aktif = 1 AND t.status_aktif = 1 AND c.status_aktif = 1;

-- Statistik laporan per status (dashboard admin)
CREATE OR REPLACE VIEW v_statistik_status AS
SELECT status_laporan, COUNT(*) AS jumlah
FROM reports
GROUP BY status_laporan;

-- Statistik laporan per kategori (dashboard admin)
CREATE OR REPLACE VIEW v_statistik_kategori AS
SELECT c.nama_kategori, COUNT(r.id_laporan) AS jumlah
FROM categories c
LEFT JOIN facility_types t ON t.id_kategori = c.id_kategori
LEFT JOIN facility_items i ON i.id_jenis = t.id_jenis
LEFT JOIN reports r        ON r.id_barang = i.id_barang
GROUP BY c.id_kategori, c.nama_kategori;
