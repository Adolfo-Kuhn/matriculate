<form class='new-form' name="new-profesor" action="insertar.php" method="POST">
	<div class="form-group col-5">
		<label for="nombre">Nombre</label>
		<input type="text" class="form-control" id="nombre" name="nombre" required>
	</div>
	<div class="form-group col-5">
		<label for="apellido">Apellidos</label>
		<input type="text" class="form-control" id="apellido" name="apellido" required>
	</div>
	<div class="form-group col-5">
		<label for="dni">D.N.I.</label>
		<input type="text" class="form-control" id="dni" name="dni" required>
	</div>
	<div class="form-group col-5">
		<label for="fecha">Fecha de nacimiento</label>
		<input type="date" class="form-control" id="fecha" name="fecha">
	</div>
	<div class="form-group col-5">
		<label for="sexo">Sexo</label>
		<select class="custom-select" id="sexo" name='sexo'>
			<option value='h'>Hombre</option>
			<option value='m'>Mujer</option>
		</select>
	</div>
	<div class='form-group col-5 btn-submit'>
		<input type="submit" class="btn btn-info" value='Insertar'>
	</div>
	<input type='hidden' name='formulario' value='Profesor'>
</form>