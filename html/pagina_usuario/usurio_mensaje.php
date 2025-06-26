<!--***********************************************************************-->
<!--Mensajes usuario: Red femenina de apoyo-->
<!--***********************************************************************-->

<!-- KM = Archivo PHP -->
<?php include("../../src/auth/usuario_inicio.php");
$icono = '../../iconos/iconos_perfil/' . ($_SESSION['fotoPerfil'] ?? 'icono_default_otro.png'); ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <!-- KM = Permitir caracteres especiales -->
  <meta charset="UTF-8">
  <!-- KM = Optimizador para dispositivos móviles -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- KM = Título -->
  <title>Portal usuario</title>
  <!-- KM = Icono de la página -->
  <link href="../../iconos/pagina_principal/logo_fem_icon.png" rel="icon">
  <!-- KM = Estilos CSS -->
  <link href="../../css/pagina_usuario/estilo_mensaje_usuario.css" rel="stylesheet">
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

    <!-- KM = Espacio de usuario que ingresa -->
    <div class="informacion_user" onclick="toggleUserMenu()">
      <div class="barra_user">
        <img src="<?= $icono ?>" alt="Avatar Usuario" class="avatar_user">

        <!-- KM = Nombre y rol del usuario que ingreso -->
        <div class="texto_user">
          <span class="nombre_user" title="<?= htmlspecialchars($nombre) ?>">
            <?= htmlspecialchars($nombre) ?>
            <br>
            <span class="rol_user"><?= $rolGenero ?></span>
        </div>
        <img src="../../iconos/pagina_empleado/bajar_perfil.png" alt="Desplegar menú" class="bajar_menu">
      </div>

      <!-- KM = Menú desplegable del usuario -->
      <div class="menu_usuario" id="user-menu">
        <ul>
          <li><a href="editar_pefil.php">Editar perfil</a></li>
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

  <!-- KM = Menu de navegacion del usuario -->
  <nav class="menu_secundario">
    <!-- KM = Inicio usuario -->
    <a href="inicio_usuario.php" class="menu-item">
      <img src="../../iconos/pagina_usuario/inicio_usuario.png" alt="Inicio usuario" class="menu-icon">
      <span>Inicio</span>
    </a>

    <!-- KM = Mensajes -->
    <a href="usurio_mensaje.php" class="menu-item">
      <img src="../../iconos/pagina_usuario/icono_mensaje.png" alt="Mensajes usuario" class="menu-icon">
      <span>Mensajes</span>
    </a>

    <!-- KM = Mirar el reporte de caso -->
    <a href="usuario_reporte.php" class="menu-item">
      <img src="../../iconos/pagina_usuario/icono_reporte.png" alt="Reporte caso" class="menu-icon">
      <span>Reporte caso</span>
    </a>

    <!-- KM = Agendar cita-->
    <a href="usuario_agendar_cita.php" class="menu-item">
      <img src="../../iconos/pagina_usuario/icono_agendar.png" alt="Agendar cita" class="menu-icon">
      <span>Agendar cita</span>
    </a>

    <!-- KM = Ingresar a la cita -->
    <a href="usuario_ingresar.php" class="menu-item">
      <img src="../../iconos/pagina_usuario/icono_llamda.png" alt="Entrar a llamada" class="menu-icon">
      <span>Ingresar cita</span>
    </a>
  </nav>

  <!-- KM = Cuerpo de la página - Sección de chat o mensajes -->
  <div class="contenedor-general">
    <div class="chat-wrapper">
      <div class="chat-sidebar" id="sidebar-asesor">
        <!-- KM = Se agregan las personas de apoyo -->
      </div>

      <div class="chat-main" id="chat-main">
        <button id="btn-back" class="chat-back-btn">Volver</button>

        <div class="chat-messages" id="chat-box">
          <!-- KM = Cargue de mensajes -->
          <ul id="chat-lista"></ul>
        </div>

        <div class="chat-input-container">
          <input type="text" id="mensaje" placeholder="Escribe tu mensaje..." class="chat-input">
          <button id="btn-enviar" class="chat-send-btn">✉️</button>
        </div>
      </div>

      <!-- KM = Datos ocultos para el chat -->
      <input type="hidden" id="corUsu" value="<?= $_SESSION['corUsu'] ?>">
      <input type="hidden" id="docApo" value="">
      <input type="hidden" id="room-id" value="">
    </div>
  </div>

  <script src="../../java_script/pagina_usuario/mensaje_usuario.js"></script>
</body>

</html>