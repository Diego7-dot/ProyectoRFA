<!--KM = Ingresar código de recuperación de contraseña (Persona de Apoyo) -->
<?php
session_start();
$tiempoRestante = 0;

if (isset($_SESSION['expira_codigo_apoyo'])) {
    $tiempoRestante = $_SESSION['expira_codigo_apoyo'] - time();
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
    <!--KM = Optimizador para los dispositivos móviles-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--KM = Referente de icono-->
    <link href="../../iconos/pagina_principal/logo_fem_icon.png" rel="icon">
    <!--KM = Título-->
    <title>Recuperar contraseña</title>
    <!--KM = Referencia al CSS-->
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

    <!--KM = Formulario de verificación del código-->
    <form action="../../src/auth/recuperar_con/procesar_codigo_apoyo.php" method="POST" id="form-codigo">
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

    <!--KM = Librerias y scrip de js-->
    <script src="../../java_script/pagina_principal/recuperar_codigo.js"></script>
    <script src="../../java_script/pagina_principal/particulas.min.js"></script>
    <script src="../../java_script/pagina_principal/aplicacion_particula.js"></script>
    <script>
        const tiempoRestante = <?= $tiempoRestante ?>;
    </script>
</body>

</html>