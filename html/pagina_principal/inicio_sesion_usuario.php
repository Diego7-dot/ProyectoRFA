<!--KM = Inicio sesion_Empleado: Red femenina de apoyo-->

<?php
session_start();

$mensajeError = '';
$correoGuardado = '';

if (isset($_GET['error'])) {
  switch ($_GET['error']) {
    case 'credenciales_invalidas':
      $mensajeError = '*Correo o contraseña inválido 😢';
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

if (isset($_GET['correo'])) {
  $correoGuardado = htmlspecialchars($_GET['correo']);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <!--KM = Evitar cache y autocompletado -->
  <meta http-equiv="Cache-Control" content="no-store" />
  <meta http-equiv="Pragma" content="no-cache" />
  <!--KM = Permitir caracteres especiales-->
  <meta charset="utf-8">
  <!--KM = Optimizador para los dispositivos moviles-->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--KM = Referente de icono-->
  <link href="../../iconos/pagina_principal/logo_fem_icon.png" rel="icon">
  <!--KM = Titulo-->
  <title>Inicio de sesión</title>
  <!--KM = Referencia a el CSS-->
  <link href="../../css/pagina_principal/inicio_sesion_usuario.css" rel="stylesheet">
</head>

<body>
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

  <!--KM = llamado de las partículas-->
  <div id="particles-js"></div>

  <main>
    <!--KM = Creación del formulario para su envío-->
    <form action="../../src/auth/login_usuario.php" method="post">
      <section class="form-sesion">
        <label class="titulo">Inicio de sesión</label>
        <label class="titula">Usuari@</label>

        <!--KM = Formulario de usuario (Correo y contraseña)-->
        <fieldset>
          <div id="form-usuario">
            <label class="label-bold" for="corUsu">Correo eléctronico:</label>
            <input class="controls" type="email" name="corUsu" id="corUsu"
              value="<?= $correoGuardado ?>" placeholder="..." required>

            <label class="label-bold" for="password-usuario">Contraseña:</label>
            <div class="input-icon">
              <input class="controls" type="password" name="psw" id="psw" placeholder="..." required autocomplete="off">
              <img src=" ../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="togglePasswordIcon">
            </div>
          </div>

          <?php if (!empty($mensajeError)) : ?>
            <small id="mensaje-error" class="error-message"><?= $mensajeError ?></small>
          <?php endif; ?>

          <button class="boton-ma" type="submit">Ingresar</button>
        </fieldset>

        <!--KM = Enlaces y botón de envío, no se muestra el registro en el empleado :)-->
        <div id="usuario-links">
          <p>¿No tienes cuenta?<a href="registro_usuario.php" id="registro-usuario"> Regístrate</a></p>
          <a href="recuperar_contraseña.php" id="forgot-password">¿Olvidaste tu contraseña?</a>
        </div>

        <a href="inicio_sesion.html" class="btn-glow">Volver</a>

      </section>
    </form>
  </main>

  <!--Llamado de JavaScript-->
  <script src="../../java_script/pagina_principal/inicio_sesion_usuario.js"></script>
  <script src="../../java_script/pagina_principal/particulas.min.js"></script>
  <script src="../../java_script/pagina_principal/aplicacion_particula.js"></script>
</body>

</html>

<!-- KM = Limpiar los errores -->
<?php unset($_SESSION['error_login']); ?>

<!--Plantilla tomada de: Andev.web y modifiado por Keren Morgado-->