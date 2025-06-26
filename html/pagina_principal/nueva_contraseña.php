<!--KM =  INgresar la nueva contraseña -->
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
    <title>Recuperar contraseña</title>
    <!--KM = Referencia a el CSS-->
    <link rel="stylesheet" href="../../css/pagina_principal/estilo_nueva_contraseña.css">
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

    <form action="../../src/auth/recuperar_con/guardar_contra_usu.php" method="POST" id="form-restablecer">
        <h2>Ingresa tu nueva contraseña</h2>

        <label for="psw1">Nueva contraseña:</label>
        <div class="input-icon">
            <input class="controls" type="password" name="nuevaPsw" id="psw1" placeholder="..." required autocomplete="off">
            <img src="../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="togglePassword1">
        </div>

        <label for="psw2">Confirmar contraseña:</label>
        <div class="input-icon">
            <input class="controls" type="password" name="confirmarPsw" id="psw2" placeholder="..." required autocomplete="off">
            <img src="../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="togglePassword2">
        </div>

        <button type="submit" class="botons">Guardar contraseña</button>

        <p id="mensaje-error" style="color:red;"></p>
        <?php
        if (isset($_GET['error'])) echo "<p style='color:red'>" . htmlspecialchars($_GET['error']) . "</p>";
        if (isset($_GET['exito'])) echo "<p style='color:green'>" . htmlspecialchars($_GET['exito']) . "</p>";
        ?>
    </form>

    <!-- KM = Js con estilo y particulas -->
    <script src="../../java_script/pagina_principal/nueva_contraseña.js"></script>
    <script src="../../java_script/pagina_principal/particulas.min.js"></script>
    <script src="../../java_script/pagina_principal/aplicacion_particula.js"></script>
</body>

</html>