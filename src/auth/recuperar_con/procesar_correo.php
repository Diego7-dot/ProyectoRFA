<?php
// KM = Recuperar contraseña de usuario
session_start();

require_once '../../../Config/conexion.php';
require '../../../libreria/PHPMailer/src/Exception.php';
require '../../../libreria/PHPMailer/src/PHPMailer.php';
require '../../../libreria/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Validar entrada
if (!isset($_POST['correo']) || empty(trim($_POST['correo']))) {
    header("Location: ../../../html/pagina_principal/recuperar_contraseña.php?error=correo_vacio");
    exit;
}

$correo = trim($_POST['correo']);

// Validar formato de correo
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../../../html/pagina_principal/recuperar_contraseña.php?error=formato_invalido&correo=" . urlencode($correo));
    exit;
}

$conexion = Conexion();
$sql = "SELECT * FROM usuario WHERE corUsu = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $fila = $result->fetch_assoc();
    $nombreUsuario = $fila['nomUsu'];
    $codigo = rand(100000, 999999);

    $_SESSION['codigo'] = $codigo;
    $_SESSION['correo'] = $correo;
    $_SESSION['expira_codigo'] = time() + (10 * 60); // 10 minutos

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
        $mail->Subject = 'Codigo de recuperacion de cuenta';
        $mail->Body = '<div style="font-family: Arial, sans-serif; font-size: 14px;">' .
            '<p>Hola <strong>' . htmlspecialchars($nombreUsuario) . '</strong>,</p>' .
            '<p>Tu código de recuperación es: <strong>' . $codigo . '</strong></p>' .
            '<p>Este código expirará en 10 minutos. Úsalo pronto 💜</p>' .
            '<p>Si tú no solicitaste este código, puedes ignorar este correo.</p>' .
            '<br><p>El equipo de la Red Femenina de Apoyo 💌</p></div>';

        $mail->send();
        header("Location: ../../../html/pagina_principal/recuperar_codigo.php");
        exit;
    } catch (Exception $e) {
        header("Location: ../../../html/pagina_principal/recuperar_contraseña.php?error=error_envio&correo=" . urlencode($correo));
        exit;
    }
} else {
    header("Location: ../../../html/pagina_principal/recuperar_contraseña.php?error=correo_no_encontrado&correo=" . urlencode($correo));
    exit;
}
