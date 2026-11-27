<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
<script src="../../assets/js/config/tailwind-config.js"></script><title>Profil - SIPERUSAK</title></head>
<body class="bg-canvas text-on-surface font-body-md text-body-md antialiased" data-role="mahasiswa" data-nav="profil" data-crumbs="Portal Mahasiswa|Profil">
<aside id="sidebar" class="fixed left-0 top-0 h-screen w-[230px] bg-surface border-r border-border z-50 hidden md:flex flex-col justify-between select-none"></aside>
<div class="pl-0 md:pl-[230px]">
<header id="topbar" class="fixed top-0 left-0 md:left-[230px] right-0 h-16 bg-surface border-b border-border z-40 px-4 md:px-space-lg flex items-center justify-between"></header>
<main class="w-full pt-16 min-h-screen bg-canvas">
<div class="w-full px-4 md:px-margin-desktop py-space-lg md:py-space-xl flex flex-col items-center">
  <div class="w-full max-w-4xl flex flex-col gap-space-lg">

    <div class="flex flex-col gap-1">
      <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Profil</h1>
      <p class="font-body-md text-body-md text-text-secondary">Informasi akun dan identitas mahasiswa.</p>
    </div>

    <div class="bg-surface rounded-lg shadow-sm flex flex-col overflow-hidden">

      <!-- Identitas -->
      <div class="p-space-lg md:p-space-xl flex flex-col sm:flex-row sm:items-center justify-between gap-space-md bg-surface-container-low/40">
        <div class="flex items-center gap-space-md">
          <div class="w-16 h-16 rounded-full bg-secondary-container/50 flex items-center justify-center flex-shrink-0 select-none">
            <span class="font-headline-lg text-headline-lg font-bold text-primary-container">AF</span>
          </div>
          <div class="flex flex-col min-w-0">
            <h2 class="font-headline-sm text-headline-sm text-on-surface font-semibold truncate leading-tight">Ahmad Fauzi</h2>
            <span class="font-body-md text-body-md text-text-secondary mt-0.5">NIM 21051204012</span>
          </div>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-center">
          <span class="inline-flex items-center px-3 py-1 rounded font-label-sm text-label-sm font-semibold bg-surface-muted text-primary-container">Mahasiswa</span>
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded font-label-sm text-label-sm font-semibold bg-status-completed-bg text-status-completed-text">
            <span class="w-1.5 h-1.5 rounded-full bg-status-completed-text"></span>Aktif
          </span>
        </div>
      </div>

      <div class="h-[1px] w-full bg-surface-container"></div>

      <!-- Rincian data -->
      <div class="p-space-lg md:p-space-xl flex flex-col gap-space-md">
        <div class="flex items-center gap-2 pb-2">
          <span class="material-symbols-outlined text-[20px] text-text-muted">badge</span>
          <span class="font-label-md text-label-md text-on-surface uppercase tracking-wider">Rincian Data Pengguna</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">Nama Lengkap</span>
            <span class="font-body-strong text-body-strong text-on-surface truncate">Ahmad Fauzi</span>
          </div>
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">Nomor Induk Mahasiswa (NIM)</span>
            <span class="font-body-strong text-body-strong text-on-surface truncate">21051204012</span>
          </div>
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">Email Kampus / Pengguna</span>
            <span class="font-body-strong text-body-strong text-on-surface truncate">ahmad.fauzi@student.kampus.ac.id</span>
          </div>
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">Nomor Telepon</span>
            <span class="font-body-strong text-body-strong text-on-surface truncate">081234567890</span>
          </div>
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">Program Studi / Fakultas</span>
            <span class="font-body-strong text-body-strong text-on-surface truncate">S1 Teknik Informatika / Fakultas Teknologi Informasi</span>
          </div>
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">Status Akun</span>
            <span class="font-body-strong text-body-strong text-on-surface">Aktif (Terdaftar Resmi)</span>
          </div>
        </div>
      </div>

      <div class="h-[1px] w-full bg-surface-container"></div>

      <!-- Catatan -->
      <div class="p-space-md md:p-space-lg bg-surface-muted/60 flex items-start gap-space-sm text-text-secondary">
        <span class="material-symbols-outlined text-[20px] text-primary-container flex-shrink-0 mt-0.5">info</span>
        <div class="flex flex-col text-left">
          <span class="font-label-sm text-label-sm text-on-surface mb-0.5">Catatan Pengguna</span>
          <p class="font-caption text-caption text-text-secondary leading-relaxed">Apabila terdapat kesalahan data identitas atau perubahan kontak, silakan menghubungi Bagian Administrasi Akademik atau Pusat Layanan Kampus.</p>
        </div>
      </div>
    </div>
  </div>
</div>
</main>
</div>
<script src="../../assets/js/components/layout.js"></script></body></html>
