<?php
// KM = Consultar usuarios (Bueno si, luego de que no me dejara un error por como consultaba, simplemnete lo separe jaja)
session_start();
require_once("../../Config/conexion.php");
$con = Conexion();

// KM = Consultar correo
$correo = $_SESSION['corUsu'] ?? '';

// KM = Traer la informacion del usuario
$sql = "SELECT nomUsu, fecNacUsu, genUsu, fotoPerfil FROM usuario WHERE corUsu = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$res = $stmt->get_result();

// KM = Llenar
if ($res && $row = $res->fetch_assoc()) {
    $nombre = $row['nomUsu'];
    $fecNacUsu = date('Y-m-d', strtotime($row['fecNacUsu']));
    $genero = $row['genUsu'];
    $foto = $row['fotoPerfil'];
} else {
    $nombre = '';
    $fecNacUsu = '';
    $genero = '';
    $foto = '';
}
