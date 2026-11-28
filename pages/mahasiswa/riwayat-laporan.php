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
<script src="../../assets/js/config/tailwind-config.js"></script><title>Riwayat Laporan - SIPERUSAK</title></head>
<body class="bg-canvas text-on-surface font-body-md text-body-md antialiased" data-role="mahasiswa" data-nav="riwayat-laporan" data-crumbs="Dashboard|Riwayat Laporan">
<aside id="sidebar" class="fixed left-0 top-0 h-screen w-[230px] bg-surface border-r border-border z-50 hidden md:flex flex-col justify-between select-none"></aside>
<div class="pl-0 md:pl-[230px]">
<header id="topbar" class="fixed top-0 left-0 md:left-[230px] right-0 h-16 bg-surface border-b border-border z-40 px-4 md:px-space-lg flex items-center justify-between"></header>
<main class="w-full pt-16 min-h-screen bg-canvas">
<div class="w-full px-4 md:px-space-xl py-space-lg max-w-7xl mx-auto flex flex-col gap-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex flex-col gap-1">
      <h1 class="font-display text-display text-on-surface">Riwayat Laporan</h1>
      <p class="font-body-md text-body-md text-text-secondary">Pantau perkembangan laporan kerusakan fasilitas yang telah Anda kirimkan.</p>
    </div>
    <a href="buat-laporan.php" class="h-[42px] px-4 rounded-lg bg-primary-container hover:bg-primary text-on-primary font-body-strong text-body-strong flex items-center justify-center gap-2 transition-colors shadow-sm"><span class="material-symbols-outlined text-[18px]">add</span><span>Buat Laporan</span></a>
  </div>

  <!-- Ringkasan -->
  <div class="w-full bg-surface rounded-xl p-4 shadow-sm">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div class="flex items-center gap-3.5 p-2 bg-surface-container-low rounded-lg">
        <div class="w-10 h-10 rounded-lg bg-surface flex items-center justify-center text-on-surface-variant flex-shrink-0"><span class="material-symbols-outlined text-[20px]">assignment</span></div>
        <div class="flex flex-col min-w-0"><span class="font-caption text-caption text-text-muted">Total Laporan</span><span class="font-headline-lg text-headline-lg tabular-nums text-on-surface">12</span></div>
      </div>
      <div class="flex items-center gap-3.5 p-2 bg-status-waiting-bg/40 rounded-lg">
        <div class="w-10 h-10 rounded-lg bg-surface flex items-center justify-center text-status-waiting-text flex-shrink-0"><span class="material-symbols-outlined text-[20px]">schedule</span></div>
        <div class="flex flex-col min-w-0"><span class="font-caption text-caption text-status-waiting-text">Menunggu</span><span class="font-headline-lg text-headline-lg tabular-nums text-status-waiting-text">2</span></div>
      </div>
      <div class="flex items-center gap-3.5 p-2 bg-status-processing-bg/60 rounded-lg">
        <div class="w-10 h-10 rounded-lg bg-surface flex items-center justify-center text-status-processing-text flex-shrink-0"><span class="material-symbols-outlined text-[20px]">build</span></div>
        <div class="flex flex-col min-w-0"><span class="font-caption text-caption text-status-processing-text">Diproses</span><span class="font-headline-lg text-headline-lg tabular-nums text-status-processing-text">3</span></div>
      </div>
      <div class="flex items-center gap-3.5 p-2 bg-status-completed-bg/60 rounded-lg">
        <div class="w-10 h-10 rounded-lg bg-surface flex items-center justify-center text-status-completed-text flex-shrink-0"><span class="material-symbols-outlined text-[20px]">check_circle</span></div>
        <div class="flex flex-col min-w-0"><span class="font-caption text-caption text-status-completed-text">Selesai</span><span class="font-headline-lg text-headline-lg tabular-nums text-status-completed-text">6</span></div>
      </div>
    </div>
  </div>

  <!-- Tabel & filter -->
  <div class="bg-surface rounded-xl shadow-sm overflow-hidden flex flex-col">
    <div class="p-4 md:p-5 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 bg-surface">
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
        <div class="relative w-full sm:w-80">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] pointer-events-none">search</span>
          <input type="text" placeholder="Cari kode laporan atau nama fasilitas..." class="w-full h-[42px] pl-9 pr-3 rounded-lg bg-surface text-on-surface font-body-md text-body-md placeholder:text-text-muted focus:outline-none focus:bg-surface-container-low transition-colors"/>
        </div>
        <div class="relative w-full sm:w-48">
          <select class="w-full h-[42px] px-3.5 pr-8 rounded-lg bg-surface text-on-surface font-body-md text-body-md appearance-none focus:outline-none focus:bg-surface-container-low transition-colors">
            <option value="">Semua Status</option><option value="menunggu">Menunggu</option><option value="diverifikasi">Diverifikasi</option><option value="diproses">Diproses</option><option value="selesai">Selesai</option><option value="ditolak">Ditolak</option>
          </select>
          <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-text-muted text-[18px] pointer-events-none">expand_more</span>
        </div>
        <div class="hidden xl:flex items-center gap-2">
          <button type="button" class="h-[42px] px-3.5 rounded-lg bg-surface-container-low hover:bg-surface-muted text-on-surface-variant font-label-sm text-label-sm flex items-center gap-1.5 transition-colors"><span class="material-symbols-outlined text-[16px]">calendar_today</span><span>Februari 2026</span></button>
        </div>
      </div>
      <div class="flex items-center justify-between sm:justify-end gap-3 text-text-secondary font-caption text-caption self-end sm:self-auto">
        <span>Menampilkan <strong class="text-on-surface font-label-sm">5</strong> dari <strong class="text-on-surface font-label-sm">12</strong> laporan</span>
      </div>
    </div>

    <!-- Desktop -->
    <div class="w-full overflow-x-auto hidden md:block">
      <table class="w-full text-left font-body-md text-body-md">
        <thead>
          <tr class="bg-surface-muted text-text-secondary font-label-sm text-label-sm tracking-wider uppercase">
            <th class="py-3.5 px-5" scope="col">Kode Laporan</th><th class="py-3.5 px-5" scope="col">Nama Fasilitas</th><th class="py-3.5 px-5" scope="col">Lokasi</th><th class="py-3.5 px-5" scope="col">Tanggal Laporan</th><th class="py-3.5 px-5" scope="col">Status</th><th class="py-3.5 px-5 text-right" scope="col">Aksi</th>
          </tr>
        </thead>
        <tbody class="text-on-surface">
          <tr class="hover:bg-canvas transition-colors">
            <td class="py-4 px-5 font-mono text-[13px] font-semibold text-on-surface">LPR-2026-0012</td>
            <td class="py-4 px-5"><span class="font-body-strong text-body-strong block text-on-surface">Air Conditioner (AC Daikin 2 PK)</span><span class="font-caption text-caption text-text-muted truncate block max-w-xs">Unit tidak mengeluarkan udara dingin &amp; terdengar dengung keras</span></td>
            <td class="py-4 px-5 text-text-secondary font-body-md">Gedung A, Lantai 2 – Ruang A203</td>
            <td class="py-4 px-5 text-text-secondary whitespace-nowrap">24 Feb 2026, 08:30 WIB</td>
            <td class="py-4 px-5 whitespace-nowrap"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-status-waiting-bg text-status-waiting-text font-label-sm text-label-sm"><span class="w-1.5 h-1.5 rounded-full bg-status-waiting-text"></span>Menunggu</span></td>
            <td class="py-4 px-5 text-right whitespace-nowrap"><a href="detail-laporan.php" class="inline-flex items-center gap-0.5 text-primary-container hover:text-primary font-label-md text-label-md transition-colors"><span>Lihat Detail</span><span class="material-symbols-outlined text-[16px]">chevron_right</span></a></td>
          </tr>
          <tr class="hover:bg-canvas transition-colors">
            <td class="py-4 px-5 font-mono text-[13px] font-semibold text-on-surface">LPR-2026-0009</td>
            <td class="py-4 px-5"><span class="font-body-strong text-body-strong block text-on-surface">Proyektor Epson EB-X400</span><span class="font-caption text-caption text-text-muted truncate block max-w-xs">Kabel HDMI longgar dan lampu indikator berkedip merah</span></td>
            <td class="py-4 px-5 text-text-secondary font-body-md">Gedung B, Lantai 1 – Ruang Seminar 102</td>
            <td class="py-4 px-5 text-text-secondary whitespace-nowrap">22 Feb 2026, 14:15 WIB</td>
            <td class="py-4 px-5 whitespace-nowrap"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-status-verified-bg text-status-verified-text font-label-sm text-label-sm"><span class="w-1.5 h-1.5 rounded-full bg-status-verified-text"></span>Diverifikasi</span></td>
            <td class="py-4 px-5 text-right whitespace-nowrap"><a href="detail-laporan.php" class="inline-flex items-center gap-0.5 text-primary-container hover:text-primary font-label-md text-label-md transition-colors"><span>Lihat Detail</span><span class="material-symbols-outlined text-[16px]">chevron_right</span></a></td>
          </tr>
          <tr class="hover:bg-canvas transition-colors">
            <td class="py-4 px-5 font-mono text-[13px] font-semibold text-on-surface">LPR-2026-0007</td>
            <td class="py-4 px-5"><span class="font-body-strong text-body-strong block text-on-surface">Lampu LED Koridor Gedung</span><span class="font-caption text-caption text-text-muted truncate block max-w-xs">3 lampu padam bergantian di sepanjang lorong koridor barat</span></td>
            <td class="py-4 px-5 text-text-secondary font-body-md">Gedung C, Lantai 3 – Koridor Barat</td>
            <td class="py-4 px-5 text-text-secondary whitespace-nowrap">20 Feb 2026, 09:40 WIB</td>
            <td class="py-4 px-5 whitespace-nowrap"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-status-processing-bg text-status-processing-text font-label-sm text-label-sm"><span class="w-1.5 h-1.5 rounded-full bg-status-processing-text"></span>Diproses</span></td>
            <td class="py-4 px-5 text-right whitespace-nowrap"><a href="detail-laporan.php" class="inline-flex items-center gap-0.5 text-primary-container hover:text-primary font-label-md text-label-md transition-colors"><span>Lihat Detail</span><span class="material-symbols-outlined text-[16px]">chevron_right</span></a></td>
          </tr>
          <tr class="hover:bg-canvas transition-colors">
            <td class="py-4 px-5 font-mono text-[13px] font-semibold text-on-surface">LPR-2026-0004</td>
            <td class="py-4 px-5"><span class="font-body-strong text-body-strong block text-on-surface">Kran Air Wastafel</span><span class="font-caption text-caption text-text-muted truncate block max-w-xs">Pipa bawah bocor, air menggenangi area lantai wastafel</span></td>
            <td class="py-4 px-5 text-text-secondary font-body-md">Gedung D, Lantai 1 – Toilet Pria</td>
            <td class="py-4 px-5 text-text-secondary whitespace-nowrap">18 Feb 2026, 11:20 WIB</td>
            <td class="py-4 px-5 whitespace-nowrap"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-status-completed-bg text-status-completed-text font-label-sm text-label-sm"><span class="w-1.5 h-1.5 rounded-full bg-status-completed-text"></span>Selesai</span></td>
            <td class="py-4 px-5 text-right whitespace-nowrap"><a href="detail-laporan.php" class="inline-flex items-center gap-0.5 text-primary-container hover:text-primary font-label-md text-label-md transition-colors"><span>Lihat Detail</span><span class="material-symbols-outlined text-[16px]">chevron_right</span></a></td>
          </tr>
          <tr class="hover:bg-canvas transition-colors">
            <td class="py-4 px-5 font-mono text-[13px] font-semibold text-on-surface">LPR-2026-0002</td>
            <td class="py-4 px-5"><span class="font-body-strong text-body-strong block text-on-surface">Stopkontak Meja Laboratorium</span><span class="font-caption text-caption text-status-error-text truncate block max-w-xs">Ditolak: Kerusakan fasilitas di luar tanggung jawab kampus / inventaris pribadi</span></td>
            <td class="py-4 px-5 text-text-secondary font-body-md">Gedung A, Lantai 4 – Lab Komputer 401</td>
            <td class="py-4 px-5 text-text-secondary whitespace-nowrap">15 Feb 2026, 16:05 WIB</td>
            <td class="py-4 px-5 whitespace-nowrap"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-status-error-bg text-status-error-text font-label-sm text-label-sm"><span class="w-1.5 h-1.5 rounded-full bg-status-error-text"></span>Ditolak</span></td>
            <td class="py-4 px-5 text-right whitespace-nowrap"><a href="detail-laporan.php" class="inline-flex items-center gap-0.5 text-primary-container hover:text-primary font-label-md text-label-md transition-colors"><span>Lihat Detail</span><span class="material-symbols-outlined text-[16px]">chevron_right</span></a></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Mobile -->
    <div class="flex flex-col gap-3 p-4 md:hidden">
      <div class="bg-surface-container-low rounded-lg p-4 flex flex-col gap-3">
        <div class="flex items-start justify-between gap-2">
          <span class="font-mono text-caption font-semibold text-on-surface bg-surface px-2 py-0.5 rounded">LPR-2026-0012</span>
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-status-waiting-bg text-status-waiting-text font-label-sm text-label-sm">Menunggu</span>
        </div>
        <div>
          <h3 class="font-body-strong text-body-strong text-on-surface">Air Conditioner (AC Daikin 2 PK)</h3>
          <p class="font-caption text-caption text-text-secondary mt-0.5 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">pin_drop</span>Gedung A, Lantai 2 – Ruang A203</p>
        </div>
        <div class="flex items-center justify-between pt-2">
          <span class="font-caption text-caption text-text-muted">24 Feb 2026, 08:30 WIB</span>
          <a href="detail-laporan.php" class="text-primary-container hover:text-primary font-label-md text-label-md flex items-center gap-0.5"><span>Detail</span><span class="material-symbols-outlined text-[16px]">chevron_right</span></a>
        </div>
      </div>
      <div class="bg-surface-container-low rounded-lg p-4 flex flex-col gap-3">
        <div class="flex items-start justify-between gap-2">
          <span class="font-mono text-caption font-semibold text-on-surface bg-surface px-2 py-0.5 rounded">LPR-2026-0009</span>
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-status-verified-bg text-status-verified-text font-label-sm text-label-sm">Diverifikasi</span>
        </div>
        <div>
          <h3 class="font-body-strong text-body-strong text-on-surface">Proyektor Epson EB-X400</h3>
          <p class="font-caption text-caption text-text-secondary mt-0.5 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">pin_drop</span>Gedung B, Lantai 1 – Ruang Seminar 102</p>
        </div>
        <div class="flex items-center justify-between pt-2">
          <span class="font-caption text-caption text-text-muted">22 Feb 2026, 14:15 WIB</span>
          <a href="detail-laporan.php" class="text-primary-container hover:text-primary font-label-md text-label-md flex items-center gap-0.5"><span>Detail</span><span class="material-symbols-outlined text-[16px]">chevron_right</span></a>
        </div>
      </div>
      <div class="bg-surface-container-low rounded-lg p-4 flex flex-col gap-3">
        <div class="flex items-start justify-between gap-2">
          <span class="font-mono text-caption font-semibold text-on-surface bg-surface px-2 py-0.5 rounded">LPR-2026-0007</span>
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-status-processing-bg text-status-processing-text font-label-sm text-label-sm">Diproses</span>
        </div>
        <div>
          <h3 class="font-body-strong text-body-strong text-on-surface">Lampu LED Koridor Gedung</h3>
          <p class="font-caption text-caption text-text-secondary mt-0.5 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">pin_drop</span>Gedung C, Lantai 3 – Koridor Barat</p>
        </div>
        <div class="flex items-center justify-between pt-2">
          <span class="font-caption text-caption text-text-muted">20 Feb 2026, 09:40 WIB</span>
          <a href="detail-laporan.php" class="text-primary-container hover:text-primary font-label-md text-label-md flex items-center gap-0.5"><span>Detail</span><span class="material-symbols-outlined text-[16px]">chevron_right</span></a>
        </div>
      </div>
      <div class="bg-surface-container-low rounded-lg p-4 flex flex-col gap-3">
        <div class="flex items-start justify-between gap-2">
          <span class="font-mono text-caption font-semibold text-on-surface bg-surface px-2 py-0.5 rounded">LPR-2026-0004</span>
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-status-completed-bg text-status-completed-text font-label-sm text-label-sm">Selesai</span>
        </div>
        <div>
          <h3 class="font-body-strong text-body-strong text-on-surface">Kran Air Wastafel</h3>
          <p class="font-caption text-caption text-text-secondary mt-0.5 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">pin_drop</span>Gedung D, Lantai 1 – Toilet Pria</p>
        </div>
        <div class="flex items-center justify-between pt-2">
          <span class="font-caption text-caption text-text-muted">18 Feb 2026, 11:20 WIB</span>
          <a href="detail-laporan.php" class="text-primary-container hover:text-primary font-label-md text-label-md flex items-center gap-0.5"><span>Detail</span><span class="material-symbols-outlined text-[16px]">chevron_right</span></a>
        </div>
      </div>
      <div class="bg-surface-container-low rounded-lg p-4 flex flex-col gap-3">
        <div class="flex items-start justify-between gap-2">
          <span class="font-mono text-caption font-semibold text-on-surface bg-surface px-2 py-0.5 rounded">LPR-2026-0002</span>
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-status-error-bg text-status-error-text font-label-sm text-label-sm">Ditolak</span>
        </div>
        <div>
          <h3 class="font-body-strong text-body-strong text-on-surface">Stopkontak Meja Laboratorium</h3>
          <p class="font-caption text-caption text-text-secondary mt-0.5 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">pin_drop</span>Gedung A, Lantai 4 – Lab Komputer 401</p>
        </div>
        <div class="flex items-center justify-between pt-2">
          <span class="font-caption text-caption text-text-muted">15 Feb 2026, 16:05 WIB</span>
          <a href="detail-laporan.php" class="text-primary-container hover:text-primary font-label-md text-label-md flex items-center gap-0.5"><span>Detail</span><span class="material-symbols-outlined text-[16px]">chevron_right</span></a>
        </div>
      </div>
    </div>

    <!-- Paginasi -->
    <div class="px-5 py-3.5 bg-surface flex flex-col sm:flex-row items-center justify-between gap-3">
      <span class="font-caption text-caption text-text-secondary">Menampilkan <span class="font-semibold text-on-surface">1-5</span> dari <span class="font-semibold text-on-surface">12</span> data laporan</span>
      <div class="flex items-center gap-1 select-none">
        <button type="button" disabled class="h-8 px-2.5 rounded bg-surface-muted text-text-muted font-label-sm text-label-sm flex items-center gap-1 cursor-not-allowed opacity-60"><span class="material-symbols-outlined text-[16px]">chevron_left</span><span class="hidden sm:inline">Sebelumnya</span></button>
        <button type="button" class="w-8 h-8 rounded bg-primary-container text-on-primary font-label-sm text-label-sm flex items-center justify-center">1</button>
        <button type="button" class="w-8 h-8 rounded bg-surface hover:bg-surface-muted text-on-surface font-label-sm text-label-sm flex items-center justify-center transition-colors">2</button>
        <button type="button" class="w-8 h-8 rounded bg-surface hover:bg-surface-muted text-on-surface font-label-sm text-label-sm flex items-center justify-center transition-colors">3</button>
        <button type="button" class="h-8 px-2.5 rounded bg-surface hover:bg-surface-muted text-on-surface font-label-sm text-label-sm flex items-center gap-1 transition-colors"><span class="hidden sm:inline">Selanjutnya</span><span class="material-symbols-outlined text-[16px]">chevron_right</span></button>
      </div>
    </div>
  </div>

  <!-- Pratinjau empty state -->
  <div class="mt-4 bg-surface rounded-xl p-6 shadow-sm flex flex-col gap-4">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2"><span class="material-symbols-outlined text-text-muted text-[18px]">info</span><span class="font-label-md text-label-md text-text-secondary">Pratinjau Keadaan Kosong (Empty State Preview)</span></div>
      <button id="toggle-empty-state" type="button" class="text-caption text-text-muted hover:text-on-surface transition-colors">Beralih Tampilan</button>
    </div>
    <div id="empty-state-box" class="bg-surface-container-low rounded-lg py-12 px-6 flex flex-col items-center justify-center text-center max-w-xl mx-auto w-full">
      <div class="w-14 h-14 rounded-full bg-surface-muted flex items-center justify-center text-text-muted mb-4"><span class="material-symbols-outlined text-[28px]">assignment_late</span></div>
      <h2 class="font-headline-sm text-headline-sm text-on-surface mb-1">Belum ada laporan.</h2>
      <p class="font-body-md text-body-md text-text-secondary mb-5 max-w-md">Mulai buat laporan kerusakan fasilitas kampus agar dapat segera ditangani oleh petugas teknis.</p>
      <a href="buat-laporan.php" class="h-[42px] px-5 rounded-lg bg-primary-container hover:bg-primary text-on-primary font-body-strong text-body-strong flex items-center justify-center gap-2 transition-colors"><span class="material-symbols-outlined text-[18px]">add</span><span>Buat Laporan</span></a>
    </div>
  </div>
</div>
</main>
</div>
<?= Auth::userScript() ?>
<script src="../../assets/js/components/layout.js?v=2"></script><script src="../../assets/js/pages/mahasiswa/riwayat-laporan.js"></script></body></html>
