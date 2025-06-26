<?php
// KM = Obtener los mensajes de la BD

// KM = Evitar romper el Json
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

ini_set('error_log', __DIR__ . '/obtener_mensajes.log');

// KM = Conexion
require_once __DIR__ . "/../../Config/conexion.php";
$con = Conexion();

try {
  $room   = intval($_GET['codConChat'] ?? 0);
  $lastId = intval($_GET['lastId']      ?? 0);

  if (!$room) {
    echo json_encode(["exito" => false, "msg" => "Sala inválida"]);
    exit;
  }

  // KM = Verificar que la persona de apoyo este activa: $chk = $con->prepare("
  $chk = $con->prepare("
  SELECT c.estado, p.estadoApo
    FROM conexion_chat c
    JOIN personaApoyo p 
      ON p.docApo = c.docApoConChat
   WHERE c.codConChat = ?
");

  $chk->bind_param("i", $room);
  $chk->execute();

  $chk->store_result();
  $chk->bind_result($estadoChat, $estadoApo);

  $hasRow = $chk->fetch();

  $chk->free_result();
  $chk->close();

  // DEBUG opcional (quita luego)
  error_log("DBG obtener_mensajes: hasRow={$hasRow}, estadoChat={$estadoChat}, estadoApo={$estadoApo}");

  // KM = Si no encontro fila, o chat cerrado,o asesor inactivo...
  if (!$hasRow || $estadoChat == 0 || $estadoApo == 0) {
    echo json_encode([
      "exito"  => false,
      "closed" => true,
      "msg"    => "Tu asesor ha quedado inhabilitado. Buscando uno nuevo…"
    ]);
    exit;
  }

  // KM = Recuperar los mensajes nuevos
  $sql = "
    SELECT codMen,
           docApoRemMen, corUsuRemMen,
           docApoDestMen, corUsuDestMen,
           mensajeMen, fechaEnvioMen
      FROM mensaje
     WHERE codConChat  = ?   
       AND codMen         > ?
     ORDER BY codMen ASC
    ";

  $stmt = $con->prepare($sql);
  $stmt->bind_param("ii", $room, $lastId);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result(
    $codMen,
    $docApoRemMen,
    $corUsuRemMen,
    $docApoDestMen,
    $corUsuDestMen,
    $mensajeMen,
    $fechaEnvioMen
  );

  $mensajes = [];

  $mensajes = [];
  while ($stmt->fetch()) {
    $mensajes[] = [
      'codMen'       => $codMen,
      'docApoRemMen' => $docApoRemMen,
      'corUsuRemMen' => $corUsuRemMen,
      'docApoDestMen' => $docApoDestMen,
      'corUsuDestMen' => $corUsuDestMen,
      'mensajeMen'   => $mensajeMen,
      'fechaEnvioMen' => $fechaEnvioMen,
      'nombreRem'    => $corUsuRemMen ? 'Usuaria' : 'Asesor'
    ];
  }

  $stmt->free_result();
  $stmt->close();

  echo json_encode(["exito" => true, "mensajes" => $mensajes]);
} catch (\mysqli_sql_exception $e) {
  error_log("Error en obtener_mensajes.php: " . $e->getMessage());
  http_response_code(500);
  echo json_encode([
    "exito" => false,
    "error" => "Error interno al obtener mensajes"
  ]);
  exit;
}
