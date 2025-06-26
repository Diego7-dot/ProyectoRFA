<?php
// KM = Edición de persona de apoyo
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../../html/pagina_principal/inicio_sesion_empleado.php");
    exit();
}

require_once("../../Config/conexion.php");
$con = Conexion();

if (!isset($_SESSION['docApo'])) {
    header("Location: ../../../html/pagina_principal/inicio_sesion_empleado.php");
    exit();
}

$documento = $_SESSION['docApo'];

function mostrarError($mensaje)
{
    $_SESSION["error_edicion"] = $mensaje;
    header("Location: ../../html/pagina_empleado/admin_editar.php");
    exit();
}

// KM = Recibir datos
$correo = trim($_POST["corApo"]);
$nombre = trim($_POST["nomApo"]);
$apellido = trim($_POST["apeApo"]);
$fechaNacimiento = trim($_POST["fecNacApo"]);
$genero = trim($_POST["genApo"]);
$telefono = trim($_POST["telApo"]);
$direccion = trim($_POST["dirApo"]);
$codCiudad = $_POST["codCiuApo"] ?? '';
$foto = trim($_POST["fotoPerfil"]);
$nuevaClave = $_POST["nuevaPsw"] ?? '';
$confirmarClave = $_POST["confirmarPsw"] ?? '';
$seccionActiva = $_POST["seccionContrasenaActiva"] ?? '0';

// KM = Validacion de campos vacios 
if (empty($nombre) || empty($apellido) || empty($fechaNacimiento) || empty($genero) || empty($telefono) || empty($direccion) || empty($correo) || empty($codCiudad)) {
    mostrarError("Por favor, completa todos los campos requeridos.");
}

if (!preg_match('/^\d{7,11}$/', $telefono)) {
    mostrarError("El teléfono debe contener solo números y tener entre 7 y 11 dígitos.");
}

$edad = (new DateTime())->diff(new DateTime($fechaNacimiento))->y;
if ($edad < 18 || $edad > 120) {
    mostrarError("Edad no válida.");
}

if (!in_array($genero, ['F', 'M', 'O'])) {
    mostrarError("Género no válido.");
}

// KM = Validar correo único
$sqlCorreo = "SELECT docApo FROM personaApoyo WHERE corApo = ? AND docApo != ?";
$stmtCorreo = $con->prepare($sqlCorreo);
$stmtCorreo->bind_param("ss", $correo, $documento);
$stmtCorreo->execute();
$resCorreo = $stmtCorreo->get_result();
if ($resCorreo->num_rows > 0) {
    mostrarError("Este correo ya está en uso por otra persona.");
}

// KM = Validar contraseña si se va a cambiar
if ($seccionActiva === '1') {
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
        mostrarError("La contraseña no debe tener caracteres especiales.");
    }
    $claveFinal = password_hash($nuevaClave, PASSWORD_DEFAULT);
}

// KM = Foto
$fotoSeleccionada = trim($_POST['fotoPerfil'] ?? '');
$foto = !empty($fotoSeleccionada) ? $fotoSeleccionada : $_SESSION['fotoPerfil'];

// KM = SQL para actualizar
$sql = "UPDATE personaApoyo SET 
            corApo = ?, nomApo = ?, apeApo = ?, fecNacApo = ?, genApo = ?, 
            telApo = ?, dirApo = ?, codCiuApo = ?, fotoPerfil = ?" .
    ($seccionActiva === '1' ? ", pswApo = ?" : "") .
    " WHERE docApo = ?";

$stmt = $con->prepare($sql);

if ($seccionActiva === '1') {
    $stmt->bind_param("sssssssssss", $correo, $nombre, $apellido, $fechaNacimiento, $genero, $telefono, $direccion, $codCiudad, $foto, $claveFinal, $documento);
} else {
    $stmt->bind_param("ssssssssss", $correo, $nombre, $apellido, $fechaNacimiento, $genero, $telefono, $direccion, $codCiudad, $foto, $documento);
}

if ($stmt->execute()) {
    $_SESSION["fotoPerfil"] = $foto;
    $_SESSION["nomApo"] = $nombre;
    $_SESSION["genApo"] = $genero;
    $_SESSION["corApo"] = $correo;
    header("Location: ../../html/pagina_empleado/admin_editar.php");
    exit();
} else {
    mostrarError("Error al actualizar: " . $stmt->error);
}
