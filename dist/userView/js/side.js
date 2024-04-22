let hamburger = document.getElementById("hamburger");
let bg = document.querySelector(".bg-side");
function Menu(e) {
  let list = document.querySelector("ul");

  e.name === "menu"
    ? ((e.name = "close"),
      (hamburger.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
            `),
      list.classList.add("right-[-70px]"),
      bg.classList.add("right-[3px]"),
      list.classList.add("opacity-100"),
      bg.classList.add("opacity-100"))
    : ((e.name = "menu"),
      (hamburger.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>

            `),
      list.classList.remove("right-[-70px]"),
      bg.classList.remove("right-[3px]"),
      list.classList.remove("opacity-100"),
      bg.classList.remove("opacity-100"));
}
