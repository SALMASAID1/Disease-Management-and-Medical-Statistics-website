const button = document.getElementById("userDropdownButton");
const menu = document.getElementById("userDropdownMenu");

button.addEventListener("click", () => {
  const isExpanded = button.getAttribute("aria-expanded") === "true";
  button.setAttribute("aria-expanded", !isExpanded);
  menu.style.display = isExpanded ? "none" : "block";
  menu.setAttribute("aria-hidden", isExpanded);
});

document.addEventListener("click", (event) => {
  if (!button.contains(event.target) && !menu.contains(event.target)) {
    button.setAttribute("aria-expanded", "false");
    menu.style.display = "none";
    menu.setAttribute("aria-hidden", "true");
  }
});