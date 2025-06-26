<?php
// KM = Consultar informacion para llenar el calendario
session_start();
require_once("../../Config/conexion.php");
$con = Conexion();

// KM = Parametros
$doc  = $_GET['docApo'] ?? $_SESSION['docApo'] ?? '';
$mes  = intval($_GET['mes'] ?? date('n'));
$anio = intval($_GET['anio'] ?? date('Y'));

$bloques = [];

if (!$doc || !$mes || !$anio) {
    echo json_encode([]);
    exit;
}

// KM = Rangos de
$fechaInicioMes = new DateTime("$anio-$mes-01");
$primerDiaSem   = (int)$fechaInicioMes->format('w');
$inicioGrid     = clone $fechaInicioMes;
$inicioGrid->modify("-{$primerDiaSem} days");
$finGrid        = clone $inicioGrid;
$finGrid->modify('+41 days');
$iniStr = $inicioGrid->format('Y-m-d');
$finStr = $finGrid->format('Y-m-d');

// KM = Consultas
$sql  = "SELECT fecha, horaIni, horaFin
         FROM disponibilidad
         WHERE docApoDisp=? AND activo=1
           AND fecha BETWEEN ? AND ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("sss", $doc, $iniStr, $finStr);
$stmt->execute();
$res = $stmt->get_result();

$bloques = [];                      // KM = Clave = YYYY-MM-DD
while ($r = $res->fetch_assoc()) {
    $f = $r['fecha'];
    $h = substr($r['horaIni'], 0, 5) . ' – ' . substr($r['horaFin'], 0, 5);
    $bloques[$f][] = ['hora' => $h, 'origen' => 'real'];
}
$stmt->close();

// KM = Consultar plantilla semanal
$sqlT = "SELECT diaSem, horaIni, horaFin
         FROM disponibilidad_template
         WHERE docApoDisp=? AND activo=1";
$stmtT = $con->prepare($sqlT);
$stmtT->bind_param("s", $doc);
$stmtT->execute();
$resT = $stmtT->get_result();

$plantilla = [];
while ($r = $resT->fetch_assoc()) {
    $dia = strtolower($r['diaSem']);
    $h   = substr($r['horaIni'], 0, 5) . ' – ' . substr($r['horaFin'], 0, 5);
    $plantilla[$dia][] = $h;
}
$stmtT->close();

// KM = Rellenar las fechas futuras
$iter = clone $inicioGrid;
while ($iter <= $finGrid) {
    $fStr  = $iter->format('Y-m-d');
    $diaEn = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'][$iter->format('w')];

    if (!isset($bloques[$fStr]) && isset($plantilla[$diaEn])) {
        foreach ($plantilla[$diaEn] as $h) {
            $bloques[$fStr][] = ['hora' => $h, 'origen' => 'template'];
        }
    }
    $iter->modify('+1 day');
}

// KM = Devuelve Json
header('Content-Type: application/json');
echo json_encode($bloques);
