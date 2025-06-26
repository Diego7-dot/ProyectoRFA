document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("form-restablecer");
  const nueva = document.getElementById("psw1");
  const confirmar = document.getElementById("psw2");
  const mensaje = document.getElementById("mensaje-error");

  const alternar1 = document.getElementById("togglePassword1");
  const alternar2 = document.getElementById("togglePassword2");
  const scrollTopBtn = document.getElementById("btnScrollTop");
  const iconoModo = document.getElementById("icono-modo");

  // KM = Validar coincidencia y longitud de la contraseña
  if (form) {
    form.addEventListener("submit", function (e) {
      if (nueva.value.length < 6) {
        e.preventDefault();
        mensaje.textContent = "La contraseña debe tener al menos 6 caracteres.";
        return;
      }
      if (nueva.value !== confirmar.value) {
        e.preventDefault();
        mensaje.textContent = "Las contraseñas no coinciden.";
        return;
      }
      mensaje.textContent = "";
    });
  }

  // KM = Mostrar/ocultar contraseña 1
  if (alternar1 && nueva) {
    alternar1.addEventListener("click", () => {
      const tipo = nueva.type === "password" ? "text" : "password";
      nueva.type = tipo;
      alternar1.src = tipo === "password"
        ? "../../iconos/pagina_principal/ojo_cerrado.png"
        : "../../iconos/pagina_principal/ojo_abierto.png";
    });
  }

  // KM = Mostrar/ocultar contraseña 2
  if (alternar2 && confirmar) {
    alternar2.addEventListener("click", () => {
      const tipo = confirmar.type === "password" ? "text" : "password";
      confirmar.type = tipo;
      alternar2.src = tipo === "password"
        ? "../../iconos/pagina_principal/ojo_cerrado.png"
        : "../../iconos/pagina_principal/ojo_abierto.png";
    });
  }

  // KM = Botón de scroll hacia arriba
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

  // KM = Limpiar campos al volver atrás
  window.addEventListener("pageshow", function (event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
      if (nueva) nueva.value = "";
      if (confirmar) confirmar.value = "";
    }
  });
});
