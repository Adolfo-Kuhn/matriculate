<form class='new-form' name="new-asignatura" action="insertar.php" method="POST">
	<div class="form-group col-5">
		<label for="nombre">Nombre</label>
		<input type="text" class="form-control" id="nombre" name="nombre">
	</div>
	<div class="form-group col-5">
		<label for="siglas">Siglas</label>
		<input type="text" class="form-control" id="siglas" name="siglas">
	</div>
	<?php echo obtenerSelect('ciclo', 'Ciclo Formativo', SQL_CREAR_ASIGNATURA_1) ?>
	<div class="form-group col-5">
		<label for="curso">Curso</label>
		<select class='custom-select' id='curso' name='curso'>
			<option value='1'>Seleccione curso...</option>
			<option value='1'>Primero</option>
			<option value='2'>Segundo</option>
		</select>
	</div>
	<?php echo obtenerSelect('profesor', 'Profesor', SQL_CREAR_ASIGNATURA_2) ?>
	<div class='form-group col-5'>
		<label for="horas">Horas</label>
		<input type="number" class="form-control" id='horas' name='horas'>
	</div>
	<div class='form-group col-5'>
		<label for="anio">Año</label>
		<input type="number" class="form-control" id='anio' name='anio' min='2020'>
	</div>
	<div class='form-group col-5'>
		<label for="url">URL Logotipo</label>
		<input type="url" class="form-control" id='url' name='url'>
	</div>
	<div class='form-group col-5 btn-submit'>
		<input type="submit" class="btn btn-info" value='Aceptar'>
	</div>
	<input type='hidden' name='formulario' value='Asignatura'>
</form>