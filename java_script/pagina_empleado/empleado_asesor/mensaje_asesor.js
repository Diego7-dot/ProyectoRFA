/* KM = Mensajes de asesor */

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

// KM = Chat del asesor
const BASE      = '/Proyecto_real_v1/Proyecto_real';
const wrapper   = document.querySelector('.chat-wrapper');

// KM = Elementos del sidebar y chat
const ulSalas   = document.getElementById('lista-salas');
const sidebar   = document.getElementById('sidebar-conversaciones');
const chatMain  = document.getElementById('chat-main-asesor');
const btnBack   = document.getElementById('btn-back');

// KM = Elementos de mensajes
const ulMsgs    = document.getElementById('chat-lista');
const inpTxt    = document.getElementById('mensaje');
const btnEnv    = document.getElementById('btn-enviar');

// KM = Ocultos inputs
const hidDocApo = document.getElementById('docApo').value;
const hidRoom   = document.getElementById('room-id');
const hidUser   = document.getElementById('corUsu');

let lastId      = 0;
let evtSource   = null;

// KM = Cargar la lista de usuario en 10 segundos
cargarSalas();
setInterval(cargarSalas, 10000);

// KM = Yo no fui
btnEnv.addEventListener('click', enviarMensaje);
inpTxt.addEventListener('keydown', e => { if (e.key==='Enter') enviarMensaje(); });
btnBack.addEventListener('click', cerrarChat);

// Funciones
async function cargarSalas(){
  const res = await fetch(`${BASE}/src/mensajes/obtener_salas_asesor.php`, {
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body: JSON.stringify({ docApo: hidDocApo })
  });
  const salas = await res.json(); // [{codConChat, corUsu, nombre, foto, genero}, ...]
  ulSalas.innerHTML = '';

  salas.forEach(s => {
    // pronombre según género
    const pronombre = s.genero === 'F' ? 'Usuaria'
                      : s.genero === 'M' ? 'Usuario'
                      : 'Usuario';

    const li = document.createElement('li');
    li.className = 'sala-item';
    li.dataset.room = s.codConChat;
    li.dataset.user = s.corUsu;

    li.innerHTML = `
      <img src="${BASE}/iconos/iconos_perfil/${s.foto}"
           alt="Avatar ${s.nombre}"
           class="sala-avatar"
           onerror="this.src='${BASE}/iconos/iconos_perfil/icono_default_otro.php'">
      <div class="sala-info">
        <strong class="sala-nombre">${s.nombre}</strong>
        <small class="sala-rol">${pronombre}</small>
      </div>
    `;

    // Marcar activa si coincide con la sala actual
    if (parseInt(hidRoom.value,10) === s.codConChat) {
      li.classList.add('activo');
    }

    li.addEventListener('click', () => seleccionarSala(li));
    ulSalas.appendChild(li);
  });
}

async function seleccionarSala(li){
  // Limpiar selección previa
  ulSalas.querySelectorAll('li').forEach(x => x.classList.remove('activo'));
  li.classList.add('activo');

  // Configurar sala y usuario en hidden inputs
  hidRoom.value = li.dataset.room;
  hidUser.value = li.dataset.user;
  lastId = 0;
  ulMsgs.innerHTML = '';

  // Mostrar chat, ocultar sidebar
  wrapper.classList.add('open-chat');

  // Cargar historial y arrancar SSE
  await obtenerMensajes();
  iniciarStream();
}

async function obtenerMensajes(){
  if (!hidRoom.value) return;
  const qs = new URLSearchParams({ codConChat: hidRoom.value, lastId });
  const res = await fetch(`${BASE}/src/mensajes/obtener_mensajes.php?${qs}`);
  const data = await res.json();
  if (!data.exito) return;

  data.mensajes.forEach(m => appendMensaje(m));
}

function iniciarStream(){
  if (!hidRoom.value) return;
  if (evtSource) evtSource.close();

  evtSource = new EventSource(
    `${BASE}/src/mensajes/stream_mensajes.php?` +
    new URLSearchParams({ codConChat: hidRoom.value, lastId })
  );

  evtSource.onmessage = e => {
    JSON.parse(e.data).forEach(m => appendMensaje(m));
  };
  evtSource.onerror = () => {
    evtSource.close();
    setTimeout(iniciarStream, 3000);
  };
}

function appendMensaje(m){
     console.log('mensaje', m.codMen, 'corUsuRemMen=', m.corUsuRemMen);
  if (m.codMen <= lastId) return;
  const li = document.createElement('li');
  li.className = m.corUsuRemMen ? 'msg-otro' : 'msg-mio';

  li.innerHTML = `
    <div class="msg-contenido">
      <p>${m.mensajeMen}</p>
    </div>
    <div class="msg-time">
      ${new Date(m.fechaEnvioMen).toLocaleTimeString([], {
        hour: '2-digit', minute: '2-digit'
      })}
    </div>`;
  ulMsgs.appendChild(li);
  lastId = m.codMen;
  ulMsgs.scrollTop = ulMsgs.scrollHeight;
}

async function enviarMensaje(){
  const txt = inpTxt.value.trim();
  if (!txt || !hidRoom.value) return;
  inpTxt.value = '';
  await fetch(`${BASE}/src/mensajes/guardar_mensaje.php`, {
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body: JSON.stringify({ codConChat: hidRoom.value, msg: txt })
  });
  await obtenerMensajes();
}

function cerrarChat(){
  if (evtSource) evtSource.close();
  wrapper.classList.remove('open-chat');
}
