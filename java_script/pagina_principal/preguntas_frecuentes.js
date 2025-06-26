  // Js de preguntas frecuentes

  document.addEventListener('DOMContentLoaded', function () {
  const categorias = document.querySelectorAll('.categoria');
  const contenedoresPreguntas = document.querySelectorAll('.contenedor-preguntas');
    
  let categoriaActiva = null;

  contenedoresPreguntas.forEach(contenedor => {
    contenedor.style.display = 'none';
  });

  categorias.forEach(categoria => {
    categoria.addEventListener('click', function () {
        const categoriaSeleccionada = this.getAttribute('data-categoria');
        const preguntasAsociadas = document.querySelector(`.contenedor-preguntas[data-categoria="${categoriaSeleccionada}"]`);

        
        if (categoriaActiva === categoriaSeleccionada) {
            preguntasAsociadas.style.display = 'none';
            categoriaActiva = null;
            return;
        }

        contenedoresPreguntas.forEach(contenedor => {
            contenedor.style.display = 'none';
        });

        preguntasAsociadas.style.display = 'block';
        preguntasAsociadas.classList.add('expandido');

        categoria.insertAdjacentElement('afterend', preguntasAsociadas);

        categoriaActiva = categoriaSeleccionada;
        });
    });

    // KM = Mostrar cada categoria o ocultarla
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('pregunta')) {
            const respuesta = event.target.nextElementSibling;
            const estaAbierta = event.target.getAttribute('aria-expanded') === 'true';

            document.querySelectorAll('.respuesta').forEach(resp => {
                resp.style.maxHeight = null;
            });

            document.querySelectorAll('.pregunta').forEach(p => {
                p.setAttribute('aria-expanded', 'false');
            });

            if (!estaAbierta) {
                event.target.setAttribute('aria-expanded', 'true');
                respuesta.style.maxHeight = respuesta.scrollHeight + 'px';
            }
        }
    });

  // KM =  Modo oscuro
  const btnModo = document.getElementById("modo-toggle");
  const iconoModo = document.getElementById("icono-modo");

  btnModo.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");

  // KM = Cambiar ícono según el modo ya sea oscuro a luz y viceversa
  const modoOscuroActivo = document.body.classList.contains("dark-mode");

  iconoModo.src = modoOscuroActivo
    ? "../../iconos/pagina_principal/sol.png" 
    : "../../iconos/pagina_principal/luna.png";

  iconoModo.alt = modoOscuroActivo ? "Modo claro" : "Modo oscuro";
  });

  // KM = Scroll para subir al incio de la pagina
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
});
