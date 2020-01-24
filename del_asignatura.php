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
		$tabla = 'Asignatura';
	}
	if (isset($_REQUEST['asignatura'])) {
		if (strcmp($_POST['asignatura'], '-') !== 0) {
			$asignatura = $_POST['asignatura'];
			$conexion = conectarBD();
			$consulta = $conexion->stmt_init();
			$consulta->prepare(SQL_DELASIGNATURA_1);
			$consulta->bind_param('i', $asignatura);
			$consulta->execute();
			$consulta->bind_result($nombre, $siglas, $horas, $profe, $ciclo, $curso, $anio, $url);
			$consulta->fetch();
			$asignaturaTxt = ucwords(strtolower($nombre));
			$consulta->prepare(SQL_NOMBRE_CICLO);
			$consulta->bind_param('i', $ciclo);
			$consulta->execute();
			$consulta->bind_result($nombreCiclo);
			$consulta->fetch();
			$consulta->prepare(SQL_NOMBRE_PROFESOR);
			$consulta->bind_param('s', $profe);
			$consulta->execute();
			$consulta->bind_result($nombreProfe);
			$consulta->fetch();
			$consulta->close();
			$conexion->close();
		} else {
			$asignatura = '-';
			$respuesta = getAlertElement('Es necesario que seleccione un ciclo para eliminar.', 'warning');
		}
	}
	if (isset($_REQUEST['formulario'])) {
		$asignatura = $_POST['asignatura'];
		$conexion = conectarBD();
		$consulta = $conexion->stmt_init();
		$consulta->prepare(SQL_DELASIGNATURA_2);
		$consulta->bind_param('i', $asignatura);
		// se ejecuta la sentencia SQL y se muestra mensaje de éxito o fallo
		if ($consulta->execute()) {
			$msg = '<strong>¡Eliminación exitosa!</strong> el registro se borró correctamente';
			$respuesta = getAlertElement($msg, 'success');
		} else {
			$msg = "<strong>¡Eliminación fallida!</strong> {$conexion->errno} - {$conexion->error}";
			$respuesta = getAlertElement($msg, 'warning');
		}
		$consulta->close();
		$conexion->close();
	}
	$selector = obtenerLabeledSelect('asignatura', 'Asignatura', SQL_LEER_ASIGNATURA_1, $asignatura);
	$action = './del_asignatura.php';
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
							<li class="breadcrumb-item">Eliminar</li>
							<?php if (isset($asignaturaTxt)): ?>
								<?php if (strcasecmp($asignaturaTxt, '-') !== 0): ?>
									<li class="breadcrumb-item">Asignatura</li>
									<li class="breadcrumb-item active"><?= $asignaturaTxt ?></li>
								<?php endif; ?>
							<?php else: ?>
								<li class="breadcrumb-item active">Asignatura</li>
							<?php endif; ?>
						</ol>
						<aside class='container'>
							<form class='selector-box mb-3' action='eliminar.php' method='POST'>
								<div class="input-group col-5">
									<?php include_once './components/selec-tabla.inc.php'; ?>
									<div class="input-group-append">
										<label class="input-group-text" for="selec-tabla">Eliminación</label>
									</div>
								</div>
								<button type="submit" class="btn btn-success">Seleccionar</button>
							</form>
							<?php if (isset($selector)): ?>
								<form class='selector-box mb-3' name="del_asignatura" action="del_asignatura.php" method='POST'>
									<?= $selector; ?>
									<?php if (isset($asignaturaTxt)): ?>
										<input type='hidden' name='asignaturaTxt' value='<?= $asignaturaTxt ?>'>
									<?php else: ?>
										<input type='hidden' name='asignaturaTxt'>
									<?php endif; ?>
									<button type="submit" class="btn btn-success">Seleccionar</button>
									<input type='hidden' name='tabla' value="Asignatura">
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
						<form class='new-form' name="del-asignatura" action="del_asignatura.php" method="POST">
							<div class="form-group col-5">
								<label for="nombre">Nombre</label>
								<input type="text" class="form-control" id="nombre" value="<?= $asignaturaTxt ?>" readonly>
								<input type="hidden" name="asignatura" value="<?= $asignatura ?>">
							</div>
							<div class="form-group col-5">
								<label for="siglas">Siglas</label>
								<input type="text" class="form-control" id="siglas" value="<?= $siglas ?>" readonly>
							</div>
							<div class="form-group col-5">
								<label for="ciclo">Ciclo Formativo</label>
								<input type="text" class="form-control" id="ciclo" value="<?= ucwords(strtolower($nombreCiclo)) ?>" readonly>
							</div>
							<div class="form-group col-5">
								<label for="curso">Curso</label>
								<input type="text" class="form-control" id="curso" value="<?= $curso ?>" readonly>
							</div>
							<div class="form-group col-5">
								<label for="profesor">Profesor</label>
								<input type="text" class="form-control" id="profesor"  value='<?= $nombreProfe ?>' readonly>
							</div>
							<div class="form-group col-5">
								<label for="horas">Horas</label>
								<input type="text" class="form-control" id="horas" value='<?= $horas ?>' readonly>
							</div>
							<div class="form-group col-5">
								<label for="anio">Año</label>
								<input type="text" class="form-control" id="anio" value='<?= $anio ?>' readonly>
							</div>
							<div class="form-group col-5">
								<label for="url">URL Logotipo</label>
								<input type="text" class="form-control" id="url" value='<?= $url ?>' readonly>
							</div>
							<div class='form-group col-5 btn-submit'>
								<input type="submit" class="btn btn-danger" value='Eliminar'>
							</div>
							<input type='hidden' name='formulario' value='Ciclo'>
						</form>
					</main>
				</article>
			</main>
        </div>
    </body>
</html>