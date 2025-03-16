create database RFA_1;
use RFA_1;


create table ciudad (
codCiu int not null,
nomCiu varchar(100) not null,
primary key (codCiu)
);

-- Tabla de roles -- 

create table rol (
    codRol int not null,
    nomRol varchar(50) not null,
    primary key (codRol)
);

-- Tabla de usuarios --

create table usuario (
corUsu varchar (100) not null,
nomUsu varchar (100) not null,
fecNacUsu date not null,
genUsu varchar (1) not null,
psw varchar (30) not null,
primary key (corUsu)
);

-- Tabla de empleados --

create table empleado (
docEmp varchar (11) not null,
corEmp varchar (100) not null,
nomEmp varchar (100) not null,
apeEmp varchar (100) not null,
fecNacEmp date not null,
genEmp varchar (1) not null,
telEmp varchar (11) not null,
dirEmp varchar (30) not null,
codCiuEmp int not null,
numTarProEmp varchar (11) not null,
espProEmp varchar (100) not null,
pswEmp varchar (30) not null,
primary key (docEmp),
foreign key (codCiuEmp) references ciudad(codCiu)
);

-- Tabla de empleado rol --

create table empleadoRol (
    codEmpRol int not null,
    docEmpRol varchar(11) not null,
    codRolEmp int not null,
    primary key (codEmpRol),
    foreign key (docEmpRol) references empleado(docEmp),
    foreign key (codRolEmp) references rol(codRol)
);

-- Tabla de reportes -- 

create table reporte (
codRep int not null,
docEmpRep varchar(11) not null,
corUsuRep varchar(100) not null,
motRep varchar (100) not null,
desRep varchar (255) not null,
fecRep date not null,
primary key (codRep),
foreign key (corUsuRep) references usuario(corUsu),
foreign key (docEmpRep) references empleado(docEmp)
);

-- Tabla de caso --

create table caso (
codCas int not null,
corUsuCas varchar (100) not null,
desCas varchar (255) not null,
tesCas varchar (255) not null,
recCas varchar (255) not null,
fecCas date not null,
estCas varchar (30) not null,
primary key (codCas),
foreign key (corUsuCas) references usuario(corUsu)
);

-- Tabla de historial --

create table historialModificaciones (
codHisMod int not null,
codRepHisMod int not null,
codCasHisMod int not null,
docEmpHisMod varchar(11) not null,
desHisMod varchar(255) not null,
fecHisMod datetime not null,
primary key (codHisMod),
foreign key (codRepHisMod) references reporte(codRep),
foreign key (codCasHisMod ) references caso(codCas),
foreign key (docEmpHisMod) references empleado(docEmp)
);

-- Tabla solicitar cita --

create table solicitudCita (
codSolCit int not null,
corUsuSolCit varchar(100) not null,
fecSolCit date not null,
motSolCit varchar(255) not null,
estSolCit varchar(30) not null,
primary key (codSolCit),
foreign key (corUsuSolCit) references usuario(corUsu)
);

-- Tabla de citas --

create table cita (
codCit int not null,
codSolCitCit int not null,
corUsuCit varchar (100) not null,
docEmpCit varchar (11) not null,
codRepCit int not null,
fecCit date not null,
horCit time not null,
obsCit varchar (255) not null,
primary key (codCit),
foreign key (codSolCitCit) references solicitudCita(codSolCit),
foreign key (corUsuCit) references usuario (corUsu),
foreign key (docEmpCit) references empleado (docEmp),
foreign key (codRepCit) references reporte (codRep)
);

-- Tabla historial citas -- 

create table historialCitas (
codHisCit int not null,
codCitHisCit int not null,
corUsuHisCit varchar(100) not null,
docEmpHisCit varchar(11) not null,
fecHisCit date not null,
obsHisCit varchar(255),
primary key (codHisCit),
foreign key (codCitHisCit) references cita(codCit),
foreign key (corUsuHisCit) references usuario(corUsu),
foreign key (docEmpHisCit) references empleado(docEmp)
);

-- Tabla de inicio sesion usuario --

create table loginUsuario (
codLogUsu int not null,
corUsuLogUsu varchar(100) not null,
passLogUsu varchar (30) not null,
fecLogUsu datetime not null,
primary key (codLogUsu),
foreign key (corUsuLogUsu) references usuario(corUsu)
);

-- Tabla de inicio sesion empleado --

create table loginEmpleado (
codLogEmp int not null,
docEmpLogEmp varchar(100) not null,
passLogEmp varchar (30) not null,
fecLogEmp datetime not null,
primary key (codLogEmp),
foreign key (docEmpLogEmp) references empleado(docEmp)
);