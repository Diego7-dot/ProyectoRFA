<?php
// KM = Iniciar sesión
session_start();

// KM = Verificar sesión activa
if (!isset($_SESSION['docApo'])) {
    header("Location: ../../../html/pagina_principal/inicio_sesion_empleado.php");
    exit();
}

// KM = Variables para personalizar la vista del consultor legal
$nombre = $_SESSION['nomApo'] ?? 'Consultor';
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
    'F' => 'Consultora legal',
    'M' => 'Consultor legal',
    'O' => 'Persona consultora',
    default => 'Consultor(a)'
};
