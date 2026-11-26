(function () {
  const grid = document.getElementById("facility-grid");
  const filterBar = document.getElementById("facility-filters");
  if (!grid || !filterBar) return;

  const ACTIVE = ["bg-primary-container", "text-on-primary"];
  const INACTIVE = [
    "bg-surface",
    "border",
    "border-border",
    "text-text-secondary",
    "hover:bg-surface-container",
  ];

  function cardTemplate(f) {
    const s = window.FACILITY_STATUS[f.status];
    return `
      <div class="bg-surface border border-border rounded-xl p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between">
        <div>
          <div class="flex items-start justify-between gap-3 mb-4">
            <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary-container shrink-0">
              <span class="material-symbols-outlined text-[20px]">${f.icon}</span>
            </div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md ${s.bg} ${s.text} font-label-sm text-label-sm">
              <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Kondisi: ${s.label}
            </span>
          </div>
          <div class="mb-3">
            <span class="font-caption text-caption text-text-muted">Kategori: ${f.category}</span>
            <h3 class="font-headline-sm text-headline-sm text-on-surface mt-0.5">${f.name}</h3>
            <span class="inline-block mt-1 font-caption text-caption text-text-secondary font-mono px-2 py-0.5 bg-surface-container-low border border-border/60 rounded">Kode: ${f.code}</span>
          </div>
          <div class="space-y-1.5 text-text-secondary font-caption text-caption mb-5">
            <p class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-text-muted">domain</span>${f.building}</p>
            <p class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-text-muted">category</span>${f.desc}</p>
          </div>
        </div>
        <div class="pt-4 flex items-center justify-between border-t border-border/60">
          <a class="inline-flex items-center gap-1 text-primary-container hover:text-primary font-label-md text-label-md transition-colors" href="login.html?kode=${encodeURIComponent(f.code)}">
            <span class="material-symbols-outlined text-[16px]">report</span><span>Laporkan Kerusakan</span>
          </a>
          <span class="font-caption text-caption text-text-muted">${f.tag}</span>
        </div>
      </div>`;
  }

  function render(category) {
    const list =
      category === "Semua"
        ? window.FACILITIES
        : window.FACILITIES.filter((f) => f.category === category);
    grid.innerHTML = list.map(cardTemplate).join("");
  }

  filterBar.addEventListener("click", (e) => {
    const btn = e.target.closest("button[data-filter]");
    if (!btn) return;
    filterBar.querySelectorAll("button").forEach((b) => {
      b.classList.remove(...ACTIVE);
      b.classList.add(...INACTIVE);
    });
    btn.classList.remove(...INACTIVE);
    btn.classList.add(...ACTIVE);
    render(btn.dataset.filter);
  });

  render("Semua");
})();
