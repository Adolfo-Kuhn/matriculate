<?php
/* Este fichero incluye constantes con sentencias SQL que se utilizan a lo largo del código.
 * La segunda palabra en el identificador de la constante representa el fichero en el que se
 * utiliza, aunque en algunos casos estas constantes se pueden usar en mśa de un fichero */

const SQL_LEER_ASIGNATURA_1 = 'select idAsignatura, nombre from asignatura order by 2';
const SQL_LEER_ALUMNO = 'select dni, apellidos, nombre, fecha_nacimiento from alumno';
const SQL_LEER_ASIGNATURA_2 = <<<'SQL'
select asignatura.nombre, ciclo.siglas, asignatura.curso,
concat(profesor.nombre, ' ', profesor.apellidos), horasSemana, asignatura.urlLogotipo
from asignatura inner join profesor on dniProfesor = dni
inner join ciclo on asignatura.idCiclo = ciclo.idCiclo
SQL;
const SQL_LEER_CICLO = 'select idCiclo, nombre, siglas, urlLogotipo from ciclo';
const SQL_NOMBRE_CICLO = 'select nombre from ciclo where idCiclo = ?';
const SQL_LEER_PROFESOR = 'select dni, apellidos, nombre, fecha_nacimiento from profesor';
const SQL_NOMBRE_PROFESOR = "select concat(apellidos, ', ', nombre) from profesor where dni = ?";
const SQL_LEER_MATRICULA = <<<'SQL'
select asignatura.nombre, concat(alumno.nombre, ' ', alumno.apellidos), dniAlumno, repetidor
from asignatura natural join matricula inner join alumno on dniAlumno = dni where idAsignatura =
SQL;

const SQL_CREAR_ASIGNATURA_1 = "select idCiclo, nombre from ciclo";
const SQL_CREAR_ASIGNATURA_2 = "select dni, concat(apellidos, ', ', nombre) from profesor";
const SQL_CREAR_MATRICULA = "select idAsignatura, nombre from asignatura";
const SQL_CREAR_ALUMNO_INSERT = "insert into alumno values (?, ?, ?, ?, ?)";
const SQL_CREAR_ASIGNATURA_INSERT = "insert into asignatura values (?, ?, ?, ?, ?, ?, ?, ?, ?)";
const SQL_CREAR_CICLO_INSERT = "insert into ciclo values (?, ?, ?, ?)";
const SQL_CREAR_MATRICULA_INSERT = "insert into matricula values (?, ?, ?, ?)";
const SQL_CREAR_PROFESOR_INSERT = "insert into profesor values (?, ?, ?, ?, ?)";

const SQL_CAMBIAR_ASIGNATURA = "select idAsignatura, nombre from asignatura";
const SQL_CAMBIAR_MATRICULA = <<<'SQL'
select dniAlumno, idAsignatura, concat(alumno.nombre, ' ', apellidos, ' (', dni, ')'), asignatura.nombre
from matricula natural join asignatura inner join alumno on dniAlumno = dni order by 1
SQL;

const SQL_MODALUMNO_1 = <<<'SQL'
select nombre, apellidos, dni, sexo, fecha_nacimiento
from alumno where dni = ?
SQL;
const SQL_MODALUMNO_2 = <<<'SQL'
update alumno set nombre = ?, apellidos = ?, sexo = ?, fecha_nacimiento = ? where dni = ?
SQL;

const SQL_MODASIGNATURA_1 = <<<'SQL'
select nombre, siglas, horasSemana, dniProfesor, idCiclo, curso, anho, urlLogotipo 
from asignatura where idAsignatura = ?
SQL;
const SQL_MODASIGNATURA_2 = <<<'SQL'
update asignatura set nombre = ?, siglas = ?, horasSemana = ?,
dniProfesor = ?, idCiclo = ?, curso = ?, anho = ?, urlLogotipo = ? where idAsignatura = ?
SQL;

const SQL_MODCICLO_1 = "select nombre, siglas, urlLogotipo from ciclo where idCiclo = ?";
const SQL_MODCICLO_2 = "update ciclo set nombre = ?, siglas = ?, urlLogotipo = ? where idCiclo = ?";

const SQL_MODMATRICULA_ALUMNOS = "select dni, concat(apellidos, ', ', nombre) from alumno inner join matricula on dni = dniAlumno";
const SQL_MODMATRICULA = "select idAsignatura, asignatura.nombre from matricula natural join asignatura where dniAlumno = ";
const SQL_MODMATRICULA_1 = <<<'SQL'
select repetidor, notaFinal from matricula where dniAlumno = ? and idAsignatura = ?
SQL;
const SQL_MODMATRICULA_2 = <<<'SQL'
update matricula set dniAlumno = ?, idAsignatura = ?, repetidor = ?, notaFinal = ? 
where dniAlumno = ? and idAsignatura = ?
SQL;

const SQL_MODPROFESOR_1 = <<<'SQL'
select nombre, apellidos, dni, sexo, fecha_nacimiento from profesor where dni = ?
SQL;
const SQL_MODPROFESOR_2 = <<<'SQL'
update profesor set nombre = ?, apellidos = ?, sexo = ?, fecha_nacimiento = ? where dni = ?
SQL;

const SQL_BORRAR_MATRICULA_1 = "select dni, concat(nombre, ' ', apellidos) from alumno";
const SQL_BORRAR_ASIGNATURA = 'select idAsignatura, nombre from asignatura';
const SQL_BORRAR_MATRICULA_2 = <<<'SQL'
select dniAlumno, idAsignatura, concat(alumno.nombre, ' ', apellidos, ' (', dni, ')'), 
asignatura.nombre from matricula natural join asignatura
inner join alumno on dniAlumno = dni order by 1
SQL;
const SQL_DELALUMNO_1 = "select nombre, apellidos, sexo, dni, fecha_nacimiento from alumno where dni= ";
const SQL_DELALUMNO_2 = "delete from alumno where dni = ?";

const SQL_DELASIGNATURA_1 = <<<'SQL'
select nombre, siglas, horasSemana, dniProfesor, idCiclo, curso,
anho, urlLogotipo from asignatura where idAsignatura = ?
SQL;
const SQL_DELASIGNATURA_2 = "delete from asignatura where idAsignatura = ?";

const SQL_DELCICLO_1 = "select nombre, siglas, urlLogotipo from ciclo where idCiclo = ?";
const SQL_DELCICLO_2 = "delete from ciclo where idCiclo = ?";

const SQL_DELMATRICULA = "delete from matricula where dniAlumno = ? and idAsignatura = ?";

const SQL_DELMATRICULA_1 = <<<'SQL'
select concat(alumno.nombre, ' ', apellidos) as 'alumno',
asignatura.nombre as asignatura, repetidor, notaFinal from matricula
natural join asignatura inner join alumno on dniAlumno = dni where dniAlumno=
SQL;

const SQL_DELMATRICULA_2 = " and matricula.idAsignatura=";
const SQL_DELMATRICULA_3 = "delete from matricula where dniAlumno=";
const SQL_DELMATRICULA_4 = " and idAsignatura=";

const SQL_DELPROFESOR_1 = <<<'SQL'
select nombre, apellidos, sexo, dni, fecha_nacimiento from profesor where dni=
SQL;
const SQL_DELPROFESOR_2 = "delete from profesor where dni=";