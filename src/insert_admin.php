<?php
// KM = Esta es una insercion de un administrador de pruebas (Temporal ya que encontre fallas en el insert desde la bd por la contraseña)
// KM = Rol = Administrador
// KM = Documento = 12345678902
// KM = Contraseña = admin1234

// KM = Conexion con la db
require_once(__DIR__ . "/../Config/conexion.php");
$con = Conexion();

// KM = Generar hash desde PHP de forma segura
$clave = "admin1234";
$hash = password_hash($clave, PASSWORD_BCRYPT);

// KM = Insertar en la BD
$sql = "INSERT INTO personaApoyo (
    docApo, corApo, nomApo, apeApo, fecNacApo, genApo, telApo, dirApo,
    codCiuApo, codRolApo, numTarProApo, espProApo, pswApo, estadoApo, fotoPerfil
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// KM = Definir parametros
$stmt = $con->prepare($sql);
$stmt->bind_param(
    "ssssssssissssss",
    $doc,
    $correo,
    $nombre,
    $apellido,
    $fecha,
    $genero,
    $telefono,
    $direccion,
    $ciudad,
    $rol,
    $tarjeta,
    $especialidad,
    $hash,
    $estado,
    $foto
);

// KM = Parametros insertados
$doc = "12345678902";
$correo = "admin@rfa.com";
$nombre = "Admin";
$apellido = "Principal";
$fecha = "1990-01-01";
$genero = "O";
$telefono = "3001234567";
$direccion = "Calle 123";
$ciudad = 1;
$rol = 1;
$tarjeta = "99999999999";
$especialidad = "Gestión General";
$estado = true;
$foto = "icono_default_hombre.png";

// KM = Insercion exitosa
if ($stmt->execute()) {
    echo "Admin insertado correctamente.";

    // KM = Error (Nunca me ha salido un error con esto?) 
} else {
    echo " Error: " . $stmt->error;
}

// KM = Cerrar
$stmt->close();
$con->close();
