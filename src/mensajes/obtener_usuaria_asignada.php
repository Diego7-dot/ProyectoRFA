<?php
// Devuelve correo y nombre del usuario conectada al asesor
session_start();
require_once("../../Config/conexion.php");
$con = Conexion();

$docApo = $_SESSION['docApo'] ?? '';
if (!$docApo) {
    echo json_encode(["exito" => false]);
    exit;
}

$sql = "SELECT corUsuConChat, nomUsu
          FROM conexion_chat
          INNER JOIN usuario ON corUsuConChat = usuario.corUsu
         WHERE docApoConChat = ? AND estado = 1
         LIMIT 1";

$stmt = $con->prepare($sql);
$stmt->bind_param("s", $docApo);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

echo json_encode($res
    ? ["exito" => true, "corUsu" => $res['corUsuConChat'], "nombreUsu" => $res['nomUsu']]
    : ["exito" => false, "mensaje" => "Sin usuaria conectada"]);
