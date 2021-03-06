<?php
require_once './data/funciones.inc.php';
require_once './data/data.php';
require_once './data/data_sql.php';
try {
	session_start();

	$tabla = null;
	if (!isset($_SESSION['user'])) {
		header('location: index.php');
	} else {
		$user = $_SESSION['user'];
	}
	if (isset($_REQUEST['tabla'])) {
		$tabla = $_POST['tabla'];
		switch (strtolower($tabla)) {
			case 'alumno':
				$listado = crearCuerpoTabla(CABECERAS_ALUMNO, SQL_LEER_ALUMNO);
				break;
			case 'asignatura':
				$listado = crearCuerpoTabla(CABECERAS_ASIGNATURA, SQL_LEER_ASIGNATURA_2);
				break;
			case 'ciclo':
				$listado = crearCuerpoTabla(CABECERAS_CICLO, SQL_LEER_CICLO);
				break;
			case 'matrícula':
				if (isset($_POST['asignatura'])) {
					if ($_POST['asignatura'] > 0) {
						$asignatura = $_POST['asignatura'];
						$txtAsignatura = $_POST['txtAsig'];
						$sql = SQL_LEER_ASIGNATURA_1;
						$selec_matricula = obtenerLabeledSelect('asignatura', 'Matrícula', $sql, $asignatura);
						$sql = SQL_LEER_MATRICULA . $asignatura;
						$listado = crearCuerpoTabla(CABECERAS_MATRICULA, $sql);
					}
				} else {
					$selec_matricula = obtenerLabeledSelect('asignatura', 'Matrícula', SQL_LEER_ASIGNATURA_1);
				}
				break;
			case 'profesor':
				$listado = crearCuerpoTabla(CABECERAS_ALUMNO, SQL_LEER_PROFESOR);
				break;
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
							<?php if (isset($tabla)): ?>
								<li class="breadcrumb-item">Consultar</li>
								<?php if (strcasecmp($tabla, 'seleccione registro...') !== 0): ?>
									<?php if (isset($asignatura)): ?>
										<li class="breadcrumb-item "><?= $tabla ?></li>
										<li class="breadcrumb-item active"><?= $txtAsignatura ?></li>
									<?php else: ?>
										<li class="breadcrumb-item active"><?= $tabla ?></li>
									<?php endif; ?>
								<?php endif; ?>
							<?php else: ?>
								<li class="breadcrumb-item active">Consultar</li>
							<?php endif; ?>
						</ol>
						<aside class='container'>
							<form class='selector-box mb-3' name='consulta' action='consultar.php' method='POST'>
								<div class="input-group col-5">
									<?php include_once './components/selec-tabla.inc.php'; ?>
									<div class="input-group-append">
										<label class="input-group-text" for="selec-tabla">Consulta</label>
									</div>
								</div>
								<?php
								if (isset($selec_matricula)) {
									echo $selec_matricula;
								}
								?>
								<input type='hidden' name='txtAsig' id='txtAsig'>
								<button type="submit" class="btn btn-success">Seleccionar</button>
							</form>
						</aside>
					</header>
					<?php if (isset($listado)): ?>
					<main class='container showcase'><?= $listado ?></main>
					<?php endif; ?>
				</article>
			</main>
        </div>
    </body>
</html>