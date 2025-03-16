// JS de index, matenme plis

// Activar el menu en mobil 
document.addEventListener("DOMContentLoaded", function() {
    const menu = document.getElementById("menu-navegacion");
    const boton = document.querySelector(".boton-menu");

    boton.addEventListener("click", function() {
        menu.classList.toggle("activo");
    });
});

