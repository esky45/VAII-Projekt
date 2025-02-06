document.addEventListener("DOMContentLoaded", function() {
    // Select all links with class 'smarthome-link' and add target="_blank"
    document.querySelectorAll(".newpage-link").forEach(function(link) {
        link.setAttribute("target", "_blank");
    });
});