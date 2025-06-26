<!--***********************************************************************-->
<!--Gestionar personañ de apoyo: Red femenina de apoyo-->
<!--***********************************************************************-->

<!-- KM = Archivo PHP que carga variables de bienvenida -->
<?php
include("../../src/auth/admin_inicio.php"); ?>
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
  <link href="../../css/pagina_empleado/admin_agregar_empleado.css" rel="stylesheet">
  <script>
    // KM = Pasamos los errores desde PHP a JS como array (si existen)
    window.erroresImportacion = <?php
                                session_start();
                                echo json_encode($_SESSION['errores_importacion'] ?? []);
                                unset($_SESSION['errores_importacion']);
                                ?>;
  </script>
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
  <div id="modal-ayuda" class="modal-ayuda">
    <div class="modal-contenido-ayuda">
      <span id="cerrar-modal" class="cerrar">&times;</span>
      <h2>¿Necesitas ayuda o soporte?</h2>
      <p>Estamos aquí para ti. Elige la opción que mejor se adapte a lo que necesitas.</p>
      <div class="botones-modal-ayuda">
        <a href="mailto:redapoyofemenino@gmail.com?subject=Ayuda%20necesaria&body=Hola%2C%20necesito%20ayuda%20con..." class="btn-modal">Escribir al correo de apoyo</a>
        <a href="mailto:soportetecnico@tudominio.com?subject=Problema%20técnico&body=Hola%2C%20tengo%20un%20problema%20técnico..." class="btn-modal">Contactar soporte técnico</a>
      </div>
    </div>
  </div>

  <!-- KM = Eleccion de manual -->
  <div id="modal-manuales" class="modal-ayuda">
    <div class="modal-contenido-ayuda">
      <span id="cerrar-modal-manuales" class="cerrar">&times;</span>
      <h2>Selecciona un Manual</h2>
      <p>¿Qué tipo de manual deseas ver?</p>
      <div class="botones-modal-ayuda">
        <button id="btn-usuario" class="btn-modal-ayuda">Manual de Usuario</button>
        <button id="btn-tecnico" class="btn-modal-ayuda">Manual Técnico</button>
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
    <h2>Gestionar personal de apoyo</h2>
  </section>

  <!-- KM = Barra de busqueda y filtros -->
  <section class="search-section">
    <div class="search-wrapper-responsive">

      <!-- KM = Input de búsqueda por documento-->
      <div class="search-input-wrapper">
        <input type="text" id="employee-search" placeholder="Buscar por documento...">
        <button id="btnSearch" aria-label="Buscar"></button>
      </div>

      <!-- KM = Filtros combinados -->
      <div class="filtro-mobil">
        <!-- KM = Estado -->
        <select id="status-filter">
          <option value="all">Todos</option>
          <option value="activo">Activo</option>
          <option value="inactivo">Inactivo</option>
        </select>

        <!-- KM = Rol -->
        <select id="rol-filter">
          <option value="todos">Todos los roles</option>
          <option value="Psicólogo">Psicólogo</option>
          <option value="Asesor">Asesor</option>
          <option value="Enfermero">Enfermero</option>
          <option value="Consultor Legal">Consultor Legal</option>
        </select>
      </div>

    </div>
  </section>

  <!-- KM = Subir el documento de excel oculto (solo para abrir modal) -->
  <form id="form-upload-excel-shadow" style="display: none;">
    <input type="file" name="archivoExcel" id="archivoExcel-shadow" accept=".xlsx,.xls">
  </form>

  <!-- KM = Lo mismo que arriba pero en mobil (Lo cambie y aun no se porque no se alinea...)-->
  <section class="action-buttons mobile">
    <div class="top-button">
      <button id="btn-open-add" class="btn-add">Agregar persona de apoyo</button>
      <button class="btn-upload">Subir excel</button>
    </div>
  </section>

  <!-- KM = Tabla de empleados -->
  <section class="employee-list-section">
    <div class="table-container">
      <table id="employee-table">
        <thead>
          <tr>
            <th>Editar</th>
            <th>Estado</th>
            <th>Documento</th>
            <th>Correo</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Fecha de Nacimientao</th>
            <th>Género</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Ciudad</th>
            <th>Tarjeta Profesional</th>
            <th>Especialidad</th>
            <th>Contraseña</th>
          </tr>
        </thead>
        <tbody>
          <!-- KM = Se llenan los campos por la tabla -->
        </tbody>
      </table>
    </div>

    <!-- KM = Modal editar persona de apoyo -->
    <div id="modal-edit" class="modal">
      <form id="employee-form-edit" class="formulario-modal" autocomplete="off">
        <h2>Editar persona de apoyo</h2>

        <div class="form-group">
          <label for="documento_edit">Documento:</label>
          <input type="text" id="documento_edit" name="documento_edit" required readonly pattern="^\d{6,11}$" title="Debe tener entre 6 y 11 números" maxlength="11">
        </div>

        <div class="form-group">
          <label for="correo_edit">Correo:</label>
          <input type="email" id="correo_edit" name="correo_edit" required maxlength="100" title="Correo válido y máximo 100 caracteres">
        </div>

        <div class="form-group">
          <label for="nombre_edit">Nombre:</label>
          <input type="text" id="nombre_edit" name="nombre_edit" required maxlength="100" pattern="^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$" title="Solo letras y espacios">
        </div>

        <div class="form-group">
          <label for="apellido_edit">Apellido:</label>
          <input type="text" id="apellido_edit" name="apellido_edit" required maxlength="100" pattern="^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$" title="Solo letras y espacios">
        </div>

        <div class="form-group">
          <label for="fechaNacimiento_edit">Fecha de nacimiento:</label>
          <input type="date" id="fechaNacimiento_edit" name="fechaNacimiento_edit" required>
        </div>

        <div class="form-group">
          <label for="genero_edit">Género:</label>
          <select id="genero_edit" name="genero_edit" required>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
            <option value="O">Otro</option>
          </select>
        </div>

        <div class="form-group">
          <label for="telefono_edit">Teléfono:</label>
          <input type="text" id="telefono_edit" name="telefono_edit" pattern="\d{7,11}" maxlength="11" required>
        </div>

        <div class="form-group">
          <label for="direccion_edit">Dirección:</label>
          <input type="text" id="direccion_edit" name="direccion_edit" required maxlength="30" title="Máximo 30 caracteres">
        </div>

        <div class="form-group">
          <label for="codigoCiudad_edit">Ciudad:</label>
          <select id="codigoCiudad_edit" name="codigoCiudad_edit" required>
            <!-- KM = Se llenará con las ciudades -->
          </select>
        </div>

        <div class="form-group">
          <label for="rol_edit">Rol:</label>
          <select id="rol_edit" name="rol_edit" required>
            <!-- KM = Se llenará con los roles disponibles, excluyendo 'Administrador' -->
          </select>
        </div>

        <div class="form-group">
          <label for="tarjeta_edit">Tarjeta profesional:</label>
          <input type="text" id="tarjeta_edit" name="tarjeta_edit" required maxlength="11" pattern="^\d{1,11}$" title="Solo números, máximo 11 dígitos">
        </div>

        <div class="form-group">
          <label for="especialidad_edit">Especialidad:</label>
          <input type="text" id="especialidad_edit" name="especialidad_edit" required maxlength="100" title="Máximo 100 caracteres">
        </div>

        <!-- KM = Botón para mostrar campos de contraseña -->
        <button type="button" class="boton-mac" id="btnContrasena" onclick="mostrarCamposContrasena()">Cambiar contraseña</button>
        <input type="hidden" name="seccionContrasenaActiva" id="seccionContrasenaActiva" value="0">

        <!-- KM = Campos ocultos para cambiar contraseña -->
        <div id="seccion-contrasena" style="display: none; margin-top: 20px;">
          <label for="nuevaPsw" class="label-bold">Contraseña:</label>
          <div class="input-icon">
            <input type="password" name="nuevaPsw" id="nuevaPsw" class="controls" placeholder="..." autocomplete="new-password" minlength="6" maxlength="250" title="Mínimo 6 caracteres">
            <img src="../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="togglePasswordIcon">
          </div>

          <label for="confirmarPsw" class="label-bold">Confirmar contraseña:</label>
          <div class="input-icon">
            <input type="password" name="confirmarPsw" id="confirmarPsw" class="controls" placeholder="..." autocomplete="new-password" minlength="6" maxlength="250" title="Debe coincidir con la contraseña">
            <img src="../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="toggleVerifyPasswordIcon">
          </div>
        </div>

        <div class="form-group">
          <label for="estado_edit">Estado:</label>
          <select id="estado_edit" name="estado_edit" required>
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
          </select>
        </div>

        <div class="form-group" id="motivo_estado_group" style="display: none;">
          <label for="motivo_edit">Motivo de inactivación:</label>
          <textarea id="motivo_edit" name="motivo_edit" placeholder="Describe el motivo..." rows="3" minlength="4" maxlength="250" title="Mínimo 4 caracteres y máximo 250"></textarea>
        </div>

        <!-- KM = Mensaje de error -->
        <div id="mensaje-error-edit" class="mensaje-error" style="display: none;"></div>

        <div class="botones-modal">
          <button type="submit" id="btnGuardarEdit">Guardar cambios</button>
          <button type="button" id="btnCancelEdit">Cancelar</button>
        </div>
      </form>
    </div>

    <!-- KM = Modal para registrar persona de apoyo -->
    <div id="modal-agregar-persona" class="modal" style="display: none;">
      <div class="modal-content">
        <h2>Registro persona de apoyo</h2>

        <form id="formulario-nuevo-registro" autocomplete="off">
          <div class="form-group">
            <label for="docNuevo">Documento:</label>
            <input type="text" id="docNuevo" name="docNuevo" pattern="\d{6,11}" maxlength="11" required>
          </div>

          <div class="form-group">
            <label for="correoNuevo">Correo:</label>
            <input type="email" id="correoNuevo" name="correoNuevo" maxlength="100" title="Correo válido, máximo 100 caracteres" required>
          </div>

          <div class="form-group">
            <label for="nombreNuevo">Nombre:</label>
            <input type="text" id="nombreNuevo" name="nombreNuevo" pattern="^[A-Za-zÁÉÍÓÚáéíóúñÑ ]{1,100}$" maxlength="100" title="Solo letras y espacios (máx. 100)" required>
          </div>

          <div class="form-group">
            <label for="apellidoNuevo">Apellido:</label>
            <input type="text" id="apellidoNuevo" name="apellidoNuevo" pattern="^[A-Za-zÁÉÍÓÚáéíóúñÑ ]{1,100}$" maxlength="100" title="Solo letras y espacios (máx. 100)" required>
          </div>

          <div class="form-group">
            <label for="nacimientoNuevo">Fecha de nacimiento:</label>
            <input type="date" id="nacimientoNuevo" name="nacimientoNuevo" required>
          </div>

          <div class="form-group">
            <label for="generoNuevo">Género:</label>
            <select id="generoNuevo" name="generoNuevo" required>
              <option value="">Selecciona...</option>
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
              <option value="O">Otro</option>
            </select>
          </div>

          <div class="form-group">
            <label for="telefonoNuevo">Teléfono:</label>
            <input type="text" id="telefonoNuevo" name="telefonoNuevo" pattern="\d{7,11}" maxlength="11" required>
          </div>

          <div class="form-group">
            <label for="direccionNuevo">Dirección:</label>
            <input type="text" id="direccionNuevo" name="direccionNuevo" maxlength="30" title="Máximo 30 caracteres" required>
          </div>

          <div class="form-group">
            <label for="ciudadNuevo">Ciudad:</label>
            <select id="ciudadNuevo" name="ciudadNuevo" required>
              <!-- KM: Cargar ciudades desde JS -->
            </select>
          </div>

          <div class="form-group">
            <label for="rolNuevo">Rol:</label>
            <select id="rolNuevo" name="rolNuevo" required>
              <!-- KM: Cargar roles desde JS -->
            </select>
          </div>

          <div class="form-group">
            <label for="tarjetaNuevo">Tarjeta profesional:</label>
            <input type="text" id="tarjetaNuevo" name="tarjetaNuevo" pattern="^\d{1,11}$" maxlength="11" title="Solo números, máximo 11 dígitos" required>
          </div>

          <div class="form-group">
            <label for="especialidadNuevo">Especialidad:</label>
            <input type="text" id="especialidadNuevo" name="especialidadNuevo" maxlength="100" title="Máximo 100 caracteres" required>
          </div>

          <!-- KM = Contraseña y confirmación para nuevo registro -->
          <div class="form-group">
            <label for="claveNuevo" class="label-bold">Contraseña:</label>
            <div class="input-icon">
              <input type="password" id="claveNuevo" name="claveNuevo" class="controls" placeholder="..." minlength="6" maxlength="250" autocomplete="new-password" required>
              <img src="../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="togglePasswordNuevoIcon">
            </div>

            <label for="confirmarClaveNuevo" class="label-bold">Confirmar contraseña:</label>
            <div class="input-icon">
              <input type="password" id="confirmarClaveNuevo" name="confirmarClaveNuevo" class="controls" placeholder="..." minlength="6" maxlength="250" autocomplete="new-password" required>
              <img src="../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="toggleVerifyNuevoIcon">
            </div>
          </div>

          <div id="mensaje-error-add" class="mensaje-error" style="display: none;"></div>

          <!-- KM = Botones de acción -->
          <div class="form-groupis" style="display: flex; justify-content: space-between; gap: 10px;">
            <button type="submit" class="btn-guardar">Registrar</button>
            <button type="button" id="btnCancelAdd" class="btn-cancelar">Cancelar</button>
          </div>
        </form>
      </div>
    </div>

    <!-- KM = Confirmar subida de archivo -->
    <div class="modal-overlay" id="modal-upload">
      <div class="modal-content">
        <form id="form-upload-excel" action="/Proyecto_real_v1/Proyecto_real/src/insert_pa/importar_excel.php" method="POST" enctype="multipart/form-data">
          <h2 style="margin-bottom: 10px;">Confirmar subida</h2>
          <p>¿Está segura de subir el archivo Excel?</p>

          <!-- KM = Botón personalizado -->
          <label for="archivoExcel" class="btn-confirm" style="display: inline-block; margin-top: 6px; margin-bottom: 4px; cursor: pointer;">
            Seleccionar archivo
          </label>
          <input type="file" name="archivoExcel" id="archivoExcel" accept=".xlsx,.xls" style="display: none;">

          <!-- KM = Recuadro con rayitas -->
          <div class="archivo-cargado-wrapper">
            <strong>Archivo cargado:</strong>
            <span id="nombre-archivo-cargado">Ninguno</span>
          </div>

          <div class="form-actions">
            <button type="button" class="btn-confirm" id="confirmUpload">Aceptar</button>
            <button type="button" class="btn-cancel" id="cancelUpload">Cancelar</button>
          </div>
        </form>
      </div>
    </div>

    <script src="../../libreria/sweetalert/sweetalert2.all.min.js"></script>
    <script src="../../java_script/pagina_empleado/admin_agregar_empleado.js"></script>
</body>

</html>