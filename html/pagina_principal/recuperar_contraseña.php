<!--KM = Recuperar contraseña usuarios -->

<?php
$mensajeError = '';
$correoGuardado = '';

if (isset($_GET['error'])) {
  switch ($_GET['error']) {
    case 'correo_vacio':
      $mensajeError = 'Por favor, ingresa tu correo.';
      break;
    case 'formato_invalido':
      $mensajeError = 'El formato del correo no es válido.';
      break;
    case 'correo_no_encontrado':
      $mensajeError = 'No se encontró una cuenta asociada a ese correo.';
      break;
    case 'error_envio':
      $mensajeError = 'No se pudo enviar el código. Inténtalo más tarde.';
      break;
    default:
      $mensajeError = 'Ocurrió un error inesperado.';
      break;
  }
}

if (isset($_GET['correo'])) {
  $correoGuardado = htmlspecialchars($_GET['correo']);
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
  <title>Recuperar contraseña</title>
  <!--KM = Referencia a el CSS-->
  <link href="../../css/pagina_principal/recuperar_contraseña.css" rel="stylesheet">
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
  <form action="../../src/auth/recuperar_con/procesar_correo.php" method="post" id="recuperar-form">
    <section class="form-recuperar">

      <label class="titulo">Recuperar Contraseña</label>
      <div id="paso-correo">
        <fieldset>
          <legend class="label-bold" for="correo">Correo electrónico:</legend>
          <input class="controls" type="email" name="correo" id="correo" required>

          <!-- KM = Mensaje de error -->
          <?php if (!empty($mensajeError)): ?>
            <small id="mensaje-error" class="error-message"><?= $mensajeError ?></small>
          <?php endif; ?>

          <button class="botons" type="submit">Enviar código</button>
        </fieldset>
      </div>

      <p><a href="inicio_sesion_usuario.php" class="btn-glow">Volver al inicio de sesión</a></p>
      <p><a href="index.html" class="btn-glow">Regresar al menú principal</a></p>
    </section>
  </form>

  <div id="mensaje-info" style="text-align: center; color: red; margin-top: 10px;"></div>

  <!-- KM = Estilo de js -->
  <script src="../../java_script/pagina_principal/recuperar_contraseña.js"></script>
  <script src="../../java_script/pagina_principal/particulas.min.js"></script>
  <script src="../../java_script/pagina_principal/aplicacion_particula.js"></script>
</body>

</html>

<!-- KM = Limpiar los errores -->
<?php unset($_SESSION['error_login']); ?>

<!--Plantilla tomada de: Andev.web-->