document.addEventListener("DOMContentLoaded", function () {
    const recuperarForm = document.getElementById("recuperar-form");
    const correoInput = document.getElementById("correo");
    const mensajeResultado = document.getElementById("mensaje-resultado");

    recuperarForm.addEventListener("submit", async function (event) {
        event.preventDefault(); // Evita el envío automático del formulario
        limpiarMensajesDeError();
        
        let correo = correoInput.value.trim();
        let esValido = true;

        // Verificar si el campo está vacío
        if (correo === "") {
            mostrarError(correoInput, "El correo es obligatorio.");
            esValido = false;
        }
        // Validar formato de correo
        else if (!validarCorreo(correo)) {
            mostrarError(correoInput, "Ingrese un correo válido.");
            esValido = false;
        }

        if (!esValido) return;

        // Simulación de validación con la base de datos
        try {
            const response = await fetch("/verificar-correo", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ correo })
            });

            const resultado = await response.json();

            if (resultado.existe) {
                mensajeResultado.textContent = "Se ha enviado un enlace de recuperación a tu correo.";
                mensajeResultado.classList.add("mensaje-exito");
            } else {
                mensajeResultado.textContent = "El correo ingresado no está registrado.";
                mensajeResultado.classList.add("mensaje-error");
            }
        } catch (error) {
            console.error("Error en la solicitud:", error);
            mensajeResultado.textContent = "Error de conexión con el servidor.";
            mensajeResultado.classList.add("mensaje-error");
        }
    });

    // Función para validar correo con expresión regular
    function validarCorreo(correo) {
        const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regexCorreo.test(correo);
    }

    // Función para mostrar mensaje de error
    function mostrarError(campo, mensaje) {
        const mensajeError = document.createElement("small");
        mensajeError.classList.add("error-message");
        mensajeError.textContent = mensaje;
        campo.parentNode.insertBefore(mensajeError, campo.nextSibling);
    }

    // Función para limpiar los mensajes de error previos
    function limpiarMensajesDeError() {
        document.querySelectorAll(".error-message").forEach(error => error.remove());
        mensajeResultado.textContent = "";
        mensajeResultado.classList.remove("mensaje-exito", "mensaje-error");
    }
});
