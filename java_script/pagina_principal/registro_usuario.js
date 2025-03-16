// JS del registro

document.addEventListener('DOMContentLoaded', function () {
  const formulario = document.querySelector('form');
  const contraseña = document.getElementById('psw');
  const confirmarContraseña = document.getElementById('verConUsu');
  const casillaAceptacion = document.getElementById('cbox1');
  const fechaNacimiento = document.getElementById('fecNacUsu');
  const alternarContraseña = document.getElementById('togglePasswordIcon');
  const alternarConfirmarContraseña = document.getElementById('toggleVerifyPasswordIcon');

  if (formulario) {
    formulario.addEventListener('submit', function (evento) {
      limpiarMensajesDeError();
      let esValido = true;
      
      //KM =  Validar que el usuario sea mayor de 18 años, se me olvido esto jaja ;()
      if (!esMayorDeEdad(fechaNacimiento.value)) {
        mostrarError(fechaNacimiento, "*Lo lamento, debes ser mayor de 18 años para registrarte.");
        esValido = false;
      }
      
      //KM = Validar que la contraseña cumpla con los minimos requisitos
      const validacion = validarContraseña(contraseña.value);
      if (!validacion.valida) {
        mostrarError(contraseña, validacion.mensaje);
        esValido = false;
      }

      //KM = Validar si las contraseñas coinciden (Luego se envian a BD)
      if (contraseña.value !== confirmarContraseña.value) {
        mostrarError(confirmarContraseña, "*Las contraseñas no coinciden 😔");
        esValido = false;
      }

      //KM = Validar si el checkbox esta marcado
      if (!casillaAceptacion.checked) {
        mostrarError(casillaAceptacion, "*Para continuar, debes aceptar los términos y condiciones 😊");
        esValido = false;
      }

      //KM = Si hay algun error, no mamite eh, no
      if (!esValido) {
        evento.preventDefault();
      }
    });
  }

  ///KM = Funcion para verificar si el usuario es mayor de edad (18 años)
  function esMayorDeEdad(fechaNacimiento) {
    const hoy = new Date();
    const fechaNac = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - fechaNac.getFullYear();
    const diferenciaMeses = hoy.getMonth() - fechaNac.getMonth();

    if (diferenciaMeses < 0 || (diferenciaMeses === 0 && hoy.getDate() < fechaNac.getDate())) {
      edad--;
    }

    return edad >= 18;
  }

  //KM = Funcion para validar la contraseña, cumpliendo las medidas que decidio el equipo
  function validarContraseña(contraseña) {
    if (contraseña.length < 8) {
      return { valida: false, mensaje: "*La contraseña debe tener al menos 8 caracteres." };
    }
    if (contraseña.length > 20) {
      return { valida: false, mensaje: "*La contraseña no puede tener más de 20 caracteres." };
    }

    const soloNumeros = contraseña.match(/\d/g) || [];
    if (soloNumeros.length < 1 || soloNumeros.length > 10) {
      return { valida: false, mensaje: "*La contraseña debe incluir al menos un número y no más de 10 números." };
    }

    const regexEspeciales = /[^A-Za-z0-9]/;
    if (regexEspeciales.test(contraseña)) {
      return { valida: false, mensaje: "*La contraseña no puede contener caracteres especiales." };
    }

    return { valida: true, mensaje: "" };
  }

  //KM = Funcion para mostrar el mensaje de error
  function mostrarError(campo, mensaje) {
    const mensajeError = document.createElement('small');
    mensajeError.classList.add('error-message');
    mensajeError.textContent = mensaje;
    
    if (campo.type === "checkbox") {
      campo.parentNode.appendChild(mensajeError);
    } else {
      campo.parentNode.insertBefore(mensajeError, campo.nextSibling);
    }
  }

  //KM = Borrar los mensajes previos
  function limpiarMensajesDeError() {
    document.querySelectorAll('.error-message').forEach(error => error.remove());
  }

  //Derechos reservados del icono a: https://icons8.com/icon/988/invisible

  // KM = Alternar visibilidad de la contraseña y cambiar icono
  if (alternarContraseña) {
    alternarContraseña.addEventListener('click', function () {
      const tipo = contraseña.type === 'password' ? 'text' : 'password';
      contraseña.type = tipo;
      alternarContraseña.src = tipo === 'password' ? '../../iconos/pagina_principal/ojo_cerrado.png' : '../../iconos/pagina_principal/ojo_abierto.png';
    });
  }

  if (alternarConfirmarContraseña) {
    alternarConfirmarContraseña.addEventListener('click', function () {
      const tipo = confirmarContraseña.type === 'password' ? 'text' : 'password';
      confirmarContraseña.type = tipo;
      alternarConfirmarContraseña.src = tipo === 'password' ? '../../iconos/pagina_principal/ojo_cerrado.png' : '../../iconos/pagina_principal/ojo_abierto.png';
    });
  }
});
