// Form Buat Laporan: penghitung karakter + simulasi pengiriman
document.addEventListener("DOMContentLoaded", () => {
  const textarea = document.getElementById("deskripsi-kerusakan");
  const charCount = document.getElementById("char-count");
  if (textarea && charCount) {
    const update = () => { charCount.textContent = `${textarea.value.length} / 500 karakter`; };
    textarea.addEventListener("input", update);
    update();
  }

  const form = document.getElementById("form-laporan");
  const submitBtn = document.getElementById("btn-submit");
  if (form && submitBtn) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span><span>Mengirim...</span>';
      setTimeout(() => {
        alert("Laporan berhasil dikirim! Petugas pemeliharaan akan segera memverifikasi laporan Anda.");
        submitBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">check</span><span>Terkirim</span>';
        submitBtn.classList.remove("bg-primary-container");
        submitBtn.classList.add("bg-status-completed-text");
      }, 800);
    });
  }
});
