<?php
session_start();
require_once '../../../Config/conexion.php';
$conexion = Conexion();

// KM = Validar que sesión esté activa y el código haya sido validado
if (!isset($_SESSION['documento_en_recuperacion'], $_SESSION['rol_en_recuperacion'], $_SESSION['codigo_validado_apoyo'])) {
    header("Location: ../../../html/pagina_principal/recuperar_contraseña_apoyo.php?error=Sesion invalida");
    exit;
}

$documento = $_SESSION['documento_en_recuperacion'];
$rol = $_SESSION['rol_en_recuperacion'];

// KM = Validar método y campos
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nueva = $_POST['nuevaPsw'] ?? '';
    $confirmar = $_POST['confirmarPsw'] ?? '';

    if (empty($nueva) || empty($confirmar)) {
        header("Location: ../../../html/pagina_principal/nueva_contraseña_apoyo.php?error=Campos vacíos");
        exit;
    }

    if ($nueva !== $confirmar) {
        header("Location: ../../../html/pagina_principal/nueva_contraseña_apoyo.php?error=Las contraseñas no coinciden");
        exit;
    }

    // KM = Hashear contraseña nueva
    $hash = password_hash($nueva, PASSWORD_DEFAULT);

    // KM = Actualizar contraseña
    $stmt = $conexion->prepare("UPDATE personaApoyo SET pswApo = ? WHERE docApo = ? AND codRolApo = ?");
    $stmt->bind_param("ssi", $hash, $documento, $rol);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        die("No se actualizó ninguna fila. ¿Misma contraseña o error?");
    }

    // KM = Marcar el código como usado
    $stmt = $conexion->prepare("UPDATE recuperacionApo SET usaRecApo = TRUE 
        WHERE docRecApo = ? AND codRolRecApo = ? AND usaRecApo = FALSE 
        ORDER BY fecEnvRecApo DESC LIMIT 1");
    $stmt->bind_param("si", $documento, $rol);
    $stmt->execute();

    // KM = Cerrar sesión y redirigir
    session_unset();
    session_destroy();

    header("Location: ../../../html/pagina_principal/inicio_sesion_empleado.php?exito=Contraseña actualizada");
    exit;
} else {
    header("Location: ../../../html/pagina_principal/nueva_contraseña_apoyo.php?error=Solicitud inválida");
    exit;
}
