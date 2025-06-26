<?php
// KM = Generar la tabla "disponibilidad" a partir de la plantilla semanal
require_once("../../Config/conexion.php");

$con = Conexion();

// KM = Verificamos si se recibe el documento por POST (puede venir de JS o de otro include)
$docApo = $_POST['docApoDisp'] ?? '';

if (empty($docApo)) {
    echo json_encode([
        "exito" => false,
        "mensaje" => "No se recibió el documento de la persona de apoyo."
    ]);
    exit;
}

// KM = Verificar si la persona tiene disponibilidad general activa
$verificarGen = $con->prepare("SELECT estGen, motDis FROM disponibilidad_template WHERE docApoDisp = ? ORDER BY idTemplate DESC LIMIT 1");
$verificarGen->bind_param("s", $docApo);
$verificarGen->execute();
$verificarGen->bind_result($estadoGeneral, $motivo);
$verificarGen->fetch();
$verificarGen->close();

if (!$estadoGeneral) {
    echo json_encode([
        "exito" => false,
        "mensaje" => "La disponibilidad general está desactivada para esta persona. Motivo: " . ($motivo ?? 'Sin especificar.')
    ]);
    exit;
}

try {
    // KM = Traer la plantilla de disponibilidad del asesor
    $sql = $con->prepare("SELECT diaSem, horaIni, horaFin, fecIniApli FROM disponibilidad_template WHERE docApoDisp = ? AND activo = TRUE");
    $sql->bind_param("s", $docApo);
    $sql->execute();
    $result = $sql->get_result();

    $plantilla = [];
    while ($row = $result->fetch_assoc()) {
        $plantilla[] = $row;
    }
    $sql->close();

    if (empty($plantilla)) {
        echo json_encode([
            "exito" => false,
            "mensaje" => "No hay plantilla activa para este documento."
        ]);
        exit;
    }

    // KM = Mapeo de dias para calcular fechas concretas
    $diasSemana = [
        'lunes' => 1,
        'martes' => 2,
        'miércoles' => 3,
        'jueves' => 4,
        'viernes' => 5,
        'sábado' => 6,
        'domingo' => 0
    ];

    $insertados = 0;

    foreach ($plantilla as $item) {
        $diaTexto = strtolower($item['diaSem']);
        $horaIni = $item['horaIni'];
        $horaFin = $item['horaFin'];
        $inicio = new DateTime($item['fecIniApli']);

        // KM = Avanzar al primer dia de la semana que coincida
        while ((int)$inicio->format('w') !== $diasSemana[$diaTexto]) {
            $inicio->modify('+1 day');
        }

        // KM = Generar fecha exacta y registrar
        $fecha = $inicio->format('Y-m-d');

        // KM = Verificar que no exista esa fecha ya
        $verificar = $con->prepare("SELECT COUNT(*) FROM disponibilidad WHERE docApoDisp = ? AND fecha = ?");
        $verificar->bind_param("ss", $docApo, $fecha);
        $verificar->execute();
        $verificar->bind_result($existe);
        $verificar->fetch();
        $verificar->close();

        if ($existe > 0) continue;

        $stmt = $con->prepare("INSERT INTO disponibilidad (docApoDisp, fecha, horaIni, horaFin, genDesTem, activo) VALUES (?, ?, ?, ?, TRUE, TRUE)");
        $stmt->bind_param("ssss", $docApo, $fecha, $horaIni, $horaFin);

        if ($stmt->execute()) {
            $insertados++;
        }

        $stmt->close();
    }

    echo json_encode([
        "exito" => true,
        "mensaje" => "Se generaron $insertados registros de disponibilidad."
    ]);
} catch (Exception $e) {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Error al generar la disponibilidad: " . $e->getMessage()
    ]);
}
