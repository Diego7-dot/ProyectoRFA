<?php

$server = "localhost";
$usu = "root";
$pasword = "1234";
$bd = "rfa_1";

$conexion = new mysqli($server,$usu,$pasword,$bd);

if ($conexion-> conect_error){
    die("Conexion fallid" . $conexion->conect_error);
}else{
    echo "Conexion exitosa";
}

?>