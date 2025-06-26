<!--KM = Editar usuario -->

<?php require_once("../../src/auth/consultar_usuario.php"); ?>

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

            <!-- KM = Mostrar el correo pero no sera editable -->
            <form id="formEditar" action="../../src/auth/editar_usuario.php" method="POST" enctype="multipart/form-data">
                <div class="campo">
                    <label for="corUsu">Correo:</label>
                    <input type="email" name="corUsu" id="corUsu" value="<?= htmlspecialchars($correo) ?>" readonly class="input-bloqueado">
                </div>

                <!-- KM = Cambio de nombre o alias -->
                <div class="campo">
                    <label for="nomUsu">Nombre o alias:</label>
                    <input type="text" name="nomUsu" id="nomUsu" value="<?= htmlspecialchars($nombre) ?>" maxlength="100" required>
                </div>

                <!-- KM = Cambio de fecha de nacimiento -->
                <div class="campo">
                    <label for="fecNacUsu">Fecha de nacimiento:</label>
                    <input type="date" name="fecNacUsu" value="<?= htmlspecialchars($fecNacUsu) ?>" required>
                </div>

                <!-- KM = Cambio de genero -->
                <div class="campo">
                    <label for="genUsu">Género:</label>
                    <select name="genUsu" id="genUsu" required>
                        <option value="F" <?= $genero === 'F' ? 'selected' : '' ?>>Femenino</option>
                        <option value="M" <?= $genero === 'M' ? 'selected' : '' ?>>Masculino</option>
                        <option value="O" <?= $genero === 'O' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>

                <!-- KM = Boton para mostrar campos de contraseña -->
                <button type="button" class="boton-mac" id="btnContrasena" onclick="mostrarCamposContrasena()">Cambiar contraseña</button>
                <input type="hidden" name="seccionContrasenaActiva" id="seccionContrasenaActiva" value="0">

                <!-- KM = Contenedor oculto de los campos de contraseña -->
                <div id="seccion-contrasena" style="display: none; margin-top: 20px;">
                    <!-- KM = Contraseña -->
                    <label class="label-bold" for="nuevaPsw">Contraseña:</label>
                    <div class="input-icon">
                        <input class="controls" type="password" name="nuevaPsw" id="nuevaPsw" placeholder="..." autocomplete="new-password">
                        <img src="../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="togglePasswordIcon">
                    </div>

                    <!-- KM = Confirmar contraseña -->
                    <label class="label-bold" for="confirmarPsw">Confirmar contraseña:</label>
                    <div class="input-icon">
                        <input class="controls" type="password" name="confirmarPsw" id="confirmarPsw" placeholder="..." autocomplete="new-password">
                        <img src="../../iconos/pagina_principal/ojo_cerrado.png" alt="Mostrar contraseña" class="icon" id="toggleVerifyPasswordIcon">
                    </div>
                </div>

                <!-- KM = Cambio de foto de perfil -->
                <div class="campo">
                    <label class="fotito" for="fotoPerfil">Foto de perfil:</label>
                    <div id="previewImagenSeleccionada">
                        <img src="../../iconos/iconos_perfil/<?= htmlspecialchars($foto) ?>" alt="Foto actual" class="preview-foto" id="imagenActual">
                    </div>
                    <button type="button" onclick="abrirSelectorImagen()" class="boton-mai">Seleccionar imagen</button><input type="hidden" name="fotoPerfil" id="fotoPerfil" value="<?= htmlspecialchars($foto) ?>">
                </div>
                <button class="boton-ma" type="submit">Guardar cambios</button>
                <a class="btn-glow" href="inicio_usuario.php">Volver</a>
            </form>
        </section>
    </main>

    <!-- KM = Imagenes para que el usuario seleccione -->
    <div id="modalImagen" class="modal" style="display: none;">
        <div class="modal-contenido">
            <div class="encabezado-modal">
                <h3 class="ylacosasunea">Selecciona tu imagen de perfil</h3>
                <span class="cerrar" onclick="cerrarSelectorImagen()">&times;</span>
            </div>
            <div class="imagenes-opciones">
                <img src="../../iconos/iconos_perfil/icono_usuario_1.png" onclick="seleccionarImagen(this, 'icono_usuario_1.png')">
                <img src="../../iconos/iconos_perfil/icono_usuario_2.png" onclick="seleccionarImagen(this, 'icono_usuario_2.png')">
                <img src="../../iconos/iconos_perfil/icono_usuario_3.png" onclick="seleccionarImagen(this, 'icono_usuario_3.png')">
                <img src="../../iconos/iconos_perfil/icono_usuario_4.png" onclick="seleccionarImagen(this, 'icono_usuario_4.png')">
                <img src="../../iconos/iconos_perfil/icono_usuario_5.png" onclick="seleccionarImagen(this, 'icono_usuario_5.png')">
                <img src="../../iconos/iconos_perfil/icono_usuario_6.png" onclick="seleccionarImagen(this, 'icono_usuario_6.png')">
            </div>
            <button type="button" class="boton-mai" onclick="confirmarImagen()">Aceptar</button>
        </div>
    </div>

    <!-- KM = Js del editar usuario-->
    <script src="../../java_script/pagina_usuario/editar_usuario.js"></script>
</body>

</html>