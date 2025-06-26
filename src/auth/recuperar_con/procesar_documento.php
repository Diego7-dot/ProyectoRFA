<?php
// KM = Recuperar contraseña de persona de apoyo
session_start();

require_once '../../../Config/conexion.php';
require '../../../libreria/PHPMailer/src/Exception.php';
require '../../../libreria/PHPMailer/src/PHPMailer.php';
require '../../../libreria/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// KM = Validar entrada
if (!isset($_POST['documento'], $_POST['codRolApo']) || trim($_POST['documento']) === '') {
    $doc = isset($_POST['documento']) ? urlencode(trim($_POST['documento'])) : '';
    header("Location: ../../../html/pagina_principal/recuperar_contraseña_apoyo.php?error=campos_vacios&documento=$doc");
    exit;
}

$documento = trim($_POST['documento']);
$rol = intval($_POST['codRolApo']);

$conexion = Conexion();

//  KM = Si esta innavilitado  no mandara el correo de recuperacion
$sql = "SELECT estadoApo FROM personaApoyo WHERE docApo = ? AND codRolApo = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("si", $documento, $rol);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if ($row['estadoApo'] == 0) {
        // KM = Inhabilitado
        header("Location: ../../../html/pagina_principal/recuperar_contraseña_apoyo.php?error=inhabilitado&documento=" . urlencode($documento));
        exit;
    }
} else {
    echo json_encode([
        'error' => true,
        'motivo' => 'El documento y rol no coinciden con ningún registro.'
    ]);
    exit;
}

$sql = "SELECT p.*, r.nomRol FROM personaApoyo p JOIN rol r ON p.codRolApo = r.codRol WHERE p.docApo = ? AND p.codRolApo = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("si", $documento, $rol);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $fila = $result->fetch_assoc();
    $correo = $fila['corApo'];
    $nombre = $fila['nomApo'];
    $genero = $fila['genApo'];
    $rolNombre = strtolower($fila['nomRol']);

    // Ajustar por género
    if ($genero === 'F') {
        if ($rol === 1) $rolNombre = 'administradora';
        elseif ($rol === 2) $rolNombre = 'psicóloga';
        elseif ($rol === 3) $rolNombre = 'asesora';
        elseif ($rol === 4) $rolNombre = 'enfermera';
        elseif ($rol === 5) $rolNombre = 'consultora legal';
    } elseif ($genero === 'O') {
        $rolNombre = 'persona de apoyo';
    }

    // KM = Generar código y expira en 10 minutos
    $codigo = rand(100000, 999999);
    $_SESSION['codigo_apoyo'] = $codigo;
    $_SESSION['documento_apoyo'] = $documento;
    $_SESSION['rol_apoyo'] = $rol;
    $_SESSION['expira_codigo_apoyo'] = time() + (10 * 60);

    // KM = Guardar código en la tabla recuperacionApo
    $sqlRec = $conexion->prepare("INSERT INTO recuperacionApo (docRecApo, codRolRecApo, codRecApo) VALUES (?, ?, ?)");
    $sqlRec->bind_param("sis", $documento, $rol, $codigo);
    $sqlRec->execute();

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'redapoyofemenino@gmail.com';
        $mail->Password = 'fcvlxktguryptrzv';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('redapoyofemenino@gmail.com', 'Red Femenina de Apoyo');
        $mail->addAddress($correo);
        $mail->isHTML(true);
        $mail->Subject = 'Codigo de recuperacion - Persona de Apoyo';
        $mail->Body = '<div style="font-family: Arial, sans-serif; font-size: 14px;">'
            . '<p>Hola <strong>' . htmlspecialchars($rolNombre) . ' ' . htmlspecialchars($nombre) . '</strong>,</p>'
            . '<p>Tu código de recuperación es: <strong>' . $codigo . '</strong></p>'
            . '<p>Este código expirará en 10 minutos. Úsalo pronto 💜</p>'
            . '<p>Si tú no solicitaste este código, puedes ignorar este correo.</p>'
            . '<br><p>El equipo de la Red Femenina de Apoyo 📬</p></div>';

        $mail->send();
        header("Location: ../../../html/pagina_principal/recuperar_codigo_apoyo.php");
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Error al enviar el correo: " . addslashes($mail->ErrorInfo) . "'); window.history.back();</script>";
    }
} else {
    header("Location: ../../../html/pagina_principal/recuperar_contraseña_apoyo.php?error=no_encontrado&documento=" . urlencode($documento));
    exit;
}
