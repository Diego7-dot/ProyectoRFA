<?php
// KM = Crear conexion al chat entre usuario y asesor
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

session_start();

// KM = COnexion con la BD
require_once("../../Config/conexion.php");
$con = Conexion();

// KM = Archivos JSON
header('Content-Type: application/json');

// KM = Verficar correo del usuario
$correoUsu = $_SESSION['corUsu'] ?? '';
if (!$correoUsu) {
    echo json_encode(["exito" => false, "msg" => "Sesión expirada"]);
    exit;
}

// KM = Existe una sala?
$ya = $con->prepare(
    "SELECT codConChat, docApoConChat
       FROM conexion_chat
      WHERE corUsuConChat=? AND estado=1
      LIMIT 1"
);

$ya->bind_param("s", $correoUsu);
$ya->execute();
$sala = $ya->get_result()->fetch_assoc();

if ($sala) {
    echo json_encode([
        "exito"      => true,
        "codConChat" => $sala['codConChat'],
        "docApo"     => $sala['docApoConChat']
    ]);
    exit;
}

// KM = Solicitar un asesor libre
ob_start();
include __DIR__ . "/seleccionar_asesor.php";
$raw   = ob_get_clean();
$asesor = json_decode($raw, true);

if (! isset($asesor['exito']) || ! $asesor['exito']) {
    echo json_encode([
        "exito" => false,
        "msg"   => "Todos nuestros asesores están ocupados. Intenta más tarde 🙏"
    ]);
    exit;
}
$docApo = $asesor['docApo'];

if (!$asesor) {
    echo json_encode([
        "exito" => false,
        "msg" => "Todos nuestros asesores están ocupados. Intenta más tarde 🙏"
    ]);
    exit;
}
$docApo = $asesor['docApo'];

// KM = Crear conexion 
$in = $con->prepare(
    "INSERT INTO conexion_chat (corUsuConChat, docApoConChat, estado)
             VALUES (?,?,1)"
);
$in->bind_param("ss", $correoUsu, $docApo);
$in->execute();

echo json_encode([
    "exito"      => true,
    "codConChat" => $con->insert_id,
    "docApo"     => $docApo
]);
