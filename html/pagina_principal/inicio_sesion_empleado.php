<!-- KM = Inicio sesion de persona de apoyo: Red femenina de apoyo-->

<!-- KM = Mensajes de error desde php-->
<?php
$mensajeError = '';
$documentoGuardado = '';

if (isset($_GET['error'])) {
  switch ($_GET['error']) {
    case 'credenciales_invalidas':
      $mensajeError = '*Documento o contraseña inválido 😢';
      break;
    case 'campos_vacios':
      $mensajeError = 'Por favor completa todos los campos.';
      break;
    case 'error_desconocido':
    default:
      $mensajeError = 'Ocurrió un error inesperado.';
      break;
  }
}

if (isset($_GET['documento'])) {
  $documentoGuardado = htmlspecialchars($_GET['documento']);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <!--KM = Permitir caracteres especiales-->
  <meta charset="utf-8">
  <!--KM = Optimizador para los dispositivos moviles-->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--KM = Referente de icono-->
  <link href="../../iconos/pagina_principal/logo_fem_icon.png" rel="icon">
  <!--KM = Titulo-->
  <title>Inicio de sesión</title>
  <!--KM = Referencia a el CSS-->
  <link href="../../css/pagina_principal/inicio_sesion_empleado.css" rel="stylesheet">
</head>

<body>
  <!--KM = llamado de las partículas-->
  <div id="particles-js"></div>

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

  <!--KM = Creación del formulario para su envío-->
  <form action="../../src/auth/login_empleado.php" method="post">
    <section class="form-sesion">
      <label class="titulo">Inicio de sesión</label>
      <label class="titula">Persona de apoyo</label>

      <fieldset>
        <div id="form-empleado">

          <label class="label-bold" for="rol">Rol:</label>
          <select class="controls" id="rol" name="codRolApo" required>
            <option value="" disabled selected>Selecciona...</option>
            <!-- KM = Roles predefinidos -->
            <option value="1">Administrador/a</option>
            <option value="2">Psicólogo/a</option>
            <option value="3">Asesor/a</option>
            <option value="4">Enfermero/a</option>
            <option value="5">Consultor/a legal</option>
          </select>

          <!-- KM = Validaciones al ingresar los documentos -->
          <label class="label-bold" for="documento">Documento:</label>
          <input class="controls" type="text" id="documento" name="documento"
            placeholder="..." required pattern="\d{7,11}"
            title="El documento debe tener entre 7 y 11 dígitos numéricos">

          <label class="label-bold" for="password">Contraseña:</label>
          <div class="input-icon">
            <input class="controls" type="password" name="password" id="password" placeholder="..." required autocomplete="current-password">
            <img src="../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="toggleVerifyPasswordIcon">
          </div>
        </div>

        <!--KM = Mensajes de error -->
        <?php if (!empty($mensajeError)) : ?>
          <small id="mensaje-error" class="error-message"><?= $mensajeError ?></small>
        <?php endif; ?>

        <button class="boton-ma" type="submit">Ingresar</button>
      </fieldset>

      <!--KM = Recuperacion de cuenta y volver (Arriba esta el ingresar jaja)-->
      <div id="usuario-links">
        <a href="recuperar_contraseña_apoyo.php" id="forgot-password">¿Olvidaste tu contraseña?</a>
      </div>

      <a href="inicio_sesion.html" class="btn-glow">Volver</a>
    </section>
  </form>

  <!-- KM = Llamado de JavaScript-->
  <script src="../../java_script/pagina_principal/inicio_sesion_empelado.js"></script>
  <script src="../../java_script/pagina_principal/particulas.min.js"></script>
  <script src="../../java_script/pagina_principal/aplicacion_particula.js"></script>
  <!-- KM = Librería SweetAlert2 -->
  <script src="../../libreria/sweetalert/sweetalert2.all.min.js"></script>

  <!-- KM = Mostrar un mensaje con el motivo si esta desactivado-->
  <?php if (isset($_GET['error']) && $_GET['error'] === 'inhabilitado' && isset($_GET['motivo'])): ?>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        Swal.fire({
          icon: 'error',
          title: 'Acceso Denegado',
          html: `<p><strong>Motivo:</strong> <?= urldecode($_GET['motivo']) ?></p>
                   <p>Si tienes más dudas, puedes escribirnos a:<br>
                   <a href="mailto:mailto:redapoyofemenino@gmail.com">redapoyofemenino@gmail.com</a></p>`,
          confirmButtonText: 'Entendido',
          confirmButtonColor: '#d33'
        });
      });
    </script>
  <?php endif; ?>
</body>

</html>

<!-- KM = Limpiar los errores -->
<?php unset($_SESSION['error_login']); ?>

<!--Plantilla tomada de: Andev.web-->