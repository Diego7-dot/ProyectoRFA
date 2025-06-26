<?php
// KM = Cerrar sesion
session_start();

// KM = Borran las varibles del incio de sesion
$_SESSION = [];

// KM = Borrar las cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// KM = Fue bueno conocerte sesion, te ira bien en el bote
session_destroy();

// KM = Los regresa al inicio
header("Location: ../../html/pagina_principal/index.html");
exit();
