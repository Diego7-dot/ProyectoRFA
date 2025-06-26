<?php
// KM = Consultar datos de persona de apoyo
session_start();
require_once("../../Config/conexion.php");
$con = Conexion();

// KM = Validar sesión activa correctamente
$documento = $_SESSION['docApo'] ?? '';
if (!$documento) {
    header("Location: ../../../html/login/login_admin.php?error=No autorizado");
    exit();
}

// KM = Obtener también codCiu y nombre del rol y ciudad
$sql = "SELECT pa.*, r.nomRol, c.nomCiu 
        FROM personaApoyo pa
        JOIN rol r ON pa.codRolApo = r.codRol
        JOIN ciudad c ON pa.codCiuApo = c.codCiu
        WHERE pa.docApo = ?";


$stmt = $con->prepare($sql);
$stmt->bind_param("s", $documento);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $row = $res->fetch_assoc()) {
    $correo       = $row['corApo'] ?? '';
    $nombre       = $row['nomApo'] ?? '';
    $apellido     = $row['apeApo'] ?? '';
    $fecNac       = isset($row['fecNacApo']) ? date('Y-m-d', strtotime($row['fecNacApo'])) : '';
    $genero       = $row['genApo'] ?? '';
    $telefono     = $row['telApo'] ?? '';
    $direccion    = $row['dirApo'] ?? '';
    $codCiudad    = $row['codCiuApo'] ?? '';
    $nombreCiudad = $row['nomCiu'] ?? '';
    $codRol       = $row['codRolApo'] ?? '';
    $nombreRol    = $row['nomRol'] ?? '';
    $tarjeta      = $row['numTarProApo'] ?? '';
    $especialidad = $row['espProApo'] ?? '';
    $foto         = $row['fotoPerfil'] ?? 'icono_default_otro.png';
} else {
    header("Location: ../../../html/login/login_admin.php?error=No encontrado");
    exit();
}

// KM = Traer todas las ciudades disponibles para mostrar en el select
$ciudades = [];
$sqlCiudades = "SELECT codCiu, nomCiu FROM ciudad";
$resCiudades = $con->query($sqlCiudades);
if ($resCiudades) {
    while ($ciu = $resCiudades->fetch_assoc()) {
        $ciudades[] = $ciu;
    }
}
