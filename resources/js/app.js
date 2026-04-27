document.addEventListener('DOMContentLoaded', () => {
    // Mobile Sidebar Toggle
    const sidebartoggler = document.querySelectorAll(".sidebartoggler");
    sidebartoggler.forEach(el => {
        el.addEventListener("click", () => {
            document.querySelector(".left-sidebar").classList.toggle("show-sidebar");
        });
    });
});
