# SIPERUSAK

**Sistem Pelaporan Kerusakan Fasilitas Kampus.**

Aplikasi web untuk melaporkan, memverifikasi, dan memantau penanganan kerusakan fasilitas kampus. Sistem memiliki tiga role: **Mahasiswa**, **Petugas**, dan **Admin**.

Repositori ini sedang berada pada **Fase 1 — Persiapan Struktur Direktori Fullstack-Ready**. Frontend statis yang sudah ada dimigrasikan dari `.html` menjadi `.php` sebagai fondasi integrasi backend **PHP 8+ Native OOP + MySQL PDO** pada fase berikutnya.

> **Status Fase 1:** Halaman publik dan halaman Mahasiswa sudah tersedia dalam format `.php`. Antarmuka **Petugas** dan **Admin** belum dirancang, sehingga folder terkait hanya berisi `README.md` sebagai placeholder. Data fasilitas masih memakai `assets/js/data/facilities.js` agar prototipe Mahasiswa tetap berjalan sampai API tersedia di Fase 3.

## Menjalankan Proyek

Letakkan folder proyek di dalam direktori web server lokal (mis. `C:\laragon\www` untuk Laragon atau `htdocs` untuk XAMPP), lalu buka:

```
http://localhost/projek%20uas%20web/
```

Prasyarat:

- PHP 8.0 atau lebih baru (Apache/Nginx melalui Laragon atau XAMPP).
- MySQL/MariaDB untuk Fase 2 dan seterusnya.
- Koneksi internet saat membuka halaman, karena Tailwind CDN, Google Fonts, dan Material Symbols dimuat langsung dari internet.
- Tidak ada proses build. Semua aset frontend dimuat langsung.

## Arsitektur Fase 1

```text
projek-uas-web/
├── index.php                      # Beranda publik (hasil migrasi index.html)
├── README.md
├── .gitignore                     # Mengabaikan uploads/reports/*, .env, file OS
├── docs/
│   ├── prd.md                     # Kebutuhan produk
│   └── design.md                  # Design system
├── database/                      # Menunggu siperusak.sql (Fase 2)
├── app/                           # Kerangka backend PHP OOP (Fase 2 & 3)
│   ├── config/                    # Konfigurasi aplikasi & koneksi PDO
│   ├── core/                      # Router, Controller, Model, Session, dsb.
│   ├── models/                    # Model data
│   └── controllers/               # Controller per role/fitur
├── api/                           # Endpoint JSON (Fase 3)
├── assets/
│   ├── css/
│   │   └── style.css              # Style global (scroll, safe-area, font)
│   ├── img/                       # Aset gambar/ikon SVG
│   └── js/
│       ├── config/
│       │   └── tailwind-config.js # Konfigurasi Tailwind (token warna, font, spacing)
│       ├── data/
│       │   └── facilities.js      # Data dummy — dipertahankan sampai Fase 3
│       ├── services/              # Menunggu api-client.js (Fase 3)
│       ├── components/
│       │   └── layout.js          # App shell: sidebar + topbar semua role
│       └── pages/
│           ├── home.js            # Beranda publik
│           ├── auth.js            # Login & registrasi
│           ├── shared/            # Menunggu profil.js
│           ├── mahasiswa/
│           │   ├── daftar-fasilitas.js
│           │   ├── buat-laporan.js
│           │   └── riwayat-laporan.js
│           ├── petugas/README.md  # Placeholder — belum ada JS
│           └── admin/README.md    # Placeholder — belum ada JS
├── pages/
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── mahasiswa/
│   │   ├── dashboard.php
│   │   ├── daftar-fasilitas.php
│   │   ├── buat-laporan.php
│   │   ├── riwayat-laporan.php
│   │   ├── detail-laporan.php
│   │   └── profil.php
│   ├── petugas/README.md          # Placeholder — belum ada PHP
│   └── admin/README.md            # Placeholder — belum ada PHP
└── uploads/
    └── reports/
        └── .htaccess              # php_flag engine off
```

Folder kosong yang disiapkan untuk fase berikutnya dijaga dengan file `.gitkeep`.

## Konvensi

- **Penamaan file:** `kebab-case` (contoh: `buat-laporan.php`, `riwayat-laporan.js`).
- **Satu halaman = satu script controller** di `assets/js/pages/<role>/` dengan nama yang sama.
- **Path aset** selalu relatif dari file halaman:
  - `index.php` → `assets/...`
  - `pages/auth/*.php` dan `pages/mahasiswa/*.php` → `../../assets/...`
- **Tautan internal selalu berakhiran `.php`**, termasuk `href` di markup dan tautan yang dihasilkan JavaScript.
- **Sidebar & topbar tidak diduplikasi di HTML.** Elemen `<aside id="sidebar">` dan `<header id="topbar">` diisi oleh `assets/js/components/layout.js` berdasarkan atribut `<body>`:

  ```html
  <body
    data-role="mahasiswa"
    data-nav="dashboard"
    data-crumbs="Portal Mahasiswa|Dashboard">
  ```

  - `data-role`: `mahasiswa` (default), `petugas`, atau `admin`.
  - `data-nav`: key menu yang aktif pada sidebar.
  - `data-crumbs`: breadcrumb, dipisah `|`.

- **Tailwind config tunggal** ada di `assets/js/config/tailwind-config.js` dan harus dimuat setelah CDN Tailwind.
- **Backend:** PHP 8+ Native OOP dengan **PDO** (bukan `mysqli`). Kredensial dan konfigurasi environment akan disimpan di `.env` (diabaikan Git) pada Fase 2.
- **Upload foto laporan** disimpan di `uploads/reports/`; isi folder diabaikan Git dan eksekusi PHP di dalamnya dimatikan melalui `.htaccess`.
- **Data contoh** ada di `assets/js/data/facilities.js`; nanti diganti dengan data dari API pada Fase 3.

## Alur Halaman

- Publik: `index.php` → `pages/auth/login.php` / `pages/auth/register.php`
- Mahasiswa: `pages/mahasiswa/dashboard.php` → daftar fasilitas → buat laporan → riwayat → detail → profil
- Petugas: **belum diimplementasikan** (placeholder di `pages/petugas/`)
- Admin: **belum diimplementasikan** (placeholder di `pages/admin/`)

## Status Laporan

Mengacu `docs/prd.md` dan `docs/design.md`, sistem memakai lima status baku:

| Status | Warna teks | Warna background |
|---|---|---|
| Menunggu | `#8A5A13` | `#FBF1DD` |
| Diverifikasi | `#315E82` | `#EAF1F7` |
| Diproses | `#68518B` | `#F1ECF8` |
| Selesai | `#356B4D` | `#E9F3EC` |
| Ditolak | `#A4433A` | `#F8EAE8` |

Tipografi utama: **Plus Jakarta Sans**. Token warna dan font didefinisikan di `assets/js/config/tailwind-config.js` dan `assets/css/style.css`.

## Rencana Fase

1. **Fase 1 (selesai):** persiapan struktur direktori fullstack, migrasi `.html` → `.php`, dan pembaruan tautan internal.
2. **Fase 2:** `database/siperusak.sql`, koneksi PDO, core PHP OOP, autentikasi, dan pembagian role.
3. **Fase 3:** endpoint JSON di `api/`, `assets/js/services/api-client.js`, dan penggantian `facilities.js` dengan data backend.
4. **Fase 4 dan seterusnya:** implementasi antarmuka Petugas, Admin, unggah foto, serta pengujian.

## Dokumen

- [`docs/prd.md`](docs/prd.md) — kebutuhan produk, role, status laporan, rancangan database.
- [`docs/design.md`](docs/design.md) — design system (warna, tipografi, komponen, layout).
