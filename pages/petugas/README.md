# Halaman Petugas

Folder ini sengaja hanya berisi `README.md` pada **Fase 1**. Antarmuka role **Petugas** belum dirancang, sehingga belum ada file `.php`, `.html`, maupun `.js` yang dibuat untuk role ini.

## Rencana halaman (mengacu `docs/prd.md`)

| File | Keterangan |
|---|---|
| `dashboard.php` | Ringkasan laporan masuk dan data barang |
| `data-barang.php` | Daftar barang/fasilitas yang dikelola |
| `barang-tambah.php` | Form tambah barang |
| `barang-edit.php` | Form ubah barang |
| `daftar-laporan.php` | Seluruh laporan yang masuk |
| `detail-laporan.php` | Verifikasi, penolakan, dan pembaruan status |
| `profil.php` | Profil petugas |

## Cara menambah halaman (fase berikutnya)

1. Salin struktur dari `pages/mahasiswa/` (sidebar & topbar diisi otomatis oleh `assets/js/components/layout.js`).
2. Set atribut pada `<body>`:
   ```html
   <body data-role="petugas" data-nav="dashboard" data-crumbs="Portal Petugas|Dashboard">
   ```
3. Taruh script khusus halaman di `assets/js/pages/petugas/` (lihat placeholder di sana).
4. Gunakan path aset berawalan `../../assets/...` dan tautan internal berakhiran `.php`.
