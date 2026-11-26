# Design System
## Sistem Pelaporan Kerusakan Fasilitas Kampus

**Versi:** 1.0  
**Status:** Design direction  
**Penggunaan:** Google Stitch, desain UI, dan implementasi frontend  
**Bahasa antarmuka:** Bahasa Indonesia  

---

## 1. Design Direction

### 1.1 Konsep utama

Gunakan pendekatan **Modern Institutional SaaS** dengan karakter portal akademik kampus seperti SIKAD: fungsional, terstruktur, informatif, dan berorientasi pada aktivitas administrasi. Website harus terasa seperti sistem internal kampus yang benar-benar digunakan setiap hari, bukan landing page produk SaaS atau dashboard marketing.

Arah visual yang dipilih:
- Gaya dasar: Modern SaaS.
- Referensi pengalaman: portal sistem informasi akademik kampus seperti SIKAD.
- Palet: Neutral Earth.
- Bentuk komponen: Soft Rounded.
- Elevasi: border tipis dengan shadow sangat halus.
- Tipografi: Plus Jakarta Sans.
- Ikon: custom SVG-style icons dengan bahasa visual yang konsisten.
- Motion: interactive motion yang halus dan fungsional.
- Kepadatan: balanced, tidak terlalu padat dan tidak terlalu kosong.
- Navigasi: collapsible sidebar.

### 1.2 Karakter produk

Sistem ini adalah aplikasi administrasi kampus. Prioritas desain adalah:
1. Pengguna dapat menemukan menu dan informasi tanpa harus menebak.
2. Tabel, formulir, status, dan detail laporan mudah dipindai.
3. Ketiga role memiliki pola visual konsisten, tetapi navigasi dan isi dashboard disesuaikan dengan tugas masing-masing.
4. Status dan tindakan penting terlihat jelas.
5. Tampilan tetap sederhana saat data bertambah.

### 1.3 Batasan arah visual

Jangan membuat desain seperti:
- Landing page startup dengan hero besar dan ilustrasi dekoratif.
- Dashboard yang dipenuhi kartu statistik tanpa prioritas informasi.
- Template admin generik dengan gradient, glassmorphism, atau efek neon.
- Antarmuka yang terlalu ramai dengan banyak warna aksen.
- Layout mobile yang hanya mengecilkan tabel desktop tanpa penyesuaian.

Gunakan SIKAD sebagai referensi karakter sistem kampus dan pola administrasi, bukan menyalin logo, aset, atau tampilan persis sistem yang sudah ada.

---

## 2. Visual Foundation

### 2.1 Color palette

Palet utama menggunakan warna netral hangat, charcoal, dan aksen earth brown. Warna aksen dipakai secara terukur untuk tombol utama, navigasi aktif, tautan penting, dan fokus interaksi.

| Token | Hex | Penggunaan |
|---|---|---|
| `color-brand-700` | `#76563D` | Tombol utama, elemen aktif, tautan penting |
| `color-brand-600` | `#8B6B4F` | Aksen utama dan ikon aktif |
| `color-brand-100` | `#F1EAE3` | Background navigasi aktif dan aksen lembut |
| `color-ink` | `#292929` | Teks utama dan sidebar |
| `color-text-secondary` | `#69645E` | Teks sekunder |
| `color-text-muted` | `#89837B` | Placeholder, metadata, teks bantuan |
| `color-canvas` | `#F7F6F3` | Background utama aplikasi |
| `color-surface` | `#FFFFFF` | Card, tabel, modal, dan form |
| `color-surface-muted` | `#F1EFEB` | Area pendukung dan field nonaktif |
| `color-border` | `#E5E0D9` | Border, divider, dan garis tabel |
| `color-grid` | `#EDE9E3` | Grid background dekoratif yang sangat halus |

**Aturan penggunaan warna:**
- Background utama menggunakan `color-canvas`, bukan putih murni di seluruh area.
- Surface untuk tabel, form, dan card menggunakan putih agar konten mudah dibaca.
- Gunakan `color-brand-700` untuk tombol utama dengan teks putih.
- Jangan memakai `color-brand-600` sebagai warna teks kecil di atas background putih jika kontrasnya tidak memadai. Gunakan `color-brand-700` atau `color-ink`.
- Hindari menggunakan cokelat sebagai warna status laporan. Status harus memakai warna semantik tersendiri.
- Maksimal satu aksen utama dalam satu komponen.

### 2.2 Status colors

Status harus dapat dikenali tanpa hanya mengandalkan warna. Selalu sertakan label teks dan, bila diperlukan, ikon kecil.

| Status | Teks | Background | Border opsional |
|---|---|---|---|
| Menunggu | `#8A5A13` | `#FBF1DD` | `#F0DDAF` |
| Diverifikasi | `#315E82` | `#EAF1F7` | `#D4E2EF` |
| Diproses | `#68518B` | `#F1ECF8` | `#E0D7EF` |
| Selesai | `#356B4D` | `#E9F3EC` | `#D3E6D9` |
| Gagal / Ditolak (bila diterapkan) | `#A4433A` | `#F8EAE8` | `#EED4D1` |

Warna merah hanya dipakai untuk kondisi gagal, penolakan, atau tindakan destruktif. Jangan menambahkan status Ditolak ke alur utama tanpa keputusan kebutuhan produk.

### 2.3 Background texture

Gunakan kombinasi background solid dan tekstur grid geometris yang sangat halus.
- Area utama tetap menggunakan `color-canvas`.
- Grid tipis boleh digunakan pada area sambutan dashboard atau header halaman, dengan jarak sekitar 32-40 px.
- Opacity grid harus sangat rendah sehingga hampir tidak terasa saat halaman dilihat sekilas.
- Jangan menggunakan tekstur pada area tabel, form, modal, atau konten padat.
- Jangan menggunakan noise berat, pola besar, ilustrasi abstrak, atau gradient dekoratif.

Tekstur adalah detail pendukung, bukan elemen yang menarik perhatian lebih besar daripada data.

---

## 3. Typography

### 3.1 Font family

Gunakan **Plus Jakarta Sans** sebagai font utama untuk seluruh antarmuka.

Fallback:

```css
font-family: "Plus Jakarta Sans", Inter, ui-sans-serif, system-ui, sans-serif;
```

### 3.2 Type scale

| Token | Ukuran | Weight | Penggunaan |
|---|---:|---:|---|
| `text-page-title` | 24-28 px | 700 | Judul halaman utama |
| `text-section-title` | 18-20 px | 600 | Judul bagian |
| `text-card-title` | 14-16 px | 600 | Judul card dan panel |
| `text-body` | 14 px | 400-500 | Teks utama dan isi tabel |
| `text-label` | 13 px | 500-600 | Label form dan metadata penting |
| `text-caption` | 12 px | 400-500 | Tanggal, keterangan, helper text |
| `text-button` | 14 px | 600 | Label tombol |

### 3.3 Aturan tipografi

- Gunakan ukuran 14 px sebagai ukuran dasar untuk aplikasi desktop.
- Judul halaman harus jelas, tetapi tidak oversized.
- Hindari terlalu banyak variasi ukuran dan weight dalam satu halaman.
- Gunakan angka tabular untuk jumlah laporan, tanggal, dan angka statistik jika tersedia.
- Jangan menggunakan uppercase untuk paragraf atau label panjang.
- Pastikan teks sekunder tetap terbaca dan tidak terlalu pucat.

---

## 4. Shape, Border, dan Elevation

### 4.1 Border radius

Gunakan soft rounded dengan radius sedang dan konsisten.

| Token | Nilai | Penggunaan |
|---|---:|---|
| `radius-sm` | 6 px | Badge kecil, elemen kompak |
| `radius-md` | 9 px | Input, tombol, dropdown |
| `radius-lg` | 12 px | Card, panel, modal |
| `radius-xl` | 16 px | Area upload atau panel khusus bila diperlukan |
| `radius-pill` | 999 px | Badge status berbentuk pill bila sesuai |

Jangan membuat semua komponen berbentuk kapsul. Tombol utama dan field cukup menggunakan radius 9 px.

### 4.2 Border

- Gunakan border 1 px dengan `color-border`.
- Border harus lebih sering digunakan daripada shadow untuk memisahkan area.
- Hindari garis tebal dan outline gelap pada semua komponen.
- Divider tabel dibuat tipis dan konsisten.

### 4.3 Shadow

Gunakan shadow sangat halus hanya untuk memberi kedalaman pada elemen yang berada di atas surface lain.

```css
--shadow-card: 0 1px 3px rgba(41, 41, 41, 0.05);
--shadow-popover: 0 8px 24px rgba(41, 41, 41, 0.10);
--shadow-modal: 0 16px 48px rgba(41, 41, 41, 0.16);
```

Card biasa tidak perlu shadow yang terlihat jelas. Modal dan popover boleh memakai shadow lebih kuat agar hierarkinya jelas.

---

## 5. Layout System

### 5.1 Desktop application shell

Prioritaskan desain desktop dengan frame acuan 1440 × 900 px.

Struktur dasar:
- Sidebar kiri dengan mode expanded dan collapsed.
- Topbar horizontal untuk breadcrumb, judul konteks, notifikasi bila diperlukan, dan profil pengguna.
- Area konten dengan background `color-canvas`.
- Konten utama memakai card atau panel putih dengan batas visual yang jelas.

Ukuran acuan:

| Elemen | Ukuran |
|---|---:|
| Sidebar expanded | 248-264 px |
| Sidebar collapsed | 72-80 px |
| Topbar | 64-72 px |
| Content padding desktop | 24-32 px |
| Jarak antar-section | 24 px |
| Jarak antar-card | 16 px |
| Tinggi input standar | 42-44 px |
| Tinggi tombol standar | 40-44 px |

Batasi lebar konten pada halaman form agar field tidak terlalu melebar. Halaman tabel boleh memakai hampir seluruh lebar konten.

### 5.2 Sidebar

Sidebar harus terasa seperti navigasi aplikasi institusi.
- Logo atau simbol aplikasi dan nama sistem di bagian atas.
- Menu dikelompokkan berdasarkan fungsi.
- Ikon berada di kiri label.
- Item aktif menggunakan background `color-brand-100`, teks `color-brand-700`, dan indikator aktif yang halus.
- Item tidak aktif menggunakan teks sekunder.
- Sidebar dapat diciutkan menjadi ikon saja.
- Tooltip muncul saat sidebar collapsed.
- Informasi profil atau tombol keluar dapat diletakkan pada area bawah sidebar atau menu profil topbar.

Hindari terlalu banyak grup menu dan submenu yang tidak dibutuhkan.

### 5.3 Topbar

Topbar harus sederhana dan informatif.
- Breadcrumb atau nama halaman di area kiri.
- Informasi notifikasi hanya jika fitur notifikasi benar-benar tersedia.
- Avatar atau inisial pengguna, nama singkat, dan role di area kanan.
- Dropdown profil berisi menu profil dan logout.
- Hindari menaruh terlalu banyak tombol di topbar.

### 5.4 Grid dan spacing

Gunakan sistem spacing berbasis kelipatan 4 px.

| Token | Nilai |
|---|---:|
| `space-1` | 4 px |
| `space-2` | 8 px |
| `space-3` | 12 px |
| `space-4` | 16 px |
| `space-5` | 20 px |
| `space-6` | 24 px |
| `space-8` | 32 px |
| `space-10` | 40 px |
| `space-12` | 48 px |

Gunakan layout balanced. Beri ruang yang cukup antar-section, tetapi jangan membuat halaman administrasi terlalu kosong.

---

## 6. Iconography

Gunakan custom SVG-style icons dengan karakter visual konsisten.

Aturan ikon:
- Bentuk sederhana, mudah dikenali, dan tidak dekoratif berlebihan.
- Ukuran standar 20 × 20 px untuk navigasi dan 18 × 18 px untuk tombol.
- Gunakan viewBox 24 × 24.
- Stroke sekitar 1.75-2 px dengan ujung garis rounded.
- Ikon memakai warna yang mengikuti state komponen.
- Gunakan satu bahasa visual untuk semua role.
- Ikon harus konsisten dari segi proporsi, sudut, detail, dan ketebalan garis.
- Hindari emoji, ikon 3D, ikon campuran outline dan solid, serta penggunaan ikon hanya sebagai hiasan.

Ikon yang dibutuhkan antara lain: dashboard, laporan, tambah, riwayat, kategori, pengguna, petugas, profil, pengaturan, pencarian, filter, unggah foto, kalender, lokasi, notifikasi, dan logout.

---

## 7. Motion and Interaction

Gunakan interactive motion yang terasa responsif, bukan animasi dekoratif.

### 7.1 Timing dan easing

| Interaksi | Durasi |
|---|---:|
| Hover dan active state | 120-160 ms |
| Button feedback | 100-140 ms |
| Dropdown dan tooltip | 140-180 ms |
| Modal | 180-220 ms |
| Sidebar collapse | 200-240 ms |
| Toast notification | 180-220 ms |

Gunakan easing yang lembut seperti `ease-out` untuk elemen yang muncul dan `ease-in-out` untuk perubahan layout.

### 7.2 Motion yang diperbolehkan

- Button memiliki hover, pressed, dan focus state.
- Sidebar memiliki transisi saat expand atau collapse.
- Dropdown dan popover muncul dengan fade dan pergeseran kecil.
- Modal memiliki fade-in dan scale ringan.
- Toast muncul dan menghilang secara halus.
- Upload area memberi feedback saat file sedang di-drag.
- Status badge dapat berubah dengan transisi warna singkat.
- Loading menggunakan skeleton atau spinner sederhana.

### 7.3 Motion yang dihindari

- Parallax.
- Animasi looping yang tidak memberi informasi.
- Efek bounce berlebihan.
- Transisi halaman yang lambat.
- Animasi angka statistik yang mengganggu pembacaan.
- Animasi besar pada tabel atau setiap baris saat halaman dibuka.

Hormati preferensi `prefers-reduced-motion` dan sediakan state tanpa animasi yang tidak penting.

---

## 8. Component Guidelines

### 8.1 Buttons

Jenis tombol:
- **Primary:** background `color-brand-700`, teks putih. Untuk tindakan utama seperti Kirim Laporan atau Simpan Perubahan.
- **Secondary:** background putih, border `color-border`, teks `color-ink`.
- **Tertiary / Ghost:** transparan, tanpa border permanen, untuk tindakan sekunder.
- **Destructive:** warna merah semantik untuk hapus atau tindakan yang tidak dapat dibatalkan.

Ketentuan:
- Satu area/form sebaiknya memiliki satu tombol primary.
- Gunakan label tindakan yang jelas, misalnya "Kirim Laporan", bukan "Submit".
- Tombol ikon saja harus memiliki tooltip atau accessible label.
- Tampilkan disabled, loading, hover, pressed, dan focus state.

### 8.2 Form fields

- Label diletakkan di atas field.
- Gunakan tinggi input 42-44 px.
- Placeholder memberi contoh, bukan menggantikan label.
- Helper text dan error message diletakkan dekat field terkait.
- Error menggunakan teks yang menjelaskan cara memperbaiki kesalahan.
- Field required diberi indikator yang konsisten.
- Textarea deskripsi memiliki tinggi awal yang cukup dan dapat diperluas.

### 8.3 Upload area

Area upload foto harus terlihat sebagai komponen fungsional.
- Gunakan border dashed yang tipis.
- Tampilkan ikon upload SVG, instruksi singkat, dan batas tipe/ukuran file.
- Sediakan tombol pilih file.
- Setelah file dipilih, tampilkan preview gambar, nama file, ukuran, dan tombol hapus.
- Tampilkan state error jika tipe atau ukuran file tidak valid.
- Jangan menggunakan ilustrasi besar di area upload.

### 8.4 Data tables

Tabel adalah komponen utama aplikasi administrasi.
- Gunakan surface putih dengan border luar yang halus.
- Header tabel memiliki background netral yang sangat ringan.
- Tinggi row desktop sekitar 52-60 px.
- Gunakan garis horizontal tipis untuk memisahkan baris.
- Hindari garis vertikal di setiap kolom kecuali memang dibutuhkan.
- Kolom tindakan diletakkan di sisi kanan.
- Gunakan alignment konsisten untuk teks, tanggal, angka, dan status.
- Sediakan empty state, loading state, dan error state.
- Untuk layar kecil, gunakan horizontal scroll yang terkontrol atau ubah row menjadi card/list. Jangan memaksakan semua kolom tampil sekaligus.

### 8.5 Search and filters

- Search bar memiliki ikon pencarian SVG dan placeholder yang spesifik.
- Filter status, kategori, dan tanggal menggunakan dropdown atau date picker.
- Filter aktif ditampilkan secara jelas dan dapat dihapus.
- Jangan memenuhi toolbar tabel dengan terlalu banyak kontrol. Prioritaskan pencarian dan filter yang paling sering dipakai.

### 8.6 Statistic cards

- Gunakan untuk ringkasan singkat, bukan menggantikan tabel utama.
- Tampilkan label, angka, dan ikon kecil.
- Hindari gradient dan ilustrasi di dalam card.
- Batasi jumlah card agar informasi tetap memiliki hierarki.
- Warna status hanya boleh digunakan pada statistik yang memang mewakili status tersebut.

### 8.7 Status badge

- Bentuk pill atau rounded rectangle dengan radius konsisten.
- Gunakan ukuran teks 12-13 px, weight 600.
- Selalu tampilkan label status secara eksplisit.
- Warna badge mengikuti tabel status colors.

### 8.8 Modal and confirmation

- Modal memiliki judul, penjelasan singkat, tombol aksi, dan tombol tutup.
- Konfirmasi tindakan destruktif harus menjelaskan dampaknya.
- Jangan menggunakan modal untuk formulir panjang. Gunakan halaman tersendiri.

### 8.9 Timeline

Pada detail laporan, tampilkan urutan:

`Menunggu → Diverifikasi → Diproses → Selesai`

- Tahap yang sudah dilewati ditandai sebagai selesai.
- Tahap aktif terlihat jelas.
- Tahap mendatang tampil lebih muted.
- Tanggal dan catatan ditampilkan bila datanya tersedia.
- Jangan menandai tahap sebagai selesai sebelum status benar-benar diperbarui di sistem.

---

## 9. Role-based Experience

Semua role menggunakan design system yang sama. Perbedaan terletak pada informasi, navigasi, dan tindakan yang tersedia.

### 9.1 Mahasiswa

**Sidebar:**
- Dashboard
- Buat Laporan
- Riwayat Laporan
- Profil

**Dashboard:**
- Sapaan singkat dan tombol utama "Buat Laporan".
- Ringkasan Total Laporan, Menunggu, Diproses, dan Selesai.
- Tabel laporan terbaru milik mahasiswa.
- Tampilkan status dan tanggal laporan secara jelas.

**Buat Laporan:**
- Form berisi nama fasilitas, kategori, lokasi kerusakan, deskripsi, dan foto.
- Pisahkan informasi fasilitas dari informasi kerusakan bila membantu pemahaman.
- Berikan petunjuk singkat untuk mengisi lokasi secara spesifik.
- Tampilkan preview foto sebelum laporan dikirim.

**Riwayat Laporan:**
- Tabel laporan milik mahasiswa yang sedang login.
- Search dan filter status.
- Aksi utama adalah melihat detail laporan.

**Detail Laporan:**
- Informasi fasilitas dan lokasi.
- Deskripsi kerusakan.
- Foto bukti.
- Status terkini dan timeline.
- Catatan tindak lanjut yang tersedia untuk pelapor.

### 9.2 Petugas

**Sidebar:**
- Dashboard
- Daftar Laporan
- Profil

**Dashboard:**
- Ringkasan laporan Menunggu, Diverifikasi, Diproses, dan Selesai.
- Daftar laporan yang perlu diperiksa atau ditindaklanjuti.
- Prioritaskan laporan menunggu dan diproses.

**Daftar Laporan:**
- Tabel laporan masuk.
- Informasi pelapor, fasilitas, lokasi, tanggal, dan status.
- Search dan filter status/kategori.
- Detail menjadi tindakan utama per baris.

**Detail Laporan:**
- Informasi lengkap laporan dan bukti foto.
- Identitas pelapor yang diperlukan untuk tindak lanjut.
- Kontrol perubahan status.
- Field catatan penanganan.
- Tombol simpan perubahan.
- Riwayat perubahan status bila tersedia.

### 9.3 Admin

**Sidebar:**
- Dashboard
- Manajemen Laporan
- Data Mahasiswa
- Data Petugas
- Kategori Fasilitas
- Profil

**Dashboard:**
- Ringkasan seluruh laporan.
- Total mahasiswa dan petugas.
- Grafik sederhana untuk tren laporan atau distribusi kategori jika datanya tersedia.
- Tabel laporan terbaru.
- Hindari menambahkan grafik yang tidak mendukung keputusan admin.

**Manajemen Laporan:**
- Tabel seluruh laporan.
- Search dan filter berdasarkan status, kategori, tanggal, atau pelapor.
- Detail dan pengelolaan laporan sesuai hak akses.

**Data Mahasiswa dan Data Petugas:**
- Tabel data akun.
- Aksi tambah, lihat, ubah, dan hapus.
- Form tambah/ubah menggunakan pola field yang konsisten.
- Aksi hapus harus memakai konfirmasi.

**Kategori Fasilitas:**
- Daftar kategori.
- Nama dan deskripsi kategori.
- Aksi tambah, ubah, dan hapus.

---

## 10. Responsive Behavior

### Desktop: 1200 px ke atas
- Sidebar expanded secara default.
- Tabel menggunakan layout penuh.
- Dashboard memakai grid beberapa kolom.
- Form dapat memakai lebar maksimum agar mudah dibaca.

### Tablet: 768-1199 px
- Sidebar dapat collapsed.
- Statistik menggunakan grid dua kolom.
- Toolbar tabel boleh membungkus ke baris kedua.
- Form dapat menggunakan satu kolom.

### Mobile: di bawah 768 px
- Sidebar berubah menjadi drawer atau navigasi mobile yang ringkas.
- Topbar hanya menampilkan kontrol yang penting.
- Statistik memakai satu atau dua kolom sesuai lebar layar.
- Form menggunakan satu kolom dan tombol utama mudah dijangkau.
- Tabel menggunakan horizontal scroll terkontrol atau tampilan list/card.
- Detail laporan disusun vertikal.
- Timeline berubah menjadi vertikal bila ruang horizontal tidak cukup.

Jangan hanya mengecilkan ukuran seluruh elemen desktop. Susun ulang prioritas informasi untuk mobile.

---

## 11. Accessibility and Usability

- Kontras teks dan background harus memadai.
- Jangan menjadikan warna sebagai satu-satunya penanda status.
- Semua field memiliki label yang terlihat.
- Semua tombol dan ikon interaktif memiliki accessible name.
- Fokus keyboard harus terlihat.
- Urutan tab harus mengikuti urutan visual dan logis.
- Berikan pesan sukses, gagal, dan validasi yang jelas.
- Hindari teks terlalu kecil, terutama pada tabel dan metadata.
- Sediakan empty state yang menjelaskan apa yang dapat dilakukan pengguna berikutnya.

---

## 12. UI States yang Wajib Dirancang

Untuk halaman dan komponen utama, sediakan state berikut:
- Default.
- Hover.
- Focus.
- Active/pressed.
- Disabled.
- Loading.
- Empty state.
- Error state.
- Success state.

Contoh khusus:
- Daftar laporan kosong.
- Foto berhasil diunggah.
- Format foto tidak didukung.
- Laporan berhasil dikirim.
- Status laporan berhasil diperbarui.
- Data gagal dimuat.
- Pencarian tidak menemukan hasil.

---

## 13. Screen Inventory untuk Google Stitch

Buat high-fidelity UI untuk layar berikut dengan konsistensi visual yang ketat:

1. Login.
2. Registrasi mahasiswa.
3. Dashboard mahasiswa.
4. Form buat laporan.
5. Riwayat laporan mahasiswa.
6. Detail laporan mahasiswa.
7. Dashboard petugas.
8. Daftar laporan petugas.
9. Detail dan pembaruan status laporan oleh petugas.
10. Dashboard admin.
11. Manajemen seluruh laporan.
12. Manajemen data mahasiswa.
13. Manajemen data petugas.
14. Manajemen kategori fasilitas.
15. Profil pengguna.

Semua teks antarmuka menggunakan Bahasa Indonesia. Gunakan data contoh yang realistis, misalnya:
- AC Ruang Kuliah A203.
- Proyektor Laboratorium Komputer.
- Lampu Koridor Gedung Fakultas.
- Toilet Lantai 2.
- Jaringan Internet Perpustakaan.
- Lokasi: Gedung Fakultas Teknik, Lantai 2, Ruang A203.

Gunakan ID laporan contoh seperti `LPR-2026-0001`.

---

## 14. Instructions for Google Stitch

Saat membuat desain di Google Stitch:
1. Terapkan dokumen ini sebagai satu design system untuk seluruh layar.
2. Mulai dari application shell: sidebar, topbar, dan content area.
3. Buat satu halaman dashboard sebagai acuan visual sebelum membuat halaman role lainnya.
4. Reuse warna, tipografi, radius, spacing, tabel, tombol, field, badge, dan ikon yang sama.
5. Bedakan role berdasarkan navigasi dan hak akses, bukan dengan membuat tiga tema visual berbeda.
6. Prioritaskan desktop 1440 px, lalu buat adaptasi tablet dan mobile.
7. Buat desain yang realistis untuk aplikasi administrasi kampus, bukan halaman promosi produk.
8. Jangan menambahkan fitur yang tidak tercantum dalam kebutuhan produk.
9. Jangan mengarang logo resmi kampus. Gunakan placeholder logo sederhana sampai aset resmi tersedia.
10. Hindari placeholder teks seperti Lorem Ipsum. Gunakan data contoh berbahasa Indonesia.

### Visual quality checklist

Sebelum desain dianggap selesai, pastikan:
- [ ] Semua halaman terlihat berasal dari aplikasi yang sama.
- [ ] Sidebar dan topbar konsisten di semua role.
- [ ] Status laporan konsisten dari dashboard sampai detail.
- [ ] Tabel tetap mudah dibaca.
- [ ] Form pelaporan jelas dan tidak terasa rumit.
- [ ] Tombol utama mudah ditemukan.
- [ ] Ikon memiliki gaya SVG yang seragam.
- [ ] Background texture tidak mengganggu konten.
- [ ] Shadow dan radius tidak berlebihan.
- [ ] Motion mendukung interaksi, bukan dekorasi.
- [ ] Tampilan mobile disusun ulang dengan benar.
- [ ] Tidak ada elemen dekoratif yang mengalahkan informasi utama.

---

## 15. CSS Token Reference

Token berikut dapat digunakan sebagai titik awal implementasi frontend.

```css
:root {
  /* Brand */
  --color-brand-700: #76563D;
  --color-brand-600: #8B6B4F;
  --color-brand-100: #F1EAE3;

  /* Text */
  --color-ink: #292929;
  --color-text-secondary: #69645E;
  --color-text-muted: #89837B;

  /* Surfaces */
  --color-canvas: #F7F6F3;
  --color-surface: #FFFFFF;
  --color-surface-muted: #F1EFEB;
  --color-border: #E5E0D9;
  --color-grid: #EDE9E3;

  /* Status */
  --status-waiting-text: #8A5A13;
  --status-waiting-bg: #FBF1DD;
  --status-verified-text: #315E82;
  --status-verified-bg: #EAF1F7;
  --status-processing-text: #68518B;
  --status-processing-bg: #F1ECF8;
  --status-completed-text: #356B4D;
  --status-completed-bg: #E9F3EC;
  --status-error-text: #A4433A;
  --status-error-bg: #F8EAE8;

  /* Typography */
  --font-sans: "Plus Jakarta Sans", Inter, ui-sans-serif, system-ui, sans-serif;

  /* Radius */
  --radius-sm: 6px;
  --radius-md: 9px;
  --radius-lg: 12px;
  --radius-xl: 16px;
  --radius-pill: 999px;

  /* Shadow */
  --shadow-card: 0 1px 3px rgba(41, 41, 41, 0.05);
  --shadow-popover: 0 8px 24px rgba(41, 41, 41, 0.10);
  --shadow-modal: 0 16px 48px rgba(41, 41, 41, 0.16);

  /* Motion */
  --duration-fast: 140ms;
  --duration-normal: 180ms;
  --duration-layout: 220ms;
  --ease-standard: ease-out;
}
```

---

## 16. Final Design Principle

**Buat sistem yang terlihat seperti portal administrasi kampus yang modern, bukan dashboard promosi.** Desain harus mengutamakan kejelasan informasi, kemudahan pelaporan, keterbacaan tabel, dan konsistensi antar-role. Gunakan warna earth tone secara hemat, komponen rounded secukupnya, ikon SVG yang seragam, serta motion yang memberikan feedback nyata kepada pengguna.
