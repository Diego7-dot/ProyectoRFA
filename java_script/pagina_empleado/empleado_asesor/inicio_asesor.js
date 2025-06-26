/* KM = JS de inicio del asesor*/

/* KM = Movimiento del menú desplegable */
function toggleUserMenu() {
  const userMenu = document.getElementById('user-menu');
  userMenu.classList.toggle('active');
}

/* KM = Abrir el menú secundario */
document.querySelectorAll('.menu_secundario .menu-item').forEach(item => {
  item.addEventListener('click', function () {
    document.querySelectorAll('.menu_secundario .menu-item').forEach(btn => btn.classList.remove('active'));
    this.classList.add('active');

    const section = this.getAttribute('data-section');
    loadSection(section);
    window.location.hash = section;
  });
});

/* KM = Abrir y cerrar Ayuda / Soporte */
const botonAbrirSoporte = document.getElementById('abrir-soporte');
const modalAyuda = document.getElementById('modal-ayuda');
const cerrarModalAyuda = document.getElementById('cerrar-modal');

botonAbrirSoporte.addEventListener('click', (e) => {
  e.preventDefault();
  modalAyuda.style.display = 'block';
});

cerrarModalAyuda.addEventListener('click', () => {
  modalAyuda.style.display = 'none';
});

/* KM = Abrir y cerrar Manuales */
const botonAbrirManuales = document.getElementById('abrir-manuales');
const modalManuales = document.getElementById('modal-manuales');
const cerrarModalManuales = document.getElementById('cerrar-modal-manuales');

botonAbrirManuales.addEventListener('click', (e) => {
  e.preventDefault();
  modalManuales.style.display = 'block';
});

cerrarModalManuales.addEventListener('click', () => {
  modalManuales.style.display = 'none';
});

/* KM = Cerrar los ventanas emergentes si hacen clic afuera */
window.addEventListener('click', (e) => {
  if (e.target === modalAyuda) {
    modalAyuda.style.display = 'none';
  }
  if (e.target === modalManuales) {
    modalManuales.style.display = 'none';
  }
});

/* KM = Botones internos para abrir los manuales */
document.getElementById('btn-usuario').addEventListener('click', () => {
  window.open('../../../manuales/manual_usuario.pdf', '_blank');
});

document.getElementById('btn-tecnico').addEventListener('click', () => {
  window.open('../../../manuales/manual_tecnico.pdf', '_blank');
});

/* KM = Modo oscuro */
const btnModo = document.getElementById('modo-toggle');
const iconoModo = document.getElementById('icono-modo');

btnModo.addEventListener('click', () => {
  document.body.classList.toggle('dark-mode');

  const modoOscuroActivo = document.body.classList.contains('dark-mode');
  iconoModo.src = modoOscuroActivo
    ? "../../../iconos/pagina_principal/sol.png"
    : "../../../iconos/pagina_principal/luna.png";

  iconoModo.alt = modoOscuroActivo ? "Modo claro" : "Modo oscuro";
});

/* KM = Botón scroll top */
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

// KM = Verificar si el asesor ya tiene disponibilidad registrada
function verificarDisponibilidadInicial() {
  const recordatorio = localStorage.getItem("recordar_disponibilidad");

  // KM = Si existe y no han pasado los 10 minutos, no mostrar modal (Botón de más tarde)
  if (recordatorio && new Date().getTime() < parseInt(recordatorio)) return;

  // KM = Llamar al backend
  fetch("/Proyecto_real_v1/Proyecto_real/src/disponibilidad/consultar_disponibilidad.php")
    .then(res => res.json())
    .then(data => {
      console.log("Verificación inicial:", data);

      const hoy = new Date().toISOString().split("T")[0];

      if (!data.tieneDisponibilidad) {
        Swal.fire({
          title: "¡Importante!",
          text: "Aún no has registrado tu disponibilidad. ¿Deseas hacerlo ahora?",
          icon: "warning",
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: "Registrar ahora",
          denyButtonText: "Recordar más tarde",
          cancelButtonText: "Cancelar",
          customClass: {
            confirmButton: 'btn-registro',
            denyButton: 'btn-recordar',
            cancelButton: 'btn-cancelar'
          },
          buttonsStyling: false
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "disponibilidad_asesor.php";
          } else if (result.isDenied) {
            const diezMinutos = new Date().getTime() + 10 * 60 * 1000;
            localStorage.setItem("recordar_disponibilidad", diezMinutos);
            Swal.fire("¡Claro!", "Podrás registrar tu disponibilidad más tarde.", "info");
          }
        });

      } else if (data.estado === "noIniciada") {
        Swal.fire({
          title: "¡Preparado con anticipación!",
          text: `Tu disponibilidad comenzará el ${data.fecIni}.`,
          icon: "info",
          confirmButtonText: "Aceptar",
          customClass: {
            confirmButton: 'btn-registro'
          },
          buttonsStyling: false
        });

      } else if (data.estado === "vencida") {
        Swal.fire({
          title: "Disponibilidad vencida",
          text: "Tu disponibilidad ha expirado. ¿Deseas actualizarla?",
          icon: "warning",
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: "Actualizar",
          denyButtonText: "Recordar más tarde",
          cancelButtonText: "Cancelar",
          customClass: {
            confirmButton: 'btn-registro',
            denyButton: 'btn-recordar',
            cancelButton: 'btn-cancelar'
          },
          buttonsStyling: false
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "disponibilidad_asesor.php";
          } else if (result.isDenied) {
            const diezMinutos = new Date().getTime() + 10 * 60 * 1000;
            localStorage.setItem("recordar_disponibilidad", diezMinutos);
            Swal.fire("Recordado", "Te volveremos a preguntar más tarde.", "info");
          }
        });
      }

      // KM = Si está activa, no hacer nada
    })
    .catch(err => {
      console.error("Error al verificar disponibilidad:", err);
    });
}

// KM = Ejecutar verificación al cargar la página
document.addEventListener("DOMContentLoaded", () => {
  verificarDisponibilidadInicial();
});