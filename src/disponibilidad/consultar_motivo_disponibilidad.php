<?php
require_once("../../Config/conexion.php");
$con = Conexion();
session_start();

$docApo = $_SESSION['docApo'] ?? '';

$motivoGuardado = '';

if ($docApo) {
    $stmt = $con->prepare("SELECT motDis FROM disponibilidad_template WHERE docApoDisp = ? AND motDis IS NOT NULL LIMIT 1");
    $stmt->bind_param("s", $docApo);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $motivoGuardado = $row["motDis"];
    }
}

echo json_encode([
    "exito" => true,
    "motivo" => $motivoGuardado
]);
