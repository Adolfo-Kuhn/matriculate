<?php
require_once './data/funciones.inc.php';
require_once './data/data.php';
require_once './data/data_sql.php';

$tabla = null;
try {
	session_start();

	if (!isset($_SESSION['user'])) {
		header('location: index.php');
	}
	if (isset($_REQUEST['alumno_mat'])) {
		if (strcmp($_POST['alumno_mat'], '-') !== 0) {
			$alumno = $_POST['alumno_mat'];
			$tabla = 'Matrícula';
			$selector = obtenerLabeledSelect('alumno_mat', 'Alumno', SQL_BORRAR_MATRICULA_1, $alumno);
			if (isset($_REQUEST['asignatura'])) {
				if (strcmp($_POST['asignatura'], '-') !== 0) {
					$asignaturaTxt = $_POST['asignaturaTxt'];
				} else {
					$respuesta = getAlertElement('Es obligatorio indicar la asignatura matriculada.', 'warning');
				}				
			}
		}
	}
	if (isset($_REQUEST['cancel_mod_mat'])) {
		header('location: ./modificar.php');
	}
	if (isset($_REQUEST['mod_mat_info'])) {
		$dniAntiguo = $_POST['idAlumno'];
		$dniNuevo = $_POST['alumno'];
		$asignaturaAntigua = $_POST['idAsignatura'];
		$asignaturaNueva = $_POST['asignatura'];
		$repetidor = $_POST['repetidor'];
		$nota = $_POST['nota'];
		$conexion = conectarBD();
		$consulta = $conexion->stmt_init();
		$consulta->prepare(SQL_MODMATRICULA_2);
		$consulta->bind_param('siidsi', $dniNuevo, $asignaturaNueva, $repetidor, $nota, $dniAntiguo, $asignaturaAntigua);
		if ($consulta->execute()) {
			$msg = '<strong>¡Modificación exitosa!</strong> el registro se actualizó correctamente';
			$respuesta = getAlertElement($msg, 'success');
			$selector = obtenerLabeledSelect('alumno_mat', 'Alumno', SQL_MODMATRICULA_ALUMNOS);
			$tabla = 'Matrícula';
		} else {
			$msg = "<strong>¡Modificación fallida!</strong> {$conexion->errno} - {$conexion->error}";
			$respuesta = getAlertElement($msg, 'danger');
		}
	}
} catch (Exception $e) {
	$exc = getAlertElement($e, 'warning');
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
					<span class='header__titulo-id__logged'><?= $_SESSION['user'] ?></span>
				</div>
			</header>
			<main class='main'>
				<?php
				if (strcmp($_SESSION['user'], 'admin') === 0) {
					include_once './components/nav_admin.inc.php';
				} else {
					include_once './components/nav_user.inc.php';
				}
				?>
				<article class='screen'>
					<header class='form-header'>
						<ol class="breadcrumb">
							<li class="breadcrumb-item">Modificar</li>
							<?php if (isset($alumnoTxt)): ?>
								<li class="breadcrumb-item">Matrícula</li>
								<?php if (isset($asignaturaTxt)): ?>
									<li class="breadcrumb-item"><?= $alumnoTxt ?></li>
									<li class="breadcrumb-item active"><?= $asignaturaTxt ?></li>
								<?php else: ?>
									<li class="breadcrumb-item active"><?= $_POST['alumnoTxt'] ?></li>
								<?php endif; ?>
							<?php else: ?>
								<li class="breadcrumb-item active">Matrícula</li>
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
								<form class='selector-box mb-3' name="<?= 'edit-' . $tabla ?>" action="modificar.php" method='POST'>
									<?= $selector; ?>
									<button type="submit" class="btn btn-success">Modificar</button>
								</form>
							<?php endif; ?>
						</aside>
					</header>
					<main class='container form-showcase'>
						<?php
						if (isset($respuesta)) {
							echo $respuesta;
							echo '</main></article></main></div></body></html>';
							die();
						}
						?>
						<form class='new-form' name="edit-matricula" action="edit_matricula.php" method="POST">
							<?php
							if (isset($alumno)) {
								echo obtenerSelect('alumno', 'Alumno', SQL_BORRAR_MATRICULA_1, $alumno);
								echo "<input type='hidden' name=idAlumno value='$alumno'>";
								echo "<input type='hidden' name=idAsignatura value='{$_POST['asignatura']}'>";
							} else {
								echo obtenerSelect('alumno', 'Alumno', SQL_BORRAR_MATRICULA_1);
							}
							?>
							<div class="form-group col-5">
								<label for='repetidor'>Repetidor</label>
								<select class='custom-select' id='repetidor' name='repetidor'>
									<option value='0'>No</option>
									<option value='1'>Si</option>
								</select>
							</div>
							<?php echo obtenerSelect('asignatura', 'Asignatura', SQL_CREAR_MATRICULA, $_POST['asignatura']) ?>
							<div class='form-group col-5'>
								<label for="nota">Nota Final</label>
								<input type="number" class="form-control" id='nota' name='nota' min='0' max='10' step='0.01'>
							</div>
							<div class='form-group col-5 btn-submit'>
								<input type="submit" class="btn btn-info" name="mod_mat_info" value='Aceptar'>
							</div>
							<div class='form-group col-5 btn-submit'>
								<input type="submit" class="btn btn-danger" name="cancel_mod_mat" value='Cancelar'>
							</div>
						</form>
					</main>
				</article>
			</main>
        </div>
    </body>
</html>