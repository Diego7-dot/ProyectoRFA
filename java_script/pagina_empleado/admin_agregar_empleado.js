/* KM = JS de inicio administrador */

document.addEventListener("DOMContentLoaded", () => {
  // KM = Ocultar modales al iniciar
  const modalesOcultar = ['modal-ayuda', 'modal-manuales'];
  modalesOcultar.forEach(id => {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'none';
  });

  // KM = Movimiento del menú desplegable
  const userMenu = document.getElementById('user-menu');
  window.toggleUserMenu = function () {
    if (userMenu) userMenu.classList.toggle('active');
  };

  // KM = Activar secciones al hacer clic en el menú secundario
  document.querySelectorAll('.menu_secundario .menu-item').forEach(item => {
    item.addEventListener('click', function () {
      document.querySelectorAll('.menu_secundario .menu-item').forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
      const section = this.getAttribute('data-section');
      if (section) {
        loadSection(section);
        window.location.hash = section;
      }
    });
  });

  // KM = Abrir y cerrar modal de Ayuda
  const abrirSoporte = document.getElementById('abrir-soporte');
  const modalAyuda = document.getElementById('modal-ayuda');
  const cerrarAyuda = document.getElementById('cerrar-modal');

  if (abrirSoporte && modalAyuda && cerrarAyuda) {
    abrirSoporte.addEventListener('click', e => {
      e.preventDefault();
      modalAyuda.style.display = 'block';
    });
    cerrarAyuda.addEventListener('click', () => modalAyuda.style.display = 'none');
    window.addEventListener('click', e => {
      if (e.target === modalAyuda) modalAyuda.style.display = 'none';
    });
  }

  // KM = Abrir y cerrar modal de Manuales
  const abrirManuales = document.getElementById('abrir-manuales');
  const modalManuales = document.getElementById('modal-manuales');
  const cerrarManuales = document.getElementById('cerrar-modal-manuales');

  if (abrirManuales && modalManuales && cerrarManuales) {
    abrirManuales.addEventListener('click', e => {
      e.preventDefault();
      modalManuales.style.display = 'block';
    });
    cerrarManuales.addEventListener('click', () => modalManuales.style.display = 'none');
    window.addEventListener('click', e => {
      if (e.target === modalManuales) modalManuales.style.display = 'none';
    });
  }

  // KM = Botones para abrir los manuales
  const btnUsuario = document.getElementById('btn-usuario');
  const btnTecnico = document.getElementById('btn-tecnico');
  if (btnUsuario) {
    btnUsuario.addEventListener('click', () => {
      window.open('../../manuales/manual_usuario.pdf', '_blank');
    });
  }
  if (btnTecnico) {
    btnTecnico.addEventListener('click', () => {
      window.open('../../manuales/manual_tecnico.pdf', '_blank');
    });
  }

  // KM = Modales: editar, agregar, subir Excel, descargar PDF
  const modalEdit = document.getElementById('modal-edit');
  const modalAdd = document.getElementById('modal-agregar-persona');
  const modalUpload = document.getElementById('modal-upload');
  const modalDownload = document.getElementById('modal-download');

  const btnCancelEdit = document.getElementById('btnCancelEdit');
  const btnCancelAdd = document.getElementById('btnCancelAdd');
  const cancelUpload = document.getElementById('cancelUpload');
  const cancelDownload = document.getElementById('btnCancelDownload');

  if (btnCancelEdit && modalEdit) btnCancelEdit.addEventListener('click', () => modalEdit.classList.remove('active'));
  if (btnCancelAdd && modalAdd) btnCancelAdd.addEventListener('click', () => modalAdd.style.display = 'none');
  if (cancelUpload && modalUpload) cancelUpload.addEventListener('click', () => modalUpload.style.display = 'none');
  if (cancelDownload && modalDownload) cancelDownload.addEventListener('click', () => modalDownload.style.display = 'none');

  document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', () => {
      if (modalEdit) modalEdit.classList.add('active');
    });
  });

  // KM = Cerrar modal de edición y limpiar errores si se cancela
  document.getElementById("btnCancelEdit").addEventListener("click", () => {
    document.getElementById("modal-edit").classList.remove("active");
    limpiarFormularioEdicion();
  });

  document.querySelectorAll('.btn-add').forEach(button => {
    button.addEventListener('click', () => {
      if (modalAdd) modalAdd.style.display = 'flex';
    });
  });

  document.querySelectorAll('.btn-upload').forEach(button => {
    button.addEventListener('click', () => {
      if (modalUpload) modalUpload.style.display = 'flex';
    });
  });

  document.querySelectorAll('.btn-download').forEach(button => {
    button.addEventListener('click', () => {
      if (modalDownload) modalDownload.style.display = 'flex';
    });
  });

  // KM = Limpiar con los botones de registro
  document.getElementById("btnCancelAdd").addEventListener("click", () => {
    document.getElementById("modal-agregar-persona").style.display = "none";

    // KM = Limpiar mensaje de error
    const errorMsg = document.getElementById("mensaje-error-add");
    errorMsg.textContent = '';
    errorMsg.style.display = 'none';

    // KM = Limpiar campos de contraseña
    document.getElementById("claveNuevo").value = '';
    document.getElementById("confirmarClaveNuevo").value = '';
  });

  // KM = Validar solo números
  function soloNumeros(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
      input.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '');
      });
    }
  }
  soloNumeros('documento');
  soloNumeros('telefono');
  soloNumeros('codigoCiudad');

  const telefono = document.getElementById('telefono');
  if (telefono) {
    telefono.addEventListener('input', function () {
      if (this.value.length > 10) this.value = this.value.slice(0, 10);
    });
  }

  // KM = Validar edad mínima
  const fechaNacimiento = document.getElementById('fechaNacimiento_add');
  if (fechaNacimiento) {
    fechaNacimiento.addEventListener('change', function () {
      const fecha = new Date(this.value);
      const hoy = new Date();
      let edad = hoy.getFullYear() - fecha.getFullYear();
      const mes = hoy.getMonth() - fecha.getMonth();
      if (mes < 0 || (mes === 0 && hoy.getDate() < fecha.getDate())) edad--;
      if (edad < 18) {
        alert('No se puede registrar alguien menor de edad.');
        this.value = '';
      }
    });
  }

  // KM = Modo oscuro
  const btnModo = document.getElementById('modo-toggle');
  const iconoModo = document.getElementById('icono-modo');
  if (btnModo && iconoModo) {
    btnModo.addEventListener('click', () => {
      document.body.classList.toggle('dark-mode');
      const activo = document.body.classList.contains('dark-mode');
      iconoModo.src = activo ? "../../iconos/pagina_principal/sol.png" : "../../iconos/pagina_principal/luna.png";
      iconoModo.alt = activo ? "Modo claro" : "Modo oscuro";
    });
  }

  // KM = Botón scroll top
  const scrollTopBtn = document.getElementById('btnScrollTop');
  if (scrollTopBtn) {
    window.addEventListener('scroll', () => {
      scrollTopBtn.classList.toggle('visible', window.scrollY > 300);
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
  }
});

// KM = Luego de editar actuliza la tabla 
function activarBotonesEditar() {
  document.querySelectorAll(".btn-edit").forEach(btn => {
    btn.addEventListener("click", () => {
      const doc = btn.dataset.doc;
      // 
      fetch(`../../src/insert_pa/importar_personas_apoyo.php?docApo=${doc}`)
        .then(res => res.json())
        .then(data => {
          if (data.error) {
            alert("Error: " + data.error);
            return;
          }
          document.getElementById('modal-edit').classList.add('active');
        });
    });
  });
}

// KM = Sera que por fin me dejara de votar error? jaja 
function setValue(id, valor) {
  const el = document.getElementById(id);
  if (el) el.value = valor ?? '';
}

// KM = Evento delegado: escucha clics en todo el documento para botones .btn-edit
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('btn-edit')) {
    const doc = e.target.dataset.doc;

    fetch(`../../src/insert_pa/importar_personas_apoyo.php?docApo=${doc}`)
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          alert("Error: " + data.error);
          return;
        }

        // KM = Llenar campos simples con función auxiliar
        setValue('documento_edit', data.docApo);
        setValue('correo_edit', data.correo);
        setValue('nombre_edit', data.nombre);
        setValue('apellido_edit', data.apellido);
        setValue('fechaNacimiento_edit', data.fecNac);
        setValue('genero_edit', data.genero);
        setValue('telefono_edit', data.telefono);
        setValue('direccion_edit', data.direccion);
        setValue('tarjeta_edit', data.tarjeta);
        setValue('especialidad_edit', data.especialidad);
        setValue('contrasena_edit', '');
        setValue('estado_edit', data.estado == 1 ? '1' : '0');
        setValue('motivo_edit', data.motivoInactivo);

        // KM = Cargar ciudades y marcar la actual
        const ciudadSelect = document.getElementById('codigoCiudad_edit');
        ciudadSelect.innerHTML = '';
        data.ciudades.forEach(c => {
          const opt = document.createElement('option');
          opt.value = c.codCiu;
          opt.text = c.nomCiu;
          if (c.codCiu == data.codCiu) opt.selected = true;
          ciudadSelect.appendChild(opt);
        });

        // KM = Cargar roles y marcar el actual (sin administrador)
        const rolSelect = document.getElementById('rol_edit');
        rolSelect.innerHTML = '';
        data.roles.forEach(r => {
          const opt = document.createElement('option');
          opt.value = r.codRol;
          opt.text = r.nomRol;
          if (r.codRol == data.codRol) opt.selected = true;
          rolSelect.appendChild(opt);
        });

        // KM = Mostrar motivo si el estado es inactivo
        const motivoGroup = document.getElementById('motivo_estado_group');
        if (data.estado == 0) {
          motivoGroup.style.display = 'block';
        } else {
          motivoGroup.style.display = 'none';
          setValue('motivo_edit', '');
        }

        // KM = Restablece los campos de contraseña
        const nuevaPsw = document.getElementById("nuevaPsw");
        const confirmarPsw = document.getElementById("confirmarPsw");
        const seccion = document.getElementById("seccion-contrasena");
        const campoEstado = document.getElementById("seccionContrasenaActiva");
        if (nuevaPsw) nuevaPsw.value = "";
        if (confirmarPsw) confirmarPsw.value = "";
        if (seccion) seccion.style.display = "none";
        if (campoEstado) campoEstado.value = "0";

        // KM = Mostrar modal
        document.getElementById('modal-edit').classList.add('active');
      })
      .catch(err => {
        alert("Ocurrió un error cargando los datos.");
      });
  }
});

window.addEventListener("DOMContentLoaded", cargarPersonas);

document.getElementById("status-filter").addEventListener("change", cargarPersonas);
document.getElementById("rol-filter").addEventListener("change", cargarPersonas);
document.getElementById("btnSearch").addEventListener("click", cargarPersonas);

// KM = Variables globales para filtros y tabla
function cargarPersonas() {
  const tabla = document.getElementById("employee-table");
  const filtroEstado = document.getElementById("status-filter");
  const filtroRol = document.getElementById("rol-filter");
  const inputDocumento = document.getElementById("employee-search");

  if (!tabla || !filtroEstado || !filtroRol || !inputDocumento) return;

  const tbody = tabla.querySelector("tbody");
  if (!tbody) return;

  const estado = filtroEstado.value;
  const documento = inputDocumento.value.trim();
  const rol = filtroRol.value;

  const params = new URLSearchParams();
  if (estado !== "all") params.append("estado", estado);
  if (documento !== "") params.append("documento", documento);
  if (rol !== "todos") params.append("rol", rol);

  fetch(`../../src/insert_pa/listar_personas_apoyo.php?${params.toString()}`)
    .then(r => r.json())
    .then(data => {
      tbody.innerHTML = "";

      if (data.error) {
        tbody.innerHTML = `<tr><td colspan='14'> ${data.error}</td></tr>`;
        return;
      }

      if (!Array.isArray(data) || data.length === 0) {
        tbody.innerHTML = "<tr><td colspan='14'>No se encontraron resultados.</td></tr>";
        return;
      }

      data.forEach(p => {
        const fila = `
          <tr>
            <td><button class="btn-edit" data-doc="${p.docApo}">Modificar</button></td>
            <td><span class="status ${p.estadoApo == 1 ? 'active' : 'inactive'}">${p.estadoApo == 1 ? 'Activo' : 'Inactivo'}</span></td>
            <td>${p.docApo}</td>
            <td>${p.corApo}</td>
            <td>${p.nomApo}</td>
            <td>${p.apeApo}</td>
            <td>${p.fecNacApo}</td>
            <td>${p.genApo}</td>
            <td>${p.telApo}</td>
            <td>${p.dirApo}</td>
            <td>${p.nomCiu}</td>
            <td>${p.numTarProApo}</td>
            <td>${p.espProApo}</td>
            <td>********</td>
          </tr>`;
        tbody.insertAdjacentHTML("beforeend", fila);
      });
    })
    .catch(err => {
      tbody.innerHTML = "<tr><td colspan='14'>Error al cargar los datos.</td></tr>";
    });
}

// KM = Limpiar búsqueda por documento al cambiar el filtro de estado o rol
document.getElementById("status-filter").addEventListener("change", () => {
  document.getElementById("employee-search").value = "";
  cargarPersonas(); // KM = Actualiza la tabla con el nuevo filtro
});

document.getElementById("rol-filter").addEventListener("change", () => {
  document.getElementById("employee-search").value = "";
  cargarPersonas(); // KM = Actualiza la tabla con el nuevo filtro
});

// KM = Activar el morivo si se inhabilita una persona de apoyo
document.addEventListener('DOMContentLoaded', function () {
  const estadoSelect = document.getElementById('estado_edit');
  const grupoMotivo = document.getElementById('motivo_estado_group');

  // KM = Mostrar el campo de motivo solo si el estado es inactivo
  estadoSelect.addEventListener('change', function () {
    if (estadoSelect.value === "0") {
      grupoMotivo.style.display = 'block';
    } else {
      grupoMotivo.style.display = 'none';
    }
  });
});

// KM = Que JS mas largo, ya sueltame que me estas lastimando PD: Mostrar los compos de la contraseña en editar
function mostrarCamposContrasena() {
  const seccion = document.getElementById("seccion-contrasena");
  const boton = document.getElementById("btnContrasena");
  const campoEstado = document.getElementById("seccionContrasenaActiva");

  if (seccion.style.display === "none" || seccion.style.display === "") {
    seccion.style.display = "block";
    boton.textContent = "Cancelar cambio de contraseña";
    campoEstado.value = "1";
  } else {
    seccion.style.display = "none";
    boton.textContent = "Cambiar contraseña";
    campoEstado.value = "0";
    document.getElementById("nuevaPsw").value = "";
    document.getElementById("confirmarPsw").value = "";
  }
}

// Cambiar entre mostrar y no mostrar la contraseña
const togglePassword = document.getElementById("togglePasswordIcon");
const nuevaPsw = document.getElementById("nuevaPsw");

if (togglePassword && nuevaPsw) {
  togglePassword.addEventListener("click", () => {
    nuevaPsw.type = nuevaPsw.type === "password" ? "text" : "password";
    togglePassword.src = nuevaPsw.type === "password"
      ? "../../iconos/pagina_principal/ojo_cerrado.png"
      : "../../iconos/pagina_principal/ojo_abierto.png";
  });
}

const toggleVerify = document.getElementById("toggleVerifyPasswordIcon");
const confirmarPsw = document.getElementById("confirmarPsw");

if (toggleVerify && confirmarPsw) {
  toggleVerify.addEventListener("click", () => {
    confirmarPsw.type = confirmarPsw.type === "password" ? "text" : "password";
    toggleVerify.src = confirmarPsw.type === "password"
      ? "../../iconos/pagina_principal/ojo_cerrado.png"
      : "../../iconos/pagina_principal/ojo_abierto.png";
  });
}

// KM = Mostrar/ocultar contraseña en el formulario de registro
document.getElementById("togglePasswordNuevoIcon").addEventListener("click", function () {
  const input = document.getElementById("claveNuevo");
  input.type = input.type === "password" ? "text" : "password";
  this.src = input.type === "password"
    ? "../../iconos/pagina_principal/ojo_cerrado.png"
    : "../../iconos/pagina_principal/ojo_abierto.png";
});

document.getElementById("toggleVerifyNuevoIcon").addEventListener("click", function () {
  const input = document.getElementById("confirmarClaveNuevo");
  input.type = input.type === "password" ? "text" : "password";
  this.src = input.type === "password"
    ? "../../iconos/pagina_principal/ojo_cerrado.png"
    : "../../iconos/pagina_principal/ojo_abierto.png";
});

document.getElementById("formulario-nuevo-registro").addEventListener("submit", function (e) {
  const clave = document.getElementById("claveNuevo").value.trim();
  const confirmar = document.getElementById("confirmarClaveNuevo").value.trim();

  if (clave.length < 6 || clave.length > 250 || confirmar.length < 6 || confirmar.length > 250) {
    e.preventDefault();
    mostrarErrorRegistro("La contraseña debe tener entre 6 y 250 caracteres.");
    return;
  }

  if (clave !== confirmar) {
    e.preventDefault();
    mostrarErrorRegistro("Las contraseñas no coinciden.");
  }
});

// KM = Mostrar errores a registrar a persona de apoyo
function mostrarErrorRegistro(msg) {
  const errorDiv = document.getElementById("mensaje-error-add");
  errorDiv.textContent = msg;
  errorDiv.style.display = "block";
}

// KM = Función para cargar datos en el modal de edición
function cargarDatosEnModal(datos) {

  // KM = Asignar valores al formulario
  document.getElementById("documento_edit").value = datos.docApo;
  document.getElementById("correo_edit").value = datos.correo;
  document.getElementById("nombre_edit").value = datos.nombre;
  document.getElementById("apellido_edit").value = datos.apellido;
  document.getElementById("fechaNacimiento_edit").value = datos.fecNac;
  document.getElementById("genero_edit").value = datos.genero;
  document.getElementById("telefono_edit").value = datos.telefono;
  document.getElementById("direccion_edit").value = datos.direccion;
  document.getElementById("codigoCiudad_edit").value = datos.codCiu;
  document.getElementById("rol_edit").value = datos.codRol;
  document.getElementById("tarjeta_edit").value = datos.tarjeta;
  document.getElementById("especialidad_edit").value = datos.especialidad;
  document.getElementById("estado_edit").value = datos.estado;
  document.getElementById("motivo_edit").value = "";

  // KM = Restablecer campos de contraseña
  document.getElementById("nuevaPsw").value = "";
  document.getElementById("confirmarPsw").value = "";
  document.getElementById("seccion-contrasena").style.display = "none";
  document.getElementById("seccionContrasenaActiva").value = "0";

  // KM = Mostrar el modal
  const modal = document.getElementById("modal-edit");
  if (modal) modal.classList.add("active");
}

// KM = Enviar datos del formulario de edición con validación
document.getElementById("employee-form-edit").addEventListener("submit", function (e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);
  const errores = [];

  // KM = Captura de datos
  const doc = formData.get("documento_edit")?.trim(); // Solo lectura, pero aún se envía
  const correo = formData.get("correo_edit")?.trim();
  const nombre = formData.get("nombre_edit")?.trim();
  const apellido = formData.get("apellido_edit")?.trim();
  const fechaNac = formData.get("fechaNacimiento_edit");
  const genero = formData.get("genero_edit");
  const telefono = formData.get("telefono_edit")?.trim();
  const direccion = formData.get("direccion_edit")?.trim();
  const tarjeta = formData.get("tarjeta_edit")?.trim();
  const especialidad = formData.get("especialidad_edit")?.trim();
  const estado = formData.get("estado_edit");
  const motivo = formData.get("motivo_edit")?.trim();
  const cambiarPass = formData.get("seccionContrasenaActiva") === "1";
  const pass = formData.get("nuevaPsw")?.trim();
  const pass2 = formData.get("confirmarPsw")?.trim();

  // KM = Validaciones
  if (!/^\d{6,11}$/.test(doc)) errores.push("🆔 Documento debe tener entre 6 y 11 números.");
  if (!correo || correo.length > 100 || !correo.includes("@")) errores.push("📧 Correo inválido o muy largo (máx. 100).");
  if (!nombre || nombre.length > 100) errores.push("👤 Nombre obligatorio (máx. 100).");
  if (!apellido || apellido.length > 100) errores.push("👤 Apellido obligatorio (máx. 100).");
  if (!fechaNac) errores.push("📅 Fecha de nacimiento obligatoria.");

  const hoy = new Date();
  const fnac = new Date(fechaNac);
  const edad = hoy.getFullYear() - fnac.getFullYear();
  if (isNaN(edad) || edad < 18 || edad > 80) errores.push("🎂 Edad inválida (debe ser entre 18 y 80 años).");

  if (!['F', 'M', 'O'].includes(genero)) errores.push("🚻 Género inválido.");
  if (!/^\d{7,11}$/.test(telefono)) errores.push("📱 Teléfono debe tener entre 7 y 11 números.");
  if (!direccion || direccion.length > 30) errores.push("🏠 Dirección obligatoria (máx. 30).");
  if (tarjeta.length > 11) errores.push("📄 Tarjeta profesional muy larga (máx. 11).");
  if (especialidad.length > 100) errores.push("💼 Especialidad muy larga (máx. 100).");

  if (estado === "0" && motivo.length < 4) errores.push("📝 Motivo de inactivación requerido (mínimo 4 caracteres).");

  if (cambiarPass) {
    if (pass.length < 6) errores.push("🔒 Contraseña muy corta (mínimo 6 caracteres).");
    if (pass !== pass2) errores.push("❌ Las contraseñas no coinciden.");
  }

  if (errores.length > 0) {
    mostrarError(errores.join("\n"));
    Swal.fire({
      icon: "warning",
      title: "Campos inválidos 😥",
      html: errores.join("<br>"),
      confirmButtonText: "Corregir"
    });
    return;
  }

  // KM = Enviar al backend
  fetch('../../src/insert_pa/editar_personas_apoyo.php', {
    method: 'POST',
    body: formData
  })
    .then(async res => {
      const text = await res.text();
      try {
        const json = JSON.parse(text);
        if (json.success) {
          Swal.fire({
            icon: 'success',
            title: 'Actualizado 💜',
            text: 'Los datos fueron modificados exitosamente',
            timer: 2000,
            showConfirmButton: false
          });
          limpiarFormularioEdicion();
          document.getElementById("modal-edit").classList.remove("active");
          setTimeout(() => cargarPersonas(), 500);
        } else {
          Swal.fire("Error 🥺", json.error || "Error inesperado", "error");
        }
      } catch (e) {
        Swal.fire("Ups", "No se pudo actualizar. Intenta de nuevo", "error");
      }
    });
});

// KM = Limpiar errores y campos de contraseña al cerrar modal
function limpiarFormularioEdicion() {
  const mensaje = document.getElementById("mensaje-error-edit");
  if (mensaje) {
    mensaje.textContent = "";
    mensaje.style.display = "none";
  }

  // KM = Limpiar campos de contraseña y ocultar sección
  document.getElementById("nuevaPsw").value = "";
  document.getElementById("confirmarPsw").value = "";
  document.getElementById("seccion-contrasena").style.display = "none";
  document.getElementById("seccionContrasenaActiva").value = "0";
}

// KM = Mostrar mensaje de error debajo del formulario
function mostrarError(msg) {
  const mensaje = document.getElementById("mensaje-error-edit");
  mensaje.textContent = msg;
  mensaje.style.display = "block";
}

document.querySelectorAll(".btn-edit").forEach(btn => {
  btn.addEventListener("click", () => {
    const mensaje = document.getElementById("mensaje-error-edit");
    if (mensaje) mensaje.style.display = "none";
  });
});

document.getElementById("btnSearch").addEventListener("click", cargarPersonas);
document.getElementById("status-filter").addEventListener("change", cargarPersonas);
cargarPersonas(); // KM = Cargar al inicio

document.getElementById("btn-open-add").addEventListener("click", async () => {
  const modal = document.getElementById("modal-agregar-persona");
  if (!modal) return;

  modal.style.display = "flex";
  document.getElementById("formulario-nuevo-registro").reset();

  try {
    const res = await fetch("../../src/insert_pa/listar_ciudades_roles.php");
    const data = await res.json();

    if (data.error) {
      alert("Error al cargar datos: " + data.error);
      return;
    }

    // KM = Cargar ciudades
    const selectCiudad = document.getElementById("ciudadNuevo");
    selectCiudad.innerHTML = "";

    // KM = Opcion por defecto
    const opcionCiudad = document.createElement("option");
    opcionCiudad.value = "";
    opcionCiudad.disabled = true;
    opcionCiudad.selected = true;
    opcionCiudad.textContent = "Seleccione una ciudad";
    selectCiudad.appendChild(opcionCiudad);

    // KM = Ciudades desde la bd
    data.ciudades.forEach(c => {
      const opt = document.createElement("option");
      opt.value = c.codCiu;
      opt.text = c.nomCiu;
      selectCiudad.appendChild(opt);
    });

    // KM = Cargar roles (excluyendo "Administrador")
    const selectRol = document.getElementById("rolNuevo");
    selectRol.innerHTML = "";

    // KM = Opcion por defecto
    const opcionRol = document.createElement("option");
    opcionRol.value = "";
    opcionRol.disabled = true;
    opcionRol.selected = true;
    opcionRol.textContent = "Seleccione un rol";
    selectRol.appendChild(opcionRol);

    data.roles
      .filter(r => r.nomRol !== "Administrador")
      .forEach(r => {
        const opt = document.createElement("option");
        opt.value = r.codRol;
        opt.text = r.nomRol;
        selectRol.appendChild(opt);
      });

  } catch (err) {
    alert("Ocurrió un error inesperado al cargar datos.");
  }
});

// KM = Enviar datos del formulario de agregar persona de apoyo
document.getElementById("formulario-nuevo-registro").addEventListener("submit", async function (e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);

  // Validar que todos los campos estén llenos
  for (const [key, value] of formData.entries()) {
    if (!value.trim()) {
      mostrarErrorAdd("Por favor completa todos los campos.");
      return;
    }
  }

  // KM = Validación de edad mínima
  const fechaNacimiento = new Date(formData.get("nacimientoNuevo"));
  if (isNaN(fechaNacimiento)) {
    mostrarErrorAdd("Por favor ingresa una fecha válida.");
    return;
  }

  const hoy = new Date();
  let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
  const mes = hoy.getMonth() - fechaNacimiento.getMonth();
  if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) edad--;
  if (edad < 18) {
    mostrarErrorAdd("La persona de apoyo debe ser mayor de edad.");
    return;
  }

  try {
    const res = await fetch("../../src/insert_pa/registro_persona_apoyo.php", {
      method: "POST",
      body: formData
    });

    const textoPlano = await res.text(); // KM = Casi me mato por esto jaja (Leer el texto plano)
    let result = null;

    try {
      result = JSON.parse(textoPlano); // KM = Intentamos parsear (Carlos?)
    } catch (jsonError) {
      console.error("Respuesta no es JSON válido:", textoPlano); // KM = No es lo mejor dejar consola, pero siento que esta es esencial
      mostrarErrorAdd("Ocurrió un error inesperado. Intenta más tarde.");
      return;
    }

    if (result.success) {
      Swal.fire({
        icon: 'success',
        title: '¡Registro exitoso! 💌',
        text: 'La persona de apoyo fue registrada y recibió su correo.',
        timer: 2500,
        showConfirmButton: false
      });

      document.getElementById("modal-agregar-persona").style.display = "none";
      cargarPersonas(); // recargar tabla
    } else {
      mostrarErrorAdd(result.error || "Error al registrar.");
    }

  } catch (err) {
    mostrarErrorAdd("No se pudo registrar. Intenta nuevamente.");
  }
});

// KM = Mostrar errores en formulario de agregar
function mostrarErrorAdd(msg) {
  const mensaje = document.getElementById("mensaje-error-add");
  mensaje.textContent = msg;
  mensaje.style.display = "block";
}

// KM = Bloquear envío directo del formulario si no pasa por JS
document.getElementById('form-upload-excel').addEventListener('submit', function (e) {
  const input = document.getElementById('archivoExcel');
  if (!input.files.length) {
    e.preventDefault();
    return false;
  }
});

// KM = Mostrar modal y abrir selector de archivos
document.querySelector('.btn-upload').addEventListener('click', () => {
  const modal = document.getElementById('modal-upload');
  modal.style.display = 'flex';
});

// KM = Ocultar modal
document.getElementById('cancelUpload').addEventListener('click', () => {
  document.getElementById('modal-upload').style.display = 'none';
  document.getElementById('nombre-archivo-cargado').textContent = 'Ninguno';
  document.getElementById('archivoExcel').value = ''; // Reset
});

// KM = Mostrar nombre del archivo
document.getElementById('archivoExcel').addEventListener('change', (e) => {
  const archivo = e.target.files[0];
  const nombreArchivo = archivo ? archivo.name : 'Ninguno';
  document.getElementById('nombre-archivo-cargado').textContent = nombreArchivo;
});

// KM = Validación final y envío
document.getElementById('confirmUpload').addEventListener('click', () => {
  const input = document.getElementById('archivoExcel');
  if (!input.files.length) {
    Swal.fire({
      icon: 'warning',
      title: 'Archivo no seleccionado',
      text: 'Por favor selecciona un archivo Excel antes de continuar.',
      confirmButtonColor: '#6A3D7B'
    });
    input.click();
    return;
  }

  const archivo = input.files[0];
  const tipoValido = [
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.ms-excel'
  ];

  if (!tipoValido.includes(archivo.type)) {
    Swal.fire({
      icon: 'error',
      title: 'Tipo de archivo inválido',
      text: 'Solo se permiten archivos .xlsx o .xls',
      confirmButtonColor: '#6A3D7B'
    });
    return;
  }

  // KM = Enviar formulario si todo está bien
  document.getElementById('form-upload-excel').submit();
});

// KM = SweetAlert de éxito o error según parámetros de URL
const params = new URLSearchParams(window.location.search);

if (params.get("importado") === "ok") {
  Swal.fire({
    icon: "success",
    title: "¡Importación exitosa! 💖",
    text: "Se registraron los datos y se enviaron los correos correctamente.",
    confirmButtonColor: "#6A3D7B"
  });
  history.replaceState(null, '', window.location.pathname);
}

if (params.get("error") === "archivo") {
  Swal.fire({
    icon: "error",
    title: "Archivo inválido 😵",
    text: "Solo se permiten archivos Excel con extensión .xlsx o .xls.",
    confirmButtonColor: "#6A3D7B"
  });
  history.replaceState(null, '', window.location.pathname);
}

// KM = Mostrar mensaje si hay errores desde PHP (Momento de subir el excel)
const urlParams = new URLSearchParams(window.location.search);
const errores = urlParams.get('errores');
const importado = urlParams.get('importado');

if (importado === 'parcial' && errores) {
  Swal.fire({
    icon: 'warning',
    title: '❗Errores en el Excel',
    html: '<pre style="text-align:left; white-space:pre-wrap;">' + decodeURIComponent(errores) + '</pre>',
    confirmButtonText: 'Entendido',
    width: '40%',
    customClass: {
      popup: 'sweet-modal',
      title: 'swal-title',
      htmlContainer: 'swal-html',
      confirmButton: 'btn-confirmar'
    }
  });
} else if (importado === 'ok') {
  Swal.fire({
    icon: 'success',
    title: '¡Importación exitosa!',
    text: 'Todos los registros fueron agregados correctamente 💖',
    confirmButtonText: 'Continuar'
  });
} else if (urlParams.get('error') === 'archivo') {
  Swal.fire({
    icon: 'error',
    title: 'Archivo inválido',
    text: 'Solo se permiten archivos Excel (.xlsx o .xls)',
    confirmButtonText: 'Entendido'
  });
} else if (urlParams.get('error') === 'subida') {
  Swal.fire({
    icon: 'error',
    title: 'No se pudo cargar el archivo',
    text: 'Revisa que hayas seleccionado un archivo válido 📄',
    confirmButtonText: 'Intentar de nuevo'
  });
}

// KM = Mostrar errores si vienen desde PHP vía variable JS (protegido)
document.addEventListener("DOMContentLoaded", () => {
  // KM = Detectar si hay errores de importación cargados en variable de sesión convertida a JS
  const errores = window.erroresImportacion || [];

  if (Array.isArray(errores) && errores.length > 0) {
    Swal.fire({
      icon: 'warning',
      title: 'Importación parcial ⚠️',
      html: errores.map(e => `<div>${e}</div>`).join(''),
      width: 600,
      confirmButtonText: 'Entendido'
    });
  }
});

// KM = Solo numeros en editar telefono
document.getElementById("telefono_edit").addEventListener("input", function () {
  this.value = this.value.replace(/\D/g, '');
});

// KM = Solo numeros en registro telefono
document.getElementById("telefonoNuevo").addEventListener("input", function () {
  this.value = this.value.replace(/\D/g, '');
});

// KM = Lo mismo que el anterior pero para documento y en registro
document.getElementById("docNuevo").addEventListener("input", function () {
  this.value = this.value.replace(/\D/g, '');
});

// KM = En el filtro de documento solo se ingresan numeros 
document.getElementById("employee-search").addEventListener("input", function () {
  this.value = this.value.replace(/\D/g, '');
});