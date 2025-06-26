<?php
// KM = Editar persona de apoyo (NO modifica el documento)
require_once("../../Config/conexion.php");
$con = Conexion();

// KM = Llamar a PHPMailer desde la libreria
require '../../libreria/PHPMailer/src/Exception.php';
require '../../libreria/PHPMailer/src/PHPMailer.php';
require '../../libreria/PHPMailer/src/SMTP.php';

// KM = Usar PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// KM = Obtener datos del formulario
$docActual     = $_POST["documento_edit"]          ?? '';
$correo        = $_POST["correo_edit"]             ?? '';
$nombre        = $_POST["nombre_edit"]             ?? '';
$apellido      = $_POST["apellido_edit"]           ?? '';
$fechaNac      = $_POST["fechaNacimiento_edit"]    ?? '';
$genero        = $_POST["genero_edit"]             ?? '';
$telefono      = $_POST["telefono_edit"]           ?? '';
$direccion     = $_POST["direccion_edit"]          ?? '';
$ciudad        = $_POST["codigoCiudad_edit"]       ?? '';
$rol           = $_POST["rol_edit"]                ?? '';
$tarjeta       = $_POST["tarjeta_edit"]            ?? '';
$especialidad  = $_POST["especialidad_edit"]       ?? '';
$estado        = $_POST["estado_edit"]             ?? '1';
$motivo        = $_POST["motivo_edit"]             ?? '';

$cambiarPass   = $_POST["seccionContrasenaActiva"] ?? '0';
$nuevaPsw      = $_POST["nuevaPsw"]                ?? '';

$huboCambios = false; // KM = Verficiar si hubo cambios antes de editar

// KM = Obtener valores actuales de la persona de apoyo antes de editar
$sqlAnterior = "SELECT * FROM personaApoyo WHERE docApo = ?";
$stmtAnterior = $con->prepare($sqlAnterior);
$stmtAnterior->bind_param("s", $docActual);
$stmtAnterior->execute();
$resAnterior = $stmtAnterior->get_result();
$datosAnteriores = $resAnterior->fetch_assoc();

$correoOriginal  = $datosAnteriores['corApo'];
$nombreBD        = $datosAnteriores['nomApo'];
$apellidoBD      = $datosAnteriores['apeApo'];
$telefonoBD      = $datosAnteriores['telApo'];
$direccionBD     = $datosAnteriores['dirApo'];
$especialidadBD  = $datosAnteriores['espProApo'];
$rolAnterior     = $datosAnteriores['codRolApo'];
$estadoAnterior = (string) $datosAnteriores['estadoApo'];

// KM = Obtener nombre del rol anterior y nuevo
$sqlRolAnterior = "SELECT nomRol FROM rol WHERE codRol = ?";
$stmtRolAnt = $con->prepare($sqlRolAnterior);
$stmtRolAnt->bind_param("i", $rolAnterior);
$stmtRolAnt->execute();
$resRolAnt = $stmtRolAnt->get_result();
$nombreRolAnterior = ($filaRolAnt = $resRolAnt->fetch_assoc()) ? $filaRolAnt['nomRol'] : 'Desconocido';

$sqlRolNuevo = "SELECT nomRol FROM rol WHERE codRol = ?";
$stmtRolNvo = $con->prepare($sqlRolNuevo);
$stmtRolNvo->bind_param("i", $rol);
$stmtRolNvo->execute();
$resRolNvo = $stmtRolNvo->get_result();
$nombreRolNuevo = ($filaRolNvo = $resRolNvo->fetch_assoc()) ? $filaRolNvo['nomRol'] : 'Desconocido';

// KM = Verificar que el correo no esté repetido en otro registro
if (!empty($correo)) {
    $sqlCorreo = "SELECT docApo FROM personaApoyo WHERE corApo = ? AND docApo != ?";
    $stmtCorreo = $con->prepare($sqlCorreo);
    $stmtCorreo->bind_param("ss", $correo, $docActual);
    $stmtCorreo->execute();
    $resCorreo = $stmtCorreo->get_result();

    if ($resCorreo->num_rows > 0) {
        echo json_encode([
            "success" => false,
            "error" => "⚠️ El correo ya está registrado por otra persona."
        ]);
        exit;
    }
}

// KM = Actualizar todos los campos menos el documento
$sqlUpdate = "UPDATE personaApoyo SET 
    corApo = ?, nomApo = ?, apeApo = ?, fecNacApo = ?, genApo = ?, telApo = ?, dirApo = ?, 
    codCiuApo = ?, codRolApo = ?, numTarProApo = ?, espProApo = ?, estadoApo = ?
    WHERE docApo = ?";

$stmt = $con->prepare($sqlUpdate);
$stmt->bind_param(
    "sssssssssssis",
    $correo,
    $nombre,
    $apellido,
    $fechaNac,
    $genero,
    $telefono,
    $direccion,
    $ciudad,
    $rol,
    $tarjeta,
    $especialidad,
    $estado,
    $docActual
);

$exito = $stmt->execute();
if (!$exito) {
    echo json_encode(["success" => false, "error" => "Error al actualizar persona de apoyo"]);
    exit;
}

// KM = Registrar motivo si se inactiva
if ($estado == "0" && !empty($motivo)) {
    $sqlMotivo = "INSERT INTO historialEstadoPersonaApoyo (docApo, motivoCambio, fechaCambio)
                  VALUES (?, ?, NOW())";
    $stmtMotivo = $con->prepare($sqlMotivo);
    $stmtMotivo->bind_param("ss", $docActual, $motivo);
    $stmtMotivo->execute();
}

// KM = Si se cambia la contraseña
if ($cambiarPass == "1" && !empty($nuevaPsw)) {
    $hashed = password_hash($nuevaPsw, PASSWORD_DEFAULT);
    $sqlPass = "UPDATE personaApoyo SET pswApo = ? WHERE docApo = ?";
    $stmtPass = $con->prepare($sqlPass);
    $stmtPass->bind_param("ss", $hashed, $docActual);
    $stmtPass->execute();
}

// KM = Fallo al enciar el correo 
function enviarCorreoCambio($destinatario, $asunto, $cuerpo)
{
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
        $mail->addAddress($destinatario);
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $cuerpo;
        $mail->send();
    } catch (Exception $e) {
        file_put_contents(__DIR__ . '/mail_error.txt', "Error al enviar correo a $destinatario: " . $mail->ErrorInfo . PHP_EOL, FILE_APPEND);
    }
}

// KM = Informa al correo anterior que se cambió por otro
if ($correo !== $correoOriginal) {
    $mensajeAnterior = "<h2>¡Tu acceso ha sido actualizado!</h2><p>El administrador de la plataforma ha registrado un nuevo correo para tu cuenta.</p><p><strong>Documento asociado:</strong> $docActual</p>";
    if ($cambiarPass == "1" && !empty($nuevaPsw)) {
        $mensajeAnterior .= "<p>Además, se ha actualizado tu contraseña por motivos administrativos.</p><p>Si no reconoces este cambio, contacta al equipo de soporte.</p>";
    } else {
        $mensajeAnterior .= "<p>Tu contraseña actual sigue siendo válida 💜</p>";
    }
    enviarCorreoCambio($correoOriginal, "Aviso: tu correo fue actualizado", $mensajeAnterior);

    $mensajeNuevo = "<h2>¡Bienvenido/a con nuevo correo!</h2><p>Este es tu nuevo acceso registrado.</p><p><strong>Documento:</strong> $docActual</p>";
    if ($cambiarPass == "1" && !empty($nuevaPsw)) {
        $mensajeNuevo .= "<p>También se cambió tu contraseña por razones administrativas. Puedes restablecerla en cualquier momento usando la opción de recuperar contraseña 💜</p>";
    } else {
        $mensajeNuevo .= "<p>Tu contraseña actual sigue siendo válida.</p>";
    }
    enviarCorreoCambio($correo, "Nuevo correo registrado", $mensajeNuevo);
}

// KM = Correo para cuando se cambian los datos
$huboCambioRol = ((int)$rol !== (int)$rolAnterior);

if ($nombre !== $nombreBD || $apellido !== $apellidoBD || $telefono !== $telefonoBD || $direccion !== $direccionBD || $especialidad !== $especialidadBD || $huboCambioRol) {
    $huboCambios = true;
}

if ($huboCambios) {
    // KM = Debug opcional 
    file_put_contents("debug_envio.txt", "Rol anterior: $rolAnterior ($nombreRolAnterior), nuevo rol: $rol ($nombreRolNuevo) para $correo\n", FILE_APPEND);

    $resumen = "<h3>Actualización de tus datos</h3><p>El administrador ha realizado cambios en tus datos registrados:</p><ul>
        <li><strong>Nombre:</strong> $nombre $apellido</li>
        <li><strong>Teléfono:</strong> $telefono</li>
        <li><strong>Dirección:</strong> $direccion</li>
        <li><strong>Especialidad:</strong> $especialidad</li>";
    if ($huboCambioRol) {
        $resumen .= "<li><strong>Rol anterior:</strong> $nombreRolAnterior</li><li><strong>Nuevo rol:</strong> $nombreRolNuevo</li>";
    }
    $resumen .= "</ul><p>Este procedimiento fue hecho por el personal autorizado del sistema.</p>";
    enviarCorreoCambio($correo, "Datos actualizados", $resumen);
}

// KM = Informa de cambio de contraseña
if ($cambiarPass == "1" && !empty($nuevaPsw)) {
    $mensajePass = "<h3>Tu contraseña fue actualizada</h3><p>El administrador de la Red Femenina de Apoyo ha cambiado tu contraseña por motivos administrativos.</p><p>Por razones de seguridad, la nueva contraseña no se muestra en este mensaje.</p><p>Puedes restablecer la contraseña desde el sistema usando la opción de <strong>recuperar contraseña</strong>.</p><br><p>Gracias por tu comprensión 💜</p>";
    enviarCorreoCambio($correo, "Tu contraseña fue cambiada", $mensajePass);
}

// KM = Informa al usuario que se desactivo y el motivo o si se habilito
if ($estado == "0") {
    $motivo = htmlspecialchars($motivo, ENT_QUOTES);
    $mensajeDesactivado = "<h3>Tu cuenta ha sido desactivada</h3><p>El administrador ha marcado tu cuenta como inactiva.</p><p>Motivo registrado: <em>$motivo</em></p><p>Si tienes alguna duda, puedes comunicarte con el equipo de soporte.</p>";
    enviarCorreoCambio($correo, "Tu cuenta fue desactivada", $mensajeDesactivado);
} elseif ($estado == "1" && $estadoAnterior === "0") {
    $mensajeActivado = "<h3>¡Tu cuenta ha sido reactivada!</h3><p>El administrador te ha devuelto el acceso a la plataforma 💜</p><p>Ya puedes ingresar nuevamente. ¡Gracias por tu paciencia!</p>";
    enviarCorreoCambio($correo, "Tu cuenta fue reactivada", $mensajeActivado);
}

// KM = Todo salió bien
echo json_encode(["success" => true]);
exit;
