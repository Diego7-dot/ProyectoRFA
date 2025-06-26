<?php
/* KM = Verificacion del correo
  JS = Seños php, la base de datos tiene este correo?
  Php = No (Si) mi buen señor
  JS = Muchas gracias, caballero 
  kakaka salveneme por favor, te amo validacion (Hasta el momento)*/
require_once("../../Config/conexion.php");
// KM = Conexion
$con = Conexion();

$correo = $_GET['correo'] ?? '';
$existe = false;

// KM = Consulta
if (!empty($correo)) {
    $stmt = $con->prepare("SELECT 1 FROM usuario WHERE corUsu = ? LIMIT 1");
    $stmt->execute([$correo]);
    $existe = $stmt->fetch() ? true : false;
}

echo json_encode(['existe' => $existe]);
?>
