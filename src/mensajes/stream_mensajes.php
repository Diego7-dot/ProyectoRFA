<?php
// ─── stream_mensajes.php ─────────────────────────────────────────

// 1) Cabeceras SSE y ajustes de PHP
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
ignore_user_abort(true);
set_time_limit(0);

// Desactivar compresión y buffering para poder hacer flush()
ini_set('zlib.output_compression', 0);
while (ob_get_level() > 0) ob_end_flush();
ob_implicit_flush(true);

require_once __DIR__ . "/../../Config/conexion.php";
$con = Conexion();

// 2) Parámetros iniciales
$room   = intval($_GET['codConChat'] ?? 0);
$lastId = intval($_GET['lastId']      ?? 0);
if (!$room) {
    echo "event: error\n";
    echo 'data: ' . json_encode(["msg" => "Sala inválida"]) . "\n\n";
    exit;
}

// 3) Loop infinito que comprueba nuevos mensajes
while (true) {
    // 3a) Verificar que la sala y el asesor siguen activos
    $chk = $con->prepare("
      SELECT c.estado, p.estadoApo
        FROM conexion_chat c
        JOIN personaApoyo p ON p.docApo = c.docApoConChat
       WHERE c.codConChat = ?
    ");
    $chk->bind_param("i", $room);
    $chk->execute();
    $chk->store_result();
    $chk->bind_result($estadoChat, $estadoApo);
    $hasRow = $chk->fetch();
    $chk->free_result();
    $chk->close();

    if (!$hasRow || $estadoChat == 0 || $estadoApo == 0) {
        echo "event: closed\n";
        echo 'data: ' . json_encode(["msg" => "Tu asesor ha quedado inhabilitado"]) . "\n\n";
        break;
    }

    // 3b) Traer mensajes nuevos
    $sql = "
      SELECT codMen, docApoRemMen, corUsuRemMen,
             docApoDestMen, corUsuDestMen,
             mensajeMen, DATE_FORMAT(fechaEnvioMen,'%H:%i') AS hora
        FROM mensaje
       WHERE codConChat = ?
         AND codMen     > ?
       ORDER BY codMen ASC
    ";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $room, $lastId);
    $stmt->execute();
    $stmt->bind_result(
        $codMen,
        $docApoRem,
        $corUsuRem,
        $docApoDest,
        $corUsuDest,
        $msgText,
        $hora
    );

    $nuevos = [];
    while ($stmt->fetch()) {
        $nuevos[] = [
            'codMen'        => $codMen,
            'docApoRemMen'  => $docApoRem,
            'corUsuRemMen'  => $corUsuRem,
            'docApoDestMen' => $docApoDest,
            'corUsuDestMen' => $corUsuDest,
            'mensajeMen'    => $msgText,
            'hora'          => $hora,
        ];
        $lastId = $codMen;
    }
    $stmt->close();


    if (count($nuevos)) {
        echo "data: " . json_encode($nuevos) . "\n\n";
    }

    // 3c) Esperar un poco antes de chequear otra vez
    // y dar al cliente tiempo de reconectar si hay error
    sleep(1);
}
