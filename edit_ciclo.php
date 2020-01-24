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
		$tabla = 'Ciclo';
	}
	if (isset($_REQUEST['ciclo'])) {
		if (strcmp($_POST['ciclo'], '-') !== 0) {
			$ciclo = $_POST['ciclo'];
			$cicloTxt = $_POST['cicloTxt'];
			$conexion = conectarBD();
			$consulta = $conexion->stmt_init();
			$consulta->prepare(SQL_MODCICLO_1);
			$consulta->bind_param('s', $ciclo);
			$consulta->execute();
			$consulta->bind_result($nombre, $siglas, $url);
			$consulta->fetch();
			$consulta->close();
			$conexion->close();
		} else {
			$ciclo = '-';
			$respuesta = getAlertElement('Es necesario que seleccione un ciclo para editar.', 'warning');
		}
	}
	if (isset($_REQUEST['formulario'])) {
		$nombre = $_POST['nombre'];
		$siglas = $_POST['siglas'];
		$url = $_POST['url'];
		$ciclo = $_POST['formulario'];
		$conexion = conectarBD();
		$consulta = $conexion->stmt_init();
		$consulta->prepare(SQL_MODCICLO_2);
		$consulta->bind_param('sssi', $nombre, $siglas, $url, $ciclo);
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
	$tabla = 'Ciclo';
	$selector = obtenerLabeledSelect('ciclo', 'Ciclo', SQL_CREAR_ASIGNATURA_1, $ciclo);
	$action = './edit_ciclo.php';
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
							<li class="breadcrumb-item">Asignatura</li>
							<?php if (isset($cicloTxt)): ?>
								<li class="breadcrumb-item active"><?= $cicloTxt ?></li>
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
								<form class='selector-box mb-3' name="<?= 'edit-' . $tabla ?>" action="edit_ciclo.php" method='POST'>
									<?= $selector; ?>
									<?php if (isset($cicloTxt)): ?>
										<input type='hidden' name='cicloTxt' value='<?= $cicloTxt ?>'>
									<?php else: ?>
										<input type='hidden' name='cicloTxt'>
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
						<form class='new-form' name="edit-ciclo" action="edit_ciclo.php" method="POST">
							<div class="form-group col-5">
								<label for="nombre">Nombre</label>
								<input type="text" class="form-control" id="nombre" name="nombre" value='<?= ucwords(strtolower($nombre)) ?>'>
							</div>
							<div class="form-group col-5">
								<label for="siglas">Siglas</label>
								<input type="text" class="form-control" id="siglas" name="siglas" value='<?= $siglas ?>'>
							</div>
							<div class='form-group col-5'>
								<label for="url">URL Logotipo</label>
								<input type="url" class="form-control" id="url" name='url' value='<?= $url ?>'>
							</div>
							<div class='form-group col-5 btn-submit'>
								<input type="submit" class="btn btn-warning" value='Modificar'>
							</div>
							<input type='hidden' name='formulario' value='<?= $ciclo ?>'>
						</form>
					</main>
				</article>
			</main>
        </div>
		<script src="./js/editar.js"></script>
    </body>
</html>