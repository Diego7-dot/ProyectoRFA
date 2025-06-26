<?php
// KM = Cargar una persona de apoyo por documento (para editar)
require_once("../../Config/conexion.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);
$con = Conexion();

// KM = Validar que se encuentre el documento
if (!isset($_GET['docApo'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Falta el documento']);
    exit;
}

$docApo = $_GET['docApo'];

// KM = Consulta con join la ciudad y rol
$sql = "SELECT 
  pa.*, 
  r.nomRol, r.codRol AS codRolApo, 
  c.nomCiu, c.codCiu AS codCiuApo
FROM personaApoyo pa
JOIN rol r ON pa.codRolApo = r.codRol
JOIN ciudad c ON pa.codCiuApo = c.codCiu
WHERE pa.docApo = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("s", $docApo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Persona de apoyo no encontrada']);
    exit;
}

$persona = $resultado->fetch_assoc();

// KM = Alias para que coincidan con el JS
$persona['correo']        = $persona['corApo'];
$persona['nombre']        = $persona['nomApo'];
$persona['apellido']      = $persona['apeApo'];
$persona['fecNac']        = $persona['fecNacApo'];
$persona['genero']        = $persona['genApo'];
$persona['telefono']      = $persona['telApo'];
$persona['direccion']     = $persona['dirApo'];
$persona['codCiu']        = $persona['codCiuApo'];
$persona['codRol']        = $persona['codRolApo'];
$persona['tarjeta']       = $persona['numTarProApo'];
$persona['especialidad']  = $persona['espProApo'];
$persona['estado']        = $persona['estadoApo'];

// KM = Limpiar campos innecesarios
unset(
    $persona['corApo'],
    $persona['nomApo'],
    $persona['apeApo'],
    $persona['fecNacApo'],
    $persona['genApo'],
    $persona['telApo'],
    $persona['dirApo'],
    $persona['codCiuApo'],
    $persona['codRolApo'],
    $persona['numTarProApo'],
    $persona['espProApo'],
    $persona['estadoApo']
);

// KM = Agregar lista de ciudades
$persona['ciudades'] = [];
$resCiu = $con->query("SELECT codCiu, nomCiu FROM ciudad");
while ($row = $resCiu->fetch_assoc()) {
    $persona['ciudades'][] = $row;
}

// KM = Agregar lista de roles (excepto administrador)
$persona['roles'] = [];
$resRol = $con->query("SELECT codRol, nomRol FROM rol WHERE nomRol != 'Administrador'");
while ($row = $resRol->fetch_assoc()) {
    $persona['roles'][] = $row;
}

// KM = Verificar si la persona está inactiva y obtener el último motivo
if ($persona['estado'] == 0) {
    $sqlMotivo = "SELECT motivoCambio, fechaCambio 
              FROM historialEstadoPersonaApoyo 
              WHERE docApo = ? 
              ORDER BY fechaCambio DESC 
              LIMIT 1";
    $stmtMotivo = $con->prepare($sqlMotivo);
    $stmtMotivo->bind_param("s", $docApo);
    $stmtMotivo->execute();
    $resMotivo = $stmtMotivo->get_result();

    if ($rowMotivo = $resMotivo->fetch_assoc()) {
        $persona['motivoInactivo'] = $rowMotivo['motivoCambio'];
    } else {
        $persona['motivoInactivo'] = '';
    }
}

// KM = Ocultar contraseña si no deseas mostrarla (opcional)
// unset($persona['pswEmp']);

echo json_encode($persona);
