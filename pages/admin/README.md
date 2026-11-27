# Halaman Admin

Folder ini sengaja hanya berisi `README.md` pada **Fase 1**. Antarmuka role **Admin** belum dirancang, sehingga belum ada file `.php`, `.html`, maupun `.js` yang dibuat untuk role ini.

## Rencana halaman (mengacu `docs/prd.md`)

| File | Keterangan |
|---|---|
| `dashboard.php` | Statistik laporan, jumlah mahasiswa & petugas |
| `laporan.php` | Manajemen seluruh laporan |
| `detail-laporan.php` | Detail dan riwayat perubahan status |
| `mahasiswa.php` | CRUD akun mahasiswa |
| `petugas.php` | CRUD akun petugas |
| `kategori.php` | CRUD kategori fasilitas |
| `lokasi.php` | CRUD lokasi kampus |
| `profil.php` | Profil admin |

## Cara menambah halaman (fase berikutnya)

1. Salin struktur dari `pages/mahasiswa/` (sidebar & topbar diisi otomatis oleh `assets/js/components/layout.js`).
2. Set atribut pada `<body>`:
   ```html
   <body data-role="admin" data-nav="dashboard" data-crumbs="Portal Admin|Dashboard">
   ```
3. Taruh script khusus halaman di `assets/js/pages/admin/` (lihat placeholder di sana).
4. Gunakan path aset berawalan `../../assets/...` dan tautan internal berakhiran `.php`.
