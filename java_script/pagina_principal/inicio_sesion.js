// KM = Js de la seleccion de inicio de sesion

document.addEventListener("DOMContentLoaded", function () {
    // KM  = GSAP animación
    gsap.from(".icono-rol", {
        opacity: 0,
        y: 15,
        duration: 1,
        stagger: 0.3,
        ease: "power1.out"
    });

    gsap.to(".icono-rol", {
        y: -5,
        repeat: -1,
        duration: 1.5,
        yoyo: true,
        ease: "power1.inOut"
    });

    // KM = Scroll top y modo oscuro
    const scrollTopBtn = document.getElementById("btnScrollTop");
    const iconoModo = document.getElementById("icono-modo");

    // KM = Boton de subir al top
    if (scrollTopBtn) {
        window.addEventListener("scroll", () => {
            scrollTopBtn.classList.toggle("visible", window.scrollY > 300);
        });

        scrollTopBtn.addEventListener("click", () => {
            const scrollStep = -window.scrollY / 60;
            const scrollInterval = setInterval(() => {
                if (window.scrollY !== 0) {
                    window.scrollBy(0, scrollStep);
                } else {
                    clearInterval(scrollInterval);
                }
            }, 16);
        });
    }

    // KM = Boton modo oscuro
    if (iconoModo) {
        document.getElementById("modo-toggle").addEventListener("click", () => {
            document.body.classList.toggle("dark-mode");
            const oscuro = document.body.classList.contains("dark-mode");
            iconoModo.src = oscuro
                ? "../../iconos/pagina_principal/sol.png"
                : "../../iconos/pagina_principal/luna.png";
            iconoModo.alt = oscuro ? "Modo claro" : "Modo oscuro";
        });
    }
});
