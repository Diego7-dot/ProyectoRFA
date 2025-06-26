<?php
// KM = Registro persona de apoyo

// KM = Limpiar las salidas anteriores
if (ob_get_length()) ob_end_clean();
header('Content-Type: application/json; charset=utf-8');

// KM = Conexion
require_once '../../Config/conexion.php';
// KM = Llamar a PHPMailer desde la libreria
require '../../libreria/PHPMailer/src/Exception.php';
require '../../libreria/PHPMailer/src/PHPMailer.php';
require '../../libreria/PHPMailer/src/SMTP.php';

// KM = Usar PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conexion = Conexion();

// KM = Tomar los datos del formulario
$doc      = $_POST['docNuevo'] ?? '';
$correo   = $_POST['correoNuevo'] ?? '';
$nombre   = $_POST['nombreNuevo'] ?? '';
$apellido = $_POST['apellidoNuevo'] ?? '';
$fecha    = $_POST['nacimientoNuevo'] ?? '';
$genero   = $_POST['generoNuevo'] ?? '';
$telefono = $_POST['telefonoNuevo'] ?? '';
$direccion = $_POST['direccionNuevo'] ?? '';
$ciudad   = $_POST['ciudadNuevo'] ?? '';
$rol      = $_POST['rolNuevo'] ?? '';
$tarjeta  = $_POST['tarjetaNuevo'] ?? '';
$especial = $_POST['especialidadNuevo'] ?? '';
$clavePlano = $_POST['claveNuevo'] ?? '';
$claveHash = password_hash($clavePlano, PASSWORD_DEFAULT);

// KM = Validar campos obligatorios
$campos = [
    'docNuevo' => $doc,
    'correoNuevo' => $correo,
    'nombreNuevo' => $nombre,
    'apellidoNuevo' => $apellido,
    'nacimientoNuevo' => $fecha,
    'generoNuevo' => $genero,
    'telefonoNuevo' => $telefono,
    'direccionNuevo' => $direccion,
    'ciudadNuevo' => $ciudad,
    'rolNuevo' => $rol,
    'tarjetaNuevo' => $tarjeta,
    'especialidadNuevo' => $especial,
    'claveNuevo' => $clavePlano
];

$faltantes = [];
foreach ($campos as $key => $valor) {
    if (empty($valor)) $faltantes[] = $key;
}
if (!empty($faltantes)) {
    echo json_encode(['success' => false, 'error' => 'Faltan: ' . implode(', ', $faltantes)]);
    exit;
}

// KM =  Verificar duplicado (Documento y correo)
$stmt = $conexion->prepare("SELECT 1 FROM personaApoyo WHERE docApo = ? OR corApo = ?");
$stmt->bind_param("ss", $doc, $correo);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'Ya existe una persona con ese documento o correo.']);
    exit;
}

// KM = Foto de perfil
switch ($genero) {
    case 'F':
        $foto = 'icono_default_mujer.png';
        break;
    case 'M':
        $foto = 'icono_default_hombre.png';
        break;
    default:
        $foto = 'icono_default_otro.png';
        break;
}

// KM =  Insertar persona de apoyo
$stmt = $conexion->prepare("INSERT INTO personaApoyo 
(docApo, corApo, nomApo, apeApo, fecNacApo, genApo, telApo, dirApo, codCiuApo, codRolApo, numTarProApo, espProApo, pswApo, fotoPerfil, estadoApo)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE)");
$stmt->bind_param("ssssssssssssss", $doc, $correo, $nombre, $apellido, $fecha, $genero, $telefono, $direccion, $ciudad, $rol, $tarjeta, $especial, $claveHash, $foto);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'error' => 'Error al registrar en la base de datos: ' . $stmt->error]);
    exit;
}

// KM = Mandar correo con el usuario y contraseña
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
    $mail->addAddress($correo, $nombre);
    $mail->isHTML(true);

    switch ($genero) {
        case 'F':
            $saludo = "¡Bienvenida a la Red Femenina!";
            $encabezado = "Hola $nombre 💜";
            break;
        case 'M':
            $saludo = "¡Bienvenido a la Red Femenina!";
            $encabezado = "Hola $nombre 💙";
            break;
        default:
            $saludo = "¡Bienvenid@ a la Red Femenina!";
            $encabezado = "Hola $nombre 💛";
            break;
    }

    // KM = Consultar nombre del rol
    $stmtRol = $conexion->prepare("SELECT nomRol FROM rol WHERE codRol = ?");
    $stmtRol->bind_param("i", $rol);
    $stmtRol->execute();
    $resRol = $stmtRol->get_result();
    $rolNombre = ($fila = $resRol->fetch_assoc()) ? $fila['nomRol'] : 'Sin rol';

    $mail->Subject = $saludo;
    $mail->Body = "
        <h2>$encabezado</h2>
        <p>Fuiste registrada como persona de apoyo en nuestra plataforma.</p>
        <p><strong>Rol asignado:</strong> $rolNombre</p>
        <p><strong>Documento:</strong> $doc</p>
        <p><strong>Contraseña:</strong> $clavePlano</p>
        <p>Por favor inicia sesión y cambia tu contraseña lo antes posible.</p>
        <br><em>Este mensaje fue generado automáticamente. No responder.</em>
    ";
    $mail->send();
} catch (Exception $e) {
    file_put_contents(__DIR__ . '/mail_error.txt', "Error al enviar correo: " . $mail->ErrorInfo);
}

// KM = Se termino la fiesta
if (ob_get_length()) ob_end_clean();
echo json_encode(['success' => true]);
exit;
