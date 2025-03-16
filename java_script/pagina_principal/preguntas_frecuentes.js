document.addEventListener('DOMContentLoaded', function () {
    const categorias = document.querySelectorAll('.categoria');
    const contenedoresPreguntas = document.querySelectorAll('.contenedor-preguntas');
    
    let categoriaActiva = null;

    // Ocultar todas las preguntas al inicio
    contenedoresPreguntas.forEach(contenedor => {
        contenedor.style.display = 'none';
    });

    categorias.forEach(categoria => {
        categoria.addEventListener('click', function () {
            const categoriaSeleccionada = this.getAttribute('data-categoria');
            const preguntasAsociadas = document.querySelector(`.contenedor-preguntas[data-categoria="${categoriaSeleccionada}"]`);

            // Si la categoría seleccionada ya está activa, la ocultamos
            if (categoriaActiva === categoriaSeleccionada) {
                preguntasAsociadas.style.display = 'none';
                categoriaActiva = null;
                return;
            }

            // Ocultar todas las preguntas antes de mostrar la seleccionada
            contenedoresPreguntas.forEach(contenedor => {
                contenedor.style.display = 'none';
            });

            // Mostrar preguntas debajo del botón de la categoría seleccionada
            preguntasAsociadas.style.display = 'block';
            preguntasAsociadas.classList.add('expandido');

            // Mover dinámicamente la sección de preguntas debajo de la categoría
            categoria.insertAdjacentElement('afterend', preguntasAsociadas);

            // Actualizar la categoría activa
            categoriaActiva = categoriaSeleccionada;
        });
    });

    // Evento para mostrar/ocultar respuestas dentro de cada categoría
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('pregunta')) {
            const respuesta = event.target.nextElementSibling;
            const estaAbierta = event.target.getAttribute('aria-expanded') === 'true';

            // Cerrar todas las respuestas antes de abrir la nueva
            document.querySelectorAll('.respuesta').forEach(resp => {
                resp.style.maxHeight = null;
            });

            document.querySelectorAll('.pregunta').forEach(p => {
                p.setAttribute('aria-expanded', 'false');
            });

            // Si la respuesta no estaba abierta, la abre
            if (!estaAbierta) {
                event.target.setAttribute('aria-expanded', 'true');
                respuesta.style.maxHeight = respuesta.scrollHeight + 'px';
            }
        }
    });
});
