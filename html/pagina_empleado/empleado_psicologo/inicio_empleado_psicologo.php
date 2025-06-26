<!--***********************************************************************-->
<!--Inicio de psicólogo: Red femenina de apoyo-->
<!--***********************************************************************-->

<!-- KM = Archivo PHP que carga variables de bienvenida -->
<?php include("../../../src/auth/psicologo_inicio.php"); ?>
<?php
// KM = Icono de perfil o imagen por defecto
$icono = '../../../iconos/iconos_perfil/' . ($_SESSION['fotoPerfil'] ?? 'icono_default_otro.png');
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <!-- KM = Permitir caracteres especiales -->
  <meta charset="UTF-8">
  <!-- KM = Optimizador para dispositivos móviles -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- KM = Título -->
  <title>Portal psicólogo</title>
  <!-- KM = Icono de la página -->
  <link href="../../../iconos/pagina_principal/logo_fem_icon.png" rel="icon">
  <!-- KM = Estilos CSS -->
  <link href="../../../css/pagina_empleado/empleado_asesor/estilo_inicio_empleado_asesor.css" rel="stylesheet">
</head>

<body>
  <!-- KM = Mensaje de procesamiento -->
  <div id="mensaje-proceso" class="mensaje-proceso"></div>

  <!-- KM = Botón para modo oscuro -->
  <button id="modo-toggle" class="modo-flotante">
    <img id="icono-modo" class="icono-modo" src="../../../iconos/pagina_principal/luna.png" alt="Modo oscuro" />
  </button>

  <!-- KM = Botón para subir al inicio -->
  <button id="btnScrollTop" class="scroll-top-btn" title="Volver arriba">
    <img id="icono-flota" class="icono-flota" src="../../../iconos/pagina_principal/flotando.png" alt="Subir" />
  </button>

  <!-- KM = ChatBot Alma -->
  <script src="https://cdn.botpress.cloud/webchat/v2.2/inject.js"></script>
  <script src="https://files.bpcontent.cloud/2025/02/20/15/20250220153220-BMQ0282N.js"></script>

  <!-- KM = Encabezado principal -->
  <header class="main_cabezera">
    <div class="logo-pagina">
      <img src="../../../iconos/pagina_principal/logo_fem_icon.png" alt="Logo RFA" class="logo_icono">
      <h1 class="pc_nombre">Red femenina de apoyo</h1>
      <h1 class="mobil_nombre">RFA</h1>
    </div>

    <!-- KM = Información del usuario activo -->
    <div class="informacion_user" onclick="toggleUserMenu()">
      <div class="barra_user">
        <img src="<?= $icono ?>" alt="Avatar Usuario" class="avatar_user">
        <div class="texto_user">
          <span class="nombre_user"><?= htmlspecialchars($nombre) ?></span>
          <span class="rol_user"><?= $rolGenero ?></span>
        </div>
        <img src="../../../iconos/pagina_empleado/bajar_perfil.png" alt="Desplegar menú" class="bajar_menu">
      </div>

      <!-- KM = Menú desplegable del usuario -->
      <div class="menu_usuario" id="user-menu">
        <ul>
          <li><a href="editar_psicologo.php">Editar usuario</a></li>
          <li><a href="#" id="abrir-soporte">Ayuda / Soporte técnico</a></li>
          <li><a href="#" id="abrir-manuales">Manuales</a></li>
          <li><a href="../../../src/auth/cerrar_sesion.php">Cerrar sesión</a></li>
        </ul>
      </div>
    </div>
  </header>

  <!-- KM = Ventana de ayuda / soporte técnico -->
  <div id="modal-ayuda" class="modal">
    <div class="modal-contenido">
      <span id="cerrar-modal" class="cerrar">&times;</span>
      <h2>¿Necesitas ayuda o soporte?</h2>
      <p>Estamos aquí para ti. Elige la opción que mejor se adapte a lo que necesitas.</p>
      <div class="botones-modal">
        <a href="mailto:redapoyofemenino@gmail.com?subject=Ayuda%20necesaria&body=Hola%2C%20necesito%20ayuda%20con..." class="btn-modal">Escribir al correo de apoyo</a>
        <a href="mailto:soportetecnico@tudominio.com?subject=Problema%20técnico&body=Hola%2C%20tengo%20un%20problema%20técnico..." class="btn-modal">Contactar soporte técnico</a>
      </div>
    </div>
  </div>

  <!-- KM = Ventana de selección de manuales -->
  <div id="modal-manuales" class="modal">
    <div class="modal-contenido">
      <span id="cerrar-modal-manuales" class="cerrar">&times;</span>
      <h2>Selecciona un Manual</h2>
      <p>¿Qué tipo de manual deseas ver?</p>
      <div class="botones-modal">
        <button id="btn-usuario" class="btn-modal">Manual de Usuario</button>
        <button id="btn-tecnico" class="btn-modal">Manual Técnico</button>
      </div>
    </div>
  </div>

  <!-- KM = Navegación lateral del psicólogo -->
  <nav class="menu_secundario">

    <!-- KM = Inicio -->
    <a href="inicio_empleado_psicologo.php" class="menu-item">
      <img src="../../../iconos/pagina_empleado/inicio_psicologia.png" alt=" Inicio" class="menu-icon">
      <span>Inicio</span>
    </a>

    <!-- KM = Mensajes de usuarios -->
    <a href="mensajes_psicologo.php" class="menu-item">
      <img src="../../../iconos/pagina_empleado/mensaje_psiclogia.png" alt="Mensajes" class="menu-icon">
      <span>Mensajes</span>
    </a>

    <!-- KM = Mirar los casos de los usurios a cargo y generar reportes -->
    <a href="casos_reportes_psicologia.php" class="menu-item">
      <img src="../../../iconos/pagina_empleado/reporte_caso.png" alt="Casos y reportes" class="menu-icon">
      <span>Casos y reportes</span>
    </a>

    <!-- KM = Gestionar citas -->
    <a href="citas_psicologo.php" class="menu-item">
      <img src="../../../iconos/pagina_empleado/atender_cita.png" alt="Atención citas" class="menu-icon">
      <span>Atención de citas</span>
    </a>

    <!-- KM = Verficiar disponibilidad del consultor -->
    <a href="disponibilidad_psicologo.php" class="menu-item">
      <img src="../../../iconos/pagina_empleado/disponibilidad_apoyo.png" alt="Disponibilidad" class="menu-icon">
      <span>Disponibilidad</span>
    </a>
  </nav>

  <!-- KM = Mensaje de bienvenida personalizado -->
  <section class="bienvenido">
    <h2><?= $titulo ?>, <?= htmlspecialchars($nombre) ?></h2>
    <p>
      Recuerda que
      <?= ($genero === 'F') ? 'la psicóloga' : (($genero === 'M') ? 'el psicólogo' : 'la persona psicóloga') ?>
      cumple un rol fundamental en el bienestar emocional de nuestras usuarias. Tu escucha, comprensión y acompañamiento
      son claves para construir espacios seguros y empáticos.
    </p>
  </section>

  <!-- KM = Script compartido con asesor para interacciones -->
  <script src="../../../java_script/pagina_empleado/empleado_asesor/inicio_asesor.js"></script>
</body>

</html>