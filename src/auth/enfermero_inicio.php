<?php
// KM = Iniciar sesión
session_start();

// KM = Verificar sesión activa
if (!isset($_SESSION['docApo'])) {
    header("Location: ../../../html/pagina_principal/inicio_sesion_empleado.php");
    exit();
}

// KM = Variables para personalizar la vista del enfermero o enfermera
$nombre = $_SESSION['nomApo'] ?? 'Enfermer@';
$genero = $_SESSION['genApo'] ?? 'O';
$icono = '../../../iconos/iconos_perfil/' . ($_SESSION['fotoPerfil'] ?? 'icono_default_otro.png');

// KM = Título de bienvenida según género
$titulo = match ($genero) {
    'F' => 'Bienvenida',
    'M' => 'Bienvenido',
    'O' => 'Bienvenid@',
    default => 'Bienvenid@'
};

// KM = Rol visible según género
$rolGenero = match ($genero) {
    'F' => 'Enfermera',
    'M' => 'Enfermero',
    'O' => 'Enfermer@',
    default => 'Enfermer@'
};
