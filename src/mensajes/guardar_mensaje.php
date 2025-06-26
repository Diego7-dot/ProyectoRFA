<?php
// KM = Guardar mensajes del chat

// KM = Si, desactivar mensjaes de erro Json
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// KM = Archivo Json
header('Content-Type: application/json; charset=utf-8');

// KM = Inicio de sesion y conexion a la BD
session_start();
require_once __DIR__ . "/../../Config/conexion.php";
$con = Conexion();

try {
    // KM = Lectura del Json
    $input       = json_decode(file_get_contents("php://input"), true);
    $codConChat  = intval($input['codConChat'] ?? 0);
    $mensajeTxt  = trim($input['msg']       ?? '');

    // KM = Datos de la seccion
    $correoUsu   = $_SESSION['corUsu'] ?? null;   // KM = Usuario
    $docApo      = $_SESSION['docApo'] ?? null;   // KM = Persona apoyo

    // KM = Validacion basica
    if (!$codConChat) {
        echo json_encode(["exito" => false, "msg" => "Sala no válida"]);
        exit;
    }

    if ($mensajeTxt === '' || mb_strlen($mensajeTxt) > 2000) {
        echo json_encode(["exito" => false, "msg" => "Mensaje vacío o muy largo"]);
        exit;
    }

    if (!$correoUsu && !$docApo) {
        echo json_encode(["exito" => false, "msg" => "Sesión expirada"]);
        exit;
    }

    // KM = Insertar mensajes
    if ($correoUsu) {

        // KM = Usuario manda su mensaje
        $sql = "
            INSERT INTO mensaje
              (codConChat, corUsuRemMen, docApoDestMen, mensajeMen)
            SELECT ?, ?, docApoConChat, ?
              FROM conexion_chat
             WHERE codConChat = ? AND estado = 1
        ";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("issi", $codConChat, $correoUsu, $mensajeTxt, $codConChat);
    } else {
        // KM = Usuario manda su mensaje
        $sql = "
            INSERT INTO mensaje
              (codConChat, docApoRemMen, corUsuDestMen, mensajeMen)
            SELECT ?, ?, corUsuConChat, ?
              FROM conexion_chat
             WHERE codConChat = ? AND estado = 1
        ";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("issi", $codConChat, $docApo, $mensajeTxt, $codConChat);
    }

    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        echo json_encode(["exito" => false, "msg" => "No se pudo guardar el mensaje"]);
        exit;
    }

    // KM = Aviso al correo de la persona de apoyo por el primer mensaje
    if ($correoUsu) {
        $q = $con->prepare("SELECT COUNT(*) FROM mensaje WHERE codConChat = ?");
        $q->bind_param("i", $codConChat);
        $q->execute();
        $q->bind_result($total);
        $q->fetch();
        if ($total === 1) {
            avisarAsesorPorCorreo($codConChat, $correoUsu);
        }
    }

    // KM = Respuesta 
    echo json_encode([
        "exito"      => true,
        "idMensaje"  => $stmt->insert_id,
        "codConChat" => $codConChat
    ]);
} catch (\mysqli_sql_exception $e) {
    // Log interno
    error_log("Error en guardar_mensaje.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "exito" => false,
        "msg"   => "Error interno al guardar mensaje"
    ]);
    exit;
}

// KM = Funciones auxiliares 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// KM = Enviar correo
require_once __DIR__ . '/../../libreria/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../libreria/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../libreria/PHPMailer/src/SMTP.php';

// KM = Funcion para avisar al asesor por correo que le llego un mensaje 
function avisarAsesorPorCorreo(int $roomId, string $correoUsu): void
{
    $con = Conexion();
    $sql = "
      SELECT pa.corApo, pa.nomApo
        FROM conexion_chat c
        JOIN personaApoyo pa ON pa.docApo = c.docApoConChat
       WHERE c.codConChat = ?
    ";
    $st = $con->prepare($sql);
    $st->bind_param("i", $roomId);
    $st->execute();
    $st->bind_result($mailApo, $nomApo);
    if (!$st->fetch()) return;

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'redapoyofemenino@gmail.com';
        $mail->Password = 'fcvlxktguryptrzv';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // KM = Contenido del mensaje
        $mail->setFrom('redapoyofemenino@gmail.com', 'Chat FEM');
        $mail->addAddress($mailApo, $nomApo);
        $mail->Subject = 'Nueva usuario en tu chat';
        $mail->Body    = "Hola $nomApo:\n\n"
            . "El usuario $correoUsu acaba de iniciar una conversación. "
            . "Ingresa a la plataforma para ofreces asesoramiento.\n\n"
            . "— Sistema de chat FEM";

        $mail->send();
    } catch (Exception $e) {
        error_log("Aviso chat (correo): " . $mail->ErrorInfo);
    }
}
