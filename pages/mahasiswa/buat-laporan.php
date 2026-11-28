<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/core/Auth.php';

$currentUser = Auth::guard([ROLE_MAHASISWA]);
?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
<script src="../../assets/js/config/tailwind-config.js"></script><title>Buat Laporan - SIPERUSAK</title></head>
<body class="bg-canvas text-on-surface font-body-md text-body-md antialiased" data-role="mahasiswa" data-nav="buat-laporan" data-crumbs="Portal Mahasiswa|Buat Laporan">
<aside id="sidebar" class="fixed left-0 top-0 h-screen w-[230px] bg-surface border-r border-border z-50 hidden md:flex flex-col justify-between select-none"></aside>
<div class="pl-0 md:pl-[230px]">
<header id="topbar" class="fixed top-0 left-0 md:left-[230px] right-0 h-16 bg-surface border-b border-border z-40 px-4 md:px-space-lg flex items-center justify-between"></header>
<main class="w-full pt-16 min-h-screen bg-canvas">
<div class="p-6 md:p-8 max-w-5xl mx-auto w-full">

  <div class="flex flex-col gap-1 mb-8">
    <div class="flex items-center gap-2 text-text-secondary text-caption font-caption mb-1">
      <a href="dashboard.php" class="hover:text-primary transition-colors flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">grid_view</span><span>Dashboard</span></a>
      <span class="text-text-muted">/</span>
      <span class="text-on-surface font-label-sm">Buat Laporan</span>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
      <div>
        <h1 class="font-display text-display text-on-surface tracking-tight">Buat Laporan</h1>
        <p class="font-body-md text-body-md text-text-secondary mt-0.5">Laporkan kerusakan fasilitas kampus agar dapat segera ditindaklanjuti.</p>
      </div>
      <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-muted text-text-secondary text-caption font-label-sm self-start sm:self-auto">
        <span class="material-symbols-outlined text-[16px] text-primary">schedule</span>
        <span>Waktu respon rata-rata: ≤ 24 jam kerja</span>
      </div>
    </div>
  </div>

  <form id="form-laporan" class="bg-surface rounded-xl shadow-sm p-6 sm:p-8 flex flex-col gap-8">

    <!-- Bagian 1 -->
    <section class="flex flex-col">
      <div class="flex items-start justify-between border-b pb-4 mb-6">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-lg bg-surface-muted flex items-center justify-center text-primary-container"><span class="material-symbols-outlined text-[18px]">domain_verification</span></div>
          <div>
            <h2 class="font-headline-sm text-headline-sm text-on-surface">Informasi Fasilitas</h2>
            <p class="font-caption text-caption text-text-muted mt-0.5">Pilih fasilitas terdaftar yang mengalami kerusakan.</p>
          </div>
        </div>
        <span class="font-caption text-caption text-text-muted uppercase tracking-wider bg-canvas px-2.5 py-1 rounded">Bagian 1 dari 2</span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="flex flex-col gap-1.5">
          <label for="jenis-fasilitas" class="font-label-sm text-label-sm uppercase tracking-wider text-text-secondary flex items-center justify-between">
            <span>Jenis Fasilitas <span class="text-error">*</span></span><span class="font-caption text-caption lowercase text-text-muted font-normal">wajib</span>
          </label>
          <div class="relative">
            <select id="jenis-fasilitas" required class="w-full h-11 bg-surface rounded-lg px-3.5 pr-10 text-body-md font-body-md text-on-surface appearance-none focus:outline-none focus:ring-2 focus:ring-primary-container/20">
              <option value="ac" selected>Air Conditioner (AC)</option><option value="lcd">Proyektor LCD</option><option value="pc">Komputer Laboratorium</option><option value="furniture">Meja &amp; Kursi Kuliah</option><option value="lighting">Lampu Ruangan</option><option value="wifi">Jaringan / Router Wi-Fi</option><option value="sanitasi">Sanitasi / Toilet</option>
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-text-muted pointer-events-none text-[20px]">expand_more</span>
          </div>
        </div>

        <div class="flex flex-col gap-1.5">
          <label for="lokasi-fasilitas" class="font-label-sm text-label-sm uppercase tracking-wider text-text-secondary flex items-center justify-between">
            <span>Lokasi Fasilitas <span class="text-error">*</span></span><span class="font-caption text-caption lowercase text-text-muted font-normal">wajib</span>
          </label>
          <div class="relative">
            <select id="lokasi-fasilitas" required class="w-full h-11 bg-surface rounded-lg px-3.5 pr-10 text-body-md font-body-md text-on-surface appearance-none focus:outline-none focus:ring-2 focus:ring-primary-container/20">
              <option value="A203" selected>Gedung A, Lantai 2 - Ruang Kuliah A203</option><option value="B101">Gedung B, Lantai 1 - Lab Komputer 1</option><option value="C302">Gedung C, Lantai 3 - Ruang Seminar</option><option value="LIB1">Perpustakaan Pusat, Lantai 1</option><option value="D104">Gedung D, Lantai 1 - Ruang Dosen</option>
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-text-muted pointer-events-none text-[20px]">expand_more</span>
          </div>
          <span class="font-caption text-caption text-text-muted flex items-center gap-1 mt-0.5"><span class="material-symbols-outlined text-[14px]">info</span>Lokasi mengacu pada data denah ruang kampus resmi.</span>
        </div>

        <div class="flex flex-col gap-1.5 md:col-span-2">
          <label for="kode-inventaris" class="font-label-sm text-label-sm uppercase tracking-wider text-text-secondary flex items-center justify-between">
            <span>Barang / Kode Inventaris <span class="text-error">*</span></span><span class="font-caption text-caption lowercase text-text-muted font-normal">wajib</span>
          </label>
          <div class="relative">
            <select id="kode-inventaris" required class="w-full h-11 bg-surface rounded-lg px-3.5 pr-10 text-body-md font-body-md text-on-surface appearance-none focus:outline-none focus:ring-2 focus:ring-primary-container/20">
              <option value="AC-002" selected>AC-002 (Unit AC Dinding Depan - Daikin 2 PK)</option><option value="AC-001">AC-001 (Unit AC Dinding Belakang - Daikin 2 PK)</option><option value="AC-003">AC-003 (Unit AC Samping Kanan - Daikin 1.5 PK)</option>
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-text-muted pointer-events-none text-[20px]">expand_more</span>
          </div>
          <div class="mt-2 p-3 bg-surface-muted rounded-lg flex flex-wrap items-center justify-between gap-3 text-caption font-caption text-text-secondary">
            <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[18px] text-primary-container">qr_code_2</span><span>ID Asset Terdaftar: <strong class="text-on-surface font-body-strong">INV-FTI-2023-A203-AC02</strong></span></div>
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded bg-status-waiting-bg text-status-waiting-text font-label-sm">Perlu Pengecekan</span>
              <span class="px-2 py-0.5 rounded bg-status-completed-bg text-status-completed-text font-label-sm">Status: Aktif</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Bagian 2 -->
    <section class="flex flex-col pt-2">
      <div class="flex items-start justify-between border-b pb-4 mb-6">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-lg bg-surface-muted flex items-center justify-center text-primary-container"><span class="material-symbols-outlined text-[18px]">build_circle</span></div>
          <div>
            <h2 class="font-headline-sm text-headline-sm text-on-surface">Detail Kerusakan</h2>
            <p class="font-caption text-caption text-text-muted mt-0.5">Jelaskan permasalahan kerusakan dan sertakan foto bukti pendukung.</p>
          </div>
        </div>
        <span class="font-caption text-caption text-text-muted uppercase tracking-wider bg-canvas px-2.5 py-1 rounded">Bagian 2 dari 2</span>
      </div>

      <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-1.5">
          <div class="flex items-center justify-between">
            <label for="deskripsi-kerusakan" class="font-label-sm text-label-sm uppercase tracking-wider text-text-secondary">Deskripsi Kerusakan <span class="text-error">*</span></label>
            <span id="char-count" class="font-caption text-caption text-text-muted">0 / 500 karakter</span>
          </div>
          <textarea id="deskripsi-kerusakan" rows="4" required maxlength="500" placeholder="Jelaskan kondisi kerusakan fasilitas secara detail..." class="w-full min-h-[110px] bg-surface rounded-lg p-3.5 text-body-md font-body-md text-on-surface placeholder:text-text-muted focus:outline-none focus:ring-2 focus:ring-primary-container/20 resize-y leading-relaxed">Unit AC mengeluarkan tetesan air ke meja baris depan dan udara hembusan tidak dingin sejak sesi kuliah pagi pukul 08.00 WIB. Mengganggu jalannya perkuliahan kalkulus di ruang A203.</textarea>
          <span class="font-caption text-caption text-text-muted flex items-start gap-1.5 mt-0.5"><span class="material-symbols-outlined text-[15px] text-text-muted mt-0.5 shrink-0">tips_and_updates</span><span>Sebutkan gejala kerusakan, kapan kendala mulai terjadi, dan dampak terhadap aktivitas belajar.</span></span>
        </div>

        <div class="flex flex-col gap-1.5">
          <label class="font-label-sm text-label-sm uppercase tracking-wider text-text-secondary">Tingkat Urgensi Gangguan</label>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <label class="flex items-center gap-3 p-3 rounded-lg bg-canvas hover:bg-surface-muted cursor-pointer transition-colors">
              <input type="radio" name="urgensi" value="rendah" class="accent-primary-container"/>
              <div class="flex flex-col"><span class="font-body-strong text-body-strong text-on-surface">Rendah</span><span class="font-caption text-caption text-text-muted">Masih berfungsi terbatas</span></div>
            </label>
            <label class="flex items-center gap-3 p-3 rounded-lg bg-surface-muted cursor-pointer transition-colors">
              <input type="radio" name="urgensi" value="sedang" checked class="accent-primary-container"/>
              <div class="flex flex-col"><span class="font-body-strong text-body-strong text-on-surface">Sedang</span><span class="font-caption text-caption text-text-muted">Mengganggu kenyamanan kelas</span></div>
            </label>
            <label class="flex items-center gap-3 p-3 rounded-lg bg-canvas hover:bg-surface-muted cursor-pointer transition-colors">
              <input type="radio" name="urgensi" value="tinggi" class="accent-primary-container"/>
              <div class="flex flex-col"><span class="font-body-strong text-body-strong text-on-surface">Tinggi / Mendesak</span><span class="font-caption text-caption text-text-muted">Bahaya atau ruang tak bisa dipakai</span></div>
            </label>
          </div>
        </div>

        <div class="flex flex-col gap-2">
          <label class="font-label-sm text-label-sm uppercase tracking-wider text-text-secondary flex items-center justify-between">
            <span>Foto Bukti Kerusakan</span><span class="font-caption text-caption text-text-muted font-normal">Maksimal 3 foto</span>
          </label>
          <div class="rounded-xl p-6 sm:p-8 bg-[#FAFAF8] text-center hover:bg-[#F5F3EF] transition-colors cursor-pointer flex flex-col items-center justify-center gap-2 group">
            <div class="w-12 h-12 rounded-full bg-surface flex items-center justify-center text-primary-container group-hover:scale-105 transition-transform"><span class="material-symbols-outlined text-[24px]">cloud_upload</span></div>
            <div class="flex flex-col items-center">
              <p class="font-body-strong text-body-strong text-on-surface">Tarik &amp; lepas foto bukti di sini, atau klik tombol di bawah</p>
              <p class="font-caption text-caption text-text-muted mt-0.5">Format yang didukung: JPG, PNG, atau WEBP. Maksimal ukuran file 5 MB per foto.</p>
            </div>
            <button type="button" class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-surface text-on-surface text-label-sm font-label-sm hover:bg-canvas transition-colors"><span class="material-symbols-outlined text-[16px] text-text-secondary">add_photo_alternate</span><span>Pilih File</span></button>
          </div>

          <div class="flex flex-col gap-2 mt-2">
            <span class="font-caption text-caption text-text-muted">Foto terlampir (2 dari 3):</span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div class="flex items-center justify-between p-2.5 bg-surface rounded-lg">
                <div class="flex items-center gap-3 min-w-0">
                  <div class="w-12 h-12 rounded bg-surface-muted overflow-hidden shrink-0"><img class="w-full h-full object-cover" alt="Kebocoran AC" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA-3MQtOwyCfh5bPDRfaDn46xa-ri1fAwMCB6Sq9ORdUa3J2Yf0X7SZ_VxtDVX1XpQgAA7dZku4sPVigEUZMqR15jldsvqAafsAQ67vWB4ApON_M4PMwfeQSnbNJ83vr--p0rCAbBh-uFNhJ5J-gthv6m1xPRsvlTdu412KCCyG94UpMx2LIHoK0PSxG54iUCZtx6uQRQXrSadPjKM-YAKcLOrfnSLMdoX3hLZ5-jo"/></div>
                  <div class="flex flex-col min-w-0">
                    <span class="font-label-sm text-label-sm text-on-surface truncate">bukti_kebocoran_ac_depan.jpg</span>
                    <div class="flex items-center gap-2 text-caption font-caption text-text-muted"><span>1.8 MB</span><span>•</span><span class="text-status-completed-text flex items-center gap-0.5"><span class="material-symbols-outlined text-[13px]">check_circle</span>Siap diunggah</span></div>
                  </div>
                </div>
                <button type="button" aria-label="Hapus file bukti_kebocoran_ac_depan.jpg" class="p-1.5 text-text-muted hover:text-status-error-text rounded hover:bg-status-error-bg transition-colors shrink-0"><span class="material-symbols-outlined text-[18px]">delete</span></button>
              </div>
              <div class="flex items-center justify-between p-2.5 bg-surface rounded-lg">
                <div class="flex items-center gap-3 min-w-0">
                  <div class="w-12 h-12 rounded bg-surface-muted overflow-hidden shrink-0"><img class="w-full h-full object-cover" alt="Label inventaris AC-002" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDRZjBXdaB10yUoDFncuFicSNfQth1H_7qFykCIWdQsOIOpICsVkSNU2SXkfznAAh_saX_mU9SRzB4BwZNpdUSAOlU9Px1wgLDDXj04LUMAKaSuK0UQ79j9tZpJx8rq7jCvkyGO7nsAL42Gr_G7jQ5IQCrKGSUFF8UIclkVdlF1P4h8o8jCk2G4zzPluI_7-wTPGi65Y_bBnBVJaqm32Ap41B8196slb3W8rqlSjnk"/></div>
                  <div class="flex flex-col min-w-0">
                    <span class="font-label-sm text-label-sm text-on-surface truncate">label_inventaris_ac002.jpg</span>
                    <div class="flex items-center gap-2 text-caption font-caption text-text-muted"><span>940 KB</span><span>•</span><span class="text-status-completed-text flex items-center gap-0.5"><span class="material-symbols-outlined text-[13px]">check_circle</span>Siap diunggah</span></div>
                  </div>
                </div>
                <button type="button" aria-label="Hapus file label_inventaris_ac002.jpg" class="p-1.5 text-text-muted hover:text-status-error-text rounded hover:bg-status-error-bg transition-colors shrink-0"><span class="material-symbols-outlined text-[18px]">delete</span></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Aksi -->
    <div class="border-t pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
      <div class="flex items-center gap-2 text-caption font-caption text-text-muted text-center sm:text-left">
        <span class="material-symbols-outlined text-[16px] text-text-secondary shrink-0">verified_user</span>
        <span>Pastikan informasi yang diisi telah sesuai sebelum mengirim laporan.</span>
      </div>
      <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
        <button type="button" onclick="window.history.back()" class="w-full sm:w-auto px-5 py-2.5 rounded-lg bg-surface text-on-surface text-body-md font-body-strong hover:bg-surface-muted transition-colors">Batal</button>
        <button id="btn-submit" type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-lg bg-primary-container text-on-primary text-body-md font-label-md hover:bg-primary transition-colors flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[18px]">send</span><span>Kirim Laporan</span></button>
      </div>
    </div>
  </form>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
    <div class="p-4 rounded-xl bg-surface flex items-start gap-3"><span class="material-symbols-outlined text-[20px] text-primary-container shrink-0 mt-0.5">assignment_turned_in</span><div><h3 class="font-label-md text-label-md text-on-surface">Verifikasi Admin</h3><p class="font-caption text-caption text-text-secondary mt-0.5">Laporan akan divalidasi oleh Bagian Umum dan Perlengkapan kampus.</p></div></div>
    <div class="p-4 rounded-xl bg-surface flex items-start gap-3"><span class="material-symbols-outlined text-[20px] text-primary-container shrink-0 mt-0.5">notifications_active</span><div><h3 class="font-label-md text-label-md text-on-surface">Notifikasi Status</h3><p class="font-caption text-caption text-text-secondary mt-0.5">Perkembangan tindak lanjut teknisi dapat dipantau di Riwayat Laporan.</p></div></div>
    <div class="p-4 rounded-xl bg-surface flex items-start gap-3"><span class="material-symbols-outlined text-[20px] text-primary-container shrink-0 mt-0.5">headset_mic</span><div><h3 class="font-label-md text-label-md text-on-surface">Kendala Darurat</h3><p class="font-caption text-caption text-text-secondary mt-0.5">Untuk insiden darurat listrik/air bahaya, hubungi Posko Pusat di Ext. 102.</p></div></div>
  </div>
</div>
</main>
</div>
<?= Auth::userScript() ?>
<script src="../../assets/js/components/layout.js?v=2"></script><script src="../../assets/js/pages/mahasiswa/buat-laporan.js"></script></body></html>
