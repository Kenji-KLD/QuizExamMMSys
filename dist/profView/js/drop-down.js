const dots = document.getElementById("dots");
const dropdownContent = document.getElementById("dropdown-content");

dots.addEventListener("click", () => {
  dropdownContent.classList.toggle("hidden");
});

document.addEventListener('click', (event) => {
  const isClickInsideDropdown = dropdownContent.contains(event.target) || event.target === dots;
  if (!isClickInsideDropdown) {
    dropdownContent.classList.add('hidden');
  }
});