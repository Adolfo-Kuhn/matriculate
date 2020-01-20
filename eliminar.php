<?php
require_once './data/funciones.inc.php';
require_once './data/data.php';
require_once './data/data_sql.php';
try {
	session_start();

	$tabla = null;
	// si no hay sesión iniciada se redirecciona al inicio
	if (!isset($_SESSION['user'])) {
		header('location: index.php');
	} else {
		$user = $_SESSION['user'];
	}
	// si se ha enviado el valor de la tabla sobre la que actuar
	if (isset($_REQUEST['tabla'])) {
		$tabla = $_POST['tabla'];
		// en función de la tabla seleccionada
		switch (strtolower($tabla)) {
			// se obtiene el desplegable con loa alumnos
			case 'alumno':
				$selector = obtenerLabeledSelect('alumno', 'Alumno', SQL_BORRAR_MATRICULA_1);
				$action = './del_alumno.php';
				break;
			case 'asignatura':
				$selector = obtenerLabeledSelect('asignatura', 'Asignatura', SQL_LEER_ASIGNATURA_1);
				$action = './del_asignatura.php';
				break;
			case 'ciclo':
				$selector = obtenerLabeledSelect('ciclo', 'Ciclo', SQL_CREAR_ASIGNATURA_1);
				$action = './del_ciclo.php';
				break;
			case 'matrícula':
				$selector = obtenerLabeledSelect('alumno_mat', 'Alumno', SQL_MODMATRICULA_ALUMNOS);
				$action = 'eliminar.php';
				break;
			case 'profesor':
				$selector = obtenerLabeledSelect('profesor', 'Profesor', SQL_CREAR_ASIGNATURA_2);
				$action = './del_profesor.php';
				break;
		}
		$hiddenTabla = strtolower($tabla);
	}
	if (isset($_REQUEST['alumno_mat'])) {
		if (strcmp($_POST['alumno_mat'], '-') !== 0) {
			$alumno = $_POST['alumno_mat'];
			if (isset($_POST['alumnoTxt'])) {
				$alumnoTxt = $_POST['alumnoTxt'];
			}
			$tabla = 'Matrícula';
			$selector = obtenerLabeledSelect('alumno_mat', 'Alumno', SQL_BORRAR_MATRICULA_1, $_POST['alumno_mat']);
			$sql = SQL_MODMATRICULA . "'{$_POST['alumno_mat']}'";
			$selector2 = obtenerLabeledSelect('asignatura', 'Asignatura', $sql);
			$action = './del_matricula.php';
		}
	}
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
				if (strcmp($_SESSION['user'], 'admin') === 0) {
					include_once './components/nav_admin.inc.php';
				} else {
					include_once './components/nav_user.inc.php';
				}
				?>
				<article class='screen'>
					<header class='form-header'>
						<ol class="breadcrumb">
							<?php if (isset($tabla)): ?>
								<li class="breadcrumb-item">Eliminar</li>
								<?php if (strcasecmp($tabla, 'seleccione registro...') !== 0): ?>
									<?php if (isset($alumnoTxt)): ?>
										<li class="breadcrumb-item"><?= $tabla ?></li>
										<li class="breadcrumb-item active"><?= $alumnoTxt ?></li>
									<?php else: ?>
										<li class="breadcrumb-item active"><?= $tabla ?></li>
									<?php endif; ?>
								<?php endif; ?>
							<?php else: ?>
								<li class="breadcrumb-item active">Eliminar</li>
							<?php endif; ?>
						</ol>
						<aside class='container'>
							<form class='selector-box mb-3' name='consulta' action='eliminar.php' method='POST'>
								<div class="input-group col-5">
									<?php include_once './components/selec-tabla.inc.php'; ?>
									<div class="input-group-append">
										<label class="input-group-text" for="selec-tabla">Eliminación</label>
									</div>
								</div>
								<button type="submit" class="btn btn-success">Seleccionar</button>
							</form>
							<?php if (isset($selector)): ?>
								<form class='selector-box mb-3' name="<?= 'del-' . $tabla ?>" action="<?= $action ?>" method='POST'>
									<?php if (strcasecmp($tabla, 'matrícula') === 0): ?>
										<?php if (isset($alumnoTxt)): ?>
											<div class="input-group col-5">
												<select class="custom-select" id="selec-tabla1" disabled>
													<option><?= $alumnoTxt ?></option>
												</select>
												<div class="input-group-append">
													<label class="input-group-text" for="selec-tabla1">Alumno</label>
												</div>
											</div>
											<input type='hidden' name='alumno_mat' value='<?= $alumno ?>'>
											<input type='hidden' name='alumnoTxt' value='<?= $alumnoTxt ?>'>
										<?php else: ?>
											<?php echo $selector ?>
											<input type='hidden' name='alumnoTxt'>
										<?php endif; ?>
										<?php if (isset($selector2)): ?>
											<?php echo $selector2 ?>
											<input type='hidden' name='asignaturaTxt'>
										<?php endif; ?>
									<?php else: ?>
										<?php echo $selector ?>
										<input type='hidden' name='<?= $hiddenTabla ?>Txt'>
									<?php endif; ?>
									<button type="submit" class="btn btn-success">Eliminar</button>
								</form>
							<?php endif; ?>
						</aside>
					</header>
				</article>
			</main>
        </div>
		<script src="./js/editar.js"></script>
    </body>
</html>