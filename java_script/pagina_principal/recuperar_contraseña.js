// KM = JS básico para recuperación de contraseña (usuarios y apoyo)
document.addEventListener("DOMContentLoaded", function () {
    const correoInput = document.getElementById("correo");

    // KM = Recordar correo si hubo error
    const params = new URLSearchParams(window.location.search);
    const correoGuardado = params.get("correo");
    if (correoGuardado && correoInput) {
        correoInput.value = correoGuardado;
    }

    // KM = Botón para volver arriba
    const scrollTopBtn = document.getElementById("btnScrollTop");
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

    // KM = Modo oscuro
    const iconoModo = document.getElementById("icono-modo");
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

    // KM = Limpiar campos al retroceder
    window.addEventListener("pageshow", function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            if (correoInput) correoInput.value = "";
        }
    });
});
