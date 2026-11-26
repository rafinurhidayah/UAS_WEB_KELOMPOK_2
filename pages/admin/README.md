# Halaman Admin

Folder ini disiapkan untuk halaman role **Admin** (belum diimplementasikan).

## Rencana halaman (mengacu `docs/prd.md`)

| File | Keterangan |
|---|---|
| `dashboard.html` | Statistik laporan, jumlah mahasiswa & petugas |
| `laporan.html` | Manajemen seluruh laporan |
| `detail-laporan.html` | Detail dan riwayat perubahan status |
| `mahasiswa.html` | CRUD akun mahasiswa |
| `petugas.html` | CRUD akun petugas |
| `kategori.html` | CRUD kategori fasilitas |
| `lokasi.html` | CRUD lokasi kampus |
| `profil.html` | Profil admin |

## Cara menambah halaman

1. Salin struktur dari `pages/mahasiswa/` (sidebar & topbar diisi otomatis oleh `assets/js/components/layout.js`).
2. Set atribut pada `<body>`:
   ```html
   <body data-role="admin" data-nav="dashboard" data-crumbs="Portal Admin|Dashboard">
   ```
3. Taruh script khusus halaman di `assets/js/pages/admin/`.
4. Gunakan path aset berawalan `../../assets/...`.
