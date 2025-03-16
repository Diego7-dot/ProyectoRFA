// Js de inicio de sesión Empleado

document.addEventListener("DOMContentLoaded", function () {
    const togglePasswordEmpleado = document.getElementById("toggleVerifyPasswordIcon");
    const passwordEmpleado = document.getElementById("password");
    const documentoInput = document.getElementById("documento");

    // KM = Función para alternar la visibilidad de la contraseña
    function togglePassword(inputField, icon) {
        if (inputField.type === "password") {
            inputField.type = "text";
            icon.src = "../../iconos/pagina_principal/ojo_abierto.png";
        } else {
            inputField.type = "password";
            icon.src = "../../iconos/pagina_principal/ojo_cerrado.png";
        }
    }

    // KM = Evento para alternar la contraseña en el formulario de empleado
    togglePasswordEmpleado.addEventListener("click", function () {
        togglePassword(passwordEmpleado, togglePasswordEmpleado);
    });

    // KM = Validación del campo Documento para permitir solo números
    documentoInput.addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, ""); // Reemplaza cualquier carácter que no sea un número
    });
});
