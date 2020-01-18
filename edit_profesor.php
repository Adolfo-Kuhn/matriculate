<?php
require_once './data/funciones.inc.php';
require_once './data/data.php';
require_once './data/data_sql.php';

$tabla = null;
try {
	session_start();

	if (!isset($_SESSION['user'])) {
		header('location: index.php');
	} else {
		$user = $_SESSION['user'];
		$tabla = 'Profesor';
	}
	if (isset($_REQUEST['profesor'])) {
		if (strcmp($_POST['profesor'], '-') !== 0) {
			$profesor = $_POST['profesor'];
			$profesorTxt = $_POST['profesorTxt'];
			$conexion = conectarBD();
			$consulta = $conexion->stmt_init();
			$consulta->prepare(SQL_MODPROFESOR_1);
			$consulta->bind_param('s', $profesor);
			$consulta->execute();
			$consulta->bind_result($nombre, $apellido, $dni, $sexo, $fecha);
			$consulta->fetch();
			$consulta->close();
			$conexion->close();
		} else {
			$profesor = '-';
			$respuesta = getAlertElement('Es necesario que seleccione un/a profesor/a para editar.', 'warning');
		}
	}
	if (isset($_REQUEST['formulario'])) {
		$nombre = $_POST['nombre'];
		$apellido = $_POST['apellido'];
		$profesor = $_POST['dni'];
		$fecha = $_POST['fecha'];
		$sexo = $_POST['sexo'];
		$conexion = conectarBD();
		$consulta = $conexion->stmt_init();
		$consulta->prepare(SQL_MODPROFESOR_2);
		$consulta->bind_param('sssss', $nombre, $apellido, $sexo, $fecha, $profesor);
		// se ejecuta la sentencia SQL y se muestra mensaje de éxito o fallo
		if ($consulta->execute()) {
			$msg = '<strong>¡Modificación exitosa!</strong> el registro se actualizó correctamente';
			$respuesta = getAlertElement($msg, 'success');
		} else {
			$msg = "<strong>¡Modificación fallida!</strong> {$conexion->errno} - {$conexion->error}";
			$respuesta = getAlertElement($msg, 'warning');
		}
		$consulta->close();
		$conexion->close();
	}
	$tabla = 'Profesor';
	$selector = obtenerLabeledSelect('profesor', 'Profesor', SQL_CREAR_ASIGNATURA_2);
	$action = './edit_profesor.php';
} catch (Exception $e) {
	$exc = getAlertElement($e, 'danger');
}
?>
<!DOCTYPE html>
<html lang='es'>
	<?php include_once './components/html_head.inc.php'; ?>
    <body>
		<div class='wrapper'>
			<?php
			if (isset($exc)) {
				echo $exc;
			}
			include_once './components/logout-box.inc.php';
			?>
			<header class='header'>
				<div class='header__logo'>Matricúl<mark class='logo-end'>Ate</mark></div>
				<div class='header__titulo'>
					<span class='header__titulo-txt'>IES Linus Torvalds</span>
					<span class='header__titulo-id__logged'><?= $user ?></span>
				</div>
			</header>
			<main class='main'>
				<?php
				if (strcmp($user, 'admin') === 0) {
					include_once './components/nav_admin.inc.php';
				} else {
					include_once './components/nav_user.inc.php';
				}
				?>
				<article class='screen'>
					<header class='form-header'>
						<ol class="breadcrumb">
							<li class="breadcrumb-item">Modificar</li>
							<li class="breadcrumb-item">Alumno</li>
							<?php if (isset($profesorTxt)): ?>
								<li class="breadcrumb-item active"><?= $profesorTxt ?></li>
							<?php endif; ?>
						</ol>
						<aside class='container'>
							<form class='selector-box mb-3' name='edicion' action='modificar.php' method='POST'>
								<div class="input-group col-5">
									<?php include_once './components/selec-tabla.inc.php'; ?>
									<div class="input-group-append">
										<label class="input-group-text" for="selec-tabla">Edición</label>
									</div>
								</div>
								<input type='hidden' name='txtAsig' id='txtAsig'>
								<button type="submit" class="btn btn-success">Seleccionar</button>
							</form>
							<?php if (isset($selector)): ?>
								<form class='selector-box mb-3' name="edit-profesor" action="edit_profesor.php" method='POST'>
									<?= $selector; ?>
									<?php if (isset($profesorTxt)): ?>
										<input type='hidden' name='profesorTxt' value='<?= $profesorTxt ?>'>
									<?php else: ?>
										<input type='hidden' name='profesorTxt'>
									<?php endif; ?>
									<button type="submit" class="btn btn-success">Modificar</button>
									<input type='hidden' name='tabla' value="<?= $tabla ?>">
								</form>
							<?php endif; ?>
						</aside>
					</header>
					<main class='container form-showcase'>
						<?php
						if (isset($respuesta)) {
							echo $respuesta;
							echo '</main></article></main></div><script src="./js/editar.js"></script></body></html>';
							die();
						}
						?>
						<form class='new-form' name="mod-profesor" action="edit_profesor.php" method="POST">
							<div class="form-group col-5">
								<label for="nombre">Nombre</label>
								<input type="text" class="form-control" id="nombre" name="nombre" value="<?= $nombre ?>">
							</div>
							<div class="form-group col-5">
								<label for="apellido">Apellidos</label>
								<input type="text" class="form-control" id="apellido" name="apellido" value="<?= $apellido ?>">
							</div>
							<div class="form-group col-5">
								<label for="dni">D.N.I.</label>
								<input type="text" class="form-control" id="dni" name="dni" value="<?= $dni ?>">
							</div>
							<div class="form-group col-5">
								<label for="fecha">Fecha de nacimiento</label>
								<input type="date" class="form-control" id="fecha" name="fecha" value="<?= $fecha ?>">
							</div>
							<div class="form-group col-5">
								<label for="sexo">Sexo</label>
								<select class="custom-select" id="sexo" name='sexo'>
									<?php if (strcasecmp($sexo, 'H') === 0): ?>
										<option value='h' selected>Hombre</option>
										<option value='m'>Mujer</option>
									<?php else: ?>
										<option value='h'>Hombre</option>
										<option value='m' selected>Mujer</option>
									<?php endif; ?>
								</select>
							</div>
							<div class='form-group col-5 btn-submit'>
								<input type="submit" class="btn btn-info" value='Aceptar'>
							</div>
							<input type='hidden' name='formulario' value='<?= $profesor ?>'>
						</form>
					</main>
				</article>
			</main>
        </div>
		<script src="./js/editar.js"></script>
    </body>
</html>