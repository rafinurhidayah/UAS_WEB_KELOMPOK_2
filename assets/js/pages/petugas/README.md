# Placeholder Script Petugas

Folder ini sengaja hanya berisi `README.md` pada **Fase 1**. Antarmuka role **Petugas** belum dirancang, sehingga tidak boleh ada file `.js` (maupun `.php`/`.html`) yang dibuat di sini.

## Rencana pengisian (fase implementasi Petugas)

| File | Keterangan |
|---|---|
| `dashboard.js` | Ringkasan laporan masuk dan data barang |
| `data-barang.js` | Daftar, tambah, dan ubah barang/fasilitas |
| `daftar-laporan.js` | Seluruh laporan yang masuk |
| `detail-laporan.js` | Verifikasi, penolakan, dan pembaruan status |
| `profil.js` | Profil petugas |

Script nantinya dimuat dari halaman PHP di `pages/petugas/` dan berkomunikasi dengan endpoint JSON di `api/` melalui `assets/js/services/api-client.js` (Fase 3).
