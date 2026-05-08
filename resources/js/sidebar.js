document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSidebar");
    const overlay = document.getElementById("overlay");

    toggleBtn.addEventListener("click", function () {
        sidebar.style.transform = "translateX(0)";
        overlay.style.display = "block";
    });

    overlay.addEventListener("click", function () {
        sidebar.style.transform = "translateX(-100%)";
        overlay.style.display = "none";
    });
});
