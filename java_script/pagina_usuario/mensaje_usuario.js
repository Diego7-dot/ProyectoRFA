/* KM = Mensajes de usuario */

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

// KM = Chat de usuario
let roomId = null;  // KM = Id de la sala actual     
let lastId = 0; // KM = Ultimo mensaje recibido
let evtSource;

const BASE = '/Proyecto_real_v1/Proyecto_real';   // KM = Constante en la ruta

const ulMsgs = document.getElementById('chat-lista');
const inpTxt = document.getElementById('mensaje');
const btnEnv = document.getElementById('btn-enviar');

const docApoInp = document.getElementById('docApo');         // KM = Documento persona apoyo
const roomInp = document.getElementById('room-id');        // KM = Conexion a chat

// KM = Al cargar pagina: negociar sala y arrancar polling
document.addEventListener('DOMContentLoaded', async () => {
  await iniciarSala();
  await cargarAsesorAsignado();
  if (roomId) {
    btnEnv.disabled = inpTxt.disabled = false;
    await obtenerMensajes();
    iniciarStream();
  } else {
    mostrarAviso('No hay asesores disponibles en este momento 😢');
    btnEnv.disabled = inpTxt.disabled = true;
  }
});

// KM = Enviar mensaje
btnEnv.addEventListener('click', enviarMensaje);
inpTxt.addEventListener('keydown', e => {
  if (e.key === 'Enter') enviarMensaje();
});

// KM = Funciones

// KM = Inicar sale de chat con php
async function iniciarSala() {
  try {
    const res = await fetch(`${BASE}/src/mensajes/crear_conexion_chat.php`, { method: 'POST' });
    const raw = await res.text();
    const data = JSON.parse(raw);
    if (data.exito) {
      roomId = +data.codConChat;
      roomInp.value = roomId;
      docApoInp.value = data.docApo;
    } else {
      mostrarAviso(data.msg || 'No se pudo crear sala');
    }
  } catch (err) {
    console.error('Error en iniciarSala:', err);
  }
}

// KM = Enviar mensaje
async function enviarMensaje() {
  const texto = inpTxt.value.trim();
  if (!texto || !roomId) return;
  inpTxt.value = '';

  try {
    await fetch(`${BASE}/src/mensajes/guardar_mensaje.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ codConChat: roomId, msg: texto })
    });
  } catch (err) {
    console.error('Error enviando mensaje:', err);
  }
}

// KM = Obtener mensaje
async function obtenerMensajes() {
  if (!roomId) return;
  try {
    const qs = new URLSearchParams({ codConChat: roomId, lastId });
    const res = await fetch(`${BASE}/src/mensajes/obtener_mensajes.php?${qs}`);
    const raw = await res.text();
    const data = JSON.parse(raw);
    if (!data.exito) return;

    data.mensajes.forEach(m => {
      if (m.codMen <= lastId) return;
      const li = document.createElement('li');
      li.className = m.corUsuRemMen ? 'msg-mio' : 'msg-otro';
      li.innerHTML = `
  <div class="msg-contenido">
    <p>${m.mensajeMen}</p>
  </div>
  <div class="msg-time">${new Date(m.fechaEnvioMen)
          .toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
`;
      ulMsgs.appendChild(li);
      lastId = m.codMen;
    });

    ulMsgs.scrollTop = ulMsgs.scrollHeight;
  } catch (err) {
    console.error('Error en obtenerMensajes():', err);
  }
}

// KM = Iniciar chat 
function iniciarStream() {
  if (!roomId) return;

  if (evtSource) {
    evtSource.close();
    evtSource = null;
  }

  evtSource = new EventSource(
    `${BASE}/src/mensajes/stream_mensajes.php?` +
    new URLSearchParams({ codConChat: roomId, lastId })
  );

  evtSource.onmessage = e => {
    const nuevos = JSON.parse(e.data);
    nuevos.forEach(m => {
      const li = document.createElement('li');
      li.className = m.corUsuRemMen ? 'msg-mio' : 'msg-otro';
      li.innerHTML = `
        <div class="msg-contenido">
          <p>${m.mensajeMen}</p>
          <span class="msg-fecha">${m.hora}</span>
        </div>`;
      ulMsgs.appendChild(li);
      lastId = m.codMen;
    });
    ulMsgs.scrollTop = ulMsgs.scrollHeight;
  };

  evtSource.addEventListener('closed', e => {
    const info = JSON.parse(e.data);
    mostrarAviso(info.msg);

    evtSource.close();
    evtSource = null;

    roomId = null;
    lastId = 0;
    ulMsgs.innerHTML = '';
    btnEnv.disabled = inpTxt.disabled = true;

    setTimeout(async () => {
      mostrarAviso('Buscando un nuevo asesor…');
      await iniciarSala();
      if (roomId) {
        btnEnv.disabled = inpTxt.disabled = false;
        await obtenerMensajes();
        iniciarStream();
      } else {
        mostrarAviso('Todavía no hay asesores disponibles 😢');
      }
    }, 3000);
  });


  evtSource.onerror = () => {
    console.error('SSE error, reintentando conexión...');
    evtSource.close();
    evtSource = null;
    setTimeout(iniciarStream, 3000);
  };
}

// KM = Mostar mensaje
function mostrarAviso(txt) {
  const div = document.createElement('div');
  div.className = 'chat-aviso';
  div.textContent = txt;
  ulMsgs.appendChild(div);
  ulMsgs.scrollTop = ulMsgs.scrollHeight;
}

// KM = Funcion que pide tu asesor y lo pinta
async function cargarAsesorAsignado() {
  const sidebar = document.getElementById('sidebar-asesor');

  try {
    const res = await fetch(`${BASE}/src/mensajes/listas_asesores.php`);
    const json = await res.json();

    if (!json.exito) {
      sidebar.innerHTML = `<p>${json.msg}</p>`;
      return;
    }

    const a = json.asesor;
    sidebar.innerHTML = `
      <div class="asesor-item" data-doc-apo="${a.docApo}">
        <img src="${a.avatar}"
             class="asesor-avatar"
             alt="Avatar ${a.nombre}"
             onerror="this.src='${BASE}/iconos/iconos_perfil/icono_default_otro.php'">
        <div class="asesor-info">
          <strong class="asesor-nombre">${a.nombre}</strong>
          <small class="asesor-rol">${a.rol}</small>
        </div>
      </div>`;

    sidebar.querySelector('.asesor-item')
      .addEventListener('click', async () => {

        await iniciarSala({ docApo: a.docApo });
        btnEnv.disabled = inpTxt.disabled = false;
        await obtenerMensajes();
        iniciarStream();
        
        wrapper.classList.add('open-chat');
      });

  } catch (err) {
    console.error('Error cargando asesor:', err);
    sidebar.innerHTML = `<p>Error cargando asesor</p>`;
  }
}

//  KM = Modificacion en iniciar sala
async function iniciarSala(opts = {}) {
  try {
    const payload = opts.docApo
      ? { docApo: opts.docApo }
      : undefined;
    const res = await fetch(
      `${BASE}/src/mensajes/crear_conexion_chat.php`,
      {
        method: 'POST',
        headers: payload
          ? { 'Content-Type': 'application/json' }
          : undefined,
        body: payload
          ? JSON.stringify(payload)
          : undefined
      }
    );
    const raw = await res.text();
    const data = JSON.parse(raw);
    if (data.exito) {
      roomId = +data.codConChat;
      roomInp.value = roomId;
      docApoInp.value = data.docApo;
    } else {
      mostrarAviso(data.msg || 'No se pudo crear sala');
    }
  } catch (err) {
    console.error('Error en iniciarSala:', err);
  }
}

// KM = Boton de volver en movil
const wrapper = document.querySelector('.chat-wrapper');
const sidebar = document.getElementById('sidebar-asesor');
const btnBack  = document.getElementById('btn-back');

sidebar.addEventListener('click', async e => {
  const item = e.target.closest('.asesor-item');
  if (!item) return;

  // 1) negocia sala, carga mensajes, arranca SSE...
  await iniciarSala({ docApo: item.dataset.docApo });
  btnEnv.disabled = inpTxt.disabled = false;
  await obtenerMensajes();
  iniciarStream();

  wrapper.classList.add('open-chat');
});

// KM = Boton de volver version mobil
btnBack.addEventListener('click', () => {
  if (evtSource) evtSource.close(), evtSource = null;
  wrapper.classList.remove('open-chat');
});
