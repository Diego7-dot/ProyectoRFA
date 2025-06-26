// ********************
// JS: Espacio joven
// ********************

// KM = Funcion para mostrar la definicion de la lista
function toggleDefinicion(elemento) {
    const definicion = elemento.querySelector('.definicion-legal');
  
    if (definicion.style.display === 'block') {
      definicion.style.display = 'none';
    } else {
      // Opcional: Cerrar otros abiertos antes
      document.querySelectorAll('.definicion-legal').forEach(def => def.style.display = 'none');
      definicion.style.display = 'block';
    }
  }

 // KM = Funcion para mostrar el mensaje de en proceso 
 function mostrarMensajeProceso(e) {
    if (e) e.preventDefault();
      const mensaje = document.getElementById('mensaje-proceso');

      mensaje.classList.add('show');

      setTimeout(() => {
        mensaje.classList.remove('show');
    }, 3000);
  }

// KM = Permite la animacion suave al bajar a un elemento de la pagina
function smoothScroll(target, duration) {
    const targetEl = document.querySelector(target);
    if (!targetEl) return;
  
    const startPosition = window.pageYOffset;
    const targetPosition = targetEl.getBoundingClientRect().top + window.pageYOffset;
    const distance = targetPosition - startPosition;
    let startTime = null;
  
    function animation(currentTime) {
      if (startTime === null) startTime = currentTime;
      const timeElapsed = currentTime - startTime;
      const run = easeInOutQuad(timeElapsed, startPosition, distance, duration);
      window.scrollTo(0, run);
      if (timeElapsed < duration) requestAnimationFrame(animation);
    }
  
    function easeInOutQuad(t, b, c, d) {
      t /= d / 2;
      if (t < 1) return c / 2 * t * t + b;
      t--;
      return -c / 2 * (t * (t - 2) - 1) + b;
    }
  
    requestAnimationFrame(animation);
  }
  
  // KM = Permite mostrar la lista al leer mas en recursos
  document.querySelectorAll('.btn-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const tarjeta = btn.closest('.tarjeta-recurso');
      tarjeta.classList.toggle('activa');
      btn.textContent = tarjeta.classList.contains('activa') ? 'Leer menos' : 'Leer más';
    });
  });

  document.addEventListener("DOMContentLoaded", () => {
    // Navegación scroll suave
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const hash = this.getAttribute('href');
        if (!hash || hash === '#') return;
        e.preventDefault();
        smoothScroll(hash, 2000);
      });
    });
  
    // Menú hamburguesa
    const menu = document.getElementById("menu-navegacion");
    const boton = document.querySelector(".boton-menu");
    if (boton) {
      boton.addEventListener("click", () => {
        menu.classList.toggle("active");
      });
    }
  
    // Modo oscuro
    const btnModo = document.getElementById("modo-toggle");
    const iconoModo = document.getElementById("icono-modo");
  
    btnModo.addEventListener("click", () => {
      document.body.classList.toggle("dark-mode");
  
      const modoOscuroActivo = document.body.classList.contains("dark-mode");
  
      iconoModo.src = modoOscuroActivo
        ? "../../iconos/pagina_principal/sol.png"
        : "../../iconos/pagina_principal/luna.png";
  
      iconoModo.alt = modoOscuroActivo ? "Modo claro" : "Modo oscuro";
    });
  
    // Botón scroll top
    const scrollTopBtn = document.getElementById('btnScrollTop');
  
    window.addEventListener('scroll', () => {
      if (window.scrollY > 300) {
        scrollTopBtn.classList.add('visible');
      } else {
        scrollTopBtn.classList.remove('visible');
      }
    });
  
    scrollTopBtn.addEventListener('click', () => {
      const scrollStep = -window.scrollY / 60;
      const scrollInterval = setInterval(() => {
        if (window.scrollY !== 0) {
          window.scrollBy(0, scrollStep);
        } else {
          clearInterval(scrollInterval);
        }
      }, 16);
    });
  
    // KM = Menu desplegable del espacio joven
    const botones = document.querySelectorAll('.pregunta-btn');

    botones.forEach(boton => {
    boton.addEventListener('click', () => {
    const contenedorPregunta = boton.parentElement; 
    const respuesta = contenedorPregunta.querySelector('.respuesta');
    const iconoImg = boton.querySelector('.icono img');

    const estaAbierto = respuesta.classList.contains('activa');

    // Cerrar todas las respuestas abiertas
    document.querySelectorAll('.respuesta').forEach(r => {
      r.classList.remove('activa');
      r.style.maxHeight = null;
    });

    document.querySelectorAll('.pregunta-btn .icono img').forEach(img => {
      img.src = '../../imagenes/icono_mas.png'; // Icono para cerrado
    });

    if (!estaAbierto) {
      respuesta.classList.add('activa');
      respuesta.style.maxHeight = respuesta.scrollHeight + "px";
      iconoImg.src = '../../imagenes/icono_menos.png'; // Icono para abierto
    }
    });
  });
});
  