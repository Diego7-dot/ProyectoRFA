<?php
// KM = Calendario de disponibilidad
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$documento = $_SESSION['docApo'] ?? '';
?>
<div class="contenedor-disponibilidad">
    <!-- KM = Barra de pestaña -->
    <div class="tabs">
        <button data-tab="cal" class="tab active">Calendario</button>
        <button data-tab="pat" class="tab">Disponibilidad semanal</button>
        <button data-tab="esp" class="tab">Fechas especiales</button>
    </div>

    <!-- KM = Formulario para visualizar el calendario -->
    <div id="panel-cal" class="tab-panel active">
        <section id="calendario-disponibilidad">
            <h2 class="titulo-calendario">Disponibilidad mensual</h2>
            <input type="hidden" id="docApo" value="<?= htmlspecialchars($documento) ?>">

            <div class="selector-mes">
                <label>Ver disponibilidad de:</label>
                <select id="select-mes"></select>
                <select id="select-anio"></select>
                <button id="btn-ver-mes">Ver</button>
            </div>

            <div class="header-calendario">
                <div>Dom</div>
                <div>Lun</div>
                <div>Mar</div>
                <div>Mié</div>
                <div>Jue</div>
                <div>Vie</div>
                <div>Sáb</div>
            </div>

            <div class="calendario-grid" id="contenedor-calendario"></div>

            <div class="leyenda-cal">
                <div class="ley-item"><span class="badge badge-real"></span>Horario confirmado</div>
                <div class="ley-item"><span class="badge badge-fut"></span>Programado (futuro)</div>
                <div class="ley-item"><span class="badge badge-past"></span>Historial pasado</div>
            </div>

        </section>
    </div>

    <div id="panel-pat" class="tab-panel">
        <!-- KM = Formulario que para editar disponibilidad -->
    </div>

    <div id="panel-esp" class="tab-panel">
        <!-- KM = Nuevo formulario para agregar vacaciones/dias unicos -->
    </div>
</div>