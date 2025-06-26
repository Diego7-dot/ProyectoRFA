<?php
// KM = Sesión iniciada
session_start();

// KM = Verificar sesión activa
if (!isset($_SESSION['docApo'])) {
    header("Location: ../../../html/pagina_principal/inicio_sesion_empleado.php");
    exit();
}

// KM = Variables para personalizar la vista del asesor
$nombre = $_SESSION['nomApo'] ?? 'Asesor';
$genero = $_SESSION['genApo'] ?? 'O';
$icono = '../../../iconos/iconos_perfil/' .
    ($_SESSION['fotoPerfil'] ?? 'icono_default_otro.png');

// KM = Saludo según género
$titulo = match ($genero) {
    'F' => 'Bienvenida',
    'M' => 'Bienvenido',
    'O' => 'Bienvenid@',
    default => 'Bienvenid@'
};

// KM = Rol según género
$rolGenero = match ($genero) {
    'F' => 'Asesora',
    'M' => 'Asesor',
    'O' => 'Asesor@',
    default => 'Asesor@'
};
