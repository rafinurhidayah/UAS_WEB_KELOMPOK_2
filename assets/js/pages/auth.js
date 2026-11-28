(function () {
  // Toggle lihat/sembunyikan password.
  // Pakai: <button data-toggle-password="idInput"> ... <span class="material-symbols-outlined">visibility</span>
  document.querySelectorAll("[data-toggle-password]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const input = document.getElementById(btn.dataset.togglePassword);
      const icon = btn.querySelector(".material-symbols-outlined");
      if (!input || !icon) return;
      const hidden = input.type === "password";
      input.type = hidden ? "text" : "password";
      icon.textContent = hidden ? "visibility_off" : "visibility";
    });
  });

  // Login: form dikirim sebagai POST ke PHP.
  // Handler ini hanya memberi umpan balik visual; tidak memblokir submit.
  const loginForm = document.getElementById("login-form");
  if (loginForm) {
    loginForm.addEventListener("submit", () => {
      const btn = loginForm.querySelector("button[type=submit]");
      if (!btn) return;
      btn.setAttribute("aria-busy", "true");
      btn.innerHTML =
        '<span class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span><span>Memverifikasi akun...</span>';
    });
  }

  // Register: validasi kecocokan sandi di sisi klien, lalu izinkan POST.
  const regForm = document.getElementById("register-form");
  if (regForm) {
    const pass = document.getElementById("password");
    const confirm = document.getElementById("confirmPassword");

    function checkMatch() {
      if (!pass || !confirm) return true;
      const mismatch = confirm.value !== "" && confirm.value !== pass.value;
      confirm.setCustomValidity(mismatch ? "Kata sandi tidak sama" : "");
      return !mismatch;
    }

    if (pass && confirm) {
      pass.addEventListener("input", checkMatch);
      confirm.addEventListener("input", checkMatch);
      confirm.addEventListener("change", checkMatch);
    }

    regForm.addEventListener("submit", (e) => {
      // Blokir hanya bila konfirmasi sandi benar-benar tidak cocok.
      if (!checkMatch()) {
        e.preventDefault();
        confirm.reportValidity();
        return;
      }

      const btn = regForm.querySelector("button[type=submit]");
      if (!btn) return;
      btn.setAttribute("aria-busy", "true");
      btn.innerHTML =
        '<span class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span><span>Mendaftarkan akun...</span>';
    });
  }
})();
