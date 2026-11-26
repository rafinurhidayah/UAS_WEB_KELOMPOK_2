# SIPERUSAK

Aplikasi Pelaporan Kerusakan Fasilitas Kampus.

Website statis (HTML + Tailwind CSS via CDN + JavaScript vanilla) untuk melaporkan, memverifikasi, dan memantau penanganan kerusakan fasilitas kampus. Sistem direncanakan memiliki tiga role: **Mahasiswa**, **Petugas**, dan **Admin**.

> Saat ini hanya halaman **Mahasiswa** dan halaman publik (beranda, login, registrasi) yang sudah dibuat. Folder `pages/petugas/` dan `pages/admin/` sudah disiapkan untuk pengembangan berikutnya.

## Menjalankan Proyek

Letakkan folder proyek di dalam `htdocs` XAMPP, lalu buka:

```
http://localhost/projek%20uas%20web/
```

Tidak ada proses build. Semua dependensi (Tailwind CDN, Google Fonts, Material Symbols) dimuat langsung dari internet, jadi koneksi internet diperlukan saat membuka halaman.

## Struktur Folder

```
projek uas web/
├── index.html                     # Beranda publik (katalog fasilitas)
├── README.md
├── docs/
│   ├── prd.md                     # Kebutuhan produk
│   └── design.md                  # Design system
├── assets/
│   ├── css/
│   │   └── style.css              # Style global (scroll, safe-area, font)
│   └── js/
│       ├── config/
│       │   └── tailwind-config.js # Konfigurasi Tailwind (token warna, font, spacing)
│       ├── data/
│       │   └── facilities.js      # Data dummy katalog fasilitas
│       ├── components/
│       │   └── layout.js          # App shell: sidebar + topbar semua role
│       └── pages/                 # Script khusus per halaman
│           ├── home.js            # Beranda publik
│           ├── auth.js            # Login & registrasi
│           ├── mahasiswa/
│           │   ├── daftar-fasilitas.js
│           │   ├── buat-laporan.js
│           │   └── riwayat-laporan.js
│           ├── petugas/           # (disiapkan)
│           └── admin/             # (disiapkan)
└── pages/
    ├── auth/
    │   ├── login.html
    │   └── register.html
    ├── mahasiswa/
    │   ├── dashboard.html
    │   ├── daftar-fasilitas.html
    │   ├── buat-laporan.html
    │   ├── riwayat-laporan.html
    │   ├── detail-laporan.html
    │   └── profil.html
    ├── petugas/README.md          # Rencana halaman petugas
    └── admin/README.md            # Rencana halaman admin
```

## Konvensi

- **Penamaan file:** `kebab-case` (contoh: `buat-laporan.html`, `riwayat-laporan.js`).
- **Satu halaman = satu script controller** di `assets/js/pages/<role>/` dengan nama yang sama.
- **Path aset** selalu relatif dari file HTML:
  - `index.html` → `assets/...`
  - `pages/auth/*.html` dan `pages/mahasiswa/*.html` → `../../assets/...`
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

- **Navigasi antarhalaman** di dalam satu role memakai path relatif biasa (mis. `dashboard.html`) karena semua halaman role berada dalam satu folder.
- **Tailwind config tunggal** ada di `assets/js/config/tailwind-config.js` dan harus dimuat setelah CDN Tailwind.
- **Data contoh** ada di `assets/js/data/facilities.js`; nanti diganti dengan data dari backend.

## Alur Halaman

- Publik: `index.html` → `pages/auth/login.html` / `pages/auth/register.html`
- Mahasiswa: `pages/mahasiswa/dashboard.html` → daftar fasilitas → buat laporan → riwayat → detail → profil

## Dokumen

- [`docs/prd.md`](docs/prd.md) — kebutuhan produk, role, status laporan, rancangan database.
- [`docs/design.md`](docs/design.md) — design system (warna, tipografi, komponen, layout).
