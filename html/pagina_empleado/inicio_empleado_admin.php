<!--***********************************************************************-->
<!--Inicio de administrador: Red femenina de apoyo-->
<!--***********************************************************************-->

<!-- KM = Archivo PHP que carga variables de bienvenida -->
<?php include("../../src/auth/admin_inicio.php"); ?>
<?php
$icono = '../../iconos/iconos_perfil/' . ($_SESSION['fotoPerfil'] ?? 'icono_default_otro.png');
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <!-- KM = Permitir caracteres especiales -->
  <meta charset="UTF-8">
  <!-- KM = Optimizador para dispositivos móviles -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- KM = Título -->
  <title>Portal administrador</title>
  <!-- KM = Icono de la página -->
  <link href="../../iconos/pagina_principal/logo_fem_icon.png" rel="icon">
  <!-- KM = Estilos CSS -->
  <link href="../../css/pagina_empleado/estilo_inicio_empleado_admin.css" rel="stylesheet">
</head>

<body>
  <!--KM = Mensaje de procesos, la pagina sigue en actualizaciones -->
  <div id="mensaje-proceso" class="mensaje-proceso"></div>

  <!--KM = Modo oscuro-->
  <button id="modo-toggle" class="modo-flotante">
    <img id="icono-modo" class="icono-modo" src="../../iconos/pagina_principal/luna.png" alt="Modo oscuro" />
  </button>

  <!--KM = Subir por scroll -->
  <button id="btnScrollTop" class="scroll-top-btn" title="Volver arriba">
    <img id="icono-flota" class="icono-flota" src="../../iconos/pagina_principal/flotando.png" alt="Subir" />
  </button>

  <!--KM = ChatBot (Alma)-->
  <script src="https://cdn.botpress.cloud/webchat/v2.2/inject.js"></script>
  <script src="https://files.bpcontent.cloud/2025/02/20/15/20250220153220-BMQ0282N.js"></script>

  <!-- KM = Encabezado de la pagina -->
  <header class="main_cabezera">
    <div class="logo-pagina">
      <img src="../../iconos/pagina_principal/logo_fem_icon.png" alt="Logo RFA" class="logo_icono">

      <!-- KM = TItulo para version de dispositivos grandes -->
      <h1 class="pc_nombre">Red femenina de apoyo</h1>

      <!-- KM = Titulo para version de dispositivos pequeños-->
      <h1 class="mobil_nombre">RFA</h1>
    </div>

    <!-- KM = Espacio de administrador que ingresa -->
    <div class="informacion_user" onclick="toggleUserMenu()">
      <div class="barra_user">
        <img src="<?= $icono ?>" alt="Avatar Usuario" class="avatar_user">

        <!-- KM = Nombre y rol del adminsitrador que ingreso -->
        <div class="texto_user">
          <span class="nombre_user"><?= htmlspecialchars($nombre) ?></span>
          <span class="rol_user"><?= $rolGenero ?></span>
        </div>
        <img src="../../iconos/pagina_empleado/bajar_perfil.png" alt="Desplegar menú" class="bajar_menu">
      </div>

      <!-- KM = Menú desplegable del empleado -->
      <div class="menu_usuario" id="user-menu">
        <ul>
          <li><a href="admin_editar.php">Editar usuario</a></li>
          <li><a href="#" id="abrir-soporte">Ayuda / Soporte técnico</a></li>
          <li><a href="#" id="abrir-manuales">Manuales</a></li>
          <li><a href="../../src/auth/cerrar_sesion.php">Cerrar sesión</a></li>
        </ul>
      </div>
    </div>
  </header>

  <!-- KM = Ventana emergente de Ayuda / Soporte Técnico -->
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

  <!-- KM = Eleccion de manual -->
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

  <!-- KM = Menu de navegacion del administrador -->
  <nav class="menu_secundario">

    <!-- KM = Inicio administrador -->
    <a href="inicio_empleado_admin.php" class="menu-item">
      <img src="../../iconos/pagina_empleado/inicio_admin.png" alt="Inicio administrador" class="menu-icon">
      <span>Inicio</span>
    </a>

    <!-- KM = Gestionar empleados -->
    <a href="admin_agregar_apoyo.php" class="menu-item">
      <img src="../../iconos/pagina_empleado/gestionar_empleado.png" alt="Gestionar empleados" class="menu-icon">
      <span>Gestionar empleados</span>
    </a>

    <!-- KM = Mirar primeras consultas -->
    <a href="admin_reporte.php" class="menu-item">
      <img src="../../iconos/pagina_empleado/primer_consulta_admin.png" alt="Primeras consultas" class="menu-icon">
      <span>Primeras consultas</span>
    </a>

    <!-- KM = Mirar el estado de los casos -->
    <a href="admin_caso.php" class="menu-item">
      <img src="../../iconos/pagina_empleado/reporte_caso_.png" alt="Estado de caso" class="menu-icon">
      <span>Estado de caso</span>
    </a>

    <!-- KM = Agendar evento -->
    <a href="admin_evento.php" class="menu-item">
      <img src="../../iconos/pagina_empleado/icono_agregar_evento.png" alt="Administrar evento" class="menu-icon">
      <span>Administrar eventos</span>
    </a>

    <!-- KM = Reporte de ai -->
    <a href="admin_chatbot.php" class="menu-item">
      <img src="../../iconos/pagina_empleado/icono_ai.png" alt="Reporte de Alma" class="menu-icon">
      <span>Reporte de Alma</span>
    </a>
  </nav>

  <!-- KM = Cuerpo de la pagina -->
  <section class="bienvenido">
    <h2><?= $titulo ?>, <?= htmlspecialchars($nombre) ?></h2>
    <p>Recuerda que <?= ($genero === 'F') ? 'la administradora' : (($genero === 'M') ? 'el administrador' : 'la persona administradora') ?> es fundamental para el éxito del equipo. ¡Gracias por tu compromiso!</p>
  </section>

  <script src="../../java_script/pagina_empleado/admin_inicio.js"></script>
</body>

</html>