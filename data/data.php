<?php
/* Este fichero incluye constantes con contenido de texto o HTML que se imprimirán sin
 * modificación programática alguna, por lo que se ha decidido extraerlos del fichero
 * en el que se utilizan. */

const CABECERAS_ALUMNO = ['DNI', 'Apellidos', 'Nombre', 'Fecha Nacimiento'];
const CABECERAS_ASIGNATURA = ['Asignatura', 'Ciclo', 'Curso', 'Profesor', 'Horas Semanales', 'Logo'];
const CABECERAS_CICLO = ['ID', 'Ciclo', 'Siglas', 'Logo'];
const CABECERAS_MATRICULA = ['Asignatura', 'Alumno', 'DNI', 'Repetidor'];

const FORM_CREAR_PERSONA = <<<'HTML'
<div class='input'>
    <label for='nombre'>Nombre</label><br />
    <input id='nombre' name='nombre' type='text' required />                        
</div>
<div class='input'>
    <label for='apellido'>Apellidos</label><br />
    <input id='apellido' name='apellido' type='text' required />                        
</div>
<div class='input'>
    <label for='dni'>DNI</label><br />
    <input id='dni' name='dni' type='text' pattern='^[0-9]{8,8}[A-Za-z]$' required /> 
</div>
<div class='input'>
    <label for='fecha'>Fecha de nacimiento</label><br />
    <input id='fecha' name='fecha' type='date' required />                        
</div>
<div class='input'>
    <label for='sexo'>Sexo</label><br />
    <select id='sexo' name='sexo'>
        <option value='H'>Hombre</option>
        <option value='M'>Mujer</option>
    </select>                        
</div>
<div class='input boton'>
    <input name='crear' type='submit' value='Crear' />                        
</div>
HTML;

const FORM_CREAR_ASIGNATURA_1 = <<<'HTML'
<div class='input'>
    <label for='nombre'>Nombre</label><br />
    <input id='nombre' name='nombre' type='text' required />                        
</div>
<div class='input'>
    <label for='siglas'>Siglas</label><br />
    <input id='siglas' name='siglas' type='text' required />                        
</div>
HTML;

const FORM_CREAR_ASIGNATURA_2 = <<<'HTML'
<div class='input'>
    <label for='curso'>Curso</label><br />
    <select id='curso' name='curso'>
        <option value='1'>Primero</option>
        <option value='2'>Segundo</option>
    </select>
</div>
HTML;

const FORM_CREAR_ASIGNATURA_3 = <<<'HTML'
<div class='input'>
    <label for='horas'>Horas Semanales</label><br />
    <input id='horas' name='horas' type='number' min=1 max=10 required />                        
</div>
<div class='input'>
    <label for='anio'>Año</label><br />
    <input id='anio' name='anio' type='number' min=2019 max=2025 required />                        
</div>
<div class='input'>
    <label for='url'>URL Logotipo</label><br />
    <input id='url' name='url' type='url' required /> 
</div>
<div class='input boton'>
    <input name='crear' type='submit' value='Crear' />                        
</div>
HTML;

const FORM_CREAR_CICLO =  <<<'HTML'
<div class='input'>
    <label for='nombre'>Nombre</label><br />
    <input id='nombre' name='nombre' type='text' required />                        
</div>
<div class='input'>
    <label for='siglas'>Siglas</label><br />
    <input id='siglas' name='siglas' type='text' required />                        
</div>
<div class='input'>
    <label for='url'>URL Logotipo</label><br />
    <input id='url' name='url' type='url' required /> 
</div>
<div class='input boton'>
    <input name='crear' type='submit' value='Crear' />                        
</div>
HTML;

const FORM_CREAR_MATRICULA_1 = <<<'HTML'
<div class='input'>
    <label for='repetidor'>Repetidor</label><br />
    <select id='repetidor' name='repetidor'>
        <option value=0>No</option>
        <option value=1>Si</option>
    </select>
</div>
HTML;

const FORM_CREAR_MATRICULA_2 = <<<'HTML'
<div class='input'>
    <label for='nota'>Nota Final</label><br />
    <input id='nota' name='nota' type='number' min=0 max=10 />                        
</div>
<div class='input boton'>
    <input name='crear' type='submit' value='Crear' />                        
</div>
HTML;

const BOTON_SELECCIONAR = <<<'HTML'
<div class='input boton'><input name='cambiar' type='submit' value='Seleccionar' /></div>
HTML;

const MSG_SAVE_BIEN = '<p class="aviso">Registro almacenado correctamente</p>';
const MSG_SAVE_MAL = '<p class="aviso">El registro no pudo almacenarse</p>';

const MSG_MOD_BIEN = '<p class="aviso">Registro modificado correctamente</p>';
const MSG_MOD_MAL = '<p class="aviso">El registro no pudo ser modificado</p>';

const MSG_DEL_BIEN = '<p class="aviso">Registro eliminado correctamente</p>';
const MSG_DEL_MAL = '<p class="aviso">El registro no pudo ser eliminado</p>';