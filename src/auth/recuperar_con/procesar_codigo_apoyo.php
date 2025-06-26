<?php
// KM = Verificar el código de recuperación de persona de apoyo
session_start();
require_once '../../../Config/conexion.php';
$conexion = Conexion();

// KM = Validar que la sesión esté activa
if (!isset($_SESSION['documento_apoyo'], $_SESSION['rol_apoyo'], $_SESSION['expira_codigo_apoyo'])) {
    header("Location: ../../../html/pagina_principal/recuperar_codigo_apoyo.php?error=Sesión expirada o inválida");
    exit;
}

// KM = Validar si el código expiró por tiempo
if (time() > $_SESSION['expira_codigo_apoyo']) {
    header("Location: ../../../html/pagina_principal/recuperar_codigo_apoyo.php?error=El código ha expirado");
    exit;
}

// KM = Obtener el código ingresado
$codigoIngresado = "";
for ($i = 1; $i <= 6; $i++) {
    if (!isset($_POST["d$i"]) || !is_numeric($_POST["d$i"])) {
        header("Location: ../../../html/pagina_principal/recuperar_codigo_apoyo.php?error=Código incompleto o inválido");
        exit;
    }
    $codigoIngresado .= $_POST["d$i"];
}

// KM = Verificar código en base de datos
$documento = $_SESSION['documento_apoyo'];
$rol = $_SESSION['rol_apoyo'];

$sql = $conexion->prepare("SELECT * FROM recuperacionApo 
    WHERE docRecApo = ? AND codRolRecApo = ? AND codRecApo = ? AND usaRecApo = FALSE 
    ORDER BY fecEnvRecApo DESC LIMIT 1");
$sql->bind_param("sis", $documento, $rol, $codigoIngresado);
$sql->execute();
$resultado = $sql->get_result();

if ($resultado->num_rows === 1) {
    // KM = Código válido
    $_SESSION['codigo_validado_apoyo'] = true;
    $_SESSION['documento_en_recuperacion'] = $documento;
    $_SESSION['rol_en_recuperacion'] = $rol;

    header("Location: ../../../html/pagina_principal/nueva_contraseña_apoyo.php");
    exit;
} else {
    header("Location: ../../../html/pagina_principal/recuperar_codigo_apoyo.php?error=Código incorrecto o usado");
    exit;
}
