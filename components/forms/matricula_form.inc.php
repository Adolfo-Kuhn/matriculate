<form class='new-form' name="new-matricula" action="insertar.php" method="POST">
	<?php echo obtenerSelect('alumno', 'Alumno', SQL_BORRAR_MATRICULA_1) ?>
	<div class="form-group col-5">
		<label for='repetidor'>Repetidor</label>
		<select class='custom-select' id='repetidor' name='repetidor'>
			<option value='1'>Seleccione opción...</option>
			<option value='1'>No</option>
			<option value='2'>Si</option>
		</select>
	</div>
	<?php echo obtenerSelect('asignatura', 'Asignatura', SQL_CREAR_MATRICULA) ?>
	<div class='form-group col-5'>
		<label for="nota">Nota Final</label>
		<input type="number" class="form-control" id='nota' name='nota' min='0' max='10' step='0.01'>
	</div>
	<div class='form-group col-5 btn-submit'>
		<input type="submit" class="btn btn-info" value='Insertar'>
	</div>
	<input type='hidden' name='formulario' value='Matrícula'>
</form>