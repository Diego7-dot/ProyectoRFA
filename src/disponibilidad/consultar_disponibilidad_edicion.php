<?php
// KM = Consultar disponibilidad para edicion (retornar JSON)
session_start();
require_once("../../Config/conexion.php");
$con = Conexion();

// KM = Obtener documento del asesor desde sesión
$documento = $_SESSION['docApo'] ?? '';

$sql = "SELECT diaSem, horaIni, horaFin, fecIni, fecFin, obsDis 
        FROM disponibilidad_template 
        WHERE docApoDisp = ? AND activo = TRUE";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $documento);
$stmt->execute();
$res = $stmt->get_result();

$datos = [];

while ($row = $res->fetch_assoc()) {
    $datos[] = [
        'dia' => $row['diaSem'] ?? null,
        'horaIni' => $row['horaIni'] ?? null,
        'horaFin' => $row['horaFin'] ?? null,
        'fecIni' => $row['fecIni'] ?? '',
        'fecFin' => $row['fecFin'] ?? '',
        'observacion' => $row['obsDis'] ?? ''
    ];
}

header('Content-Type: application/json');
// KM = Perrito encerrado wau wau, perrito encerrado wau wau
echo json_encode($datos);
