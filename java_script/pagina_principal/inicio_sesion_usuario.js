// Js de inicio de sesión Usuario

document.addEventListener("DOMContentLoaded", function () {
    const togglePasswordUsuario = document.getElementById("togglePasswordIcon");
    const passwordUsuario = document.getElementById("password-usuario");

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

    // KM = Evento para alternar la contraseña
    togglePasswordUsuario.addEventListener("click", function () {
        togglePassword(passwordUsuario, togglePasswordUsuario);
    });
});