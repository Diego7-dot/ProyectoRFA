<?php
// KM = Edicion de usuario
session_start();

// KM = Evitar ingresos o peticiones que no sean desde el post
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../pagina_principal/inicio_sesion_usuario.php");
    exit();
}

require_once("../../Config/conexion.php");
$con = Conexion();

if (!isset($_SESSION['corUsu'])) {
    header("Location: ../../pagina_principal/inicio_sesion_usuario.php");
    exit();
}

$correo = $_SESSION['corUsu'];

// KM = Al salir un error
function mostrarError($mensaje)
{
    $_SESSION["error_edicion"] = $mensaje;
    header("Location: ../../html/pagina_usuario/editar_pefil.php");
    exit();
}

// KM = Traiga los datos del usuario que inicio sesion
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = $_SESSION['corUsu'];
    $nombre = trim($_POST["nomUsu"]);
    $fechaNacimiento = trim($_POST["fecNacUsu"]);
    $genero = trim($_POST["genUsu"]);
    $foto = trim($_POST["fotoPerfil"]);
    $nuevaClave = $_POST["nuevaPsw"] ?? '';
    $confirmarClave = $_POST["confirmarPsw"] ?? '';
    $seccionActiva = $_POST["seccionContrasenaActiva"] ?? '0';

    // KM = Validacion de campos
    if (empty($nombre) || empty($fechaNacimiento) || empty($genero)) {
        mostrarError("Por favor, completa todos los campos requeridos.");
    }

    // KM = Validacion de edad
    $edad = (new DateTime())->diff(new DateTime($fechaNacimiento))->y;
    if ($edad < 18 || $edad > 120) {
        mostrarError("Edad no válida.");
    }

    // KM = Validacion de genero
    if (!in_array($genero, ['F', 'M', 'O'])) {
        mostrarError("Género no válido.");
    }

    // KM = Validacion de contraseña
    if ($seccionActiva === '1') {
        // Validación obligatoria si la sección fue abierta
        if (empty($nuevaClave) || empty($confirmarClave)) {
            mostrarError("Debes ingresar y confirmar la nueva contraseña.");
        }

        if ($nuevaClave !== $confirmarClave) {
            mostrarError("Las contraseñas no coinciden.");
        }

        if (strlen($nuevaClave) < 8 || strlen($nuevaClave) > 20) {
            mostrarError("La contraseña debe tener entre 8 y 20 caracteres.");
        }

        if (!preg_match('/\d/', $nuevaClave)) {
            mostrarError("La contraseña debe contener al menos un número.");
        }

        if (preg_match('/[^A-Za-z0-9]/', $nuevaClave)) {
            mostrarError("La contraseña no debe contener caracteres especiales.");
        }

        $claveFinal = password_hash($nuevaClave, PASSWORD_DEFAULT);
    }

    // KM = Subir una imagen como foto de perfil
    $fotoSeleccionada = trim($_POST['fotoPerfil'] ?? '');
    if (!empty($fotoSeleccionada)) {
        $foto = $fotoSeleccionada;
    } else {
        $foto = $_SESSION['fotoPerfil']; // KM = Mantener la anterior si no hubo un cambio
    }

    // KM = Consulta para al actualizacion
    $sql = "UPDATE usuario SET nomUsu = ?, fecNacUsu = ?, genUsu = ?, fotoPerfil = ?" .
        (!empty($nuevaClave) ? ", psw = ?" : "") .
        " WHERE corUsu = ?";
    $stmt = $con->prepare($sql);

    if (!empty($nuevaClave)) {
        $stmt->bind_param("ssssss", $nombre, $fechaNacimiento, $genero, $foto, $claveFinal, $correo);
    } else {
        $stmt->bind_param("sssss", $nombre, $fechaNacimiento, $genero, $foto, $correo);
    }

    // KM = Actualizar la sesion
    if ($stmt->execute()) {
        $_SESSION["nomUsu"] = $nombre;
        $_SESSION["genUsu"] = $genero;
        $_SESSION["fotoPerfil"] = $foto;
        header("Location: ../../html/pagina_usuario/editar_pefil.php");
        exit();
    } else {
        mostrarError("Error al actualizar: " . $stmt->error);
    }
}
