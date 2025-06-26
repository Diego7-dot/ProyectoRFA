<?php
session_start();
require_once("../../Config/conexion.php");
header('Content-Type: application/json; charset=utf-8');

$docApo = $_SESSION['docApo'] ?? '';
if (!$docApo) {
    echo '[]';
    exit;
}

$con = Conexion();
$sql = "
  SELECT 
    c.codConChat,
    c.corUsuConChat    AS corUsu,
    u.nomUsu           AS nombre,
    u.fotoPerfil       AS foto,    /* nombre de fichero */
    u.genUsu           AS genero
  FROM conexion_chat c
  JOIN usuario u 
    ON u.corUsu = c.corUsuConChat
  WHERE c.docApoConChat = ?
    AND c.estado = 1
  ORDER BY c.codConChat
  LIMIT 10
";
$stmt = $con->prepare($sql);
$stmt->bind_param('s', $docApo);
$stmt->execute();
$salas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode($salas);
