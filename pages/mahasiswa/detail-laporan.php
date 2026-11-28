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
<script src="../../assets/js/config/tailwind-config.js"></script><title>Detail Laporan - SIPERUSAK</title></head>
<body class="bg-canvas text-on-surface font-body-md text-body-md antialiased" data-role="mahasiswa" data-nav="riwayat-laporan" data-crumbs="Dashboard|Riwayat Laporan|Detail Laporan">
<aside id="sidebar" class="fixed left-0 top-0 h-screen w-[230px] bg-surface border-r border-border z-50 hidden md:flex flex-col justify-between select-none"></aside>
<div class="pl-0 md:pl-[230px]">
<header id="topbar" class="fixed top-0 left-0 md:left-[230px] right-0 h-16 bg-surface border-b border-border z-40 px-4 md:px-space-lg flex items-center justify-between"></header>
<main class="w-full pt-16 min-h-screen bg-canvas">
<div class="px-4 py-6 md:px-space-lg md:py-space-xl max-w-7xl mx-auto w-full">

  <div class="flex items-center justify-between mb-space-md">
    <a href="riwayat-laporan.php" class="inline-flex items-center gap-space-xs text-text-secondary hover:text-primary font-body-strong text-body-strong transition-colors group">
      <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span><span>Kembali ke Riwayat</span>
    </a>
    <div class="flex items-center gap-space-xs text-text-muted font-caption text-caption">
      <span class="inline-block w-2 h-2 rounded-full bg-status-processing-text animate-pulse"></span><span>Pembaruan otomatis aktif</span>
    </div>
  </div>

  <!-- Header -->
  <div class="bg-surface rounded-xl p-space-md md:p-space-lg shadow-sm mb-space-lg flex flex-col md:flex-row md:items-center justify-between gap-space-md">
    <div class="flex flex-col gap-1">
      <div class="flex flex-wrap items-center gap-space-sm">
        <h1 class="font-headline-lg text-headline-lg text-on-surface">Detail Laporan</h1>
        <span class="px-2.5 py-0.5 rounded-full font-label-sm text-label-sm bg-surface-muted text-on-surface-variant">LPR-2026-0009</span>
        <span class="px-2.5 py-0.5 rounded-full font-label-sm text-label-sm bg-status-processing-bg text-status-processing-text">Diproses</span>
      </div>
      <p class="font-body-md text-body-md text-text-secondary">Informasi lengkap perkembangan penanganan kerusakan fasilitas kampus.</p>
    </div>
    <button type="button" onclick="window.print()" class="self-start md:self-auto px-3 py-2 rounded-lg bg-surface-muted text-on-surface-variant hover:bg-surface-container hover:text-on-surface font-label-md text-label-md flex items-center gap-1.5 transition-colors">
      <span class="material-symbols-outlined text-[18px]">print</span><span>Cetak Bukti</span>
    </button>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">

    <!-- Kolom kiri -->
    <div class="lg:col-span-7 flex flex-col gap-space-lg">

      <section class="bg-surface rounded-xl p-space-md md:p-space-lg shadow-sm">
        <div class="flex items-center justify-between mb-space-sm">
          <span class="font-label-sm text-label-sm text-text-muted uppercase tracking-wider">Status Penanganan Terkini</span>
          <span class="font-caption text-caption text-text-muted">Diperbarui: 24 Feb 2026, 10:00 WIB</span>
        </div>
        <div class="flex items-start gap-3.5 mb-space-md">
          <div class="w-10 h-10 rounded-lg bg-status-processing-bg text-status-processing-text flex items-center justify-center flex-shrink-0 mt-0.5"><span class="material-symbols-outlined text-[22px]">engineering</span></div>
          <div>
            <span class="font-headline-sm text-headline-sm text-status-processing-text">Diproses Lapangan</span>
            <p class="font-body-md text-body-md text-text-secondary mt-0.5">Laporan sedang dalam proses penanganan oleh petugas sarana dan prasarana.</p>
          </div>
        </div>
        <div class="bg-status-processing-bg/50 rounded-lg p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-on-surface">
          <div class="flex items-center gap-2.5 min-w-0">
            <div class="w-8 h-8 rounded-full bg-status-processing-bg text-status-processing-text flex items-center justify-center flex-shrink-0"><span class="material-symbols-outlined text-[18px]">badge</span></div>
            <div class="flex flex-col min-w-0">
              <span class="font-caption text-caption text-text-muted">Petugas Penanggung Jawab</span>
              <span class="font-body-strong text-body-strong truncate text-on-surface">Bambang Sudarsono, S.T. <span class="text-text-secondary font-body-md text-caption">(Unit Sarpras Gedung B)</span></span>
            </div>
          </div>
          <div class="flex items-center gap-2.5 sm:border-l sm:border-status-processing-border sm:pl-4 flex-shrink-0">
            <div class="w-8 h-8 rounded-full bg-status-processing-bg text-status-processing-text flex items-center justify-center flex-shrink-0"><span class="material-symbols-outlined text-[18px]">event_upcoming</span></div>
            <div class="flex flex-col"><span class="font-caption text-caption text-text-muted">Estimasi Selesai</span><span class="font-body-strong text-body-strong text-on-surface">26 Feb 2026</span></div>
          </div>
        </div>
      </section>

      <section class="bg-surface rounded-xl p-space-md md:p-space-lg shadow-sm">
        <div class="flex items-center justify-between pb-space-sm mb-space-md bg-surface-muted/30 -mx-space-md -mt-space-md p-space-md rounded-t-xl">
          <div class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[20px]">inventory_2</span><h2 class="font-headline-sm text-headline-sm text-on-surface">Informasi Fasilitas &amp; Pelaporan</h2></div>
          <span class="font-caption text-caption text-text-muted">Data Registrasi Aset</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6">
          <div class="flex flex-col"><span class="font-caption text-caption text-text-muted">Kode Laporan</span><span class="font-body-strong text-body-strong text-on-surface font-mono mt-0.5">LPR-2026-0009</span></div>
          <div class="flex flex-col"><span class="font-caption text-caption text-text-muted">Tanggal Pelaporan</span><span class="font-body-strong text-body-strong text-on-surface mt-0.5">22 Feb 2026, 14:15 WIB</span></div>
          <div class="flex flex-col"><span class="font-caption text-caption text-text-muted">Nama Fasilitas</span><span class="font-body-strong text-body-strong text-on-surface mt-0.5">Proyektor Epson EB-X400</span></div>
          <div class="flex flex-col"><span class="font-caption text-caption text-text-muted">Kode Barang</span><span class="font-body-strong text-body-strong text-on-surface mt-0.5">PRO-001 <span class="text-text-secondary text-caption font-normal">(Plafon Tengah)</span></span></div>
          <div class="flex flex-col"><span class="font-caption text-caption text-text-muted">Jenis Fasilitas</span>
            <div class="inline-flex items-center gap-1.5 mt-0.5"><span class="material-symbols-outlined text-text-secondary text-[16px]">devices</span><span class="font-body-strong text-body-strong text-on-surface">Elektronik &amp; Multimedia</span></div></div>
          <div class="flex flex-col"><span class="font-caption text-caption text-text-muted">Tingkat Urgensi</span>
            <div class="inline-flex items-center gap-1.5 mt-0.5"><span class="px-2 py-0.5 rounded font-label-sm text-label-sm bg-status-waiting-bg text-status-waiting-text">Sedang</span><span class="font-caption text-caption text-text-secondary truncate">Mengganggu perkuliahan</span></div></div>
          <div class="sm:col-span-2 flex flex-col pt-2 bg-surface-muted/20 p-3 rounded-lg">
            <span class="font-caption text-caption text-text-muted">Lokasi Fasilitas</span>
            <div class="flex items-center gap-2 mt-1"><span class="material-symbols-outlined text-primary text-[18px]">location_on</span><span class="font-body-strong text-body-strong text-on-surface">Gedung B, Lantai 1 – Ruang Seminar 102</span></div>
          </div>
        </div>
      </section>

      <section class="bg-surface rounded-xl p-space-md md:p-space-lg shadow-sm">
        <div class="flex items-center gap-2 mb-space-md"><span class="material-symbols-outlined text-primary text-[20px]">description</span><h2 class="font-headline-sm text-headline-sm text-on-surface">Detail Kerusakan &amp; Bukti Foto</h2></div>
        <div class="mb-space-lg">
          <span class="font-label-sm text-label-sm text-text-muted block mb-1.5 uppercase tracking-wide">Deskripsi Kerusakan</span>
          <div class="p-3.5 rounded-lg bg-surface-muted/40 font-body-md text-body-md text-on-surface leading-relaxed">"Kabel konektor HDMI kendur dan lampu indikator proyektor berkedip merah saat dinyalakan. Tampilan layar proyektor tidak menampilkan sinyal tampilan laptop dosen saat perkuliahan berlangsung."</div>
        </div>
        <div>
          <div class="flex items-center justify-between mb-space-sm">
            <span class="font-label-sm text-label-sm text-text-muted uppercase tracking-wide">Lampiran Bukti (2 Foto)</span>
            <span class="font-caption text-caption text-text-muted">Format: JPG, Resolusi Tinggi</span>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
            <div class="group relative rounded-xl overflow-hidden bg-surface-container shadow-sm flex flex-col cursor-pointer transition-transform duration-200 hover:-translate-y-0.5">
              <div class="relative w-full h-48 bg-surface-muted overflow-hidden">
                <img alt="Unit proyektor di plafon" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAf4KXsYaO84d-4-1DoMnOJmA_BsIQRcc5C8AwTsLqtFVd1ZjXtlNGVuznkHfVcbKiqXnR0zpU45D0-SOiS0mKhONQGoZ88lXe8Hcgdl3TGd9iCuhT_Uo4Mzku3oYFVc2G8ZKOvGbJ_r6E_r7iqkahXTxDWs-PY0uir7fByPlx_rZV-05Y2QhemgXcNeb-8OuyisnIGjBmpZgpi0SSaEGfEn3GxpeDYKdFl4o52Oks"/>
                <div class="absolute inset-0 bg-on-surface/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"><span class="w-9 h-9 rounded-full bg-surface/90 text-on-surface flex items-center justify-center shadow-sm"><span class="material-symbols-outlined text-[18px]">zoom_in</span></span></div>
              </div>
              <div class="p-3 flex items-start justify-between gap-2 bg-surface">
                <div class="flex flex-col min-w-0"><span class="font-body-strong text-body-strong text-on-surface truncate">Unit proyektor di plafon &amp; kabel</span><span class="font-caption text-caption text-text-muted">2.1 MB • Foto Lampiran #1</span></div>
                <span class="material-symbols-outlined text-text-muted text-[18px]">photo_camera</span>
              </div>
            </div>
            <div class="group relative rounded-xl overflow-hidden bg-surface-container shadow-sm flex flex-col cursor-pointer transition-transform duration-200 hover:-translate-y-0.5">
              <div class="relative w-full h-48 bg-surface-muted overflow-hidden">
                <img alt="Tampilan proyektor no signal di ruang seminar" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDtnvrRWCG8PUBGhI-jp48HtKOWDYjTlUs_NKP-ba6_hYAMuLf8-GaCRCuQbvwKFmg88LGaRIwWNe3p1Ex0o0CTxzau2R0kJquGyd0v0INIn8QHpDsuWoqzgR_wsAecrbiHBWarluMByipYmTk6-6jfC2nGYShSAZtoHfwny_rA3acOOAiHsHKpMo1o5EsFUL0IUlAkyXmc4FZOaklxA4y0t6e4OiQtXOZC00jGpCc"/>
                <div class="absolute inset-0 bg-on-surface/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"><span class="w-9 h-9 rounded-full bg-surface/90 text-on-surface flex items-center justify-center shadow-sm"><span class="material-symbols-outlined text-[18px]">zoom_in</span></span></div>
              </div>
              <div class="p-3 flex items-start justify-between gap-2 bg-surface">
                <div class="flex flex-col min-w-0"><span class="font-body-strong text-body-strong text-on-surface truncate">Tampilan layar proyektor no signal</span><span class="font-caption text-caption text-text-muted">1.8 MB • Foto Lampiran #2</span></div>
                <span class="material-symbols-outlined text-text-muted text-[18px]">photo_camera</span>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- Kolom kanan -->
    <div class="lg:col-span-5 flex flex-col gap-space-lg">

      <section class="bg-surface rounded-xl p-space-md md:p-space-lg shadow-sm">
        <div class="flex items-center justify-between mb-space-md"><h2 class="font-headline-sm text-headline-sm text-on-surface">Riwayat Penanganan</h2><span class="font-caption text-caption text-text-muted">Kronologis</span></div>
        <div class="relative pl-6 space-y-6 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-[2px] before:bg-grid-line">
          <div class="relative">
            <span class="absolute -left-[30px] top-0.5 w-[22px] h-[22px] rounded-full bg-status-completed-bg text-status-completed-text flex items-center justify-center ring-4 ring-surface"><span class="material-symbols-outlined text-[14px] font-bold">check</span></span>
            <div class="flex flex-col">
              <div class="flex items-baseline justify-between gap-2"><span class="font-label-md text-label-md text-on-surface">Laporan Dikirimkan</span><span class="font-caption text-caption text-text-muted">22 Feb, 14:15</span></div>
              <p class="font-body-md text-caption text-text-secondary mt-0.5">Laporan berhasil dikirim oleh mahasiswa (Ahmad Fauzi) melalui portal SIPERUSAK.</p>
            </div>
          </div>
          <div class="relative">
            <span class="absolute -left-[30px] top-0.5 w-[22px] h-[22px] rounded-full bg-status-completed-bg text-status-completed-text flex items-center justify-center ring-4 ring-surface"><span class="material-symbols-outlined text-[14px] font-bold">check</span></span>
            <div class="flex flex-col">
              <div class="flex items-baseline justify-between gap-2"><span class="font-label-md text-label-md text-on-surface">Diverifikasi Admin</span><span class="font-caption text-caption text-text-muted">23 Feb, 09:30</span></div>
              <span class="font-caption text-caption text-primary font-medium">Bagian Sarana &amp; Prasarana</span>
              <p class="font-body-md text-caption text-text-secondary mt-1">Laporan telah diperiksa dan diverifikasi valid. Kerusakan diklasifikasikan pada perangkat optik/kabel Gedung B.</p>
            </div>
          </div>
          <div class="relative">
            <span class="absolute -left-[30px] top-0.5 w-[22px] h-[22px] rounded-full bg-status-processing-text text-on-primary flex items-center justify-center ring-4 ring-status-processing-bg shadow-sm"><span class="material-symbols-outlined text-[14px]">build</span></span>
            <div class="flex flex-col bg-status-processing-bg/40 p-3 rounded-lg">
              <div class="flex items-baseline justify-between gap-2"><span class="font-label-md text-label-md text-status-processing-text">Dalam Proses Pengerjaan</span><span class="font-caption text-caption text-status-processing-text font-medium">24 Feb, 10:00</span></div>
              <span class="font-caption text-caption text-on-surface font-semibold mt-0.5">Teknisi AV: Bambang Sudarsono</span>
              <p class="font-body-md text-caption text-text-secondary mt-1">Teknisi sedang melakukan pengecekan kabel modul port HDMI dan penggantian lampu proyektor cadangan.</p>
            </div>
          </div>
          <div class="relative opacity-60">
            <span class="absolute -left-[30px] top-0.5 w-[22px] h-[22px] rounded-full bg-surface-muted text-text-muted flex items-center justify-center ring-4 ring-surface"><span class="material-symbols-outlined text-[14px]">hourglass_empty</span></span>
            <div class="flex flex-col">
              <div class="flex items-baseline justify-between gap-2"><span class="font-label-md text-label-md text-text-muted">Selesai &amp; Serah Terima</span><span class="font-caption text-caption text-text-muted">Menunggu</span></div>
              <p class="font-body-md text-caption text-text-muted mt-0.5">Pengujian fungsionalitas visual dan penutupan tiket laporan setelah konfirmasi pengajar.</p>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-surface rounded-xl p-space-md md:p-space-lg shadow-sm">
        <div class="flex items-center gap-2 mb-space-sm"><span class="material-symbols-outlined text-primary text-[20px]">sticky_note_2</span><h2 class="font-headline-sm text-headline-sm text-on-surface">Catatan Petugas Terkini</h2></div>
        <div class="bg-surface-muted/50 rounded-lg p-3.5 flex flex-col gap-2">
          <p class="font-body-md text-body-md text-on-surface italic">"Penggantian kabel HDMI telah diajukan ke logistik sarpras. Pengecekan unit langsung dijadwalkan selesai hari ini sebelum sesi seminar sore."</p>
          <div class="pt-2 flex items-center justify-between text-text-muted font-caption text-caption"><span class="font-medium text-text-secondary">Bambang Sudarsono, S.T.</span><span>24 Feb 2026, 11:20 WIB</span></div>
        </div>
      </section>

      <section class="bg-surface rounded-xl p-space-md md:p-space-lg shadow-sm">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 mt-0.5"><span class="material-symbols-outlined text-[18px]">support_agent</span></div>
          <div class="flex flex-col gap-1">
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Pusat Bantuan Ruang Kuliah</h3>
            <p class="font-body-md text-caption text-text-secondary leading-relaxed">Apabila kondisi kerusakan bertambah parah atau membutuhkan penanganan darurat ruang kelas saat jadwal perkuliahan, hubungi Posko Sarpras.</p>
            <div class="mt-2 inline-flex items-center gap-2 bg-surface-muted px-3 py-1.5 rounded text-on-surface font-mono text-caption w-fit"><span class="material-symbols-outlined text-[16px] text-primary">call</span><span>Posko Sarpras: Ext. 102 / (031) 829-1002</span></div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
</main></div>
<?= Auth::userScript() ?>
<script src="../../assets/js/components/layout.js?v=2"></script></body></html>
