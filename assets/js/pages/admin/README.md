# Placeholder Script Admin

Folder ini sengaja hanya berisi `README.md` pada **Fase 1**. Antarmuka role **Admin** belum dirancang, sehingga tidak boleh ada file `.js` (maupun `.php`/`.html`) yang dibuat di sini.

## Rencana pengisian (fase implementasi Admin)

| File | Keterangan |
|---|---|
| `dashboard.js` | Statistik laporan, jumlah mahasiswa & petugas |
| `laporan.js` | Manajemen seluruh laporan |
| `mahasiswa.js` | CRUD akun mahasiswa |
| `petugas.js` | CRUD akun petugas |
| `kategori.js` | CRUD kategori fasilitas |
| `lokasi.js` | CRUD lokasi kampus |
| `profil.js` | Profil admin |

Script nantinya dimuat dari halaman PHP di `pages/admin/` dan berkomunikasi dengan endpoint JSON di `api/` melalui `assets/js/services/api-client.js` (Fase 3).
