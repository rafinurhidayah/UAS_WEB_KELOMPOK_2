# PRODUCT REQUIREMENTS DOCUMENT (PRD)
# SISTEM PELAPORAN KERUSAKAN FASILITAS KAMPUS

**Versi:** 2.0  
**Platform:** Website  
**Database:** MySQL  
**Target Pengguna:** Mahasiswa, Petugas, dan Admin  
**Status:** Draft Perancangan

---

# 1. DESKRIPSI PRODUK

Sistem Pelaporan Kerusakan Fasilitas Kampus merupakan sebuah website yang dirancang untuk mempermudah proses pelaporan, pengelolaan, dan pemantauan kerusakan fasilitas di lingkungan kampus.

Sistem ini memungkinkan mahasiswa melaporkan kerusakan fasilitas dengan memilih barang yang sudah terdaftar di dalam sistem. Setiap barang memiliki identitas, jenis fasilitas, kode inventaris, dan lokasi penempatan yang telah diinput oleh petugas.

Mahasiswa dapat menyampaikan laporan dengan mengisi deskripsi kerusakan serta mengunggah foto sebagai bukti pendukung. Laporan yang dikirimkan akan diterima petugas untuk diperiksa, diverifikasi, dan ditindaklanjuti.

Petugas bertanggung jawab mengelola data barang, memverifikasi laporan, memperbarui status penanganan, serta memberikan catatan perkembangan perbaikan. Sementara itu, admin memiliki wewenang untuk mengelola akun pengguna, kategori fasilitas, lokasi kampus, dan memantau keseluruhan aktivitas pelaporan.

Sistem menyediakan lima status laporan, yaitu Menunggu, Diverifikasi, Diproses, Selesai, dan Ditolak.

Dengan adanya sistem ini, proses pelaporan kerusakan fasilitas diharapkan menjadi lebih terstruktur, terdokumentasi, transparan, dan mudah dipantau.

# 2. LATAR BELAKANG

Pengelolaan kerusakan fasilitas kampus membutuhkan proses pelaporan yang jelas agar setiap permasalahan dapat diketahui dan ditindaklanjuti oleh pihak yang bertanggung jawab.

Pelaporan yang masih dilakukan secara manual berpotensi menyebabkan informasi kerusakan tidak terdokumentasi dengan baik, laporan sulit dilacak, dan perkembangan penanganan tidak diketahui oleh pelapor.

Selain itu, identifikasi fasilitas yang mengalami kerusakan dapat menjadi kendala apabila tidak terdapat pendataan barang dan lokasi yang terstruktur.

Oleh karena itu, diperlukan sebuah sistem terpusat yang menghubungkan mahasiswa sebagai pelapor, petugas sebagai pengelola fasilitas dan penanggung jawab penanganan, serta admin sebagai pengelola sistem.

# 3. TUJUAN PRODUK

Tujuan pengembangan sistem adalah:

1. Mempermudah mahasiswa dalam melaporkan kerusakan fasilitas kampus.
2. Menyediakan pendataan barang dan lokasi fasilitas secara terstruktur.
3. Mempermudah petugas dalam mengidentifikasi barang yang mengalami kerusakan.
4. Memfasilitasi proses verifikasi dan penanganan laporan.
5. Memberikan informasi perkembangan laporan kepada mahasiswa.
6. Menyimpan riwayat perubahan status dan tindakan penanganan.
7. Membantu admin mengelola data dan memantau keseluruhan aktivitas pelaporan.

# 4. TARGET PENGGUNA DAN HAK AKSES

Sistem memiliki tiga jenis pengguna dengan tanggung jawab dan hak akses yang berbeda.

## 4.1 Mahasiswa

Mahasiswa merupakan pengguna yang bertugas menyampaikan laporan kerusakan fasilitas kampus.

Hak akses:
- Melakukan registrasi dan login.
- Melihat dashboard mahasiswa.
- Melihat daftar fasilitas yang tersedia.
- Memilih jenis fasilitas, lokasi, dan barang yang akan dilaporkan.
- Membuat laporan kerusakan.
- Mengunggah foto bukti kerusakan.
- Melihat riwayat laporan milik sendiri.
- Memantau status perkembangan laporan.
- Melihat catatan penanganan dari petugas.

Mahasiswa tidak memiliki akses untuk mengubah data barang, lokasi, kategori, maupun status laporan.

## 4.2 Petugas

Petugas merupakan pengguna yang bertanggung jawab mengelola pendataan barang dan menindaklanjuti laporan kerusakan.

Hak akses:
- Melakukan login.
- Melihat dashboard petugas.
- Menambahkan data barang atau fasilitas.
- Menentukan jenis fasilitas dan lokasi penempatan barang.
- Mengelola identitas dan kode inventaris barang.
- Melihat seluruh laporan yang masuk.
- Memeriksa detail dan foto laporan.
- Memverifikasi atau menolak laporan.
- Memperbarui status penanganan.
- Memberikan catatan tindak lanjut.
- Memperbarui kondisi barang.
- Mengunggah foto hasil perbaikan (pengembangan lanjutan).

## 4.3 Admin

Admin merupakan pengguna yang memiliki kewenangan untuk mengelola data utama dan mengawasi keseluruhan sistem.

Hak akses:
- Melakukan login.
- Melihat dashboard admin.
- Mengelola akun mahasiswa.
- Mengelola akun petugas.
- Mengelola kategori fasilitas.
- Mengelola data lokasi kampus.
- Melihat data barang yang diinput petugas.
- Melihat seluruh laporan.
- Melakukan pencarian dan filter laporan.
- Melihat riwayat perubahan status.
- Melihat statistik dan rekapitulasi laporan.

Admin bertanggung jawab terhadap pengelolaan sistem, sedangkan proses operasional pendataan barang dan penanganan kerusakan menjadi tanggung jawab petugas.

# 5. KONSEP PENGELOLAAN FASILITAS

Sistem menerapkan konsep pemisahan antara kategori fasilitas, jenis fasilitas, barang fisik, dan lokasi.

Pemisahan ini bertujuan agar setiap barang memiliki identitas yang jelas dan dapat dibedakan berdasarkan lokasi penempatannya.

Contoh struktur:

**Kategori:** Elektronik

**Jenis fasilitas:**
- AC
- Proyektor
- Komputer

**Data barang:**

| Kode Barang | Jenis Fasilitas | Lokasi |
|---|---|---|
| AC-001 | AC | Gedung A, Ruang 101 |
| AC-002 | AC | Gedung A, Ruang 201 |
| PRO-001 | Proyektor | Gedung B, Ruang Seminar |
| KOM-001 | Komputer | Laboratorium Komputer |

Dengan struktur tersebut, mahasiswa dapat memilih barang secara spesifik ketika membuat laporan.

## 5.1 Alur Pendataan Barang

1. Petugas login ke sistem.
2. Petugas membuka menu data fasilitas.
3. Petugas memilih jenis fasilitas yang tersedia.
4. Petugas menentukan lokasi penempatan barang.
5. Petugas memasukkan kode inventaris dan identitas barang.
6. Sistem menyimpan data barang.
7. Barang yang telah terdaftar dapat dipilih mahasiswa ketika membuat laporan.

# 6. KEBUTUHAN FUNGSIONAL

## 6.1 Autentikasi dan Manajemen Akun

- Registrasi akun mahasiswa.
- Login dan logout.
- Penyimpanan password dalam bentuk hash.
- Pengelolaan sesi menggunakan session dan cookie.
- Pembatasan akses berdasarkan role.
- Admin dapat menambah, mengubah, dan menonaktifkan akun pengguna.
- Sistem memvalidasi kredensial pengguna sebelum memberikan akses.

## 6.2 Manajemen Kategori Fasilitas

Admin dapat:
- Melihat daftar kategori fasilitas.
- Menambahkan kategori.
- Mengubah informasi kategori.
- Mengaktifkan atau menonaktifkan kategori.

Contoh kategori:
- Elektronik.
- Perabot.
- Sanitasi.
- Jaringan.
- Fasilitas lainnya.

## 6.3 Manajemen Lokasi

Admin dapat:
- Melihat daftar lokasi kampus.
- Menambahkan data gedung.
- Menentukan lantai.
- Menambahkan nama ruangan.
- Mengubah informasi lokasi.
- Menonaktifkan lokasi yang tidak digunakan.

## 6.4 Manajemen Barang

Petugas dapat:
- Melihat daftar barang.
- Menambahkan barang.
- Memilih jenis fasilitas.
- Menentukan lokasi penempatan.
- Menginput kode inventaris.
- Mengubah identitas barang.
- Memperbarui kondisi barang.
- Menonaktifkan barang yang sudah tidak digunakan.

Barang yang pernah memiliki laporan tidak boleh dihapus secara permanen agar riwayat pelaporan tetap terjaga.

## 6.5 Manajemen Laporan Mahasiswa

Mahasiswa dapat membuat laporan melalui formulir yang terdiri dari:

- Jenis fasilitas.
- Lokasi fasilitas.
- Barang atau kode inventaris.
- Deskripsi kerusakan.
- Upload foto kerusakan.

Ketentuan:
- Barang harus sudah terdaftar di sistem.
- Lokasi barang ditampilkan berdasarkan data fasilitas.
- Foto digunakan sebagai bukti pendukung.
- Sistem menghasilkan kode laporan secara otomatis.
- Status awal laporan adalah Menunggu.
- Mahasiswa hanya dapat melihat laporan miliknya sendiri.

## 6.6 Manajemen Laporan Petugas

Petugas dapat:
- Melihat seluruh laporan yang masuk.
- Mencari laporan berdasarkan kode atau nama barang.
- Memfilter laporan berdasarkan status.
- Melihat detail laporan.
- Melihat foto kerusakan.
- Memverifikasi laporan.
- Menolak laporan dengan menyertakan alasan.
- Memperbarui status penanganan.
- Menambahkan catatan perkembangan.
- Memperbarui kondisi barang.

## 6.7 Dashboard

Dashboard menampilkan informasi yang disesuaikan dengan masing-masing role.

**Dashboard Mahasiswa:**
- Total laporan.
- Laporan menunggu.
- Laporan diproses.
- Laporan selesai.
- Riwayat laporan terbaru.

**Dashboard Petugas:**
- Total laporan masuk.
- Laporan menunggu verifikasi.
- Laporan diverifikasi.
- Laporan diproses.
- Laporan selesai.
- Daftar laporan terbaru.
- Ringkasan data barang.

**Dashboard Admin:**
- Total laporan.
- Laporan menunggu.
- Laporan diproses.
- Laporan selesai.
- Total mahasiswa.
- Total petugas.
- Statistik laporan berdasarkan status.
- Statistik laporan berdasarkan kategori atau lokasi.

# 7. ALUR STATUS LAPORAN

Sistem menggunakan lima status untuk menggambarkan perkembangan laporan.

| Status | Keterangan |
|---|---|
| Menunggu | Laporan berhasil dikirim dan belum diperiksa petugas. |
| Diverifikasi | Laporan telah diperiksa dan dinyatakan dapat ditindaklanjuti. |
| Diproses | Petugas sedang melakukan penanganan atau perbaikan. |
| Selesai | Kerusakan telah ditangani dan laporan dinyatakan selesai. |
| Ditolak | Laporan tidak dapat diterima dengan alasan yang dicatat petugas. |

Alur utama:

Menunggu → Diverifikasi → Diproses → Selesai.

Laporan yang tidak valid atau tidak dapat ditindaklanjuti dapat diubah menjadi Ditolak oleh petugas.

Setiap perubahan status harus disimpan dalam tabel riwayat perubahan status, termasuk:
- Status sebelumnya.
- Status terbaru.
- Pengguna yang melakukan perubahan.
- Catatan perubahan.
- Waktu perubahan.

# 8. RANCANGAN DATABASE

Sistem menggunakan MySQL sebagai sistem manajemen basis data.

Database terdiri dari sembilan tabel utama.

## 8.1 roles

Menyimpan jenis hak akses pengguna.

Field:
- id_role (Primary Key).
- nama_role.

Data role:
- Mahasiswa.
- Petugas.
- Admin.

## 8.2 users

Menyimpan data seluruh pengguna sistem.

Field:
- id_user (Primary Key).
- id_role (Foreign Key).
- nama.
- email.
- password.
- nim_nip.
- no_hp.
- status_akun.
- created_at.

## 8.3 categories

Menyimpan kategori fasilitas.

Field:
- id_kategori (Primary Key).
- nama_kategori.
- deskripsi.
- status_aktif.

## 8.4 facility_types

Menyimpan jenis fasilitas yang tersedia.

Field:
- id_jenis (Primary Key).
- id_kategori (Foreign Key).
- nama_fasilitas.
- deskripsi.
- status_aktif.

## 8.5 locations

Menyimpan lokasi fasilitas di lingkungan kampus.

Field:
- id_lokasi (Primary Key).
- gedung.
- lantai.
- nama_ruangan.
- keterangan.

## 8.6 facility_items

Menyimpan data barang fisik yang ditempatkan di lokasi tertentu.

Field:
- id_barang (Primary Key).
- id_jenis (Foreign Key).
- id_lokasi (Foreign Key).
- kode_barang.
- nama_label.
- kondisi.
- status_aktif.
- created_by (Foreign Key ke users).
- created_at.

## 8.7 reports

Menyimpan laporan kerusakan yang dibuat mahasiswa.

Field:
- id_laporan (Primary Key).
- kode_laporan.
- id_pelapor (Foreign Key ke users).
- id_barang (Foreign Key ke facility_items).
- id_lokasi (Foreign Key ke locations).
- deskripsi.
- prioritas.
- status_laporan.
- id_petugas (Foreign Key ke users, dapat kosong sebelum penugasan).
- created_at.
- updated_at.
- selesai_at.

## 8.8 report_photos

Menyimpan foto bukti kerusakan.

Field:
- id_foto (Primary Key).
- id_laporan (Foreign Key).
- path_file.
- uploaded_at.

Satu laporan dapat memiliki lebih dari satu foto.

## 8.9 report_status_history

Menyimpan riwayat perubahan status laporan.

Field:
- id_riwayat (Primary Key).
- id_laporan (Foreign Key).
- id_user (Foreign Key ke users).
- status_sebelumnya.
- status_baru.
- catatan.
- created_at.

## 8.10 Relasi Database

Hubungan utama antartabel:

- roles memiliki banyak users.
- categories memiliki banyak facility_types.
- facility_types memiliki banyak facility_items.
- locations memiliki banyak facility_items.
- users sebagai petugas dapat membuat banyak facility_items.
- users sebagai mahasiswa dapat membuat banyak reports.
- facility_items dapat memiliki banyak reports.
- locations dapat direferensikan oleh banyak reports.
- users sebagai petugas dapat menangani banyak reports.
- reports memiliki banyak report_photos.
- reports memiliki banyak report_status_history.
- users dapat melakukan banyak perubahan status.

# 9. FLOW SISTEM BERDASARKAN ROLE

## 9.1 Flow Mahasiswa

1. Mahasiswa membuka website.
2. Melakukan registrasi atau login.
3. Masuk ke dashboard mahasiswa.
4. Memilih menu Buat Laporan.
5. Memilih jenis fasilitas.
6. Memilih lokasi fasilitas.
7. Memilih barang berdasarkan kode inventaris.
8. Mengisi deskripsi kerusakan.
9. Mengunggah foto.
10. Mengirim laporan.
11. Sistem menyimpan laporan dengan status Menunggu.
12. Mahasiswa dapat memantau perkembangan laporan melalui menu Riwayat Laporan.
13. Mahasiswa melihat hasil penanganan ketika laporan selesai.

## 9.2 Flow Petugas

1. Petugas login.
2. Masuk ke dashboard petugas.
3. Memilih menu Data Barang untuk menginput fasilitas.
4. Menentukan jenis fasilitas dan lokasi.
5. Menginput kode inventaris dan identitas barang.
6. Menyimpan data barang.
7. Membuka menu Daftar Laporan.
8. Memilih laporan yang masuk.
9. Memeriksa deskripsi dan foto kerusakan.
10. Memutuskan apakah laporan dapat ditindaklanjuti.
11. Jika valid, petugas mengubah status menjadi Diverifikasi.
12. Petugas melakukan penanganan dan mengubah status menjadi Diproses.
13. Setelah perbaikan selesai, petugas memberikan catatan dan memperbarui kondisi barang.
14. Petugas mengubah status menjadi Selesai.
15. Sistem menyimpan seluruh perubahan ke riwayat status.

Jika laporan tidak valid, petugas dapat mengubah status menjadi Ditolak dengan menyertakan alasan.

## 9.3 Flow Admin

1. Admin login.
2. Masuk ke dashboard admin.
3. Mengelola akun mahasiswa dan petugas.
4. Mengelola kategori fasilitas.
5. Mengelola data lokasi kampus.
6. Melihat data barang yang telah diinput petugas.
7. Membuka menu Manajemen Laporan.
8. Melihat seluruh laporan.
9. Melakukan pencarian dan filter.
10. Memantau perkembangan laporan.
11. Melihat riwayat perubahan status.
12. Melihat statistik dan rekapitulasi laporan.

# 10. KEBUTUHAN NONFUNGSIONAL

**Keamanan**
- Password disimpan menggunakan algoritma hashing yang aman.
- Sistem menerapkan autentikasi session dan cookie.
- Setiap halaman dilindungi berdasarkan role.
- Validasi dilakukan pada sisi frontend dan backend.
- Pengguna tidak dapat mengakses data yang bukan menjadi kewenangannya.

**Performa**
- Halaman dapat dimuat dengan waktu yang wajar.
- Data laporan menggunakan pagination.
- Foto dioptimalkan agar tidak membebani penyimpanan dan pemuatan halaman.

**Responsivitas**
- Website dapat digunakan melalui desktop, tablet, dan smartphone.
- Navigasi dan tabel harus tetap mudah digunakan pada layar kecil.

**Keandalan**
- Data laporan dan riwayat perubahan harus tersimpan dengan baik.
- Penghapusan data master tidak boleh merusak riwayat laporan.
- Proses perubahan status harus tercatat secara konsisten.

**Kemudahan Penggunaan**
- Antarmuka mudah dipahami.
- Formulir pelaporan sederhana.
- Informasi status mudah dikenali.
- Navigasi disesuaikan dengan kebutuhan setiap role.

# 11. STRUKTUR HALAMAN WEBSITE

## Halaman Publik
- Beranda.
- Login.
- Registrasi.

## Halaman Mahasiswa
- Dashboard.
- Daftar Fasilitas.
- Buat Laporan.
- Riwayat Laporan.
- Detail Laporan.
- Profil.

## Halaman Petugas
- Dashboard.
- Data Barang.
- Tambah Barang.
- Edit Barang.
- Daftar Laporan.
- Detail Laporan.
- Profil.

## Halaman Admin
- Dashboard.
- Manajemen Laporan.
- Data Mahasiswa.
- Data Petugas.
- Kategori Fasilitas.
- Lokasi Kampus.
- Detail Laporan.
- Profil.

# 12. FITUR PENGEMBANGAN LANJUTAN

Fitur berikut tidak menjadi prioritas pada versi pertama, tetapi dapat dikembangkan kemudian:

1. Notifikasi otomatis ketika status laporan berubah.
2. Notifikasi melalui email atau WhatsApp.
3. Upload foto setelah perbaikan sebagai bukti penyelesaian.
4. Peringatan laporan duplikat untuk barang yang sama.
5. Penugasan laporan kepada petugas tertentu.
6. Rekapitulasi laporan dalam bentuk PDF atau Excel.
7. Grafik statistik kerusakan berdasarkan gedung dan kategori.
8. Integrasi dengan sistem akademik kampus.

# 13. KRITERIA KEBERHASILAN

Sistem dinyatakan berhasil apabila:

1. Mahasiswa dapat melakukan registrasi dan login.
2. Petugas dapat menambahkan data barang dan menentukan lokasi penempatannya.
3. Mahasiswa dapat memilih barang yang sudah terdaftar ketika membuat laporan.
4. Mahasiswa dapat mengunggah foto kerusakan.
5. Petugas dapat melihat dan memverifikasi laporan.
6. Petugas dapat menolak laporan dengan memberikan alasan.
7. Petugas dapat memperbarui status hingga laporan selesai.
8. Sistem menyimpan riwayat perubahan status.
9. Mahasiswa dapat memantau laporan miliknya.
10. Admin dapat mengelola data pengguna, kategori, dan lokasi.
11. Admin dapat memantau seluruh laporan dan statistik.
12. Pembagian hak akses berjalan sesuai dengan role masing-masing.

# 14. TAHAPAN PENGEMBANGAN

**Tahap 1: Perancangan**
- Finalisasi kebutuhan sistem.
- Perancangan ERD.
- Perancangan struktur database.
- Perancangan UI/UX berdasarkan design system.

**Tahap 2: Database dan Autentikasi**
- Membuat database MySQL.
- Membuat tabel dan relasi.
- Membuat registrasi dan login.
- Mengimplementasikan session dan cookie.
- Membuat pembagian role.

**Tahap 3: Manajemen Data Master**
- Membuat CRUD pengguna.
- Membuat CRUD kategori.
- Membuat CRUD lokasi.
- Membuat CRUD barang.

**Tahap 4: Sistem Pelaporan**
- Membuat formulir laporan.
- Membuat fitur pemilihan barang.
- Membuat upload foto.
- Membuat riwayat laporan mahasiswa.

**Tahap 5: Penanganan Laporan**
- Membuat halaman petugas.
- Membuat verifikasi dan penolakan laporan.
- Membuat pembaruan status.
- Membuat catatan penanganan.
- Membuat riwayat perubahan status.

**Tahap 6: Dashboard Admin**
- Membuat dashboard statistik.
- Membuat manajemen laporan.
- Membuat fitur pencarian dan filter.

**Tahap 7: Pengujian**
- Pengujian autentikasi.
- Pengujian hak akses.
- Pengujian CRUD.
- Pengujian upload foto.
- Pengujian alur laporan.
- Pengujian relasi database.

---

# 15. PENUTUP

Sistem Pelaporan Kerusakan Fasilitas Kampus dirancang sebagai solusi digital untuk mengelola proses pelaporan dan penanganan kerusakan fasilitas secara terpusat.

Dengan adanya pemisahan data kategori, jenis fasilitas, barang, dan lokasi, sistem dapat membantu mahasiswa mengidentifikasi fasilitas yang mengalami kerusakan secara lebih akurat.

Pembagian tanggung jawab antara mahasiswa, petugas, dan admin juga memungkinkan proses pelaporan berjalan secara terstruktur, mulai dari pendataan barang, pengiriman laporan, verifikasi, penanganan, hingga penyelesaian.

Dokumen PRD ini menjadi acuan utama dalam proses perancangan database, desain antarmuka, dan implementasi sistem.