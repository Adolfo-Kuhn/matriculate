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
			$asignaturaTxt = $_POST['asignaturaTxt'];
			$conexion = conectarBD();
			$consulta = $conexion->stmt_init();
			$consulta->prepare(SQL_MODASIGNATURA_1);
			$consulta->bind_param('s', $asignatura);
			$consulta->execute();
			$consulta->bind_result($nombre, $siglas, $horas, $dni, $ciclo, $curso, $anio, $url);
			$consulta->fetch();
			$datos = ['nombre' => $nombre,
				'siglas' => $siglas,
				'horas' => $horas,
				'dni' => $dni,
				'ciclo' => $ciclo,
				'curso' => $curso,
				'anio' => $anio,
				'url' => $url];
			$consulta->close();
			$conexion->close();
		} else {
			$ciclo = $dni = '-';
			$respuesta = getAlertElement('Es necesario que seleccione una asignatura para editar.', 'warning');
		}
	}
	if (isset($_REQUEST['formulario'])) {
		$nombre = $_POST['nombre'];
		$siglas = $_POST['siglas'];
		$horas = $_POST['horas'];
		$dni = $_POST['profesor'];
		$ciclo = $_POST['ciclo'];
		$curso = $_POST['curso'];
		$anio = $_POST['anio'];
		$url = $_POST['url'];
		$id = $_POST['formulario'];
		$conexion = conectarBD();
		$consulta = $conexion->stmt_init();
		$consulta->prepare(SQL_MODASIGNATURA_2);
		$consulta->bind_param('ssisiiisi', $nombre, $siglas, $horas, $dni, $ciclo, $curso, $anio, $url, $id);
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
	$tabla = 'Asignatura';
	$selector = obtenerLabeledSelect('asignatura', 'Asignatura', SQL_LEER_ASIGNATURA_1, $asignatura);
	$selCiclo = obtenerSelect('ciclo', 'Ciclo Formativo', SQL_CREAR_ASIGNATURA_1, $ciclo);
	$selProfe = obtenerSelect('profesor', 'Profesor', SQL_CREAR_ASIGNATURA_2, $dni);
	$action = './edit_asignatura.php';
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
								<form class='selector-box mb-3' name="<?= 'edit-' . $tabla ?>" action="edit_asignatura.php" method='POST'>
									<?= $selector; ?>
									<?php if (isset($asignaturaTxt)): ?>
										<input type='hidden' name='asignaturaTxt' value='<?= $asignaturaTxt ?>'>
									<?php else: ?>
										<input type='hidden' name='asignaturaTxt'>
									<?php endif; ?>
									<button type="submit" class="btn btn-success">Seleccionar</button>
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
						<form class='new-form' name="edit-asignatura" action="edit_asignatura.php" method="POST">
							<div class="form-group col-5">
								<label for="nombre">Nombre</label>
								<input type="text" class="form-control" id="nombre" name="nombre" value='<?= ucwords(strtolower($datos['nombre'])) ?>'>
							</div>
							<div class="form-group col-5">
								<label for="siglas">Siglas</label>
								<input type="text" class="form-control" id="siglas" name="siglas" value='<?= $datos['siglas'] ?>'>
							</div>
							<?= $selCiclo ?>
							<div class="form-group col-5">
								<label for="curso">Curso</label>
								<select class='custom-select' id='curso' name='curso'>
									<?php if ($datos['curso'] === 1): ?>
										<option value='1' selected>Primero</option>
										<option value='2'>Segundo</option>
									<?php else: ?>
										<option value='1'>Primero</option>
										<option value='2' selected>Segundo</option>
									<?php endif; ?>
								</select>
							</div>
							<?= $selProfe ?>
							<div class='form-group col-5'>
								<label for="horas">Horas</label>
								<input type="number" class="form-control" id='horas' name='horas' value='<?= $datos['horas'] ?>'>
							</div>
							<div class='form-group col-5'>
								<label for="anio">Año</label>
								<input type="number" class="form-control" id='anio' name='anio' min='2000' value='<?= $datos['anio'] ?>'>
							</div>
							<div class='form-group col-5'>
								<label for="url">URL Logotipo</label>
								<input type="url" class="form-control" id='url' name='url' value='<?= $datos['url'] ?>'>
							</div>
							<div class='form-group col-5 btn-submit'>
								<input type="submit" class="btn btn-warning" value='Modificar'>
							</div>
							<input type='hidden' name='formulario' value='Asignatura'>
						</form>
					</main>
				</article>
			</main>
        </div>
		<script src="./js/editar.js"></script>
    </body>
</html>