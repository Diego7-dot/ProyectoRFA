// Js de inicio de sesión Usuario

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const correo = document.getElementById("corUsu");
    const pass = document.getElementById("psw");
    const alternarContraseña = document.getElementById("togglePasswordIcon");
    const scrollTopBtn = document.getElementById("btnScrollTop");
    const iconoModo = document.getElementById("icono-modo");

    const params = new URLSearchParams(window.location.search);
    const error = params.get("error");
    const correoURL = params.get("correo");

    if (error) {
        const errores = {
            "credenciales_invalidas": "*Correo o contraseña invalido 😢",
            "error_desconocido": "Ocurrió un error inesperado.",
        };

        mostrarErrorJS(errores[error] || "Error desconocido");

        // KM = Limpia la contraseña por seguridad
        if (pass) pass.value = "";

        // KM = Coloca el correo si venía por URL
        if (correoURL && correo) correo.value = decodeURIComponent(correoURL);

        // KM = Que elimine los paramertros luego de mostrarlos
        if (window.history.replaceState) {
            const cleanURL = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanURL);
        }
    }

    // KM = Validar compos vacios y errores
    form.addEventListener("submit", function (e) {
        if (!correo.value.trim() || !pass.value.trim()) {
            e.preventDefault();
            mostrarErrorJS("Por favor completa todos los campos.");
        }
    });

    function mostrarErrorJS(mensaje) {
        const contenedor = document.getElementById("mensaje-error");
        contenedor.textContent = mensaje;
        contenedor.style.display = "block";
    }

    // KM = Visibilidad de la contraseña
    if (alternarContraseña) {
        alternarContraseña.addEventListener("click", () => {
            const tipo = pass.type === "password" ? "text" : "password";
            pass.type = tipo;
            alternarContraseña.src =
                tipo === "password"
                    ? "../../iconos/pagina_principal/ojo_cerrado.png"
                    : "../../iconos/pagina_principal/ojo_abierto.png";
        });
    }

    // KM = Boton para subir
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

    // KM = Boton para el modo oscuro
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

    //KM = Evitar fuga de seguridad cuando el usuario retroceda
    window.addEventListener("pageshow", function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            if (correo) correo.value = "";
            if (pass) pass.value = "";
        }
    });
});
