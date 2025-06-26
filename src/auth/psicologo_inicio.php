<?php
// KM = Sesión iniciada
session_start();

// KM = Verificar sesión activa para psicólogo
if (!isset($_SESSION['docApo'])) {
    header("Location: ../../../html/pagina_principal/inicio_sesion_empleado.php");
    exit();
}

// KM = Variables para personalizar la vista del psicólogo
$nombre = $_SESSION['nomApo'] ?? 'Psicólogo';
$genero = $_SESSION['genApo'] ?? 'O';
$icono = '../../../iconos/iconos_perfil/' . ($_SESSION['fotoPerfil'] ?? 'icono_default_otro.png');

// KM = Saludo según género
$titulo = match ($genero) {
    'F' => 'Bienvenida',
    'M' => 'Bienvenido',
    'O' => 'Bienvenid@',
    default => 'Bienvenid@'
};

// KM = Rol según género
$rolGenero = match ($genero) {
    'F' => 'Psicóloga',
    'M' => 'Psicólogo',
    'O' => 'Psicólog@',
    default => 'Psicólog@'
};
