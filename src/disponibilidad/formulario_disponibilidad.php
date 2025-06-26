<?php
// KM = Formualrio de registro disponibilidad
session_start();
$documento = $_SESSION['docApo'] ?? '';
?>
<section id="disponibilidad-form">
    <h2>Registrar disponibilidad semanal</h2>
    <p class="subtitulo">Agrega tus bloques de disponibilidad por día. Puedes registrar varios rangos por jornada.</p>

    <!-- KM = Documento de la persona de apoyo -->
    <input type="hidden" id="docApo" value="<?= htmlspecialchars($documento) ?>">

    <!-- KM = Contenedor general -->
    <form id="form-disponibilidad" onsubmit="return false;">
        <div id="contenedor-horarios" class="contenedor-horarios">
            <!-- KM = Aqui van los dias y botones -->
        </div>

        <button id="btn-guardar-todo" type="button">Guardar disponibilidad</button>
    </form>
</section>

<!-- KM = Modal de copiar horario -->
<div id="modal-copiar-horario" class="modal-overlay" style="display:none;">
    <div class="modal-copiar">
        <h3>Copiar horario a otros días</h3>
        <p>Selecciona los días a los que deseas aplicar este horario:</p>
        <form id="form-copiar-dias">
            <div class="dias-checkboxes">
                <!-- KM = Se llena desde JS -->
            </div>
            <div class="modal-btns">
                <button type="button" class="btn-aplicar">✔ Aplicar</button>
                <button type="button" class="btn-cancelar" id="cancelar-copia">❌ Cancelar</button>
            </div>
        </form>
    </div>
</div>