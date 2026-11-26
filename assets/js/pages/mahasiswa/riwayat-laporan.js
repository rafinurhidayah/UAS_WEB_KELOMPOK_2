// Toggle pratinjau empty state
const toggleBtn = document.getElementById("toggle-empty-state");
const emptyBox = document.getElementById("empty-state-box");
if (toggleBtn && emptyBox) {
  toggleBtn.addEventListener("click", () => emptyBox.classList.toggle("hidden"));
}
