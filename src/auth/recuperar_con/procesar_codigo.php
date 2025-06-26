<?php
// KM = Verificar que el codigo sea correcto!
session_start();

if (!isset($_SESSION['codigo']) || !isset($_SESSION['correo']) || !isset($_SESSION['expira_codigo'])) {
    header("Location: ../../../html/pagina_principal/recuperar_codigo.php?error=Sesión expirada o inválida");
    exit;
}

if (time() > $_SESSION['expira_codigo']) {
    header("Location: ../../../html/pagina_principal/recuperar_codigo.php?error=El código ha expirado");
    exit;
}

$codigoIngresado = "";
for ($i = 1; $i <= 6; $i++) {
    if (!isset($_POST["d$i"]) || !is_numeric($_POST["d$i"])) {
        header("Location: ../../../html/pagina_principal/recuperar_codigo.php?error=Código incompleto o inválido");
        exit;
    }
    $codigoIngresado .= $_POST["d$i"];
}

if (trim($codigoIngresado) === trim((string) $_SESSION['codigo'])) {
    // KM = WAU WAU RAW Llevo mas de medio dia en este error
    $_SESSION['codigo_validado'] = true;
    $_SESSION['correo_en_recuperacion'] = $_SESSION['correo'];
    header("Location: ../../../html/pagina_principal/nueva_contraseña.php");
    exit;
} else {
    header("Location: ../../../html/pagina_principal/recuperar_codigo.php?error=Código incorrecto");
    exit;
}
