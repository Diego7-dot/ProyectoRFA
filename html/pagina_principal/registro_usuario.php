<!-- Registro de usuarios: Red femenina de apoyo -->
<?php session_start(); ?>
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
  <title>Registro usuario</title>
  <!--KM = Referencia a el CSS-->
  <link href="../../css/pagina_principal/registro_usuario.css" rel="stylesheet">
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

  <!--KM = Aviso de registro exitoso o error-->
  <main class="contenido-principal">
    <?php if (isset($_SESSION["registro_exitoso"])): ?>
      <div class="mensaje-centro exito">
        <span class="cerrar-mensaje" onclick="this.parentElement.style.display='none';">✖</span>
        ¡Registro completado con éxito!
      </div>
      <?php unset($_SESSION["registro_exitoso"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["error_registro"])): ?>
      <div class="mensaje-centro error">
        <span class="cerrar-mensaje" onclick="this.parentElement.style.display='none';">✖</span>
        <?php echo htmlspecialchars($_SESSION["error_registro"]); ?>
      </div>
      <?php unset($_SESSION["error_registro"]); ?>
    <?php endif; ?>

    <!--KM = llamado las particulas-->
    <div id="particles-js"></div>

    <!--KM = Creacion del fomulario para su envio-->
    <form action="../../src/auth/insert_into_usu.php" method="post">
      <!--KM = Formulario-->
      <section class="form-register">
        <label class="titulo">Registro</label>

        <fieldset>
          <!--KM = Campos de para poder registrar al usuario-->
          <!-- KM = Correo -->
          <legend class="label-bold">Correo electrónico:</legend>
          <input class="controls" type="email" name="corUsu" id="corUsu" placeholder="..." required autocomplete="email"
            value="<?php echo $_SESSION['old_inputs']['corUsu'] ?? ''; ?>">

          <!-- KM = Nombre o alias -->
          <label class="label-bold" for="nomUsu">Nombre o alias:</label>
          <input class="controls" type="text" name="nomUsu" id="nomUsu" placeholder="..." maxlength="100" autocomplete="name"
            value="<?php echo $_SESSION['old_inputs']['nomUsu'] ?? ''; ?>">

          <!-- KM = Fecha de nacimiento -->
          <label class="label-bold" for="fecNacUsu">Fecha de nacimiento:</label>
          <input class="controls" type="date" name="fecNacUsu" id="fecNacUsu" required autocomplete="bday"
            value="<?php echo $_SESSION['old_inputs']['fecNacUsu'] ?? ''; ?>">

          <!-- KM = Género -->
          <label class="label-bold">Género:</label>
          <div class="radio-group" role="radiogroup">
            <div class="radio-option">
              <input type="radio" id="genUsuF" name="genUsu" value="F"
                <?= (($_SESSION['old_inputs']['genUsu'] ?? '') === 'F') ? 'checked' : '' ?> required>
              <label for="genUsuF">Femenino</label>
            </div>
            <div class="radio-option">
              <input type="radio" id="genUsuM" name="genUsu" value="M"
                <?= (($_SESSION['old_inputs']['genUsu'] ?? '') === 'M') ? 'checked' : '' ?> required>
              <label for="genUsuM">Masculino</label>
            </div>
            <div class="radio-option">
              <input type="radio" id="genUsuO" name="genUsu" value="O"
                <?= (($_SESSION['old_inputs']['genUsu'] ?? '') === 'O') ? 'checked' : '' ?> required>
              <label for="genUsuO">Otro...</label>
            </div>
          </div>

          <small id="gen-error" class="error-message"></small>

          <!-- KM = Contraseña -->
          <label class="label-bold" for="conUsu">Contraseña:</label>
          <div class="input-icon">
            <input class="controls" type="password" name="psw" id="psw" placeholder="..." required autocomplete="new-password">
            <img src="../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="togglePasswordIcon">
          </div>

          <!-- KM = Confirmar contraseña -->
          <label class="label-bold" for="verConUsu">Confirmar contraseña:</label>
          <div class="input-icon">
            <input class="controls" type="password" name="verConUsu" id="verConUsu" placeholder="..." required autocomplete="new-password">
            <img src="../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="toggleVerifyPasswordIcon">
          </div>

          <!--KM = Terminos y envio del registro-->
          <label for="cbox1">
            <input type="checkbox" id="cbox1" name="terminos" required>
            Estoy de acuerdo con <a href="#">Términos y condiciones</a>
          </label>

          <button class="botons" type="submit">Registrar</button>
        </fieldset>

        <p>¿Ya tienes cuenta? <a href="inicio_sesion.html">Inicia sesión</a></p>
        <a href="index.html" class="btn-glow">Volver al inicio</a>
      </section>
    </form>
  </main>

  <!--KM = Referencia a el JS-->
  <script src="../../java_script/pagina_principal/particulas.min.js"></script>
  <script src="../../java_script/pagina_principal/aplicacion_particula.js"></script>
  <script src="../../java_script/pagina_principal/registro_usuario.js"></script>

</body>

<?php unset($_SESSION["old_inputs"]); ?>

</html>

<!--Plantilla tomada de: Andev.web y modificada y adaptada por Keren Daniela Morgado-->