<?php
require_once './data/funciones.inc.php';
require_once './data/data.php';
require_once './data/data_sql.php';
try {
	session_start();

	$tabla = null;
	// si no usuario identificado se redirecciona al inicio 
	if (!isset($_SESSION['user'])) {
		header('location: index.php');
	} else {
		$user = $_SESSION['user'];
	}
	// si se ha seleccionado una tabla en la que insertar
	if (isset($_REQUEST['tabla'])) {
		$tabla = $_POST['tabla'];
		switch (strtolower($tabla)) {
			case 'alumno':
				$formulario = './components/forms/alumno_form.inc.php';
				break;
			case 'asignatura':
				$formulario = './components/forms/asignatura_form.inc.php';
				break;
			case 'ciclo':
				$formulario = './components/forms/ciclo_form.inc.php';
				break;
			case 'matrícula':
				$formulario = './components/forms/matricula_form.inc.php';
				break;
			case 'profesor':
				$formulario = './components/forms/profesor_form.inc.php';
				break;
		}
	}
	// si se ha enviado un formulario para insertar
	if (isset($_REQUEST['formulario'])) {
		$nuevo = $_POST['formulario'];
		$tabla = $nuevo;
		switch (strtolower($nuevo)) {
			case 'alumno':
				$nombre = $_POST['nombre'];
				$apellido = $_POST['apellido'];
				$dni = $_POST['dni'];
				$fecha = $_POST['fecha'];
				$sexo = $_POST['sexo'];
				$sql = SQL_CREAR_ALUMNO_INSERT;
				$param = ['sssss', $nombre, $apellido, $sexo, $dni, $fecha];
				$respuesta = consultaInsertar_5($sql, $param);
				$formulario = './components/forms/alumno_form.inc.php';
				break;
			case 'asignatura':
				$idAsignatura = obtenerIdMaximo('idAsignatura', 'asignatura');
				$nombre = strtoupper($_POST['nombre']);
				$siglas = strtoupper($_POST['siglas']);
				$horas = $_POST['horas'];
				$profesor = $_POST['profesor'];
				$ciclo = $_POST['ciclo'];
				$curso = $_POST['curso'];
				$anio = $_POST['anio'];
				$url = $_POST['url'];
				$sql = SQL_CREAR_ASIGNATURA_INSERT;
				$conexion = conectarBD();
				$consulta = $conexion->stmt_init();
				$consulta->prepare($sql);
				$consulta->bind_param('issdsisis', $idAsignatura, $nombre, $siglas, $horas, $profesor, $ciclo, $curso, $anio, $url);
				if ($consulta->execute()) {
					$respuesta = getAlertElement('<strong>Registro almacenado correctamente</strong>', 'success');
				} else {
					if ($consulta->errno) {
						$respuesta = getAlertlement("<strong>El registro no pudo almacenarse</strong> $consulta->errno - $consulta->error", 'warning');
					}
				}
				$consulta->close();
				$conexion->close();
				$formulario = './components/forms/asignatura_form.inc.php';
				break;
			case 'ciclo':
				$idCiclo = obtenerIdMaximo('idCiclo', 'ciclo');
				$nombre = strtoupper($_POST['nombre']);
				$siglas = strtoupper($_POST['siglas']);
				$url = $_POST['url'];
				$sql = SQL_CREAR_CICLO_INSERT;
				$param = ['isss', $idCiclo, $nombre, $siglas, $url];
				$respuesta = consultaInsertar_4($sql, $param);
				$formulario = './components/forms/ciclo_form.inc.php';
				break;
			case 'matrícula':
				$dniAlumno = $_POST['alumno'];
				$asignatura = $_POST['asignatura'];
				$repetidor = $_POST['repetidor'];
				$nota = ($_POST['nota'] === '') ? NULL : $_POST['nota'];
				$sql = SQL_CREAR_MATRICULA_INSERT;
				$param = ['siid', $dniAlumno, $asignatura, $repetidor, $nota];
				$respuesta = consultaInsertar_4($sql, $param);
				$formulario = './components/forms/matricula_form.inc.php';
				break;
			case 'profesor':
				$nombre = $_POST['nombre'];
				$apellido = $_POST['apellido'];
				$dni = $_POST['dni'];
				$fecha = $_POST['fecha'];
				$sexo = $_POST['sexo'];
				$sql = SQL_CREAR_PROFESOR_INSERT;
				$param = ['sssss', $dni, $nombre, $apellido, $sexo, $fecha];
				$respuesta = consultaInsertar_5($sql, $param);
				$formulario = './components/forms/profesor_form.inc.php';
				break;
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
							<?php echo obtenerBreadcrum('Insertar', $tabla); ?>
						</ol>
						<aside class='container'>
							<form class='selector-box mb-3' name='consulta' action='insertar.php' method='POST'>
								<div class="input-group col-5">
									<?php include_once './components/selec-tabla.inc.php'; ?>
									<div class="input-group-append">
										<label class="input-group-text" for="selec-tabla">Inserción</label>
									</div>
								</div>
								<button type="submit" class="btn btn-success">Seleccionar</button>
							</form>
						</aside>
					</header>
					<?php
					if (isset($formulario)) {
						echo "<main class='container form-showcase'>";
						if (isset($respuesta)) {
							echo $respuesta;
						}
						include_once $formulario;
						echo "</main>";
					}
					?>
				</article>
			</main>
        </div>
    </body>
</html>