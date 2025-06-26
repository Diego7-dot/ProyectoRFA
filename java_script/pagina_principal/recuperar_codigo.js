// KM = JS de verificar código y temporizador sincronizado con PHP
document.addEventListener("DOMContentLoaded", function () {
  // KM = Temporizador visible
  let tiempo = typeof tiempoRestante !== 'undefined' ? tiempoRestante : 0;
  const reloj = document.getElementById("temporizador");

  const actualizarReloj = () => {
    if (tiempo <= 0) {
      reloj.textContent = "Código expirado ⛔";
      reloj.style.color = "red";
      return;
    }

    const minutos = Math.floor(tiempo / 60);
    const segundos = tiempo % 60;
    reloj.textContent = `${minutos}:${segundos < 10 ? '0' : ''}${segundos}`;
    tiempo--;
  };

  actualizarReloj();
  const intervalo = setInterval(() => {
    actualizarReloj();
    if (tiempo <= 0) clearInterval(intervalo);
  }, 1000);

  // KM = Movimiento automático entre campos
  const inputs = document.querySelectorAll(".digito");
  inputs[0].focus();

  inputs.forEach((input, i) => {
    input.addEventListener("input", () => {
      if (input.value.length === 1 && i < inputs.length - 1) {
        inputs[i + 1].focus();
      }
    });

    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !input.value && i > 0) {
        inputs[i - 1].focus();
      }
    });
  });

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

  // KM = Limpiar campos al retroceder (opcional aquí, puedes omitir si no usas correoInput)
  const correoInput = document.getElementById("correo");
  window.addEventListener("pageshow", function (event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
      if (correoInput) correoInput.value = "";
    }
  });
});
