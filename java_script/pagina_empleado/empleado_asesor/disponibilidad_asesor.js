// KM = JS de la disponibilidad del asesor

/* KM = Movimiento del menú desplegable */
function toggleUserMenu() {
    const userMenu = document.getElementById('user-menu');
    userMenu.classList.toggle('active');
}

/* KM = Abrir el menú secundario */
document.querySelectorAll('.menu_secundario .menu-item').forEach(item => {
    item.addEventListener('click', function () {
        document.querySelectorAll('.menu_secundario .menu-item').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        const section = this.getAttribute('data-section');
        loadSection(section);
        window.location.hash = section;
    });
});

/* KM = Abrir y cerrar Ayuda / Soporte */
const botonAbrirSoporte = document.getElementById('abrir-soporte');
const modalAyuda = document.getElementById('modal-ayuda');
const cerrarModalAyuda = document.getElementById('cerrar-modal');

botonAbrirSoporte.addEventListener('click', (e) => {
    e.preventDefault();
    modalAyuda.style.display = 'block';
});

cerrarModalAyuda.addEventListener('click', () => {
    modalAyuda.style.display = 'none';
});

/* KM = Abrir y cerrar Manuales */
const botonAbrirManuales = document.getElementById('abrir-manuales');
const modalManuales = document.getElementById('modal-manuales');
const cerrarModalManuales = document.getElementById('cerrar-modal-manuales');

botonAbrirManuales.addEventListener('click', (e) => {
    e.preventDefault();
    modalManuales.style.display = 'block';
});

cerrarModalManuales.addEventListener('click', () => {
    modalManuales.style.display = 'none';
});

/* KM = Cerrar los ventanas emergentes si hacen clic afuera */
window.addEventListener('click', (e) => {
    if (e.target === modalAyuda) {
        modalAyuda.style.display = 'none';
    }
    if (e.target === modalManuales) {
        modalManuales.style.display = 'none';
    }
});

/* KM = Botones internos para abrir los manuales */
document.getElementById('btn-usuario').addEventListener('click', () => {
    window.open('../../../manuales/manual_usuario.pdf', '_blank');
});

document.getElementById('btn-tecnico').addEventListener('click', () => {
    window.open('../../../manuales/manual_tecnico.pdf', '_blank');
});

/* KM = Modo oscuro */
const btnModo = document.getElementById('modo-toggle');
const iconoModo = document.getElementById('icono-modo');

btnModo.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');

    const modoOscuroActivo = document.body.classList.contains('dark-mode');
    iconoModo.src = modoOscuroActivo
        ? "../../../iconos/pagina_principal/sol.png"
        : "../../../iconos/pagina_principal/luna.png";

    iconoModo.alt = modoOscuroActivo ? "Modo claro" : "Modo oscuro";
});

/* KM = Botón scroll top */
const scrollTopBtn = document.getElementById('btnScrollTop');

window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
        scrollTopBtn.classList.add('visible');
    } else {
        scrollTopBtn.classList.remove('visible');
    }
});

scrollTopBtn.addEventListener('click', () => {
    const scrollStep = -window.scrollY / 60;
    const scrollInterval = setInterval(() => {
        if (window.scrollY !== 0) {
            window.scrollBy(0, scrollStep);
        } else {
            clearInterval(scrollInterval);
        }
    }, 16);
});

let bloqueAUsarParaCopiar = null; // KM = Variable para el bloque de copiar

// KM = Carga dinamicamente la vista de registro o edicion
document.addEventListener("DOMContentLoaded", () => {
    const contenedor = document.getElementById("contenido-disponibilidad");

    fetch("/Proyecto_real_v1/Proyecto_real/src/disponibilidad/consultar_disponibilidad.php")
        .then(res => res.json())
        .then(data => {
            const url = data.tieneDisponibilidad
                ? "editar_disponibilidad.php"
                : "formulario_disponibilidad.php";

            fetch(`/Proyecto_real_v1/Proyecto_real/src/disponibilidad/${url}`)
                .then(res => res.text())
                .then(html => {
                    contenedor.innerHTML = html;
                    requestAnimationFrame(() => {

                        const selMes = document.getElementById("select-mes");
                        const selAnio = document.getElementById("select-anio");
                        const btnVer = document.getElementById("btn-ver-mes");

                        if (selMes && selAnio) {
                            cargarOpcionesFecha();
                            mostrarCalendario();

                            if (btnVer) {
                                btnVer.onclick = mostrarCalendario;
                            }
                        }
                        if (data.tieneDisponibilidad) {
                            iniciarEdicionDisponibilidad();
                        } else {
                            iniciarRegistroDisponibilidad();
                        }

                        initDisponibilidadTabs();  
                    });
                });
        })
        .catch(err => console.error("Error al consultar disponibilidad:", err));
});

/////////////////////////////////////////
// KM = Registro de disponibilidad 
/////////////////////////////////////////

function iniciarRegistroDisponibilidad() {
    const diasSemana = ["lunes", "martes", "miércoles", "jueves", "viernes", "sábado", "domingo"];
    const contenedor = document.getElementById("contenedor-horarios");

    diasSemana.forEach(dia => {
        const bloqueDia = document.createElement("div");
        bloqueDia.className = "bloque-dia";
        bloqueDia.innerHTML = `
            <h4>${dia.charAt(0).toUpperCase() + dia.slice(1)}</h4>
            <div class="rangos" id="rangos-${dia}"></div>
            <button class="btn-agregar" data-dia="${dia}">➕ Agregar</button>
        `;
        contenedor.appendChild(bloqueDia);
    });

    contenedor.addEventListener("click", (e) => {
        if (e.target.classList.contains("btn-agregar")) {
            const dia = e.target.dataset.dia;
            const contenedorRangos = document.getElementById(`rangos-${dia}`);
            const bloquesDia = contenedorRangos.querySelectorAll(".bloque-horario");

            if (bloquesDia.length >= 4) {
                Swal.fire("Límite alcanzado", "Solo puedes agregar hasta 4 bloques por día", "warning");
                return;
            }

            const bloque = document.createElement("div");
            bloque.className = "bloque-horario";
            bloque.dataset.dia = dia;
            bloque.innerHTML = `
                <div class="input-horarios">
                    <input type="time" class="hora-inicio" required> a 
                    <input type="time" class="hora-fin" required>
                    <span class="error-hora"></span>
                </div>
                <div class="botones-bloque">
                    <button class="btn-eliminar" title="Eliminar">❌ Eliminar</button>
                    <button class="btn-copiar" title="Duplicar">📋 Copiar</button>
                </div>
            `;
            contenedorRangos.appendChild(bloque);
            asignarEventosABloque(bloque);
        }

        if (e.target.classList.contains("btn-eliminar")) {
            e.target.closest(".bloque-horario").remove();
        }

        if (e.target.classList.contains("btn-copiar")) {
            const bloque = e.target.closest(".bloque-horario");
            const horaIni = bloque.querySelector(".hora-inicio").value;
            const horaFin = bloque.querySelector(".hora-fin").value;
            const diaActual = bloque.dataset.dia;

            if (!horaIni || !horaFin) {
                Swal.fire("Atención", "Completa las horas antes de copiar.", "warning");
                return;
            }

            if (horaFin <= horaIni) {
                Swal.fire("Horario inválido", "La hora de fin debe ser mayor que la de inicio", "error");
                return;
            }

            if (!validarDuracion(horaIni, horaFin)) {
                Swal.fire("Duración inválida", "La duración debe estar entre 2 y 12 horas", "warning");
                return;
            }

            if (cruzaMedianoche(horaIni, horaFin)) {
                Swal.fire("Horario no permitido", "No se permiten horarios que crucen medianoche", "error");
                return;
            }

            // KM = Guardar el bloque activo para usar al aplicar
            bloqueAUsarParaCopiar = bloque;
            abrirModalCopia();
        }
        document.dispatchEvent(new Event("registro-disponibilidad-lista"));
    });

    // KM = Boton de guardar todo
    document.getElementById("btn-guardar-todo").addEventListener("click", () => {
        const bloques = document.querySelectorAll(".bloque-horario");
        const resultado = [];
        const erroresPorDia = {};
        let hayError = false;

        bloques.forEach(bloque => {
            const dia = bloque.dataset.dia;
            const horaIni = bloque.querySelector(".hora-inicio").value;
            const horaFin = bloque.querySelector(".hora-fin").value;
            const error = bloque.querySelector(".error-hora");
            const contenedor = bloque;
            error.textContent = "";

            if (!horaIni || !horaFin) {
                error.textContent = obtenerMensajeError("vacio");
                agitarBloque(contenedor);
                hayError = true;
                return;
            }

            if (horaFin <= horaIni) {
                error.textContent = obtenerMensajeError("invalido");
                agitarBloque(contenedor);
                hayError = true;
                return;
            }

            if (!validarDuracion(horaIni, horaFin)) {
                error.textContent = obtenerMensajeError("duracion");
                agitarBloque(contenedor);
                hayError = true;
                return;
            }

            if (cruzaMedianoche(horaIni, horaFin)) {
                error.textContent = obtenerMensajeError("medianoche");
                agitarBloque(contenedor);
                hayError = true;
                return;
            }

            if (!erroresPorDia[dia]) erroresPorDia[dia] = [];
            erroresPorDia[dia].push({ horaIni, horaFin, error });

            resultado.push({ dia, horaIni, horaFin });
        });

        // KM = Validacionion paso de dias
        for (const dia in erroresPorDia) {
            const bloques = erroresPorDia[dia];
            for (let i = 0; i < bloques.length; i++) {
                for (let j = i + 1; j < bloques.length; j++) {
                    if (traslape(bloques[i], bloques[j])) {
                        bloques[i].error.textContent = obtenerMensajeError("traslape");
                        bloques[j].error.textContent = obtenerMensajeError("traslape");
                        hayError = true;
                    }
                }
            }
        }

        if (hayError || resultado.length === 0) {
            if (!hayError) Swal.fire("Atención", "Debes ingresar al menos un horario válido.", "warning");
            return;
        }

        const docApoDisp = document.getElementById("docApo")?.value || '';

        fetch("/Proyecto_real_v1/Proyecto_real/src/disponibilidad/registrar_plantilla.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ docApoDisp, disponibilidad: resultado })
        })
            .then(res => res.json())
            .then(data => {
                if (data.exito) {
                    Swal.fire("¡Guardado!", data.mensaje, "success").then(() => location.reload());
                } else {
                    Swal.fire("Error", data.mensaje || "No se pudo guardar", "error");
                }
            })
            .catch(err => {
                console.error("Error:", err);
                Swal.fire("Error", "Ocurrió un problema inesperado", "error");
            });
    });
}

// Función auxiliar: duración válida
function validarDuracion(horaIni, horaFin) {
    const [h1, m1] = horaIni.split(":").map(Number);
    const [h2, m2] = horaFin.split(":").map(Number);
    const minutos = (h2 * 60 + m2) - (h1 * 60 + m1);
    return minutos >= 120 && minutos <= 720;
}

// Función auxiliar: cruza medianoche
function cruzaMedianoche(horaIni, horaFin) {
    return horaFin <= horaIni;
}

// Función auxiliar: traslape de horarios
function traslape(b1, b2) {
    return b1.horaIni < b2.horaFin && b2.horaIni < b1.horaFin;
}
// KM = Funcion para mostrar mensajes de error
function obtenerMensajeError(tipo) {
    switch (tipo) {
        case "vacio":
            return "⏳ Por favor selecciona una hora de inicio y fin.";
        case "invalido":
            return "⚠️ La hora de fin debe ser mayor que la de inicio.";
        case "duracion":
            return "⏱️ La duración debe estar entre 2 y 12 horas.";
        case "medianoche":
            return "🌙 No se permiten horarios que crucen medianoche.";
        case "traslape":
            return "🚫 Este horario se cruza con otro. Por favor ajústalo.";
        default:
            return "⚠️ Error desconocido.";
    }
}

// KM = Función para abrir el modal de copiar horario
function abrirModalCopia() {
    const horaIni = bloqueAUsarParaCopiar?.querySelector(".hora-inicio")?.value || "";
    const horaFin = bloqueAUsarParaCopiar?.querySelector(".hora-fin")?.value || "";
    const diaActual = bloqueAUsarParaCopiar?.dataset.dia || "";

    const modal = document.getElementById("modal-copiar-horario");
    const checkboxes = modal.querySelector(".dias-checkboxes");
    checkboxes.innerHTML = ""; // KM = Limpiar

    const diasSemana = ["lunes", "martes", "miércoles", "jueves", "viernes", "sábado", "domingo"];

    diasSemana.forEach(dia => {
        if (dia !== diaActual) { // KM = Excluir dia actual
            const id = `copiar-${dia}`;
            const label = document.createElement("label");
            label.innerHTML = `
        <input type="checkbox" id="${id}" name="dias" value="${dia}">
        ${dia.charAt(0).toUpperCase() + dia.slice(1)}
      `;
            checkboxes.appendChild(label);
        }
    });

    // KM = Mostrar el modal
    modal.style.display = "flex";

    // KM = Cerrar el modal de copiar horario por dia
    const btnCancelar = document.getElementById("cancelar-copia");
    if (btnCancelar) {
        btnCancelar.onclick = () => {
            modal.style.display = "none";
            checkboxes.innerHTML = "";
        };
    }
};

document.addEventListener("registro-disponibilidad-lista", () => {
    const formCopiar = document.getElementById("form-copiar-dias");
    const modal = document.getElementById("modal-copiar-horario");
    const btnCancelar = document.getElementById("cancelar-copia");
    const btnAplicar = document.querySelector(".btn-aplicar");

    // KM = Cancelar copia
    if (btnCancelar) {
        btnCancelar.onclick = () => {
            modal.style.display = "none";
            bloqueAUsarParaCopiar = null;
        };
    }

    // KM = Enviar formulario de copia
    if (btnAplicar) {
        btnAplicar.addEventListener("click", (e) => {
            e.preventDefault();

            if (!bloqueAUsarParaCopiar) return;

            const horaIni = bloqueAUsarParaCopiar.querySelector(".hora-inicio").value;
            const horaFin = bloqueAUsarParaCopiar.querySelector(".hora-fin").value;

            const seleccionados = [...formCopiar.querySelectorAll("input[name='dias']:checked")].map(cb => cb.value);

            if (seleccionados.length === 0) {
                Swal.fire("Atención", "Selecciona al menos un día", "warning");
                return;
            }

            let copiados = 0;
            const errores = [];

            seleccionados.forEach(dia => {
                const contenedor = document.getElementById(`rangos-${dia}`);
                const bloquesDia = [...contenedor.querySelectorAll(".bloque-horario")];

                const yaExiste = bloquesDia.some(b => {
                    const ini = normalizarHora(b.querySelector(".hora-inicio").value);
                    const fin = normalizarHora(b.querySelector(".hora-fin").value);
                    return ini && fin && ini === normalizarHora(horaIni) && fin === normalizarHora(horaFin);
                });

                if (yaExiste) {
                    errores.push(`${dia}: ya tiene un bloque igual`);
                    return;
                }

                if (bloquesDia.length >= 4) {
                    errores.push(`⚠️ ${dia}: ya tiene 4 bloques`);
                    return;
                }

                let conflicto = false;

                bloquesDia.forEach(b => {
                    const ini = b.querySelector(".hora-inicio").value;
                    const fin = b.querySelector(".hora-fin").value;

                    if (horaIni < fin && horaFin > ini) {
                        conflicto = true;
                        errores.push(`❌ ${dia}: se traslapa con ${ini} - ${fin}`);
                    }
                });

                if (conflicto) return;

                const nuevoBloque = document.createElement("div");
                nuevoBloque.className = "bloque-horario";
                nuevoBloque.dataset.dia = dia;
                nuevoBloque.innerHTML = `
        <div class="input-horarios">
            <input type="time" class="hora-inicio" value="${horaIni}" required> a 
            <input type="time" class="hora-fin" value="${horaFin}" required>
            <span class="error-hora"></span>
        </div>
        <div class="botones-bloque">
            <button class="btn-eliminar" title="Eliminar">❌ Eliminar</button>
            <button class="btn-copiar" title="Duplicar">📋 Copiar</button>
        </div>
    `;
                contenedor.appendChild(nuevoBloque);
                asignarEventosABloque(nuevoBloque);

                copiados++;
            });

            // KM = Ocultar modal después de aplicar
            if (copiados > 0) {
                modal.style.display = "none";
                bloqueAUsarParaCopiar = null;
            }

            // Mensajes según resultado
            if (copiados > 0 && errores.length > 0) {
                Swal.fire({
                    title: "Se copiaron algunos días",
                    html: errores.join("<br>"),
                    icon: "warning"
                });
            } else if (copiados > 0) {
                Swal.fire({
                    title: "Bloques copiados exitosamente",
                    icon: "success"
                });
            } else {
                Swal.fire({
                    title: "Ningún día se copió",
                    html: errores.join("<br>"),
                    icon: "error"
                });
            }
        });
    }
});

function agitarBloque(elemento) {
    elemento.classList.add("shake");
    setTimeout(() => {
        elemento.classList.remove("shake");
    }, 400);
}

function normalizarHora(hora) {
    return hora?.trim()?.padStart(5, "0");
}

// KM = Función para asignar eventos a un bloque recién creado
function asignarEventosABloque(bloque) {
    const inputInicio = bloque.querySelector(".hora-inicio");
    const inputFin = bloque.querySelector(".hora-fin");
    const error = bloque.querySelector(".error-hora");

    const limpiarErrorSiEsValido = () => {
        const horaIni = inputInicio.value;
        const horaFin = inputFin.value;

        if (
            horaIni &&
            horaFin &&
            horaFin > horaIni &&
            validarDuracion(horaIni, horaFin) &&
            !cruzaMedianoche(horaIni, horaFin)
        ) {
            error.textContent = "";
            bloque.classList.remove("shake");
        }
    };

    inputInicio.addEventListener("input", limpiarErrorSiEsValido);
    inputFin.addEventListener("input", limpiarErrorSiEsValido);

    bloque.querySelector(".btn-eliminar").addEventListener("click", () => {
        bloque.remove();
    });

    bloque.querySelector(".btn-copiar").addEventListener("click", () => {
        const horaIni = inputInicio.value;
        const horaFin = inputFin.value;

        if (!horaIni || !horaFin) {
            Swal.fire("Atención", "Completa las horas antes de copiar.", "warning");
            return;
        }

        if (horaFin <= horaIni) {
            Swal.fire("Horario inválido", "La hora de fin debe ser mayor que la de inicio", "error");
            return;
        }

        if (!validarDuracion(horaIni, horaFin)) {
            Swal.fire("Duración inválida", "Debe durar entre 2 y 12 horas", "warning");
            return;
        }

        if (cruzaMedianoche(horaIni, horaFin)) {
            Swal.fire("Horario no permitido", "No se permiten horarios que crucen medianoche", "error");
            return;
        }

        bloqueAUsarParaCopiar = bloque;
        abrirModalCopia();
    });
}

////////////////////////////////////////
// KM = Edicion de disponibilidad
////////////////////////////////////////

// KM = Mostrar el calendario de disponibilidad mensual
function cargarOpcionesFecha() {
    const selectMes = document.getElementById("select-mes");
    const selectAnio = document.getElementById("select-anio");

    if (!selectMes || !selectAnio) return;

    const meses = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];
    const ahora = new Date();
    const anioActual = ahora.getFullYear();
    const mesActual = ahora.getMonth();

    for (let m = 0; m < 12; m++) {
        const option = document.createElement("option");
        option.value = m + 1;
        option.textContent = meses[m];
        if (m === mesActual) option.selected = true;
        selectMes.appendChild(option);
    }

    for (let y = anioActual; y <= anioActual + 5; y++) {
        const option = document.createElement("option");
        option.value = y;
        option.textContent = y;
        if (y === anioActual) option.selected = true;
        selectAnio.appendChild(option);
    }
}

function mostrarCalendario() {
    const docApo = document.getElementById("docApo").value;
    const mes = +document.getElementById("select-mes").value;
    const anio = +document.getElementById("select-anio").value;

    const contenedor = document.getElementById("contenedor-calendario");
    contenedor.innerHTML = "";

    // KM = Calcular estructura del mes
    const fechaInicio = new Date(anio, mes - 1, 1);
    const diasMes = new Date(anio, mes, 0).getDate();
    const primerDiaSem = fechaInicio.getDay();   // 0-Dom
    const TOTAL = 42;
    const diasPrev = primerDiaSem;
    const diasPost = TOTAL - diasPrev - diasMes;
    const ultimoPrevDia = new Date(anio, mes - 1, 0).getDate();

    // KM = Consultar bloques por fetch
    fetch(`/Proyecto_real_v1/Proyecto_real/src/disponibilidad/consultar_calendario.php?docApo=${docApo}&mes=${mes}&anio=${anio}`)
        .then(res => res.json())
        .then(data => {

            // KM = Mes anterior
            for (let i = diasPrev - 1; i >= 0; i--) {
                const diaPrev = ultimoPrevDia - i;
                const mesPrev = mes === 1 ? 12 : mes - 1;
                const anioPrev = mes === 1 ? anio - 1 : anio;
                pintarCelda(diaPrev, mesPrev, anioPrev, "dia-otro-mes", data, contenedor);
            }

            // KM = Mes actual
            for (let dia = 1; dia <= diasMes; dia++) {
                pintarCelda(dia, mes, anio, "mes-actual", data, contenedor);
            }

            // KM = Mes siguiente
            for (let i = 1; i <= diasPost; i++) {
                const mesNext = mes === 12 ? 1 : mes + 1;
                const anioNext = mes === 12 ? anio + 1 : anio;
                pintarCelda(i, mesNext, anioNext, "dia-otro-mes", data, contenedor);
            }
        })
        .catch(err => console.error("Error:", err));
}


// KM =  Iniciar edicion 
function iniciarEdicionDisponibilidad() {

}

// KM = Animacion o logica para el tabControl (Gracias Erica! jaja por la expo)
function initDisponibilidadTabs() {
    const tabButtons = document.querySelectorAll('.tabs .tab');
    const tabPanels = document.querySelectorAll('.tab-panel');

    if (!tabButtons.length) return;

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {

            tabButtons.forEach(b => b.classList.remove('active'));
            tabPanels.forEach(p => p.classList.remove('active'));

            btn.classList.add('active');
            const panel = document.querySelector('#panel-' + btn.dataset.tab);
            if (panel) panel.classList.add('active');
        }, { once: false });
    });
}

// KM = Funcion para pintar las celdas
function pintarCelda(diaReal, mesReal, anioReal, claseExtra, data, contenedor) {
    const celda = document.createElement("div");
    celda.className = "dia " + (claseExtra || "");

    const num = document.createElement("div");
    num.className = "numero";
    num.textContent = diaReal;
    celda.appendChild(num);

    const fechaKey =
        `${anioReal}-${String(mesReal).padStart(2, '0')}-${String(diaReal).padStart(2, '0')}`;

    if (data[fechaKey]) {
        const origen = data[fechaKey][0].origen;

        const hoy = new Date();
        const todayRaw = new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate());
        const loopDate = new Date(anioReal, mesReal - 1, diaReal);
        const esPasado = loopDate < todayRaw;

        let claseBarra;
        if (origen === 'real') {
            claseBarra = esPasado ? 'disponible-past' : 'disponible-real';
        } else {
            claseBarra = esPasado ? 'disponible-past' : 'disponible-fut';
        }

        const barra = document.createElement('div');
        barra.className = claseBarra;
        celda.appendChild(barra);

        barra.addEventListener('click', () =>
            abrirModalHorarios(fechaKey, data, origen)
        );
    }

    if (claseExtra === 'mes-actual') {
        const hoy = new Date();
        const today = new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate());
        const loop = new Date(anioReal, mesReal - 1, diaReal);
        if (loop < today) celda.classList.add('dia-pasado');
    }

    contenedor.appendChild(celda);
}

// KM = Mostrar el modal de horarios, dias pasados y siguientes
function abrirModalHorarios(fechaKey, data, origen) {
    let titulo;

    if (origen === 'template') {
        const [y, m, d] = fechaKey.split('-').map(Number);
        const nombreDia = ['Domingo', 'Lunes', 'Martes', 'Miércoles',
            'Jueves', 'Viernes', 'Sábado']
        [new Date(y, m - 1, d).getDay()];
        titulo = `Horarios ${nombreDia}`;
    } else {
        titulo = `Horarios ${fechaKey}`;
    }

    Swal.fire({
        title: titulo,
        html: data[fechaKey].map(b => `🕒 ${b.hora}`).join('<br>'),
        icon: 'info',
        confirmButtonText: 'Cerrar'
    });
}