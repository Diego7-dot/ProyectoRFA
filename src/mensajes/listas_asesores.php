<?php
// KM = Traer infromacion del asesor para la lista del chat
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/../../Config/conexion.php';

// KM = Verificar correo del usaurio
$correoUsu = $_SESSION['corUsu'] ?? '';
if (!$correoUsu) {
    echo json_encode(['exito' => false, 'msg' => 'No has iniciado sesión']);
    exit;
}

// KM = COnsulta
$con = Conexion();
$sql = "
  SELECT 
    c.docApoConChat   AS docApo,
    pa.nomApo         AS nombre,
    pa.apeApo         AS apellido,
    pa.espProApo      AS especialidad,
    pa.genApo         AS genero, 
    pa.fotoPerfil     AS avatar_path
  FROM conexion_chat c
  JOIN personaApoyo pa 
    ON pa.docApo = c.docApoConChat
  WHERE c.corUsuConChat = ?
    AND c.estado        = 1
  LIMIT 1
";

$stmt = $con->prepare($sql);
$stmt->bind_param("s", $correoUsu);
$stmt->execute();
$res = $stmt->get_result();

$asesor = $res->fetch_assoc();

if (!$asesor) {
    echo json_encode(['exito' => false, 'msg' => 'No tienes asesor asignado']);
    exit;
}

define('ICON_DIR', __DIR__ . '/../../iconos/iconos_perfil/');
define('ICON_URL', '/Proyecto_real_v1/Proyecto_real/iconos/iconos_perfil/');

// KM = Definir el genero del asesor
$file = trim($asesor['avatar_path']);
if (empty($file) || ! file_exists(ICON_DIR . $file)) {
    $file = 'icono_default_otro.php';
}
$avatarUrl = ICON_URL . $file;

// 3) Ahora traduces su género al rol concreto
switch ($asesor['genero']) {
    case 'F':
        $rolGenero = 'Asesora';
        break;
    case 'M':
        $rolGenero = 'Asesor';
        break;
    default:
        $rolGenero = 'Asesor@';
        break;
}

echo json_encode([
    'exito'  => true,
    'asesor' => [
        'docApo'       => $asesor['docApo'],
        'nombre'       => "{$asesor['nombre']} {$asesor['apellido']}",
        'especialidad' => $asesor['especialidad'],
        'rol'          => $rolGenero,
        'avatar'       => $avatarUrl
    ]
]);
