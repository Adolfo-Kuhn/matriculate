<form class='new-form' name="new-ciclo" action="insertar.php" method="POST">
	<div class="form-group col-5">
		<label for="nombre">Nombre</label>
		<input type="text" class="form-control" id="nombre" name="nombre" required>
	</div>
	<div class="form-group col-5">
		<label for="siglas">Siglas</label>
		<input type="text" class="form-control" id="siglas" name="siglas" required>
	</div>
	<div class='form-group col-5'>
		<label for="url">URL Logotipo</label>
		<input type="url" class="form-control" id="url" name='url' required>
	</div>
	<div class='form-group col-5 btn-submit'>
		<input type="submit" class="btn btn-info" value='Insertar'>
	</div>
	<input type='hidden' name='formulario' value='Ciclo'>
</form>