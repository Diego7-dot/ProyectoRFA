// KM= = Js del incio de sesion de empelado

document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  const documento = document.getElementById("documento");
  const pass = document.getElementById("password");
  const alternarContraseña = document.getElementById("toggleVerifyPasswordIcon");
  const scrollTopBtn = document.getElementById("btnScrollTop");
  const iconoModo = document.getElementById("icono-modo");

  const params = new URLSearchParams(window.location.search);
  const error = params.get("error");

  // KM = Errores
  if (error) {
    const errores = {
      "credenciales_invalidas": "*Documento o contraseña inválido 😢",
      "campos_vacios": "Por favor completa todos los campos.",
      "error_desconocido": "Ocurrió un error inesperado.",
    };

    mostrarErrorJS(errores[error] || "Error desconocido");

    // KM = Limpiar contraseña si se equivoca
    if (pass) pass.value = "";

    // KM = Quitar parametros de la URL
    if (window.history.replaceState) {
      const cleanURL = window.location.origin + window.location.pathname;
      window.history.replaceState({}, document.title, cleanURL);
    }
  }

  form.addEventListener("submit", function (e) {
    if (!documento.value.trim() || !pass.value.trim()) {
      e.preventDefault();
      mostrarErrorJS("Por favor completa todos los campos.");
    }
  });

  function mostrarErrorJS(mensaje) {
    const contenedor = document.getElementById("mensaje-error");
    contenedor.textContent = mensaje;
    contenedor.style.display = "block";
  }

  // KM = Alternar visibilidad de la contraseña
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

  // KM = Boton de subir
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

  // KM = Evitar volver atras con datos precargados
  window.addEventListener("pageshow", function (event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
      if (documento) documento.value = "";
      if (pass) pass.value = "";
    }
  });
});

// KM = Validacion de documento
document.getElementById("documento").addEventListener("input", function (e) {
  this.value = this.value.replace(/\D/g, ""); // Solo números
  if (this.value.length > 11) {
    this.value = this.value.slice(0, 11); // Máximo 11 dígitos
  }
});


