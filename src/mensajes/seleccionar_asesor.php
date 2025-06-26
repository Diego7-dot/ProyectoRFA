<?php
// KM = Seleccionar al asesor para crear la conexion!
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// KM = Toma la fecha de america/bogota y el Json
date_default_timezone_set('America/Bogota');
header('Content-Type: application/json');

// KM = Conexion
require_once("../../Config/conexion.php");
$con = Conexion();

// KM = Consulta
$sql = "
SELECT pa.docApo,
       COUNT(c.codConChat) AS chatsActivos
  FROM personaApoyo pa

  JOIN disponibilidad d
    ON d.docApoDisp = pa.docApo
   AND d.fecha      = CURDATE()
   AND d.horaIni   <= CURTIME()
   AND d.horaFin    >  CURTIME()
   AND d.activo     = 1

  LEFT JOIN conexion_chat c
    ON c.docApoConChat = pa.docApo
   AND c.estado        = 1
 WHERE pa.estadoApo = 1
   AND pa.codRolApo = ?
 GROUP BY pa.docApo
 HAVING chatsActivos < 10         
 ORDER BY chatsActivos ASC, pa.docApo
 LIMIT 1
";

$stmt = $con->prepare($sql);
if (!$stmt) {
       echo json_encode([
              "exito" => false,
              "error" => $con->error
       ]);
       exit;
}

// KM = Buscar asesor por rol
$ROL_ASESOR = 3;
$stmt->bind_param("i", $ROL_ASESOR);
$stmt->execute();

$res    = $stmt->get_result();
$asesor = $res->fetch_assoc();

// KM = Respuesta
if ($asesor) {
       echo json_encode([
              "exito"        => true,
              "docApo"       => $asesor['docApo'],
              "chatsActivos" => intval($asesor['chatsActivos'])
       ]);
} else {
       echo json_encode(["exito" => false]);
}
