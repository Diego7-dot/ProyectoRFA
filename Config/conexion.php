<?php
function Conexion() {
    // Parámetros de conexión
    $host = "localhost";
    $dbname = "rfa_1";
    $user = "root";
    $password = "1234";

    // Validar que los parámetros básicos no estén vacíos
    if (empty($host) || empty($dbname) || empty($user)) {
        throw new Exception("Los parámetros de conexión no están completos.");
    }
    
    // Configurar mysqli para lanzar excepciones en caso de error
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
    try {
        // Establecer la conexión
        $con = mysqli_connect($host, $user, $password, $dbname);
        if (!$con) {
            throw new Exception("Error de conexión: " . mysqli_connect_error());
        }
        
        // Configurar el juego de caracteres para evitar problemas de codificación
        if (!mysqli_set_charset($con, "utf8mb4")) {
            throw new Exception("Error al establecer el juego de caracteres: " . mysqli_error($con));
        }
        
    } catch (Exception $e) {
        // Registrar el error para depuración (en producción, evita mostrar detalles al usuario)
        error_log($e->getMessage());
        // Puedes mostrar un mensaje genérico o detener la ejecución
        die("Error al conectar con la base de datos.");
    }
    
    return $con;
}
?>
