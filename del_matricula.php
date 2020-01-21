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
		$tabla = 'Matrícula';
	}
	if (isset($_REQUEST['alumno_mat'])) {
		if (strcasecmp($_REQUEST['alumno_mat'], '-') !== 0) {
			if (isset($_REQUEST['asignatura'])) {
				if (strcasecmp($_REQUEST['asignatura'], '-') !== 0) {
					$alumno = $_REQUEST['alumno_mat'];
					$alumnoTxt = $_REQUEST['alumnoTxt'];
					$asignatura = $_REQUEST['asignatura'];
					$asignaturaTxt = $_REQUEST['asignaturaTxt'];
					$conexion = conectarBD();
					$consulta = $conexion->stmt_init();
					$consulta->prepare(SQL_MODMATRICULA_1);
					$consulta->bind_param('si', $alumno, $asignatura);
					$consulta->execute();
					$consulta->bind_result($repetidor, $notaFinal);
					$consulta->fetch();
					$consulta->close();
					$conexion->close();
				} else {
					$asignatura = '-';
					$respuesta = getAlertElement('Es necesario que seleccione la matrícula de alguna asignatura.', 'warning');
				}
			}
		} else {
			$alumno = '-';
			$respuesta = getAlertElement('Es necesario que seleccione la matrícula de algún alumno.', 'warning');
		}
	}
	if (isset($_REQUEST['formulario'])) {
		$alumno = $_POST['alumno'];
		$asignatura = $_POST['asignatura'];
		$conexion = conectarBD();
		$consulta = $conexion->stmt_init();
		$consulta->prepare(SQL_DELMATRICULA);
		$consulta->bind_param('si', $alumno, $asignatura);
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
	$selector = obtenerLabeledSelect('alumno_mat', 'Alumno', SQL_MODMATRICULA_ALUMNOS);
	$action = 'eliminar.php';
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
							<?php if (isset($alumnoTxt)): ?>
								<li class="breadcrumb-item">Matrícula</li>
								<?php if (isset($asignaturaTxt)): ?>
									<li class="breadcrumb-item"><?= $alumnoTxt ?></li>
									<li class="breadcrumb-item active"><?= $asignaturaTxt ?></li>
								<?php else: ?>
									<li class="breadcrumb-item active"><?= $alumnoTxt ?></li>
								<?php endif; ?>
							<?php else: ?>
								<li class="breadcrumb-item active">Matrícula</li>
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
								<form class='selector-box mb-3' name="del_matricula" action="eliminar.php" method='POST'>
									<?= $selector; ?>
									<?php if (isset($alumnoTxt)): ?>
										<input type='hidden' name='alumnoTxt' value='<?= $alumnoTxt ?>'>
									<?php else: ?>
										<input type='hidden' name='alumnoTxt'>
									<?php endif; ?>
									<button type="submit" class="btn btn-success">Eliminar</button>
									<input type='hidden' name='tabla' value="Matrícula">
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
						<form class='new-form' name="new-matricula" action="del_matricula.php" method="POST">
							<div class="form-group col-5">
								<label for="alumno">Alumno</label>
								<input type="text" class="form-control" name="alumnoTxt" value="<?= $alumnoTxt ?>" readonly>
								<input type="hidden" name="alumno" value="<?= $alumno ?>">
							</div>
							<div class="form-group col-5">
								<label for='repetidor'>Repetidor</label>
								<?php if ($repetidor === 0): ?>
									<input type="text" id="repetidor" class="form-control" value="No" readonly>
								<?php else: ?>
									<input type="text" id="repetidor" class="form-control" value="Si" readonly>
								<?php endif; ?>
							</div>
							<div class='form-group col-5'>
								<label for="asignatura">Asignaturas</label>
								<input type="text" class="form-control" id="asignatura" name="asignaturaTxt" value="<?= $asignaturaTxt ?>" readonly>
								<input type="hidden" name="asignatura" value="<?= $asignatura ?>">
							</div>
							<div class='form-group col-5'>
								<label for="nota">Nota Final</label>
								<input type="number" class="form-control" id='nota' name='nota' value="<?= $notaFinal ?>" readonly>
							</div>
							<div class='form-group col-5 btn-submit'>
								<input type="submit" class="btn btn-info" value='Aceptar'>
							</div>
							<input type='hidden' name='formulario' value='Matrícula'>
						</form>
					</main>
				</article>
			</main>
        </div>
    </body>
</html>
