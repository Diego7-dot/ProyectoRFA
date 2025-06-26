<?php
// KM = Cargar disponibilidad registrada en formato JSON para edicion
session_start();
header('Content-Type: application/json');

// KM = Conexion con la BD
require_once("../../../Config/conexion.php");
$con = Conexion();

// KM = Verficiar la sesion inicada
$documento = $_SESSION['docApo'] ?? '';

if (empty($documento)) {
    echo json_encode([]);
    exit;
}

// KM = Consulta
$sql = "SELECT diaSem AS dia, horaIni, horaFin, fecIni AS fechaIni, fecFin AS fechaFin, obsDis AS observacion 
        FROM disponibilidad_template 
        WHERE docApoDisp = ? AND activo = TRUE";

$stmt = $con->prepare($sql);
$stmt->bind_param("s", $documento);
$stmt->execute();
$res = $stmt->get_result();

$disponibilidad = [];

while ($row = $res->fetch_assoc()) {
    $disponibilidad[] = $row;
}

// KM = Json
echo json_encode($disponibilidad);
