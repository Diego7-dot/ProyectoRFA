<!-- KM = Recuperar contraseña para personas de apoyo -->

<?php
$mensajeError = '';
$documentoGuardado = '';

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'no_encontrado':
            $mensajeError = '*No se encontró una persona de apoyo con esos datos 😢';
            break;
        case 'campos_vacios':
            $mensajeError = 'Por favor completa todos los campos.';
            break;
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
    <!-- KM = Personalizacion de la pagina -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--KM = Referente de icono-->
    <link href="../../iconos/pagina_principal/logo_fem_icon.png" rel="icon">
    <!--KM = Titulo-->
    <title>Recuperar contraseña - Persona de Apoyo</title>
    <!--KM = Referencia a el CSS-->
    <link href="../../css/pagina_principal/recuperar_contraseña.css" rel="stylesheet">
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

    <!-- KM = Cuerpo del formulario recuperar contraseña -->
    <form action="../../src/auth/recuperar_con/procesar_documento.php" method="post" id="recuperar-form">
        <section class="form-recuperar">
            <label class="titulo">Recuperar Contraseña</label>

            <fieldset>
                <legend class="label-bold" for="codRolApo">Rol:</legend>
                <select class="controls" id="codRolApo" name="codRolApo" required>
                    <option value="" disabled selected>Selecciona tu rol</option>
                    <option value="1">Administrador/a</option>
                    <option value="2">Psicólogo/a</option>
                    <option value="3">Asesor/a</option>
                    <option value="4">Enfermero/a</option>
                    <option value="5">Consultor/a legal</option>
                </select>

                <legend class="label-bold" for="documento">Documento:</legend>
                <input class="controls" type="text" id="documento" name="documento"
                    placeholder="Ingresa tu documento" required
                    pattern="\d{7,11}" title="Debe tener entre 7 y 11 dígitos numéricos"
                    value="<?= $documentoGuardado ?>" />

                <?php if (!empty($mensajeError)) : ?>
                    <small id="mensaje-error" class="error-message"><?= $mensajeError ?></small>
                <?php endif; ?>

                <button class="botons" type="submit">Enviar código</button>
            </fieldset>

            <!-- KM = Botones de volver y recuperar contraseña -->
            <p><a href="inicio_sesion_empleado.php" class="btn-glow">Volver al inicio de sesión</a></p>
            <p><a href="index.html" class="btn-glow">Regresar al menú principal</a></p>
        </section>
    </form>

    <div id="mensaje-info" style="text-align: center; color: red; margin-top: 10px;"></div>

    <!-- KM = Estilo de js -->
    <script src="../../java_script/pagina_principal/recuperar_contraseña.js"></script>
    <script src="../../java_script/pagina_principal/particulas.min.js"></script>
    <script src="../../java_script/pagina_principal/aplicacion_particula.js"></script>

    <!-- KM = Librería SweetAlert2 -->
    <script src="../../libreria/sweetalert/sweetalert2.all.min.js"></script>

    <!-- KM = Mostrar un mensaje con el motivo si esta desactivado-->
    <?php if (isset($_GET['error']) && $_GET['error'] === 'inhabilitado'): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Cuenta inhabilitada',
                text: 'No puedes recuperar la contraseña porque esta cuenta está desactivada.',
                footer: '<a href="mailto:redapoyofemenino@gmail.com">¿Necesitas ayuda?</a>'
            });
        </script>
    <?php endif; ?>
</body>

</html>

<!-- KM = Limpiar los errores -->
<?php unset($_SESSION['error_login']); ?>