# Halaman Petugas

Folder ini disiapkan untuk halaman role **Petugas** (belum diimplementasikan).

## Rencana halaman (mengacu `docs/prd.md`)

| File | Keterangan |
|---|---|
| `dashboard.html` | Ringkasan laporan masuk dan data barang |
| `data-barang.html` | Daftar barang/fasilitas yang dikelola |
| `barang-tambah.html` | Form tambah barang |
| `barang-edit.html` | Form ubah barang |
| `daftar-laporan.html` | Seluruh laporan yang masuk |
| `detail-laporan.html` | Verifikasi, penolakan, dan pembaruan status |
| `profil.html` | Profil petugas |

## Cara menambah halaman

1. Salin struktur dari `pages/mahasiswa/` (sidebar & topbar diisi otomatis oleh `assets/js/components/layout.js`).
2. Set atribut pada `<body>`:
   ```html
   <body data-role="petugas" data-nav="dashboard" data-crumbs="Portal Petugas|Dashboard">
   ```
3. Taruh script khusus halaman di `assets/js/pages/petugas/`.
4. Gunakan path aset berawalan `../../assets/...`.
