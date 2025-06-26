<?php
// ───────────────────────────────────────────────────────────────
//  verificar_disponibilidad.php
//  ¿Existe al menos un asesor disponible para abrir una sala?
// ───────────────────────────────────────────────────────────────
require_once("../../Config/conexion.php");
$con = Conexion();

$hoy  = date('Y-m-d');
$hora = date('H:i:s');

$sql = "
SELECT 1
  FROM personaApoyo pa
  JOIN disponibilidad d     ON d.docApoDisp = pa.docApo
 WHERE d.fecha    = ?
   AND d.horaIni <= ?
   AND d.horaFin  > ?
   AND d.activo   = 1
   AND pa.estadoApo = 1                -- asesor activo
   AND (
         SELECT COUNT(*)
           FROM conexion_chat c
          WHERE c.docApoConChat = pa.docApo
            AND c.estado = 1
        ) < 10                          -- menos de 10 salas
 LIMIT 1";

$stmt = $con->prepare($sql);
$stmt->bind_param("sss", $hoy, $hora, $hora);
$stmt->execute();
$tiene = $stmt->get_result()->num_rows > 0;

echo json_encode(["disponible" => $tiene]);
