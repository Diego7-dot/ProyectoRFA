<?php
// KM = Reenviar el codigo por si falla en enviarlo la primera vez (prueba)
session_start();
require_once '../../../Config/conexion.php';
require '../../../libreria/PHPMailer/src/Exception.php';
require '../../../libreria/PHPMailer/src/PHPMailer.php';
require '../../../libreria/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['correo']) || !isset($_SESSION['codigo'])) {
    echo "Sesión inválida. No se puede reenviar.";
    exit;
}

$correo = $_SESSION['correo'];
$codigo = $_SESSION['codigo'];
$conexion = Conexion();

$sql = "SELECT * FROM usuario WHERE corUsu = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $fila = $result->fetch_assoc();
    $nombre = $fila['nomUsu'];

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
        $mail->Subject = 'Reenvío de código de recuperación';
        $mail->Body = "<p>Hola <strong>$nombre</strong>, tu código sigue siendo: <strong>$codigo</strong></p><p>Recuerda que este código caduca en 10 minutos desde su primer envío.</p>";

        $mail->send();
        echo "Código reenviado";
    } catch (Exception $e) {
        echo "Error al reenviar: " . $mail->ErrorInfo;
    }
} else {
    echo "Correo no encontrado.";
}
