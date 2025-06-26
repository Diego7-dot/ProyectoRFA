<?php
// KM = Verificar si la persona de apoyo ya tiene disponibilidad registrada
session_start();
require_once("../../Config/conexion.php");

$con = Conexion();
$docApo = $_SESSION['docApo'] ?? '';

$response = [
    "tieneDisponibilidad" => false,
    "estado" => null,
    "fecIni" => null,
    "fecFin" => null
];

if (!empty($docApo)) {
    // KM = Verificar si tiene alguna disponibilidad en la plantilla
    $stmt = $con->prepare("SELECT COUNT(*) as total FROM disponibilidad_template WHERE docApoDisp = ?");
    $stmt->bind_param("s", $docApo);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row["total"] > 0) {
        $response["tieneDisponibilidad"] = true;

        // KM = Consultar el rango de fechas más amplio que tenga
        $stmt = $con->prepare("SELECT MIN(fecIni) as fecIni, MAX(fecFin) as fecFin FROM disponibilidad_template WHERE docApoDisp = ? AND activo = 1");
        $stmt->bind_param("s", $docApo);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        $response["fecIni"] = $data["fecIni"];
        $response["fecFin"] = $data["fecFin"];

        $hoy = date("Y-m-d");
        if (!empty($data["fecFin"]) && $data["fecFin"] < $hoy) {
            $response["estado"] = "vencida";
        } elseif (!empty($data["fecIni"]) && $data["fecIni"] > $hoy) {
            $response["estado"] = "noIniciada";
        } elseif (!empty($data["fecIni"]) && !empty($data["fecFin"])) {
            $response["estado"] = "activa";
        } else {
            $response["estado"] = "sinRango";
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
