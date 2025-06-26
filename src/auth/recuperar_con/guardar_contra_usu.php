<?php
session_start();
require_once '../../../Config/conexion.php';

// KM = Verificar que hay sesión activa
if (!isset($_SESSION['correo_en_recuperacion']) || !isset($_SESSION['codigo_validado'])) {
    header("Location: http://localhost/Proyecto_real_v1/Proyecto_real/html/pagina_principal/recuperar_contraseña.php?error=Sesión no válida");
    exit();
}

$correo = $_SESSION['correo_en_recuperacion'];

$conexion = Conexion();

// KM = Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nueva = $_POST['nuevaPsw'] ?? '';
    $confirmar = $_POST['confirmarPsw'] ?? '';

    if (empty($nueva) || empty($confirmar)) {
        session_unset();
        session_destroy();
        header("Location: http://localhost/Proyecto_real_v1/Proyecto_real/html/pagina_principal/nueva_contraseña.php?error=Campos obligatorios");
        exit();
    }

    if ($nueva !== $confirmar) {
        header("Location: http://localhost/Proyecto_real_v1/Proyecto_real/html/pagina_principal/nueva_contraseña.php?error=Las contraseñas no coinciden");
        exit();
    }

    // KM = Hashear contraseña nueva
    $hash = password_hash($nueva, PASSWORD_DEFAULT);

    // KM = Actualizar en base de datos
    $stmt = $conexion->prepare("UPDATE usuario SET psw = ? WHERE corUsu = ?");
    $stmt->bind_param("ss", $hash, $correo);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        die("❌ No se actualizó ninguna fila. ¿Correo mal escrito o misma contraseña?");
    }

    // KM = Marcar código como usado
    $stmt = $conexion->prepare("UPDATE recuperacionUsu SET usaRecUsu = TRUE 
    WHERE corRecUsu = ? AND usaRecUsu = FALSE ORDER BY fecEnvRecUsu DESC LIMIT 1");
    $stmt->bind_param("s", $correo);
    $stmt->execute();

    // KM = Limpiar sesión
    session_unset();
    session_destroy();

    header("Location: http://localhost/Proyecto_real_v1/Proyecto_real/html/pagina_principal/inicio_sesion_usuario.php?exito=Contraseña actualizada");
    exit();
} else {
    header("Location: http://localhost/Proyecto_real_v1/Proyecto_real/html/pagina_principal/nueva_contraseña.php?error=Solicitud no válida");
    exit();
}
