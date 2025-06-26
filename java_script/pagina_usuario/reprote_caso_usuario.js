/* KM = JS de reporte caso */

/* KM = Movimiento del menú desplegable */
function toggleUserMenu() {
    const userMenu = document.getElementById('user-menu');
    userMenu.classList.toggle('active');
  }
  
  /* KM = Abrir el menú secundario */
  document.querySelectorAll('.menu_secundario .menu-item').forEach(item => {
    item.addEventListener('click', function() {
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
    window.open('../../manuales/manual_usuario.pdf', '_blank');
  });
  
  document.getElementById('btn-tecnico').addEventListener('click', () => {
    window.open('../../manuales/manual_tecnico.pdf', '_blank');
  });
  
  /* KM = Modo oscuro */
  const btnModo = document.getElementById('modo-toggle');
  const iconoModo = document.getElementById('icono-modo');
  
  btnModo.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
  
    const modoOscuroActivo = document.body.classList.contains('dark-mode');
    iconoModo.src = modoOscuroActivo
      ? "../../iconos/pagina_principal/sol.png"
      : "../../iconos/pagina_principal/luna.png";
    
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
  