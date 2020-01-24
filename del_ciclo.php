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
			$conexion = conectarBD();
			$consulta = $conexion->stmt_init();
			$consulta->prepare(SQL_DELCICLO_1);
			$consulta->bind_param('i', $ciclo);
			$consulta->execute();
			$consulta->bind_result($nombre, $siglas, $urlLogotipo);
			$consulta->fetch();
			$cicloTxt = ucwords(strtolower($nombre));
			$consulta->close();
			$conexion->close();
		} else {
			$ciclo = '-';
			$respuesta = getAlertElement('Es necesario que seleccione un ciclo para eliminar.', 'warning');
		}
	}
	if (isset($_REQUEST['formulario'])) {
		$ciclo = $_POST['ciclo'];
		$conexion = conectarBD();
		$consulta = $conexion->stmt_init();
		$consulta->prepare(SQL_DELCICLO_2);
		$consulta->bind_param('i', $ciclo);
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
	$selector = obtenerLabeledSelect('ciclo', 'Ciclo', SQL_CREAR_ASIGNATURA_1, $ciclo);
	$action = './del_ciclo.php';
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
							<li class="breadcrumb-item">Alumno</li>
							<?php if (isset($cicloTxt)): ?>
								<li class="breadcrumb-item active"><?= $cicloTxt ?></li>
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
								<form class='selector-box mb-3' name="del_ciclo" action="del_ciclo.php" method='POST'>
									<?= $selector; ?>
									<?php if (isset($cicloTxt)): ?>
										<input type='hidden' name='cicloTxt' value='<?= $cicloTxt ?>'>
									<?php else: ?>
										<input type='hidden' name='cicloTxt'>
									<?php endif; ?>
									<button type="submit" class="btn btn-success">Seleccionar</button>
									<input type='hidden' name='tabla' value="Ciclo">
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
						<form class='new-form' name="del-ciclo" action="del_ciclo.php" method="POST">
							<div class="form-group col-5">
								<label for="nombre">Nombre</label>
								<input type="text" class="form-control" id="nombre" name="nombre" value="<?= ucwords(strtolower($nombre)) ?>">
								<input type="hidden" name="ciclo" value="<?= $ciclo ?>">
							</div>
							<div class="form-group col-5">
								<label for="siglas">Siglas</label>
								<input type="text" class="form-control" id="siglas" name="siglas" value="<?= $siglas ?>">
							</div>
							<div class="form-group col-5">
								<label for="url">URL Logotipo</label>
								<input type="text" class="form-control" id="url" name="dni" value="<?= $urlLogotipo ?>">
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