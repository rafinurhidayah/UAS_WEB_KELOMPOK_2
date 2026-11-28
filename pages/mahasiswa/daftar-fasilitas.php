<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/core/Auth.php';

$currentUser = Auth::guard([ROLE_MAHASISWA]);
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../../assets/css/style.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../../assets/js/config/tailwind-config.js"></script>
    <title>Daftar Fasilitas - SIPERUSAK</title>
  </head>
  <body
    class="bg-canvas text-on-surface font-body-md text-body-md antialiased"
    data-role="mahasiswa"
    data-nav="daftar-fasilitas"
    data-crumbs="Portal Mahasiswa|Daftar Fasilitas"
  >
    <aside
      id="sidebar"
      class="fixed left-0 top-0 h-screen w-[230px] bg-surface border-r border-border z-50 hidden md:flex flex-col justify-between select-none"
    ></aside>
    <div class="pl-0 md:pl-[230px]">
      <header
        id="topbar"
        class="fixed top-0 left-0 md:left-[230px] right-0 h-16 bg-surface border-b border-border z-40 px-4 md:px-space-lg flex items-center justify-between"
      ></header>
      <main class="w-full pt-16 min-h-screen bg-canvas">
        <div
          class="flex flex-col w-full p-4 md:p-space-lg max-w-7xl mx-auto space-y-space-lg"
        >
          <div
            class="flex flex-col sm:flex-row sm:items-end justify-between gap-space-md pb-space-xs"
          >
            <div>
              <h1
                class="font-display text-display text-on-surface tracking-tight"
              >
                Daftar Fasilitas
              </h1>
              <p class="font-body-md text-body-md text-text-secondary mt-1">
                Temukan fasilitas kampus yang telah terdaftar dalam sistem.
              </p>
            </div>
            <div
              class="inline-flex items-center gap-space-xs px-3 py-1.5 rounded-lg bg-surface-muted border border-border text-text-secondary self-start sm:self-auto"
            >
              <span
                class="material-symbols-outlined text-primary-container text-[18px]"
                >support_agent</span
              >
              <span class="font-caption text-caption"
                >Butuh bantuan mendesak? Hubungi Posko Sarpras
                <strong class="text-on-surface font-semibold"
                  >Ext. 102</strong
                ></span
              >
            </div>
          </div>

          <!-- Pencarian & filter -->
          <div
            class="bg-surface rounded-xl border border-border p-space-md shadow-sm"
          >
            <div
              class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-md"
            >
              <div
                class="flex flex-col sm:flex-row items-stretch sm:items-center gap-space-sm flex-1"
              >
                <div class="relative flex-1 min-w-[260px]">
                  <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-text-muted"
                  >
                    <span class="material-symbols-outlined text-[19px]"
                      >search</span
                    >
                  </div>
                  <input
                    id="searchInput"
                    type="text"
                    placeholder="Cari nama fasilitas atau kode barang..."
                    class="w-full h-[42px] pl-10 pr-3 bg-surface rounded-lg border border-border text-on-surface font-body-md text-body-md placeholder:text-text-muted focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/15 transition-all"
                  />
                </div>
                <div class="relative w-full sm:w-48">
                  <select
                    id="categoryFilter"
                    class="w-full h-[42px] pl-3 pr-8 bg-surface rounded-lg border border-border text-on-surface font-body-md text-body-md appearance-none focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/15 transition-all cursor-pointer"
                  >
                    <option value="">Semua Kategori</option>
                    <option value="Elektronik">Elektronik</option>
                    <option value="Perabot">Perabot</option>
                    <option value="Sanitasi">Sanitasi</option>
                    <option value="Jaringan">Jaringan</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                  <div
                    class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-text-muted"
                  >
                    <span class="material-symbols-outlined text-[18px]"
                      >expand_more</span
                    >
                  </div>
                </div>
                <div class="relative w-full sm:w-56">
                  <select
                    id="locationFilter"
                    class="w-full h-[42px] pl-3 pr-8 bg-surface rounded-lg border border-border text-on-surface font-body-md text-body-md appearance-none focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/15 transition-all cursor-pointer"
                  >
                    <option value="">Semua Lokasi</option>
                    <option value="Gedung A">Gedung A</option>
                    <option value="Gedung B">Gedung B</option>
                    <option value="Gedung C">Gedung C</option>
                    <option value="Gedung D">Gedung D</option>
                    <option value="Laboratorium Komputer">
                      Laboratorium Komputer
                    </option>
                    <option value="Perpustakaan">Perpustakaan</option>
                  </select>
                  <div
                    class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-text-muted"
                  >
                    <span class="material-symbols-outlined text-[18px]"
                      >expand_more</span
                    >
                  </div>
                </div>
              </div>
              <div
                class="text-right flex items-center justify-between sm:justify-end gap-2 border-t sm:border-t-0 pt-2 sm:pt-0 border-border"
              >
                <span class="font-caption text-caption text-text-muted"
                  >Status Tampilan:</span
                >
                <span
                  class="font-label-sm text-label-sm text-on-surface bg-surface-muted px-2.5 py-1 rounded border border-border"
                  >Menampilkan
                  <span class="text-primary font-semibold">6</span> dari 48
                  fasilitas</span
                >
              </div>
            </div>
          </div>

          <!-- Tabel -->
          <div
            class="bg-surface rounded-xl border border-border shadow-sm overflow-hidden flex flex-col"
          >
            <div class="overflow-x-auto w-full">
              <table class="w-full border-collapse text-left">
                <thead>
                  <tr class="bg-surface-muted border-b border-border">
                    <th
                      class="py-3 px-4 font-label-sm text-label-sm uppercase tracking-wider text-text-secondary whitespace-nowrap"
                      scope="col"
                    >
                      Kode Barang
                    </th>
                    <th
                      class="py-3 px-4 font-label-sm text-label-sm uppercase tracking-wider text-text-secondary min-w-[220px]"
                      scope="col"
                    >
                      Nama Fasilitas
                    </th>
                    <th
                      class="py-3 px-4 font-label-sm text-label-sm uppercase tracking-wider text-text-secondary whitespace-nowrap"
                      scope="col"
                    >
                      Jenis Fasilitas
                    </th>
                    <th
                      class="py-3 px-4 font-label-sm text-label-sm uppercase tracking-wider text-text-secondary min-w-[240px]"
                      scope="col"
                    >
                      Lokasi
                    </th>
                    <th
                      class="py-3 px-4 font-label-sm text-label-sm uppercase tracking-wider text-text-secondary whitespace-nowrap"
                      scope="col"
                    >
                      Kondisi
                    </th>
                    <th
                      class="py-3 px-4 font-label-sm text-label-sm uppercase tracking-wider text-text-secondary text-right whitespace-nowrap"
                      scope="col"
                    >
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-border">
                  <tr class="hover:bg-canvas transition-colors">
                    <td
                      class="py-3.5 px-4 font-mono font-semibold text-caption text-on-surface whitespace-nowrap"
                    >
                      AC-001
                    </td>
                    <td class="py-3.5 px-4">
                      <div class="flex flex-col">
                        <span
                          class="font-body-strong text-body-strong text-on-surface"
                          >AC Split Daikin 2 PK</span
                        ><span class="font-caption text-caption text-text-muted"
                          >Unit Dinding Depan Ruangan</span
                        >
                      </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-caption font-medium bg-surface-muted text-text-secondary border border-border"
                        >Elektronik</span
                      >
                    </td>
                    <td class="py-3.5 px-4">
                      <div
                        class="flex items-center gap-1.5 text-text-secondary"
                      >
                        <span
                          class="material-symbols-outlined text-[16px] text-text-muted flex-shrink-0"
                          >location_on</span
                        ><span class="font-body-md text-body-md"
                          >Gedung A, Lantai 1 – Ruang 101</span
                        >
                      </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-caption font-semibold bg-status-completed-bg text-status-completed-text border border-status-completed-border"
                        ><span
                          class="w-1.5 h-1.5 rounded-full bg-status-completed-text"
                        ></span
                        >Baik</span
                      >
                    </td>
                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                      <a
                        href="buat-laporan.php?kode=AC-001"
                        title="Laporkan kerusakan AC-001"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border text-primary-container hover:bg-[#F1EAE3] hover:border-primary-container/40 transition-colors font-label-sm text-label-sm"
                        ><span class="material-symbols-outlined text-[16px]"
                          >report_problem</span
                        ><span>Laporkan Kerusakan</span></a
                      >
                    </td>
                  </tr>
                  <tr class="hover:bg-canvas transition-colors">
                    <td
                      class="py-3.5 px-4 font-mono font-semibold text-caption text-on-surface whitespace-nowrap"
                    >
                      PRO-001
                    </td>
                    <td class="py-3.5 px-4">
                      <div class="flex flex-col">
                        <span
                          class="font-body-strong text-body-strong text-on-surface"
                          >Proyektor Epson EB-X400</span
                        ><span class="font-caption text-caption text-text-muted"
                          >Plafon Tengah Bracket Gantung</span
                        >
                      </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-caption font-medium bg-surface-muted text-text-secondary border border-border"
                        >Elektronik</span
                      >
                    </td>
                    <td class="py-3.5 px-4">
                      <div
                        class="flex items-center gap-1.5 text-text-secondary"
                      >
                        <span
                          class="material-symbols-outlined text-[16px] text-text-muted flex-shrink-0"
                          >location_on</span
                        ><span class="font-body-md text-body-md"
                          >Gedung B, Lantai 1 – Ruang Seminar 102</span
                        >
                      </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-caption font-semibold bg-status-waiting-bg text-status-waiting-text border border-status-waiting-border"
                        ><span
                          class="w-1.5 h-1.5 rounded-full bg-status-waiting-text"
                        ></span
                        >Rusak Ringan</span
                      >
                    </td>
                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                      <a
                        href="buat-laporan.php?kode=PRO-001"
                        title="Laporkan kerusakan PRO-001"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border text-primary-container hover:bg-[#F1EAE3] hover:border-primary-container/40 transition-colors font-label-sm text-label-sm"
                        ><span class="material-symbols-outlined text-[16px]"
                          >report_problem</span
                        ><span>Laporkan Kerusakan</span></a
                      >
                    </td>
                  </tr>
                  <tr class="hover:bg-canvas transition-colors">
                    <td
                      class="py-3.5 px-4 font-mono font-semibold text-caption text-on-surface whitespace-nowrap"
                    >
                      KOM-001
                    </td>
                    <td class="py-3.5 px-4">
                      <div class="flex flex-col">
                        <span
                          class="font-body-strong text-body-strong text-on-surface"
                          >PC Desktop Core i7</span
                        ><span class="font-caption text-caption text-text-muted"
                          >PC-Client 08 (RAM 16GB / SSD 512GB)</span
                        >
                      </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-caption font-medium bg-surface-muted text-text-secondary border border-border"
                        >Elektronik</span
                      >
                    </td>
                    <td class="py-3.5 px-4">
                      <div
                        class="flex items-center gap-1.5 text-text-secondary"
                      >
                        <span
                          class="material-symbols-outlined text-[16px] text-text-muted flex-shrink-0"
                          >location_on</span
                        ><span class="font-body-md text-body-md"
                          >Gedung A, Lantai 4 – Lab Komputer 401</span
                        >
                      </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-caption font-semibold bg-status-completed-bg text-status-completed-text border border-status-completed-border"
                        ><span
                          class="w-1.5 h-1.5 rounded-full bg-status-completed-text"
                        ></span
                        >Baik</span
                      >
                    </td>
                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                      <a
                        href="buat-laporan.php?kode=KOM-001"
                        title="Laporkan kerusakan KOM-001"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border text-primary-container hover:bg-[#F1EAE3] hover:border-primary-container/40 transition-colors font-label-sm text-label-sm"
                        ><span class="material-symbols-outlined text-[16px]"
                          >report_problem</span
                        ><span>Laporkan Kerusakan</span></a
                      >
                    </td>
                  </tr>
                  <tr class="hover:bg-canvas transition-colors">
                    <td
                      class="py-3.5 px-4 font-mono font-semibold text-caption text-on-surface whitespace-nowrap"
                    >
                      FUR-001
                    </td>
                    <td class="py-3.5 px-4">
                      <div class="flex flex-col">
                        <span
                          class="font-body-strong text-body-strong text-on-surface"
                          >Meja Kuliah Kursi Gandeng Kayu</span
                        ><span class="font-caption text-caption text-text-muted"
                          >Model Rangka Besi Dudukan Kayu Jati</span
                        >
                      </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-caption font-medium bg-surface-muted text-text-secondary border border-border"
                        >Perabot</span
                      >
                    </td>
                    <td class="py-3.5 px-4">
                      <div
                        class="flex items-center gap-1.5 text-text-secondary"
                      >
                        <span
                          class="material-symbols-outlined text-[16px] text-text-muted flex-shrink-0"
                          >location_on</span
                        ><span class="font-body-md text-body-md"
                          >Gedung A, Lantai 2 – Ruang 201</span
                        >
                      </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-caption font-semibold bg-status-completed-bg text-status-completed-text border border-status-completed-border"
                        ><span
                          class="w-1.5 h-1.5 rounded-full bg-status-completed-text"
                        ></span
                        >Baik</span
                      >
                    </td>
                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                      <a
                        href="buat-laporan.php?kode=FUR-001"
                        title="Laporkan kerusakan FUR-001"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border text-primary-container hover:bg-[#F1EAE3] hover:border-primary-container/40 transition-colors font-label-sm text-label-sm"
                        ><span class="material-symbols-outlined text-[16px]"
                          >report_problem</span
                        ><span>Laporkan Kerusakan</span></a
                      >
                    </td>
                  </tr>
                  <tr class="hover:bg-canvas transition-colors">
                    <td
                      class="py-3.5 px-4 font-mono font-semibold text-caption text-on-surface whitespace-nowrap"
                    >
                      SAN-001
                    </td>
                    <td class="py-3.5 px-4">
                      <div class="flex flex-col">
                        <span
                          class="font-body-strong text-body-strong text-on-surface"
                          >Kran Air Wastafel Sensor</span
                        ><span class="font-caption text-caption text-text-muted"
                          >Unit Wastafel Otomatis Baris Kiri</span
                        >
                      </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-caption font-medium bg-surface-muted text-text-secondary border border-border"
                        >Sanitasi</span
                      >
                    </td>
                    <td class="py-3.5 px-4">
                      <div
                        class="flex items-center gap-1.5 text-text-secondary"
                      >
                        <span
                          class="material-symbols-outlined text-[16px] text-text-muted flex-shrink-0"
                          >location_on</span
                        ><span class="font-body-md text-body-md"
                          >Gedung C, Lantai 1 – Toilet Pria Barat</span
                        >
                      </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-caption font-semibold bg-status-error-bg text-status-error-text border border-status-error-border"
                        ><span
                          class="w-1.5 h-1.5 rounded-full bg-status-error-text"
                        ></span
                        >Rusak Berat</span
                      >
                    </td>
                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                      <a
                        href="buat-laporan.php?kode=SAN-001"
                        title="Laporkan kerusakan SAN-001"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border text-primary-container hover:bg-[#F1EAE3] hover:border-primary-container/40 transition-colors font-label-sm text-label-sm"
                        ><span class="material-symbols-outlined text-[16px]"
                          >report_problem</span
                        ><span>Laporkan Kerusakan</span></a
                      >
                    </td>
                  </tr>
                  <tr class="hover:bg-canvas transition-colors">
                    <td
                      class="py-3.5 px-4 font-mono font-semibold text-caption text-on-surface whitespace-nowrap"
                    >
                      NET-002
                    </td>
                    <td class="py-3.5 px-4">
                      <div class="flex flex-col">
                        <span
                          class="font-body-strong text-body-strong text-on-surface"
                          >Access Point WiFi Aruba AP-505</span
                        ><span class="font-caption text-caption text-text-muted"
                          >SSID: Kampus-Mahasiswa-ZonaB</span
                        >
                      </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-caption font-medium bg-surface-muted text-text-secondary border border-border"
                        >Jaringan</span
                      >
                    </td>
                    <td class="py-3.5 px-4">
                      <div
                        class="flex items-center gap-1.5 text-text-secondary"
                      >
                        <span
                          class="material-symbols-outlined text-[16px] text-text-muted flex-shrink-0"
                          >location_on</span
                        ><span class="font-body-md text-body-md"
                          >Gedung Perpustakaan Pusat – Lantai 2</span
                        >
                      </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-caption font-semibold bg-status-completed-bg text-status-completed-text border border-status-completed-border"
                        ><span
                          class="w-1.5 h-1.5 rounded-full bg-status-completed-text"
                        ></span
                        >Baik</span
                      >
                    </td>
                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                      <a
                        href="buat-laporan.php?kode=NET-002"
                        title="Laporkan kerusakan NET-002"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border text-primary-container hover:bg-[#F1EAE3] hover:border-primary-container/40 transition-colors font-label-sm text-label-sm"
                        ><span class="material-symbols-outlined text-[16px]"
                          >report_problem</span
                        ><span>Laporkan Kerusakan</span></a
                      >
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div
              class="px-4 py-3.5 border-t border-border bg-surface flex flex-col sm:flex-row items-center justify-between gap-space-md"
            >
              <span class="font-caption text-caption text-text-secondary"
                >Menampilkan
                <strong class="text-on-surface font-semibold">1–6</strong> dari
                <strong class="text-on-surface font-semibold">48</strong>
                fasilitas terdaftar</span
              >
              <div class="inline-flex items-center gap-1 select-none">
                <button
                  type="button"
                  disabled
                  class="px-2.5 py-1.5 rounded-lg border border-border bg-surface text-text-muted cursor-not-allowed font-label-sm text-label-sm flex items-center gap-1"
                >
                  <span class="material-symbols-outlined text-[16px]"
                    >chevron_left</span
                  ><span>Sebelumnya</span>
                </button>
                <button
                  type="button"
                  class="w-8 h-8 rounded-lg bg-primary-container text-on-primary font-label-sm text-label-sm flex items-center justify-center font-semibold"
                >
                  1
                </button>
                <button
                  type="button"
                  class="w-8 h-8 rounded-lg border border-border bg-surface text-on-surface hover:bg-surface-muted font-label-sm text-label-sm flex items-center justify-center transition-colors"
                >
                  2
                </button>
                <button
                  type="button"
                  class="w-8 h-8 rounded-lg border border-border bg-surface text-on-surface hover:bg-surface-muted font-label-sm text-label-sm flex items-center justify-center transition-colors"
                >
                  3
                </button>
                <span class="px-1 text-text-muted font-label-sm">...</span>
                <button
                  type="button"
                  class="w-8 h-8 rounded-lg border border-border bg-surface text-on-surface hover:bg-surface-muted font-label-sm text-label-sm flex items-center justify-center transition-colors"
                >
                  8
                </button>
                <button
                  type="button"
                  class="px-2.5 py-1.5 rounded-lg border border-border bg-surface text-on-surface hover:bg-surface-muted font-label-sm text-label-sm flex items-center gap-1 transition-colors"
                >
                  <span>Selanjutnya</span
                  ><span class="material-symbols-outlined text-[16px]"
                    >chevron_right</span
                  >
                </button>
              </div>
            </div>
          </div>

          <!-- Petunjuk -->
          <div
            class="bg-surface rounded-xl border border-border p-4 flex items-start gap-3 text-text-secondary"
          >
            <div
              class="p-1 rounded bg-surface-muted text-primary-container flex-shrink-0 mt-0.5"
            >
              <span class="material-symbols-outlined text-[18px]">info</span>
            </div>
            <div class="flex flex-col text-caption">
              <span class="font-body-strong text-on-surface text-caption"
                >Petunjuk Inventaris Kampus:</span
              >
              <p
                class="font-body-md text-caption text-text-secondary mt-0.5 leading-relaxed"
              >
                Apabila barang atau fasilitas yang rusak tidak tercantum dalam
                daftar ini, Anda dapat menghubungi petugas sarana prasarana
                kampus untuk pendataan inventaris baru atau verifikasi kode
                fisik barang.
              </p>
            </div>
          </div>
        </div>
      </main>
    </div>
    <?= Auth::userScript() ?>
    <script src="../../assets/js/components/layout.js?v=2"></script>
    <script src="../../assets/js/pages/mahasiswa/daftar-fasilitas.js"></script>
  </body>
</html>
