<!--***********************************************************************-->
<!--Inicio de asesor: Red femenina de apoyo-->
<!--***********************************************************************-->

<!-- KM = Archivo PHP que carga variables de bienvenida -->
<?php include("../../../src/auth/asesor_inicio.php"); ?>
<?php
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
  <title>Portal asesor</title>
  <!-- KM = Icono de la página -->
  <link href="../../../iconos/pagina_principal/logo_fem_icon.png" rel="icon">
  <!-- KM = Estilos CSS -->
  <link href="../../../css/pagina_empleado/empleado_asesor/estilo_inicio_empleado_asesor.css" rel="stylesheet">
</head>

<body>
  <!--KM = Mensaje de procesos, la pagina sigue en actualizaciones -->
  <div id="mensaje-proceso" class="mensaje-proceso"></div>

  <!--KM = Modo oscuro-->
  <button id="modo-toggle" class="modo-flotante">
    <img id="icono-modo" class="icono-modo" src="../../../iconos/pagina_principal/luna.png" alt="Modo oscuro" />
  </button>

  <!--KM = Subir por scroll -->
  <button id="btnScrollTop" class="scroll-top-btn" title="Volver arriba">
    <img id="icono-flota" class="icono-flota" src="../../../iconos/pagina_principal/flotando.png" alt="Subir" />
  </button>

  <!--KM = ChatBot (Alma)-->
  <script src="https://cdn.botpress.cloud/webchat/v2.2/inject.js"></script>
  <script src="https://files.bpcontent.cloud/2025/02/20/15/20250220153220-BMQ0282N.js"></script>

  <!-- KM = Encabezado de la pagina -->
  <header class="main_cabezera">
    <div class="logo-pagina">
      <img src="../../../iconos/pagina_principal/logo_fem_icon.png" alt="Logo RFA" class="logo_icono">

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
        <img src="../../../iconos/pagina_empleado/bajar_perfil.png" alt="Desplegar menú" class="bajar_menu">
      </div>

      <!-- KM = Menú desplegable del usuario -->
      <div class="menu_usuario" id="user-menu">
        <ul>
          <li><a href="editar_asesor.php">Editar usuario</a></li>
          <li><a href="#" id="abrir-soporte">Ayuda / Soporte técnico</a></li>
          <li><a href="#" id="abrir-manuales">Manuales</a></li>
          <li><a href="../../../src/auth/cerrar_sesion.php">Cerrar sesión</a></li>
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

  <!-- KM = Menu de navegacion de asesor -->
  <nav class="menu_secundario">

    <!-- KM = Inicio -->
    <a href="inicio_empleado_asesor.php" class="menu-item">
      <img src="../../../iconos/pagina_empleado/inicio_asesor.png" alt="Inicio" class="menu-icon">
      <span>Inicio</span>
    </a>

    <!-- KM = Chat de comunicacion -->
    <a href="mensajes_asesor.php" class="menu-item">
      <img src="../../../iconos/pagina_empleado/chat_asesor.png" alt="Asesoramiento de usuari@s" class="menu-icon">
      <span>Asesoramiento</span>
    </a>

    <!-- KM = Redirigimiento de usuarios a otro persona de apoyo -->
    <a href="primera_consulta_asesor.php" class="menu-item">
      <img src="../../../iconos/pagina_empleado/consulta_asesor.png" alt="Gestionar redigirimineto" class="menu-icon">
      <span>Asignación de apoyo</span>
    </a>

    <!-- KM = Disponibilidad del asesor -->
    <a href="disponibilidad_asesor.php" class="menu-item">
      <img src="../../../iconos/pagina_empleado/disponibilidad.png" alt="Consultar casos" class="menu-icon">
      <span>Disponibilidad</span>
    </a>

    <!-- KM = Generar notas de seguimiento -->
    <a href="notas_seguimiento_asesor.php" class="menu-item">
      <img src="../../../iconos/pagina_empleado/notas_seguimiento.png" alt=" Consultar reportes" class="menu-icon">
      <span>Notas de seguimiento</span>
    </a>
  </nav>

  <!-- KM = Cuerpo de la pagina -->
  <section class="bienvenido">
    <h2><?= $titulo ?>, <?= htmlspecialchars($nombre) ?></h2>
    <p>Recuerda que <?= ($genero === 'F') ? 'la asesora' : (($genero === 'M') ? 'el asesor' : 'la persona asesora') ?> es fundamental para el éxito del equipo. ¡Gracias por tu compromiso!</p>
  </section>

  <script src="../../../java_script/pagina_empleado/empleado_asesor/inicio_asesor.js"></script>
  <script src="../../../libreria/sweetalert/sweetalert2.all.min.js"></script>
</body>

</html>