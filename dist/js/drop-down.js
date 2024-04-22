const allDots = document.querySelectorAll(".dots");
const allDropdownContents = document.querySelectorAll(".dropdown-content");

allDots.forEach((dots, index) => {
  dots.addEventListener("click", () => {
    allDropdownContents[index].classList.toggle("hidden");
  });

  document.addEventListener("click", (event) => {
    const isClickInsideDropdown =
      allDropdownContents[index].contains(event.target) ||
      event.target === dots;
    if (!isClickInsideDropdown) {
      allDropdownContents[index].classList.add("hidden");
    }
  });
});
