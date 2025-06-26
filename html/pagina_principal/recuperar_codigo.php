<!--KM = Ingresar codigo de recuperacion de contraseña -->
<?php
session_start();
$tiempoRestante = 0;

if (isset($_SESSION['expira_codigo'])) {
    $tiempoRestante = $_SESSION['expira_codigo'] - time();
    if ($tiempoRestante < 0) {
        $tiempoRestante = 0;
    }
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
    <link rel="stylesheet" href="../../css/pagina_principal/recuperar_codigo.css">
    <!--KM = Envia al js el tiempo real de la expiracion del codigo-->
    <script>
        const tiempoRestante = <?= $tiempoRestante ?>;
    </script>
</head>

<body>
    <!-- KM = Particulas del formulario -->
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

    <!--KM = Codigo de recuperacion -->
    <form action="../../src/auth/recuperar_con/procesar_codigo.php" method="POST" id="form-codigo">
        <h2>Ingresa el código de verificación</h2>
        <p id="temporizador" class="temporizador">10:00</p>

        <div class="contenedor-codigo">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <input type="text" class="digito" name="d<?php echo $i; ?>" maxlength="1" inputmode="numeric" pattern="\d*" required>
            <?php endfor; ?>
        </div>

        <button type="submit">Verificar</button>

        <?php
        if (isset($_GET['error'])) echo "<p style='color:red'>" . htmlspecialchars($_GET['error']) . "</p>";
        ?>
    </form>

    <script src="../../java_script/pagina_principal/recuperar_codigo.js"></script>
    <script src="../../java_script/pagina_principal/particulas.min.js"></script>
    <script src="../../java_script/pagina_principal/aplicacion_particula.js"></script>
</body>

</html>