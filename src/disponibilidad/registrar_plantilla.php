<?php
// KM = Registro de plantilla para los dias de disponibilidad
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// KM = Conexion y formato del pdf para la disponibilidad 
require_once("../../Config/conexion.php");

// KM = Llamar el formato del pdf
require_once("pdf_disponibilidad/generar_pdf_disponibilidad.php");

// KM =  Validacion de documento con el que se inicio sesion
session_start();

// KM = Libreria del PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../libreria/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../libreria/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../libreria/PHPMailer/src/SMTP.php';

// KM = La zona horaria por default es bogota
date_default_timezone_set('America/Bogota');

// KM = Indicamos que devolveremos una respuesta JSON
header("Content-Type: application/json");

// KM = Traer la informacion del fomulario y procesarla
$rawInput = file_get_contents("php://input");
$rawInput = trim($rawInput);
$rawInput = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $rawInput);
file_put_contents(__DIR__ . '/log_raw_input.txt', "\n--- NUEVO INTENTO ---\n" . $rawInput . "\n", FILE_APPEND);

// KM = Procesar el JSON correctamente
$datos = json_decode($rawInput, true);

if ($datos === null && json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Error al decodificar JSON: " . json_last_error_msg()
    ]);
    exit;
}

if (!isset($_SESSION["docApo"])) {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Sesión no iniciada."
    ]);
    exit;
}

$docApoDisp = $datos["docApoDisp"] ?? $_SESSION["docApo"];
$disponibilidad = $datos["disponibilidad"] ?? [];

if (!$docApoDisp || !is_array($disponibilidad) || count($disponibilidad) === 0) {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Faltan datos obligatorios."
    ]);
    exit;
}

$con = Conexion();

// KM = Funcion para determinar el dia de la semana y cuando empieza la impresion de la plantilla
function calcularFechaSemanaActual($dia)
{
    $diasSemana = ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"];
    $indiceDia = array_search(mb_strtolower($dia), $diasSemana);
    if ($indiceDia === false) return null;
    $hoy = date('w');
    $offset = ($indiceDia - $hoy + 7) % 7;
    return date('Y-m-d', strtotime("+$offset days"));
}

$insertados = 0;

foreach ($disponibilidad as $item) {
    $diaSem = strtolower(trim($item["dia"] ?? ''));
    $horaIni = $item["horaIni"] ?? '';
    $horaFin = $item["horaFin"] ?? '';
    $fecIni = !empty($item["fechaInicio"]) ? $item["fechaInicio"] : null;
    $fecFin = !empty($item["fechaFin"]) ? $item["fechaFin"] : null;
    $obsDis = !empty($item["observacion"]) ? $item["observacion"] : null;
    $bloque = intval($item["bloque"] ?? 1);

    // KM = Corregir tildes comunes
    $mapaTildes = [
        'mircoles' => 'miércoles',
        'miercoles' => 'miércoles',
        'sbado'     => 'sábado',
        'sabado'    => 'sábado'
    ];

    $diaSem = $mapaTildes[$diaSem] ?? $diaSem;

    $fechaAplicada = calcularFechaSemanaActual($diaSem);
    $hoy = date('Y-m-d');
    $domingoActual = date('Y-m-d', strtotime('next sunday'));

    $diasSemana = ["lunes", "martes", "miércoles", "jueves", "viernes", "sábado", "domingo"];
    if (!in_array($diaSem, $diasSemana)) continue;
    if (empty($horaIni) || empty($horaFin)) continue;

    $fecIniApli = date('Y-m-d');

    // KM = Insertar siempre en disponibilidad_template
    $stmt = $con->prepare("INSERT INTO disponibilidad_template 
        (docApoDisp, diaSem, horaIni, horaFin, fecIni, fecFin, obsDis, activo, fecIniApli, bloque) 
        VALUES (?, ?, ?, ?, ?, ?, ?, TRUE, ?, ?)");
    $stmt->bind_param("ssssssssi", $docApoDisp, $diaSem, $horaIni, $horaFin, $fecIni, $fecFin, $obsDis, $fecIniApli, $bloque);

    if ($stmt->execute()) {
        $insertados++;

        // KM = Calcular fecha concreta para este dia de la semana
        $fechaAplicada = calcularFechaSemanaActual($diaSem);
        if ($fechaAplicada && $fechaAplicada >= $hoy && $fechaAplicada <= $domingoActual) {
            $verificar2 = $con->prepare("SELECT COUNT(*) FROM disponibilidad 
                WHERE docApoDisp = ? AND fecha = ? AND horaIni = ? AND horaFin = ?");
            $verificar2->bind_param("ssss", $docApoDisp, $fechaAplicada, $horaIni, $horaFin);
            $verificar2->execute();
            $verificar2->bind_result($existe2);
            $verificar2->fetch();
            $verificar2->close();

            if ($existe2 == 0) {
                $stmt2 = $con->prepare("INSERT INTO disponibilidad 
                    (docApoDisp, fecha, horaIni, horaFin, genDesTem, activo) 
                    VALUES (?, ?, ?, ?, TRUE, TRUE)");
                $stmt2->bind_param("ssss", $docApoDisp, $fechaAplicada, $horaIni, $horaFin);
                $stmt2->execute();
                $stmt2->close();
            }
        }
    }
}

// KM = Obtener los datos recien guardados
$plantillaSemanal = [];

$consultaPDF = $con->prepare("
    SELECT diaSem AS dia, horaIni, horaFin
      FROM disponibilidad_template
     WHERE docApoDisp = ? AND activo = 1
  ORDER BY FIELD(diaSem,'lunes','martes','miércoles','jueves',
                          'viernes','sábado','domingo'),
           horaIni");

$consultaPDF->bind_param("s", $docApoDisp);
$consultaPDF->execute();
$plantillaSemanal = $consultaPDF->get_result()->fetch_all(MYSQLI_ASSOC);
$consultaPDF->close();

// KM = Datos de la persona de apoyo
$info = $con->prepare("SELECT nomApo, corApo FROM personaApoyo WHERE docApo=?");

$info->bind_param("s", $docApoDisp);
$info->execute();
$info->bind_result($nombreApo, $correoApo);
$info->fetch();
$info->close();

// KM = Generar pdf
$pdfObj  = generarPDFDisponibilidad($docApoDisp, $nombreApo, $plantillaSemanal);
$rutaPDF = __DIR__ . "/../../temporal/plantilla_{$docApoDisp}.pdf";
$pdfObj->Output('F', $rutaPDF);

// KM = Enviar correo con PHPMailer
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'redapoyofemenino@gmail.com';
    $mail->Password = 'fcvlxktguryptrzv';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('redapoyofemenino@gmail.com', 'Red Femenina de Apoyo');
    $mail->addAddress($correoApo, $nombreApo);
    $mail->Subject = 'Tu disponibilidad ha sido registrada';
    $mail->Body = "Hola $nombreApo,\n\n" .
        "¡Tu plantilla de disponibilidad se ha guardado correctamente!\n" .
        "Adjuntamos un PDF con el resumen de los bloques que configuraste.\n\n" .
        "• Podrás consultarla o modificarla cuando lo necesites desde el módulo de Disponibilidad.\n" .
        "• Los cambios que hagas se aplicarán automáticamente a las próximas semanas.\n\n" .
        "¡Gracias por tu compromiso!\n" .
        "Equipo de la Red Femenina de Apoyo";

    $mail->addAttachment($rutaPDF);
    $mail->send();
} catch (Exception $e) {
    error_log("Error enviando correo: " . $mail->ErrorInfo);
}

echo json_encode([
    "exito" => $insertados > 0,
    "mensaje" => $insertados > 0 ? "Plantilla registrada exitosamente." : "No se insertó ningún nuevo día."
]);
