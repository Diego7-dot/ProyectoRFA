<?php
// KM = Lista de ciudades y roles para el formulario de agregar persona de apoyo

require_once("../../Config/conexion.php");
$conexion = Conexion();

header("Content-Type: application/json");

try {
    // KM = Consultar ciudades
    $queryCiudades = "SELECT codCiu, nomCiu FROM ciudad";
    $resultCiudades = $conexion->query($queryCiudades);
    $ciudades = [];

    while ($row = $resultCiudades->fetch_assoc()) {
        $ciudades[] = $row;
    }

    // KM = Consultar roles (excluyendo administrador)
    $queryRoles = "SELECT codRol, nomRol FROM rol WHERE nomRol <> 'Administrador'";
    $resultRoles = $conexion->query($queryRoles);
    $roles = [];

    while ($row = $resultRoles->fetch_assoc()) {
        $roles[] = $row;
    }

    echo json_encode([
        "ciudades" => $ciudades,
        "roles" => $roles
    ]);
} catch (Exception $e) {
    echo json_encode(["error" => "Ocurrió un error al obtener datos: " . $e->getMessage()]);
}
