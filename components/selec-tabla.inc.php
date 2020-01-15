<select class="custom-select" id="selec-tabla" name='tabla'>
	<option>Seleccione registro...</option>
	<?php if (strcmp($tabla, 'Alumno') === 0): ?>
		<option value='Alumno' selected>Alumnos</option>
	<?php else: ?>
		<option value='Alumno'>Alumnos</option>
	<?php endif; ?>
	<?php if (strcmp($tabla, 'Asignatura') === 0): ?>
		<option value='Asignatura' selected>Asignaturas</option>
	<?php else: ?>
		<option value='Asignatura'>Asignaturas</option>
	<?php endif; ?>
	<?php if (strcmp($tabla, 'Ciclo') === 0): ?>
		<option value='Ciclo' selected>Ciclos</option>
	<?php else: ?>
		<option value='Ciclo'>Ciclos</option>
	<?php endif; ?>
	<?php if (strcmp($tabla, 'Matrícula') === 0): ?>
		<option value='Matrícula' selected>Matrículas</option>
	<?php else: ?>
		<option value='Matrícula'>Matrículas</option>
	<?php endif; ?>
	<?php if (strcmp($tabla, 'Profesor') === 0): ?>
		<option value='Profesor' selected>Profesores</option>
	<?php else: ?>
		<option value='Profesor'>Profesores</option>
	<?php endif; ?>
</select>
