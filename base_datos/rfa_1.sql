-- Base de datos RFA
CREATE DATABASE RFA_1;
USE RFA_1;

-- KM = Quiero ser clara ;-; Salvenme, llevo modificando esto mas de lo que me gustaria adminita, perdoname instructor Carlos :(

-- KM = Tabla de usuarios normales: Personas que solicitan el asesoramiento :)
CREATE TABLE usuario ( 
    corUsu VARCHAR(80) NOT NULL, -- Correo del usuario: Identificador unico del usuario
    nomUsu VARCHAR(100) NOT NULL, -- Nombre o alias del usuario
    fecNacUsu DATE NOT NULL, -- Fecha de nacimiento del usuario
    genUsu VARCHAR(10) NOT NULL, -- Genero del usuario (M/F/OTRO)
    psw VARCHAR(250) NOT NULL, -- Contraseña del usuario (encriptada)
    fotoPerfil VARCHAR(100) NOT NULL DEFAULT '../iconos/',-- Imagen de perfil
    fechaReg TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha y hora de registro
    PRIMARY KEY (corUsu) -- Llave primaria: Correo del usuario 
);

-- KM = Tabla para recuperacion de contraseña usuarios
CREATE TABLE recuperacionUsu (
    idRerUsu INT AUTO_INCREMENT, -- Codigo para la tabla de recuperacion
    corRecUsu VARCHAR(100) NOT NULL, -- Correo del usuario
    codRecUsu VARCHAR(6) NOT NULL, -- Codigo de recuperacion de la contraseña
    fecEnvRecUsu TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha de envio del codigo
    usaRecUsu BOOLEAN DEFAULT FALSE, -- Si esta usado el codigo
    PRIMARY KEY (IdRerUsu), -- Llave primaria: Codigo de la tabla recuperacion
    FOREIGN KEY (corRecUsu) REFERENCES usuario (corUsu) -- Llave foranea: Correo del usuario de la tabla usuario
);

-- KM = Tabla de roles: Define los distintos roles para las personas de apoyo (ejemplo: Asesor, Psicologo, Enfermero, Asesor legal)
CREATE TABLE rol (
    codRol INT NOT NULL, -- Codigo unico del rol
    nomRol VARCHAR(50) NOT NULL, -- Nombre del rol (ej. Asesor, Psicologo)
    PRIMARY KEY (codRol) -- Llave primaria: Codigo del rol
);

-- KM = Inserción de roles (Ya establecidos)
INSERT INTO rol (codRol, nomRol) VALUES
(1, 'Administrador'),
(2, 'Psicólogo'),
(3, 'Asesor'),
(4, 'Enfermero'),
(5, 'Consultor Legal');

-- KM = Tabla de ciudades: Para almacenar las ciudades donde se encuentran las personas de apoyo
CREATE TABLE ciudad (
    codCiu INT NOT NULL, -- Codigo de la ciudad
    nomCiu VARCHAR(100) NOT NULL, -- Nombre de la ciudad
    PRIMARY KEY (codCiu) -- Llave primaria: Codigo de la ciudad
);

-- KM = Inserción de ciudades
INSERT INTO ciudad (codCiu, nomCiu) VALUES
(1, 'Bucaramanga'),
(2, 'Floridablanca'),
(3, 'Girón'),
(4, 'Piedecuesta'),
(5, 'Barrancabermeja'),
(6, 'Cúcuta'),
(7, 'Bogotá'),
(8, 'Medellín');

-- KM = Table de eventos
CREATE TABLE evento (
    codEve INT NOT NULL AUTO_INCREMENT, -- Codigo unico del evento
    nomEve VARCHAR(100) NOT NULL, -- Nombre del evento
    desEve TEXT NOT NULL, -- Descripcion del evento
    imgEve VARCHAR(255), -- Ruta o URL de la imagen
    fecEve DATE NOT NULL, -- Fecha del evento
    horEve TIME NOT NULL, -- Hora del evento
    ubiEve ENUM('Presencial', 'Virtual', 'Otro') , -- Tipo ("Presencial", "Virtual", Proximamente mas? No esoty segura)
    enlaceEve VARCHAR(255), -- Enlace si el evento es virtual
    estadoEve BOOLEAN NOT NULL DEFAULT TRUE, -- TRUE = Activo, FALSE = Inactivo
    fecCreEve DATETIME DEFAULT CURRENT_TIMESTAMP, -- Fecha de creacion del evento
    PRIMARY KEY (codEve) -- Llave primaria: Codigo del evento
);

-- KM = Tabla de registros del sistema
CREATE TABLE logSistema (
    idLog INT NOT NULL AUTO_INCREMENT, -- Codigo del sistema
    tipoAccion VARCHAR(50) NOT NULL, -- Tipo de accion
    tablaAfectada VARCHAR(50) NOT NULL, -- Nombre de la tabla modificada
    idRegistroAfectado VARCHAR(50), -- Codigo del registro modificado
    descripcion TEXT NOT NULL, -- Descripcion del cambio
    usuarioResponsable VARCHAR(100) NOT NULL,-- Usuario responsable (Correo)
    fechaAccion DATETIME NOT NULL, -- Fecha y hora del cambio
    PRIMARY KEY (idLog) -- Llave primaria: Codigo del sistema
);

-- KM = Tabla de personas de apoyo: Informacion sobre las personas que brindan apoyo a los usuarios
CREATE TABLE personaApoyo (
    docApo VARCHAR(11) NOT NULL, -- Documento de identidad de la persona de apoyo
    corApo VARCHAR(100) NOT NULL UNIQUE, -- Correo de la persona de apoyo
    nomApo VARCHAR(100) NOT NULL, -- Nombre de la persona de apoyo
    apeApo VARCHAR(100) NOT NULL, -- Apellido de la persona de apoyo
    fecNacApo DATE NOT NULL, -- Fecha de nacimiento de la persona de apoyo
    genApo VARCHAR(1) NOT NULL, -- Género (M/F/Otro)
    telApo VARCHAR(11) NOT NULL, -- Teléfono
    dirApo VARCHAR(30) NOT NULL, -- Dirección
    codCiuApo INT NOT NULL, -- Codigo de la cuidad
    codRolApo INT NOT NULL, -- Codigo unico del rol
    numTarProApo VARCHAR(11) NOT NULL, -- Tarjeta profesional
    espProApo VARCHAR(100) NOT NULL, -- Especialidad
    pswApo VARCHAR(250) NOT NULL, -- Contraseña (encriptada)
    estadoApo BOOLEAN NOT NULL DEFAULT TRUE, -- TRUE = Activo, FALSE = Inactivo
    fotoPerfil VARCHAR(100) NOT NULL DEFAULT '../iconos/', -- Imagen de perfil
    fechaReg TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha y hora de registro
    PRIMARY KEY (docApo), -- Llave primaria: Documento de identidad de la persona de apoyo
    FOREIGN KEY (codCiuApo) REFERENCES ciudad(codCiu), -- Llave foranea: Referencia a la ciudad de la tabla ciudad
    FOREIGN KEY (codRolApo) REFERENCES rol(codRol) -- Llave foranea: Referencia a la ciudad de la tabla rol
);

-- Inserta al administrador principal
INSERT INTO personaApoyo (
    docApo, corApo, nomApo, apeApo, fecNacApo, genApo, telApo, dirApo,
    codCiuApo, codRolApo, numTarProApo, espProApo, pswApo, estadoApo, fotoPerfil
) VALUES (
    '22222222222', 'kerendaniela201315@gmail.com', 'Keren', 'Infante', '2004-01-01', 'F',
    '3101234567', 'Calle 456', 1, 1, 'TP-KM002', 'Administradora',
    '$2y$12$w0UQYUn1.L3p6NUXkUL21u6iJ1XBLMX7P6FMeL9k5LtRQbXjlPQnK', TRUE, 'icono_default_mujer.png'
);

-- Tabla de recuperacion de persona de apoyo
CREATE TABLE recuperacionApo (
    idRecApo INT AUTO_INCREMENT, -- Codigo unico para la recuperacion de contraseña
    docRecApo VARCHAR(20) NOT NULL, -- Documento del personas de apoyo
    codRolRecApo INT NOT NULL, -- Codigo del rol del personal de apoyo
    codRecApo VARCHAR(6) NOT NULL, -- Codigo de recuperacion 
    fecEnvRecApo TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha ed envio del codigo 
    usaRecApo BOOLEAN DEFAULT FALSE, -- Si el codigo fue usado o no
    PRIMARY KEY (idRecApo), -- Llave primaria codigo de recuperacion
    FOREIGN KEY (docRecApo) REFERENCES personaApoyo(docApo), -- Llave foranea: Documento del personal de apoyo de la tabla PersonaApoyo
    FOREIGN KEY (codRolRecApo) REFERENCES personaApoyo(codRolApo) -- Llave foranea: Codigo del rol de la persona de apoyo de la tabla rol
);

-- KM = Tabla para llevar los cambios realizados por el usuario
CREATE TABLE historial_ediciones_usuario (
    idHistorial INT AUTO_INCREMENT, -- Codigo de la tabla
    corUsuEdi VARCHAR(80) NOT NULL, -- Correo del usuario que realizó el cambio
    campoEditado VARCHAR(50) NOT NULL, -- Campo que se editó
    valorAnterior TEXT, -- Valor anterior
    valorNuevo TEXT, -- Nuevo valor
    fechaCambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha del cambio
    PRIMARY KEY (idHistorial), -- Llave primaria
    FOREIGN KEY (corUsuEdi) REFERENCES usuario(corUsu) -- LLave foreana: Referencia al correo del usaurio que realiza la modificacion (Gimi, gimi, gimi))
);

-- KM = Tabla para registrar los cambios realizados por las personas de apoyo
CREATE TABLE historial_ediciones_apoyo (
    idHistorial INT AUTO_INCREMENT, -- Codigo del cambio
    docApoEdi VARCHAR(11) NOT NULL, -- Documento de la persona de apoyo que hizo el cambio
    campoEditado VARCHAR(50) NOT NULL, -- Campo editado
    valorAnterior TEXT, -- Valor anterior (El que fue editado)
    valorNuevo TEXT, -- Valor nuevo (Nuevo valor que fue editado)
    fechaCambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha de la edicion
    PRIMARY KEY (idHistorial), -- Llave primaria: Codigo del historial
    FOREIGN KEY (docApoEdi) REFERENCES personaApoyo(docApo) -- Llave foranea: Documento de la persona de apoyo de la tabla personaApoyo
);

-- KM = Tabla de seguimiento de persona apoyo (Asesor)
CREATE TABLE notaSeguimiento (
    idNota INT AUTO_INCREMENT, -- Codigo de la nota 
    docApoNota VARCHAR(20) NOT NULL, -- Documento del asesor que crea la nota
    corUsuNota VARCHAR(100) NOT NULL, -- Correo del usuario asociado a la nota
    contNota TEXT NOT NULL, -- Contenido de la nota
    colorNota VARCHAR(20) DEFAULT '#dedcdc', -- Color personalizado para las notas
    fechCrea TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha de creacion de la nota
    PRIMARY KEY (idNota), -- Llave primaria: Codigo de la nota
    FOREIGN KEY (docApoNota) REFERENCES personaApoyo(docApo), -- Llave foranea: Referencia al documento de la persona de apoyo (Tabla persona de apoyo)
    FOREIGN KEY (corUsuNota) REFERENCES usuario(corUsu) -- Llave foranea: Referencia a el correo del usaurio (Tabla de usaurio)
);

-- KM = Tabla de casos: Almacena los casos generados para un usuario en particular
CREATE TABLE caso (
    codCas INT NOT NULL, -- Codoigo unico del caso
    corUsuCas VARCHAR(100) NOT NULL, -- Correo del usuario asociado al caso
    desCas VARCHAR(255) NOT NULL, -- Descripcion del caso
    tesCas VARCHAR(255) NOT NULL, -- Testimonios relacionados con el caso
    recCas VARCHAR(255) NOT NULL, -- Recomendaciones sobre el caso
    fecCas DATE NOT NULL, -- Fecha en que se creo el caso
    estCas VARCHAR(30) NOT NULL, -- Estado del caso (abierto, cerrado, pendiente)
    PRIMARY KEY (codCas), -- Llave primaria: Codigo del caso
    FOREIGN KEY (corUsuCas) REFERENCES usuario(corUsu) -- Llave foranea: Referencia al correo del usuario
);

-- KM = Tabla solicitud de citas: Registra las solicitudes de citas realizadas por los usuarios
CREATE TABLE solicitudCita (
    codSolCit INT NOT NULL, -- Codigo unico de la solicitud de cita
    corUsuSolCit VARCHAR(100) NOT NULL, -- Correo del usuario que solicita la cita
    fecSolCit DATE NOT NULL, -- Fecha en que se solicita la cita
    motSolCit VARCHAR(255) NOT NULL, -- Motivo de la cita
    estSolCit VARCHAR(30) NOT NULL, -- Estado de la solicitud (pendiente, confirmada, etc.)
    PRIMARY KEY (codSolCit), -- Llave primaria: Codigo de la solicitud de cita
    FOREIGN KEY (corUsuSolCit) REFERENCES usuario(corUsu) -- Llave foranea: Referencia al correo del usuario
);

-- KM = Tabla de disponibilidad de horarios para personas de apoyo
CREATE TABLE disponibilidad (
    idDisp INT NOT NULL AUTO_INCREMENT, -- Codigo de la disponibilidad
    docApoDisp VARCHAR(11) NOT NULL,  -- Documento de la persona de apoyo
    fecha DATE NOT NULL, -- Fecha exacta: por ejemplo, 2025-06-12 (jueves)
    horaIni TIME NOT NULL, -- Hora de inicio 
    horaFin TIME NOT NULL, -- Hora de fin
    genDesTem BOOLEAN DEFAULT TRUE, -- Verificar que fue generado por la plantilla
    activo BOOLEAN NOT NULL DEFAULT TRUE, -- Estado activo de la persona de apoyo
    motDes TEXT NULL, -- Motivo por el cual no esta activo
    PRIMARY KEY (idDisp), -- Llave primaria: Codigo de la disponibilidad
    FOREIGN KEY (docApoDisp) REFERENCES personaApoyo(docApo) -- Llave foranea: Referencia al documento de la persona de apoyo
);

-- KM = Tabla para fechas especiales en disponibilidad
CREATE TABLE fechas_especiales (
    idEsp INT AUTO_INCREMENT, -- Codigo unico de la fecha especial
    docApoDisp VARCHAR(11) NOT NULL, -- Documento del asesor
    fecha DATE NOT NULL, -- Fecha unica especifica
    horaIni TIME NOT NULL, -- Hora de inicio especial
    horaFin TIME NOT NULL, -- Hora de fin especial
    motivo TEXT, -- Motivo o justificación del horario especial
    creEnDia TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha de creacion del registro
    PRIMARY KEY (idEsp), -- Llave primaria: Codigo unico del dia especial
    FOREIGN KEY (docApoDisp) REFERENCES personaApoyo(docApo) -- Llave foranea: Referencia a la tabla persona de apoyo por documento
);

-- KM = Tabla de registro cumplimiento
CREATE TABLE registroCumplimientoApo (
    idRegCum INT AUTO_INCREMENT, -- Codigo del registro de cumplimiento
    docApo VARCHAR(11) NOT NULL, -- Documento de la persona de apoyo
    fecha DATE NOT NULL, -- Dia de disponibilidad
    horaIni TIME NOT NULL, -- Hora de incio
    horaFin TIME NOT NULL, -- Hora de fin
    cumplio BOOLEAN DEFAULT FALSE, -- Campo para verificar si cumplio con si disponibilidad
    motivoIncum TEXT, -- Motivo por el cual incumplio con su disponibilidad
    just BOOLEAN DEFAULT FALSE, -- Si ya justifico
    fechaReg TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha del registro o inicio
    PRIMARY KEY (idRegCum), -- Llave primaria: Codigo de registro de cumplimiento
    FOREIGN KEY (docApo) REFERENCES personaApoyo(docApo) -- Lleva foranea: Referencia al documento de persona de apoyo de la tabla personaApoyo
);

-- KM = Plantilla semanal de disponibilidad recurrente
CREATE TABLE disponibilidad_template (
    idTemplate INT AUTO_INCREMENT, -- Codigo de la plantilla
    docApoDisp VARCHAR(11) NOT NULL, -- Documento del personal de apoyo
    diaSem ENUM('lunes','martes','miércoles','jueves','viernes','sábado','domingo') NOT NULL, -- Dia de la semana
    horaIni TIME NOT NULL, -- Hora de incio (Ingreso)
    horaFin TIME NOT NULL, -- Hora de fin (Salida)
    fecIni DATE NULL, -- Fecha desde la que se empieza a generar
    fecFin DATE NULL, -- Fecha opcional de corte
    obsDis TEXT NULL, -- Observacion sobre las fechas 
    activo BOOLEAN DEFAULT TRUE, -- Saber si un dia en especifico esta vigente
    estGen BOOLEAN DEFAULT TRUE, -- Estado general de la siponibilidad (Activo o no),
    motDis TEXT NULL, -- Motivo por el cual se quiere deshabilitar
    fecIniApli DATE NULL, -- Sabes si ya se aplico la plantilla o no
    bloque INT DEFAULT 1, -- Soportar multiples rangos de dia
    PRIMARY KEY (idTemplate), -- Llave primaria: Codigo de la plantilla semanal
    FOREIGN KEY (docApoDisp) REFERENCES personaApoyo(docApo) -- Lleva foranea: Referencia al documento de persona de apoyo de la tabla personaApoyo
);

-- KM = Tabla de login de usuario: Informacion de los logins realizados por los usuarios
CREATE TABLE loginUsuario (
    codLogUsu INT NOT NULL AUTO_INCREMENT, -- Codigo unico del login del usuario
    corUsuLogUsu VARCHAR(100) NOT NULL, -- Correo del usuario que se ha logueado
    fecLogUsu DATETIME NOT NULL, -- Fecha y hora del login
    PRIMARY KEY (codLogUsu), -- Llave primaria: Codigo del login
    FOREIGN KEY (corUsuLogUsu) REFERENCES usuario(corUsu) -- Llave foranea: Referencia al correo del usuario
);

-- Tabla de inicio sesion empleado: Guarda los registros de inicio de sesion de los empleados.
CREATE TABLE loginPersonaApoyo (
    codLogApo INT NOT NULL AUTO_INCREMENT, -- Codigo unico para cada inicio de sesion del empleado
    docApoLogApo VARCHAR(100) NOT NULL, -- Documento de identidad del empleado que inicia sesion
    fecLogApo DATETIME NOT NULL, -- Fecha y hora del inicio de sesion
    PRIMARY KEY (codLogApo), -- Llave primaria: Codigo de inicio de sesion
    FOREIGN KEY (docApoLogApo) REFERENCES personaApoyo(docApo) -- Llave foranea: Referencia al documento de la persona de apoyo (empleado)
);

-- KM = Tabla para el historial de cambios de estado de los usuarios
CREATE TABLE historialEstadoPersonaApoyo (
    idHistEstado INT NOT NULL AUTO_INCREMENT, -- ID unico para el historial de cambios
    docApo VARCHAR(11) NOT NULL,  -- Documento de la persona de apoyo
    estadoAnterior VARCHAR(10) NOT NULL,  -- Estado anterior (activo/inactivo)
    estadoNuevo VARCHAR(10) NOT NULL,  -- Estado actual (activo/inactivo)
    fechaCambio DATETIME NOT NULL, -- Fecha y hora en que se realizo el cambio
    motivoCambio VARCHAR(255) NOT NULL,  -- Motivo del cambio de estado
    PRIMARY KEY (idHistEstado),  -- Llave primaria: Codigo del historial
    FOREIGN KEY (docApo) REFERENCES personaApoyo(docApo)  -- Llave foranea: Referencia la persona de apoyo
);

-- KM = Tabla para llevar las ediciones realizadas a la disponibilidad
CREATE TABLE historialEdicionDisponibilidad (
    idHistDisp INT AUTO_INCREMENT, -- ID del historial
    idTemplate INT NOT NULL,  -- Código del template modificado
    docApoDisp VARCHAR(11) NOT NULL,  -- Documento de la persona de apoyo
    campoModificado VARCHAR(50) NOT NULL,  -- Qué se modificó (horaIni, horaFin, activo, etc.)
    valorAnterior TEXT,  -- Valor anterior
    valorNuevo TEXT,  -- Valor nuevo
    fechaCambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Cuándo se hizo el cambio
    motivoCambio TEXT,  -- Justificación del cambio (obligatorio si estGen = FALSE)
    PRIMARY KEY (idHistDisp),  -- Llave primaria: Codigo unico de la edicion en disponibilidad
    FOREIGN KEY (idTemplate) REFERENCES disponibilidad_template(idTemplate),  -- Llave foranea: Referencia al codigo unico de la plantilla disponibilidad
    FOREIGN KEY (docApoDisp) REFERENCES personaApoyo(docApo)  -- Llave foranea: Referencia a la tabla persona de apoyo por documento
);

-- KM = Tabla de primeras consultas: Almacena las consultas iniciales realizadas por los usuarios
CREATE TABLE primeraConsulta (
    codPriCon INT NOT NULL AUTO_INCREMENT, -- Codigo unico de la primera consulta
    docAsePriCon VARCHAR(11) NOT NULL, -- Documento del asesor que realizo la consulta
    corUsuPriCon VARCHAR(100) NOT NULL, -- Correo del usuario que realizo la consulta
    tipoAbusoPriCon VARCHAR(50), -- Tipo de abuso identificado (si aplica)
    motPriCon VARCHAR(100) NOT NULL, -- Motivo principal que expresa el usuario
    desPriCon TEXT NOT NULL, -- Descripcion detallada del caso
    recomendacionPriCon VARCHAR(255), -- Recomendacion del asesor (psicologa, juridica, ambos, etc.)
    redApoPriCon DATE, -- Fecha de la respuesta de seguimiento (si ya se remitio)
    fecPriCon DATE NOT NULL,-- Fecha de la primera consulta
    estadoPriCon VARCHAR(20) DEFAULT 'pendiente', -- Estado del proceso: 'pendiente', 'remitido', 'cerrado'
    PRIMARY KEY (codPriCon), -- Llave primaria: Codigo de la primera consulta
    FOREIGN KEY (corUsuPriCon) REFERENCES usuario(corUsu), -- Llave foranea: Referencia al correo del usuario
    FOREIGN KEY (docAsePriCon) REFERENCES personaApoyo(docApo) -- Llave foranea: Referencia al documento de la persona de apoyo
);

-- KM = Tabla de remisiones de la primera consulta (Esto es para que el asesor pueda conectar al usuario con las personas de apoyo :) )
CREATE TABLE remisionConsulta (
    codRem INT NOT NULL AUTO_INCREMENT, -- Codigo unico de la remision
    codPriConRem INT NOT NULL, -- Codigo de la primera consulta 
    docApoRem VARCHAR(11) NOT NULL, -- Documento de la persona de apoyo a la que se remite
    fecRem DATE NOT NULL, -- Fecha en la que se realiza la remision
    obsRem TEXT, -- Observaciones o razones de la remision
    estadoRem VARCHAR(20) DEFAULT 'pendiente', -- Estado de atencion: pendiente, aceptada, rechazada
    PRIMARY KEY (codRem), -- Llave primaria: Codigo de remision
    FOREIGN KEY (codPriConRem) REFERENCES primeraConsulta(codPriCon), -- Llave foranea: Referencia al codigo de la primera consulta
    FOREIGN KEY (docApoRem) REFERENCES personaApoyo(docApo) -- Llave foranea: Referencia al documento de asesor (Persona de apoyo)
);

-- KM = Tabla de reportes: Registra los reportes generados por las personas de apoyo
CREATE TABLE reporte (
    codRep INT NOT NULL, -- Codigo unico del reporte
    docApoRep VARCHAR(11) NOT NULL, -- Documento de la persona de apoyo que genero el reporte
    corUsuRep VARCHAR(100) NOT NULL, -- Correo del usuario que genero el reporte
    motRep VARCHAR(100) NOT NULL, -- Motivo del reporte
    desRep VARCHAR(255) NOT NULL, -- Descripcion del reporte
    fecRep DATE NOT NULL, -- Fecha del reporte
    PRIMARY KEY (codRep), -- Llave primaria: Codigo del reporte
    FOREIGN KEY (corUsuRep) REFERENCES usuario(corUsu), -- Llave foranea: Referencia al correo del usuario
    FOREIGN KEY (docApoRep) REFERENCES personaApoyo(docApo) -- Llave foranea: Referencia al documento de la persona de apoyo
);

-- KM = Tabla de citas: Registra las citas asignadas a los usuarios
CREATE TABLE cita (
    codCit INT NOT NULL, -- Codigo unico de la cita
    codSolCitCit INT NOT NULL, -- Codigo de la solicitud de cita (referencia a solicitudCita)
    corUsuCit VARCHAR(100) NOT NULL, -- Correo del usuario que tiene la cita
    docEmpCit VARCHAR(11) NOT NULL, -- Documento de la persona de apoyo asignada a la cita
    codRepCit INT NULL, -- Codigo del reporte asociado a la cita
    fecCit DATE NOT NULL, -- Fecha de la cita
    horCit TIME NOT NULL, -- Hora de la cita
    obsCit VARCHAR(255) NOT NULL, -- Observaciones sobre la cita
    PRIMARY KEY (codCit), -- Llave primaria: Codigo de la cita
    FOREIGN KEY (codSolCitCit) REFERENCES solicitudCita(codSolCit), -- Llave foranea: Referencia a la solicitud de cita
    FOREIGN KEY (corUsuCit) REFERENCES usuario(corUsu), -- Llave foranea: Referencia al correo del usuario
    FOREIGN KEY (docEmpCit) REFERENCES personaApoyo(docApo), -- Llave foranea: Referencia al documento de la persona de apoyo
    FOREIGN KEY (codRepCit) REFERENCES reporte(codRep) -- Llave foranea: Referencia al reporte
);

-- KM = Tabla historial de modificaciones: Historial de las modificaciones realizadas a casos
CREATE TABLE historialModificacionesCaso (
    codHisMod INT NOT NULL, -- Codigo unico de la modificacion
    codCasHisMod INT NOT NULL, -- Codigo del caso modificado
    docEmpHisMod VARCHAR(11) NOT NULL, -- Documento de la persona de apoyo que realizo la modificacion
    desHisMod VARCHAR(255) NOT NULL, -- Descripcion de la modificacion (incluyendo el motivo del cambio)
    estCasHisMod VARCHAR(30) NOT NULL, -- Estado del caso despues de la modificacion (abierto, cerrado, pendiente)
    fecHisMod DATETIME NOT NULL, -- Fecha y hora de la modificacion
    PRIMARY KEY (codHisMod), -- Llave primaria: Codigo de la modificacion
    FOREIGN KEY (codCasHisMod) REFERENCES caso(codCas), -- Llave foranea: Referencia al caso modificado
    FOREIGN KEY (docEmpHisMod) REFERENCES personaApoyo(docApo) -- Llave foranea: Referencia a la persona de apoyo que realizó la modificación
);

-- KM = Tabla de inscripciones a eventos
CREATE TABLE interesEvento (
    codIntEve INT NOT NULL AUTO_INCREMENT, -- Cogido de la inscripcion del ecento
    codEve INT NOT NULL, -- Evento al que se inscribio
    corUsu VARCHAR(100) NOT NULL, -- Usuario interesado
    fecRegistro DATETIME DEFAULT CURRENT_TIMESTAMP, -- Fecha del registro
    PRIMARY KEY (codIntEve), -- Llave primaria:  Codigo de la inscripcion
    FOREIGN KEY (codEve) REFERENCES evento(codEve), -- Llave foranea: Codigo del evento de la tabla evento
    FOREIGN KEY (corUsu) REFERENCES usuario(corUsu) -- Llave foranea: Correo del usuario de la tabla usuario
);

-- Tabla para la conexion del chat: Maneja las conexiones de chat entre usuarios y empleados.
CREATE TABLE conexion_chat (
    codConChat INT NOT NULL AUTO_INCREMENT, -- Codigo unico para cada conexion de chat (auto_increment)
    corUsuConChat VARCHAR(100) NOT NULL, -- Correo del usuario conectado al chat
    docApoConChat VARCHAR(11) NOT NULL, -- Documento del empleado conectado al chat
    estado BOOLEAN NOT NULL DEFAULT TRUE, -- Estado del chat ('activo' o 'cerrado')
    PRIMARY KEY (codConChat), -- Llave primaria: Codigo de conexion del chat
    FOREIGN KEY (corUsuConChat) REFERENCES usuario(corUsu), -- Llave foranea: Correo del usuario conectado
    FOREIGN KEY (docApoConChat) REFERENCES personaApoyo(docApo) -- Llave foranea: Documento del empleado conectado
);

-- KM = Historial de citas: Historial de todas las citas realizadas por los usuarios
CREATE TABLE historialCitas (
    codHisCit INT NOT NULL, -- Codigo unico del historial de citas
    codCitHisCit INT NOT NULL, -- Cudigo de la cita registrada
    corUsuHisCit VARCHAR(100) NOT NULL, -- Correo del usuario que asistiu a la cita
    docEmpHisCit VARCHAR(11) NOT NULL, -- Documento de la persona de apoyo que atendiu la cita
    fecHisCit DATE NOT NULL, -- Fecha en que se realizu la cita
    obsHisCit VARCHAR(255), -- Observaciones de la cita
    PRIMARY KEY (codHisCit), -- Llave primaria: Cudigo del historial de cita
    FOREIGN KEY (codCitHisCit) REFERENCES cita(codCit), -- Llave foranea: Referencia a la cita
    FOREIGN KEY (corUsuHisCit) REFERENCES usuario(corUsu), -- Llave foranea: Referencia al correo del usuario
    FOREIGN KEY (docEmpHisCit) REFERENCES personaApoyo(docApo) -- Llave foranea: Referencia a la persona de apoyo
);

-- Tabla para guardar los mensajes enviados: Registra los mensajes enviados entre empleados y usuarios.
CREATE TABLE mensaje (
    codMen INT NOT NULL AUTO_INCREMENT, -- Codigo unico del mensaje (auto_increment)
    codConChat INT NOT NULL, -- Codigo de la conexion chat
    docApoRemMen VARCHAR(11), -- Documento del empleado remitente del mensaje
    corUsuRemMen VARCHAR(100), -- Correo del usuario remitente del mensaje
    docApoDestMen VARCHAR(11), -- Documento del empleado destinatario del mensaje
    corUsuDestMen VARCHAR(100), -- Correo del usuario destinatario del mensaje
    mensajeMen TEXT NOT NULL, -- Contenido del mensaje
    fechaEnvioMen DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Fecha y hora del envio del mensaje (por defecto la fecha actual)
    PRIMARY KEY (codMen), -- Llave primaria: Codigo del mensaje
    FOREIGN KEY (codConChat)    REFERENCES conexion_chat(codConChat), -- Llave foranea: Codigo de la conexion del chat
    FOREIGN KEY (docApoRemMen) REFERENCES personaApoyo(docApo), -- Llave foranea: Documento del empleado remitente
    FOREIGN KEY (corUsuRemMen) REFERENCES usuario(corUsu), -- Llave foranea: Correo del usuario remitente
    FOREIGN KEY (docApoDestMen) REFERENCES personaApoyo(docApo), -- Llave foranea: Documento del empleado destinatario
    FOREIGN KEY (corUsuDestMen) REFERENCES usuario(corUsu) -- Llave foranea: Correo del usuario destinatario
);