<?php
// KM =  Verficiar el inicio de sesion
session_start();

// KM = Verificaer el usuario
if (!isset($_SESSION['corUsu'])) {
    header("Location: ../../pagina_principal/inicio_sesion_usuario.php");
    exit();
}

// KM = Obtener los datos de la sesion
$nombre = $_SESSION['nomUsu'] ?? 'Usuario';
$genero = $_SESSION['genUsu'] ?? 'O';
$icono = '../../iconos/iconos_perfil/' . ($_SESSION['fotoPerfil'] ?? 'icono_default_otro.png');

// KM = Saludo dependiendo del genero
$titulo = match ($genero) {
    'F' => 'Bienvenida,',
    'M' => 'Bienvenido,',
    'O' => 'Bienvenid@,',
    default => 'Bienvenid@,'
};

// Rol segun el genero
$rolGenero = match ($genero) {
    'F' => 'Usuaria',
    'M' => 'Usuario',
    'O' => 'Usuari@',
    default => 'Usuari@'
};
