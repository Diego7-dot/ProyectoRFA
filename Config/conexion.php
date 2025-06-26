<?php
function Conexion()
{
    // DI= Conexion a la base de datos (Parametros)
    $host = "localhost";
    $dbname = "rfa_1";
    $user = "root";
    $password = "";

    // DI = Validar que los campos no esten vacios
    if (empty($host) || empty($dbname) || empty($user)) {
        throw new Exception("Los parametros de conexion no estan completos.");
    }

    // DI = Configurar mysqli para lanzar excepciones en caso de error
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        // DI = Establecer la conexion
        $con = mysqli_connect($host, $user, $password, $dbname);
        if (!$con) {
            throw new Exception("Error de conexion: " . mysqli_connect_error());
        }

        // DI = Configurar el juego de caracteres para evitar problemas de codificacion
        if (!mysqli_set_charset($con, "utf8mb4")) {
            throw new Exception("Error al establecer el juego de caracteres: " . mysqli_error($con));
        }
    } catch (Exception $e) {
        // DI = Registrar el error para depuracion 
        error_log($e->getMessage());
        // DI = Error al conectar en la base de datos
        die("Error al conectar con la base de datos: " . $e->getMessage());
    }

    return $con;
}
