// Filter tabel fasilitas: pencarian teks + kategori + lokasi
(function () {
  const searchInput = document.getElementById("searchInput");
  const categoryFilter = document.getElementById("categoryFilter");
  const locationFilter = document.getElementById("locationFilter");
  const rows = document.querySelectorAll("tbody tr");

  function filterTable() {
    const query = searchInput.value.toLowerCase();
    const category = categoryFilter.value.toLowerCase();
    const location = locationFilter.value.toLowerCase();

    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      const ok = (!query || text.includes(query)) &&
                 (!category || text.includes(category)) &&
                 (!location || text.includes(location));
      row.style.display = ok ? "" : "none";
    });
  }

  if (searchInput && categoryFilter && locationFilter) {
    searchInput.addEventListener("input", filterTable);
    categoryFilter.addEventListener("change", filterTable);
    locationFilter.addEventListener("change", filterTable);
  }
})();
