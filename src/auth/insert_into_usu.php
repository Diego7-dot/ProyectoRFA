<?php
session_start();
// KM = Registro de usuarios
include(__DIR__ . "/../../Config/conexion.php");
$con = Conexion();

// KM = Funcion para mostrar errores de la bd (Kama saico ahahahahaha uhuhuh tumtum)
function mostrarError($mensaje)
{
    $_SESSION["old_inputs"] = $_POST;
    $_SESSION["error_registro"] = $mensaje;
    header("Location: ../../html/pagina_principal/registro_usuario.php");
    exit();
}

// KM = Verificar la solicitud de post
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["corUsu"], $_POST["nomUsu"], $_POST["fecNacUsu"], $_POST["genUsu"], $_POST["psw"])) {

        $correo = trim($_POST["corUsu"]);
        $nombre = trim($_POST["nomUsu"]);
        $fechaNacimiento = trim($_POST["fecNacUsu"]);
        $genero = trim($_POST["genUsu"]);
        $password = $_POST["psw"];

        // Validaciones
        if (empty($correo) || empty($nombre) || empty($fechaNacimiento) || empty($genero) || empty($password)) {
            mostrarError("Por favor, completa todos los campos.");
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            mostrarError("El correo electrónico no es válido.");
        }

        $verificarCorreo = $con->prepare("SELECT corUsu FROM usuario WHERE corUsu = ?");
        $verificarCorreo->bind_param("s", $correo);
        $verificarCorreo->execute();
        $verificarCorreo->store_result();
        if ($verificarCorreo->num_rows > 0) {
            mostrarError("El correo ya está registrado.");
        }
        $verificarCorreo->close();

        $fechaNacimientoObj = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNacimientoObj)->y;

        if ($edad < 18) {
            mostrarError("Debes tener al menos 18 años para registrarte.");
        }

        if ($edad > 120) {
            mostrarError("Debes ser menor de 120 años para registrarte.");
        }

        if (!in_array($genero, ['F', 'M', 'O'])) {
            mostrarError("Género inválido.");
        }

        if (strlen($password) < 8 || strlen($password) > 20) {
            mostrarError("La contraseña debe tener entre 8 y 20 caracteres.");
        }

        if (!preg_match('/\d/', $password)) {
            mostrarError("La contraseña debe contener al menos un número.");
        }

        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            mostrarError("La contraseña no puede tener caracteres especiales.");
        }

        // Asignar foto
        switch ($genero) {
            case 'F':
                $foto = 'icono_default_mujer.png';
                break;
            case 'M':
                $foto = 'icono_default_hombre.png';
                break;
            case 'O':
                $foto = 'icono_default_otro.png';
                break;
        }

        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $con->prepare("INSERT INTO usuario (corUsu, nomUsu, fecNacUsu, genUsu, psw, fotoPerfil) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            mostrarError("Error en la preparación de la consulta: " . $con->error);
        }

        $stmt->bind_param("ssssss", $correo, $nombre, $fechaNacimiento, $genero, $passwordHashed, $foto);

        if ($stmt->execute()) {
            $_SESSION["registro_exitoso"] = true;
            header("Location: ../../html/pagina_principal/registro_usuario.php");
            exit();
        } else {
            mostrarError("Error al insertar en la BD: " . $stmt->error);
        }

        $stmt->close();
    } else {
        mostrarError("Faltan campos en la solicitud.");
    }
} else {
    mostrarError("Método de solicitud no permitido.");
}
