// Application shell bersama (sidebar + topbar) untuk semua role.
//
// Atur lewat atribut <body>:
//   data-role="mahasiswa"                      -> role yang sedang login (default: mahasiswa)
//   data-nav="dashboard"                       -> menu sidebar yang aktif
//   data-crumbs="Portal Mahasiswa|Dashboard"   -> breadcrumb (dipisah "|", yang terakhir ditebalkan)
//
// Halaman di dalam satu folder role cukup memakai href relatif (mis. "dashboard.html").
(function () {
  const ROLES = {
    mahasiswa: {
      label: "Portal Mahasiswa",
      user: { name: "Ahmad Fauzi", identity: "21051204012" },
      nav: [
        { key: "dashboard", href: "dashboard.html", icon: "dashboard", label: "Dashboard" },
        { key: "daftar-fasilitas", href: "daftar-fasilitas.html", icon: "domain", label: "Daftar Fasilitas" },
        { key: "buat-laporan", href: "buat-laporan.html", icon: "add_circle", label: "Buat Laporan" },
        { key: "riwayat-laporan", href: "riwayat-laporan.html", icon: "receipt_long", label: "Riwayat Laporan" },
        { key: "profil", href: "profil.html", icon: "account_circle", label: "Profil" },
      ],
    },
    petugas: {
      label: "Portal Petugas",
      user: { name: "Bambang Sudarsono", identity: "NIP 198705122010011003" },
      nav: [
        { key: "dashboard", href: "dashboard.html", icon: "dashboard", label: "Dashboard" },
        { key: "data-barang", href: "data-barang.html", icon: "inventory_2", label: "Data Barang" },
        { key: "daftar-laporan", href: "daftar-laporan.html", icon: "receipt_long", label: "Daftar Laporan" },
        { key: "profil", href: "profil.html", icon: "account_circle", label: "Profil" },
      ],
    },
    admin: {
      label: "Portal Admin",
      user: { name: "Siti Rahmawati", identity: "NIP 199001152015012004" },
      nav: [
        { key: "dashboard", href: "dashboard.html", icon: "dashboard", label: "Dashboard" },
        { key: "laporan", href: "laporan.html", icon: "receipt_long", label: "Manajemen Laporan" },
        { key: "mahasiswa", href: "mahasiswa.html", icon: "groups", label: "Data Mahasiswa" },
        { key: "petugas", href: "petugas.html", icon: "engineering", label: "Data Petugas" },
        { key: "kategori", href: "kategori.html", icon: "category", label: "Kategori Fasilitas" },
        { key: "lokasi", href: "lokasi.html", icon: "location_on", label: "Lokasi Kampus" },
        { key: "profil", href: "profil.html", icon: "account_circle", label: "Profil" },
      ],
    },
  };

  const ACTIVE = "bg-surface-muted text-primary-container font-semibold border-l-2 border-primary-container pl-[10px]";
  const IDLE = "text-on-surface-variant hover:bg-surface-muted hover:text-on-surface font-body-md text-body-md";

  const body = document.body;
  const role = ROLES[body.dataset.role] || ROLES.mahasiswa;
  const user = role.user;
  const active = body.dataset.nav;
  const crumbs = (body.dataset.crumbs || role.label).split("|");

  const sidebar = document.getElementById("sidebar");
  if (sidebar) {
    sidebar.innerHTML = `
      <div class="flex flex-col">
        <div class="h-16 px-space-md flex items-center gap-space-sm border-b border-border">
          <div class="w-9 h-9 rounded bg-primary-container flex items-center justify-center text-on-primary flex-shrink-0">
            <span class="material-symbols-outlined text-[20px]">apartment</span>
          </div>
          <div class="flex flex-col min-w-0">
            <span class="font-label-md text-label-md text-on-surface truncate tracking-tight">SIPERUSAK</span>
            <span class="font-caption text-caption text-text-muted truncate">${role.label}</span>
          </div>
        </div>
        <div class="p-space-sm">
          <nav class="flex flex-col gap-1" aria-label="Navigasi utama">
            ${role.nav.map(n => {
              const on = n.key === active;
              return `<a href="${n.href}" ${on ? 'aria-current="page"' : ""}
                class="flex items-center gap-space-sm px-space-sm py-2 rounded transition-colors ${on ? ACTIVE : IDLE}">
                <span class="material-symbols-outlined text-[20px] flex-shrink-0 ${on ? "text-primary-container" : "text-text-secondary"}">${n.icon}</span>
                <span class="truncate">${n.label}</span></a>`;
            }).join("")}
          </nav>
        </div>
      </div>`;
  }

  const topbar = document.getElementById("topbar");
  if (topbar) {
    const initials = user.name.split(" ").map(s => s[0]).join("").slice(0, 2);
    const crumbHtml = crumbs.map((c, i) => {
      const last = i === crumbs.length - 1;
      const cls = last ? "text-on-surface font-body-strong text-body-strong truncate" : "text-text-secondary";
      return `${i ? '<span class="text-border">/</span>' : ""}<span class="${cls}">${c}</span>`;
    }).join("");
    topbar.innerHTML = `
      <div class="flex items-center gap-space-sm text-text-secondary font-body-md text-body-md">
        <button id="menu-toggle" type="button" aria-label="Menu Navigasi"
          class="md:hidden p-1.5 rounded hover:bg-surface-muted text-on-surface flex items-center justify-center">
          <span class="material-symbols-outlined text-[22px]">menu</span>
        </button>
        <span class="material-symbols-outlined text-[18px] text-text-muted hidden sm:inline-block">home</span>
        <span class="text-border hidden sm:inline-block">/</span>
        ${crumbHtml}
      </div>
      <div class="flex items-center gap-space-md">
        <div class="flex items-center gap-space-sm pl-space-xs">
          <div class="flex-col items-end text-right hidden sm:flex">
            <span class="font-label-md text-label-md text-on-surface leading-tight">${user.name}</span>
            <span class="font-caption text-caption text-text-muted">${user.identity}</span>
          </div>
          <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center flex-shrink-0" title="${initials}">
            <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
          </div>
        </div>
      </div>`;

    // Toggle sidebar di layar kecil
    const toggle = document.getElementById("menu-toggle");
    toggle && toggle.addEventListener("click", () => {
      sidebar.classList.toggle("hidden");
      sidebar.classList.toggle("flex");
    });
  }
})();
