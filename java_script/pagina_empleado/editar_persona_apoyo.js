// KM = Js de editar usuarios

document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById("formEditar");
    const nombre = document.getElementById("nomUsu");
    const fechaNacimiento = document.getElementById("fecNacUsu");
    const nuevaPsw = document.getElementById("nuevaPsw");
    const confirmarPsw = document.getElementById("confirmarPsw");

    formulario.addEventListener("submit", function (e) {
        console.log("Formulario a enviar a:", formulario.action);
        limpiarMensajesDeError();
        let esValido = true;

        // KM = Validar nombre
        if (!nombre.value.trim()) {
            mostrarError(nombre, "*El nombre no puede estar vacío.");
            esValido = false;
        }

        if (nombre.value.length > 100) {
            mostrarError(nombre, "*El nombre no debe superar los 100 caracteres.");
            esValido = false;
        }

        // KM = Validar fecha de nacimiento
        if (!fechaNacimiento.value.trim()) {
            mostrarError(fechaNacimiento, "*Selecciona tu fecha de nacimiento.");
            esValido = false;
        } else if (!esEdadValida(fechaNacimiento.value, fechaNacimiento)) {
            esValido = false;
        }

        // KM = Validar contraseña
        const seccion = document.getElementById("seccion-contrasena");
        if (seccion && seccion.style.display !== "none") {
            if (!nuevaPsw.value || !confirmarPsw.value) {
                mostrarError(nuevaPsw, "*Ingresa y confirma la nueva contraseña.");
                esValido = false;
            } else {
                const validacion = validarContraseña(nuevaPsw.value);
                if (!validacion.valida) {
                    mostrarError(nuevaPsw, validacion.mensaje);
                    esValido = false;
                }
                if (nuevaPsw.value !== confirmarPsw.value) {
                    mostrarError(confirmarPsw, "*Las contraseñas no coinciden.");
                    esValido = false;
                }
            }
        }

        if (!esValido) e.preventDefault();
    });

    // KM = Validad edad, no mayor de 18 y no mayor de 120 años jaja (Cuales es la edad de la persona mas longeva actualmente?)
    function esEdadValida(fecha, campo) {
        const hoy = new Date();
        const fechaNac = new Date(fecha);
        let edad = hoy.getFullYear() - fechaNac.getFullYear();
        const m = hoy.getMonth() - fechaNac.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) edad--;

        if (edad < 18) {
            mostrarError(campo, "*Debes tener al menos 18 años.");
            return false;
        }
        if (edad > 120) {
            mostrarError(campo, "*Edad no válida 😳");
            return false;
        }
        return true;
    }

    // KM = Validar contraseña
    function validarContraseña(pass) {
        if (pass.length < 8) return { valida: false, mensaje: "*Mínimo 8 caracteres." };
        if (pass.length > 20) return { valida: false, mensaje: "*Máximo 20 caracteres." };
        if (!/\d/.test(pass)) return { valida: false, mensaje: "*Debe contener al menos 1 número." };
        if (/[^A-Za-z0-9]/.test(pass)) return { valida: false, mensaje: "*Sin caracteres especiales." };
        return { valida: true, mensaje: "" };
    }

    // KM = Funcion para los mensajes de error
    function mostrarError(campo, mensaje) {
        const errorExistente = campo.parentNode.querySelector('.error-message');
        if (errorExistente) errorExistente.remove();

        const mensajeError = document.createElement('small');
        mensajeError.classList.add('error-message');
        mensajeError.textContent = mensaje;

        campo.parentNode.appendChild(mensajeError);
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

    /* KM = Modo oscuro */
    const btnModo = document.getElementById('modo-toggle');
    const iconoModo = document.getElementById('icono-modo');

    if (btnModo && iconoModo) {
        btnModo.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const modoOscuroActivo = document.body.classList.contains('dark-mode');
            iconoModo.src = modoOscuroActivo
                ? "../../iconos/pagina_principal/sol.png"
                : "../../iconos/pagina_principal/luna.png";
            iconoModo.alt = modoOscuroActivo ? "Modo claro" : "Modo oscuro";
        });
    }

    /* KM = Botón scroll top */
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


    // KM = Mostrar/ocultar contraseña nueva
    const togglePassword = document.getElementById("togglePasswordIcon");
    if (togglePassword && nuevaPsw) {
        togglePassword.addEventListener("click", () => {
            nuevaPsw.type = nuevaPsw.type === "password" ? "text" : "password";
            togglePassword.src = nuevaPsw.type === "password"
                ? "../../iconos/pagina_principal/ojo_cerrado.png"
                : "../../iconos/pagina_principal/ojo_abierto.png";
        });
    }

    // KM = Mostrar/ocultar confirmación de contraseña
    const toggleVerify = document.getElementById("toggleVerifyPasswordIcon");
    if (toggleVerify && confirmarPsw) {
        toggleVerify.addEventListener("click", () => {
            confirmarPsw.type = confirmarPsw.type === "password" ? "text" : "password";
            toggleVerify.src = confirmarPsw.type === "password"
                ? "../../iconos/pagina_principal/ojo_cerrado.png"
                : "../../iconos/pagina_principal/ojo_abierto.png";
        });
    }
});

let imagenSeleccionada = "";

function abrirSelectorImagen() {
    document.getElementById("modalImagen").style.display = "block";

    // KM = Cambiar la imagen seleccionada
    document.querySelectorAll(".imagenes-opciones img").forEach(img =>
        img.classList.remove("imagen-seleccionada")
    );
}

function cerrarSelectorImagen() {
    document.getElementById("modalImagen").style.display = "none";
}

function seleccionarImagen(elemento, nombreArchivo) {
    imagenSeleccionada = nombreArchivo;

    // KM = Resaltar la imagen visualmente
    document.querySelectorAll(".imagenes-opciones img").forEach(img => {
        img.classList.remove("imagen-seleccionada");
    });
    elemento.classList.add("imagen-seleccionada")
}

function confirmarImagen() {
    if (imagenSeleccionada !== "") {
        document.getElementById("fotoPerfil").value = imagenSeleccionada;

        const imgPreview = document.getElementById("imagenActual");
        imgPreview.src = "/Proyecto_real_v1/Proyecto_real/iconos/iconos_perfil/" + imagenSeleccionada;

        cerrarSelectorImagen();
    }
}

// KM = Validar el boton de contrseña, que no permita enviar si esta abierto
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

        // Limpia campos por si acaso
        document.getElementById("nuevaPsw").value = "";
        document.getElementById("confirmarPsw").value = "";
    }
}

