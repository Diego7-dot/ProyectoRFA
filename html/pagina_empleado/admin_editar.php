<!-- KM = Editar persona de apoyo -->

<?php require_once("../../src/auth/consultar_persona_apoyo.php"); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- KM = Caracteres especiales -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- KM = Referente de icono -->
    <link href="../../iconos/pagina_principal/logo_fem_icon.png" rel="icon">
    <!-- KM = Título -->
    <title>Editar perfil</title>
    <!-- KM = Estilo de CSS -->
    <link rel="stylesheet" href="../../css/pagina_usuario/estilo_editar_usuario.css">
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

    <!-- KM = Contenedor de editar perfil -->
    <main class="contenedor-editar">
        <section class="panel-editar">
            <h2 class="titulo-editar">Editar perfil</h2>

            <!-- KM = Mensajes de error -->
            <?php if (isset($_SESSION["error_edicion"])): ?>
                <div class="mensaje-error">
                    <?= htmlspecialchars($_SESSION["error_edicion"]) ?>
                    <?php unset($_SESSION["error_edicion"]); ?>
                </div>
            <?php endif; ?>

            <form id="formEditar" action="../../src/auth/editar_persona_apoyo.php" method="POST" enctype="multipart/form-data">
                <!-- KM = Documento no editable -->
                <div class="campo">
                    <label for="docApo">Documento:</label>
                    <input type="text" name="docApo" id="docApo" value="<?= htmlspecialchars($documento) ?>" readonly class="input-bloqueado">
                </div>

                <!-- KM = Correo editable, pero único en la BD -->
                <div class="campo">
                    <label for="corApo">Correo:</label>
                    <input type="email" name="corApo" id="corApo" value="<?= htmlspecialchars($correo) ?>" maxlength="100" required>
                </div>

                <!-- KM = Nombre -->
                <div class="campo">
                    <label for="nomApo">Nombre:</label>
                    <input type="text" name="nomApo" id="nomApo" value="<?= htmlspecialchars($nombre) ?>" maxlength="100" required>
                </div>

                <!-- KM = Apellido -->
                <div class="campo">
                    <label for="apeApo">Apellido:</label>
                    <input type="text" name="apeApo" id="apeApo" value="<?= htmlspecialchars($apellido) ?>" maxlength="100" required>
                </div>

                <!-- KM = Fecha de nacimiento -->
                <div class="campo">
                    <label for="fecNacApo">Fecha de nacimiento:</label>
                    <input type="date" name="fecNacApo" value="<?= htmlspecialchars($fecNac) ?>" required>
                </div>

                <!-- KM = Género -->
                <div class="campo">
                    <label for="genApo">Género:</label>
                    <select name="genApo" id="genApo" required>
                        <option value="F" <?= $genero === 'F' ? 'selected' : '' ?>>Femenino</option>
                        <option value="M" <?= $genero === 'M' ? 'selected' : '' ?>>Masculino</option>
                        <option value="O" <?= $genero === 'O' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>

                <!-- KM = Teléfono -->
                <div class="campo">
                    <label for="telApo">Teléfono:</label>
                    <input type="text" name="telApo" id="telApo"
                        value="<?= htmlspecialchars($telefono) ?>"
                        pattern="\d{7,11}"
                        maxlength="11"
                        title="El teléfono debe tener entre 7 y 11 dígitos numéricos"
                        inputmode="numeric"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        required>
                </div>

                <!-- KM = Dirección -->
                <div class="campo">
                    <label for="dirApo">Dirección:</label>
                    <input type="text" name="dirApo" id="dirApo" value="<?= htmlspecialchars($direccion) ?>" maxlength="30" required>
                </div>

                <!-- KM = Ciudad editable -->
                <div class="campo">
                    <label for="codCiuApo">Ciudad:</label>
                    <select name="codCiuApo" id="codCiuApo" required>
                        <?php foreach ($ciudades as $ciu): ?>
                            <option value="<?= $ciu['codCiu'] ?>" <?= $ciu['codCiu'] == $codCiudad ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ciu['nomCiu']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- KM = Rol (solo lectura) -->
                <div class="campo">
                    <label for="codRolApo">Rol:</label>
                    <input type="text" name="codRolApo" id="codRolApo" value="<?= htmlspecialchars($nombreRol) ?>" readonly class="input-bloqueado">
                </div>

                <!-- KM = Tarjeta profesional (solo lectura) -->
                <div class="campo">
                    <label for="numTarProApo">Tarjeta profesional:</label>
                    <input type="text" name="numTarProApo" id="numTarProApo" value="<?= htmlspecialchars($tarjeta) ?>" readonly class="input-bloqueado">
                </div>

                <!-- KM = Especialidad (solo lectura) -->
                <div class="campo">
                    <label for="espProApo">Especialidad:</label>
                    <input type="text" name="espProApo" id="espProApo" value="<?= htmlspecialchars($especialidad) ?>" readonly class="input-bloqueado">
                </div>

                <!-- KM = Botón mostrar sección contraseña -->
                <button type="button" class="boton-mac" id="btnContrasena" onclick="mostrarCamposContrasena()">Cambiar contraseña</button>
                <input type="hidden" name="seccionContrasenaActiva" id="seccionContrasenaActiva" value="0">

                <!-- KM = Contenedor oculto de contraseña -->
                <div id="seccion-contrasena" style="display: none; margin-top: 20px;">
                    <label class="label-bold" for="nuevaPsw">Contraseña:</label>
                    <div class="input-icon">
                        <input class="controls" type="password" name="nuevaPsw" id="nuevaPsw" placeholder="..." autocomplete="new-password">
                        <img src="../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="togglePasswordIcon">
                    </div>

                    <label class="label-bold" for="confirmarPsw">Confirmar contraseña:</label>
                    <div class="input-icon">
                        <input class="controls" type="password" name="confirmarPsw" id="confirmarPsw" placeholder="..." autocomplete="new-password">
                        <img src="../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="toggleVerifyPasswordIcon">
                    </div>
                </div>

                <!-- KM = Foto de perfil -->
                <div class="campo">
                    <label class="fotito" for="fotoPerfil">Foto de perfil:</label>
                    <div id="previewImagenSeleccionada">
                        <img src="../../iconos/iconos_perfil/<?= htmlspecialchars($foto) ?>" alt="Foto actual" class="preview-foto" id="imagenActual">
                    </div>
                    <button type="button" onclick="abrirSelectorImagen()" class="boton-mai">Seleccionar imagen</button>
                    <input type="hidden" name="fotoPerfil" id="fotoPerfil" value="<?= htmlspecialchars($foto) ?>">
                </div>

                <!-- KM = Botones finales -->
                <button class="boton-ma" type="submit">Guardar cambios</button>
                <a class="btn-glow" href="inicio_empleado_admin.php">Volver</a>
            </form>
        </section>
    </main>

    <!-- KM = Modal selector de imagen -->
    <div id="modalImagen" class="modal" style="display: none;">
        <div class="modal-contenido">
            <div class="encabezado-modal">
                <h3 class="ylacosasunea">Selecciona tu imagen de perfil</h3>
                <span class="cerrar" onclick="cerrarSelectorImagen()">&times;</span>
            </div>
            <div class="imagenes-opciones">
                <img src="../../iconos/iconos_perfil/icono_admin_1.png" onclick="seleccionarImagen(this, 'icono_admin_1.png')">
                <img src="../../iconos/iconos_perfil/icono_admin_2.png" onclick="seleccionarImagen(this, 'icono_admin_2.png')">
                <img src="../../iconos/iconos_perfil/icono_admin_3.png" onclick="seleccionarImagen(this, 'icono_admin_3.png')">
            </div>
            <button type="button" class="boton-mai" onclick="confirmarImagen()">Aceptar</button>
        </div>
    </div>

    <!-- KM = JS para editar persona de apoyo (usa el mismo de usuario si aplica) -->
    <script src="../../java_script/pagina_usuario/editar_usuario.js"></script>
</body>

</html>