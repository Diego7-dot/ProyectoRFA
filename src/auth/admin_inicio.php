<?php
// KM =  Sesion iniciada
session_start();

// KM = Verfica el usuario
if (!isset($_SESSION['docApo'])) {
    header("Location: ../../html/pagina_principal/inicio_sesion_empleado.php");
    exit();
}

// KM = Variables necesarias para personalizar la vista del administrador
$nombre = $_SESSION['nomApo'] ?? 'Administrador';
$genero = $_SESSION['genApo'] ?? 'O';
$icono = '../../iconos/iconos_perfil/' .
    ($_SESSION['fotoPerfil'] ?? 'icono_default_otro.png');

// KM = Saludo dependiendo del género
$titulo = match ($genero) {
    'F' => 'Bienvenida',
    'M' => 'Bienvenido',
    'O' => 'Bienvenid@',
    default => 'Bienvenid@'
};

// KM = Rol según el género
$rolGenero = match ($genero) {
    'F' => 'Administradora',
    'M' => 'Administrador',
    'O' => 'Administrador@',
    default => 'Administrador@'
};
