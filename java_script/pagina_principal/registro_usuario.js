// KM = JS del registro de usuarios

/* Nota: Este archivo contiene:
   - Validacion del formulario de registro
   - Alternar visibilidad de contraseñas
   - Mostrar mensajes de error y éxito
   - Modo oscuro (cambio de tema)
   - Boton de scroll hacia arriba 
*/

document.addEventListener('DOMContentLoaded', function () {
  const formulario = document.querySelector('form');
  const contraseña = document.getElementById('psw');
  const confirmarContraseña = document.getElementById('verConUsu');
  const campoCorreo = document.getElementById('corUsu');
  const fechaNacimiento = document.getElementById('fecNacUsu');
  const nombre = document.getElementById('nomUsu');
  const casillaAceptacion = document.getElementById('cbox1');
  const alternarContraseña = document.getElementById('togglePasswordIcon');
  const alternarConfirmarContraseña = document.getElementById('toggleVerifyPasswordIcon');
  const scrollTopBtn = document.getElementById('btnScrollTop');
  const iconoModo = document.getElementById('icono-modo');

  // KM = Validacion del correo
  campoCorreo.addEventListener('blur', function () {
    const correo = this.value.trim();
    const formatoCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!formatoCorreo.test(correo)) {
      mostrarError(this, "*Por favor ingresa un correo válido 🥺");
      return;
    }

    fetch('http://localhost/Proyecto_real_v1/Proyecto_real/src/auth/verificar_corUsu.php?correo=' + encodeURIComponent(correo))
      .then(res => res.json())
      .then(data => {
        if (data.existe) {
          mostrarError(this, "*Este correo ya está registrado 😢");
        }
      });
  });

  // KM = Validacion de campos vacios
  formulario.addEventListener('submit', function (evento) {
    limpiarMensajesDeError();
    let esValido = true;

    if (!campoCorreo.value) {
      mostrarError(campoCorreo, "*Por favor, ingresa un correo.");
      esValido = false;
    }

    if (!nombre.value) {
      mostrarError(nombre, "*Por favor, ingresa tu nombre o alias.");
      esValido = false;
    }

    if (nombre.value.length > 100) {
      mostrarError(nombre, "*El nombre no debe superar los 100 caracteres.");
      esValido = false;
    }

    if (!fechaNacimiento.value) {
      mostrarError(fechaNacimiento, "*Selecciona tu fecha de nacimiento.");
      esValido = false;
    } else if (!esEdadValida(fechaNacimiento.value)) {
      esValido = false;
    }

    if (!contraseña.value) {
      mostrarError(contraseña, "*Ingresa una contraseña.");
      esValido = false;
    }

    if (!confirmarContraseña.value) {
      mostrarError(confirmarContraseña, "*Confirma tu contraseña.");
      esValido = false;
    }

    const generoSeleccionado = document.querySelector('input[name="genUsu"]:checked');
    if (!generoSeleccionado) {
      mostrarError(document.querySelector('.radio-group'), "*Selecciona tu género.");
      esValido = false;
    }

    const validacion = validarContraseña(contraseña.value);
    if (!validacion.valida) {
      mostrarError(contraseña, validacion.mensaje);
      esValido = false;
    }

    if (contraseña.value !== confirmarContraseña.value) {
      mostrarError(confirmarContraseña, "*Las contraseñas no coinciden 😔");
      esValido = false;
    }

    if (!casillaAceptacion.checked) {
      mostrarError(casillaAceptacion, "*Debes aceptar los términos 😊");
      esValido = false;
    }

    if (!esValido) evento.preventDefault();
  });

  // KM = Funcion para los mensajes de error
  function mostrarError(campo, mensaje) {
    const errorExistente = campo.parentNode.querySelector('.error-message');
    if (errorExistente) errorExistente.remove();

    const mensajeError = document.createElement('small');
    mensajeError.classList.add('error-message');
    mensajeError.textContent = mensaje;

    if (campo.type === "checkbox" || campo.classList.contains("radio-group")) {
      campo.parentNode.appendChild(mensajeError);
    } else {
      campo.parentNode.insertBefore(mensajeError, campo.nextSibling);
    }
  }

  // KM = Funcion para los mensajes de error
  function quitarError(campo) {
    const error = campo.parentNode.querySelector('.error-message');
    if (error) error.remove();
  }

  // KM = Limpiar los mensajes
  function limpiarMensajesDeError() {
    document.querySelectorAll('.error-message').forEach(e => e.remove());
  }

  // KM = Validacion de la edad
  function esEdadValida(fecha) {
    const hoy = new Date();
    const fechaNac = new Date(fecha);
    let edad = hoy.getFullYear() - fechaNac.getFullYear();
    const m = hoy.getMonth() - fechaNac.getMonth();
    if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) edad--;

    if (edad < 18) {
      mostrarError(fechaNacimiento, "*Debes tener al menos 18 años 😔");
      return false;
    }
    if (edad > 140) {
      mostrarError(fechaNacimiento, "*Edad no válida, ¿eres inmortal? 😅");
      return false;
    }
    return true;
  }

  // Validacion de contraseña
  function validarContraseña(pass) {
    if (pass.length < 8) return { valida: false, mensaje: "*Mínimo 8 caracteres" };
    if (pass.length > 20) return { valida: false, mensaje: "*Máximo 20 caracteres" };
    const numeros = pass.match(/\d/g) || [];
    if (numeros.length < 1 || numeros.length > 10) return { valida: false, mensaje: "*Entre 1 y 10 números" };
    if (/[^A-Za-z0-9 ]/.test(pass)) return { valida: false, mensaje: "*No se permiten símbolos especiales" };
    return { valida: true, mensaje: "" };
  }

  // KM = Cambiar los entre ver y no mostrar la contraeña 
  if (alternarContraseña) {
    alternarContraseña.addEventListener('click', () => {
      const tipo = contraseña.type === 'password' ? 'text' : 'password';
      contraseña.type = tipo;
      alternarContraseña.src = tipo === 'password'
        ? '../../iconos/pagina_principal/ojo_cerrado.png'
        : '../../iconos/pagina_principal/ojo_abierto.png';
    });
  }

  if (alternarConfirmarContraseña) {
    alternarConfirmarContraseña.addEventListener('click', () => {
      const tipo = confirmarContraseña.type === 'password' ? 'text' : 'password';
      confirmarContraseña.type = tipo;
      alternarConfirmarContraseña.src = tipo === 'password'
        ? '../../iconos/pagina_principal/ojo_cerrado.png'
        : '../../iconos/pagina_principal/ojo_abierto.png';
    });
  }

  // KM = Eliminar los errores al corregir los campos
  [campoCorreo, nombre, fechaNacimiento, contraseña, confirmarContraseña].forEach(campo => {
    campo.addEventListener('input', () => quitarError(campo));
  });

  // KM = Eliminar los errores al cambiar genero
  const radiosGenero = document.querySelectorAll('input[name="genUsu"]');
  radiosGenero.forEach(radio => {
    radio.addEventListener('change', () => {
      const grupo = document.querySelector('.radio-group');
      quitarError(grupo);
    });
  });

  // KM = Eliminar error terminos y condiciones
  casillaAceptacion.addEventListener('change', () => quitarError(casillaAceptacion));

  // KM = Mensaje de registro exitoso
  if (localStorage.getItem('registro_exitoso') === '1') {
    const mensaje = document.getElementById('mensaje-exito');
    if (mensaje) {
      mensaje.style.display = 'block';
      setTimeout(() => {
        mensaje.style.display = 'none';
        localStorage.removeItem('registro_exitoso');
      }, 4000);
    }
  }

  // KM = Boton para subir del formulario
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

  // KM = Para alternar entre modo oscuro
  if (iconoModo) {
    document.getElementById('modo-toggle').addEventListener('click', () => {
      document.body.classList.toggle('dark-mode');
      const oscuro = document.body.classList.contains("dark-mode");
      iconoModo.src = oscuro
        ? "../../iconos/pagina_principal/sol.png"
        : "../../iconos/pagina_principal/luna.png";
      iconoModo.alt = oscuro ? "Modo claro" : "Modo oscuro";
    });
  }
});
