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

  // Login
  const loginForm = document.getElementById("login-form");
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const btn = loginForm.querySelector("button[type=submit]");
      const original = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML =
        '<span class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span><span>Memverifikasi akun...</span>';

      // TODO: ganti dengan request ke backend
      setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = original;
      }, 1200);
    });
  }

  // Register
  const regForm = document.getElementById("register-form");
  if (regForm) {
    const pass = document.getElementById("password");
    const confirm = document.getElementById("confirmPassword");

    function checkMatch() {
      confirm.setCustomValidity(
        confirm.value && confirm.value !== pass.value ? "Kata sandi tidak sama" : ""
      );
    }
    pass.addEventListener("input", checkMatch);
    confirm.addEventListener("input", checkMatch);

    regForm.addEventListener("submit", (e) => {
      e.preventDefault();
      checkMatch();
      if (!regForm.reportValidity()) return;

      const data = Object.fromEntries(new FormData(regForm));
      delete data.password;
      delete data.confirmPassword;
      console.log("Data registrasi:", data); // TODO: kirim ke backend
    });
  }
})();