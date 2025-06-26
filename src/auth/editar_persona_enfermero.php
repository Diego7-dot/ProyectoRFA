<?php
// KM = Edición de persona de apoyo (enfermería)
session_start();

// KM = Solo acepta POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../../html/pagina_principal/inicio_sesion_empleado.php");
    exit();
}

require_once("../../Config/conexion.php");
$con = Conexion();

// KM = Verifica si la sesión está activa
if (!isset($_SESSION['docApo'])) {
    header("Location: ../../../html/pagina_principal/inicio_sesion_empleado.php");
    exit();
}

$documento = $_SESSION['docApo'];

// KM = Función para mostrar error y redirigir
function mostrarError($mensaje)
{
    $_SESSION["error_edicion"] = $mensaje;
    header("Location: /Proyecto_real_v1/Proyecto_real/html/pagina_empleado/empleado_enfermera/editar_enfermero.php");
    exit();
}

// KM = Obtener datos del formulario
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

// KM = Validaciones generales
if (
    empty($nombre) || empty($apellido) || empty($fechaNacimiento) ||
    empty($genero) || empty($telefono) || empty($direccion) ||
    empty($correo) || empty($codCiudad)
) {
    mostrarError("Por favor, completa todos los campos requeridos.");
}

// KM = Validar formato de teléfono (solo números, 7 a 11 dígitos)
if (!preg_match('/^\d{7,11}$/', $telefono)) {
    mostrarError("El teléfono debe contener solo números y tener entre 7 y 11 dígitos.");
}

// KM = Validar edad
$edad = (new DateTime())->diff(new DateTime($fechaNacimiento))->y;
if ($edad < 18 || $edad > 120) {
    mostrarError("Edad no válida.");
}

// KM = Validar género
if (!in_array($genero, ['F', 'M', 'O'])) {
    mostrarError("Género no válido.");
}

// KM = Validar correo único (excepto para el enfermero actual)
$sqlCorreo = "SELECT docApo FROM personaApoyo WHERE corApo = ? AND docApo != ?";
$stmtCorreo = $con->prepare($sqlCorreo);
$stmtCorreo->bind_param("ss", $correo, $documento);
$stmtCorreo->execute();
$resCorreo = $stmtCorreo->get_result();
if ($resCorreo->num_rows > 0) {
    mostrarError("Este correo ya está en uso por otra persona.");
}

// KM = Validar nueva contraseña si se desea cambiar
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

// KM = Preparar foto (mantener la anterior si no se selecciona nueva)
$fotoSeleccionada = trim($_POST['fotoPerfil'] ?? '');
$foto = !empty($fotoSeleccionada) ? $fotoSeleccionada : $_SESSION['fotoPerfil'];

// KM = Construir la consulta SQL (agregar cambio de clave si aplica)
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

// KM = Ejecutar actualización
if ($stmt->execute()) {
    // KM = Actualizar datos de sesión
    $_SESSION["fotoPerfil"] = $foto;
    $_SESSION["nomApo"] = $nombre;
    $_SESSION["genApo"] = $genero;
    $_SESSION["corApo"] = $correo;

    // KM = Redirigir con mensaje de éxito
    header("Location: /Proyecto_real_v1/Proyecto_real/html/pagina_empleado/empleado_enfermera/editar_enfermero.php?exito=Perfil actualizado");
    exit();
} else {
    mostrarError("Error al actualizar: " . $stmt->error);
}
