<?php
require_once './data/funciones.inc.php';
require_once './data/data.php';
require_once './data/data_sql.php';
try {
	session_start();

	$tabla = null;
	if (!isset($_SESSION['user'])) {
		header('location: index.php');
		die();
	} else {
		$user = $_SESSION['user'];
		if (strcmp($user, 'admin') !== 0) {
			header('location: index.php');
			die();
		}
		if (isset($_REQUEST['new_user'])) {
			$id = obtenerIdMaximo('id', 'usuarios') + 1;
			$usuario = $_POST['username'];
			$pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);
			$mail = $_POST['mail'];
			$conexion = conectarBD();
			$consulta = $conexion->stmt_init();
			$consulta->prepare('insert into usuarios (id, usuario, pwd, email) values (?,?,?,?)');
			$consulta->bind_param('isss', $id, $usuario, $pass, $mail);
			if ($consulta->execute()) {
				$msg = "¡Operación realizada correctame! Usuario $usuario dado de alta.";
				$respuesta = getAlertElement($msg, 'success');
			} else {
				$msg = '¡Operación fallida! El proceso alta de usuario no fue realizado.';
				$respuesta = getAlertElement($msg, 'warning');				
			}
			$consulta->close();
			$conexion->close();		
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
				<?php include_once './components/nav_admin.inc.php'; ?>
				<article class='screen'>
					<header class='form-header'>
						<ol class="breadcrumb">
							<li class="breadcrumb-item active">Nuevo Usuario</li>
						</ol>
					</header>
					<main class='container form-showcase'>
						<?php
						if (isset($respuesta)) {
							echo $respuesta;
						}
						?>
						<form class='new-form' name="new-user" action="adduser.php" method="POST">
							<div class='h4 px-3 mb-3'>Alta de usuario</div>
							<div class="form-group col-5">
								<label for="nombre">Nombre de usuario</label>
								<input type="text" class="form-control" id="nombre" name="username" required>
							</div>
							<div class="form-group col-5">
								<label for="pass">Contraseña</label>
								<input type="password" class="form-control" id="pass" name="pass" required>
							</div>
							<div class="form-group col-5">
								<label for="nombre">Correo electrónico</label>
								<input type="email" class="form-control" id="mail" name="mail" required>
							</div>
							<div class='form-group col-5 btn-submit'>
								<input type='submit' class='btn btn-info' name='new_user' value='Aceptar'>
							</div>
						</form>
					</main>
				</article>
			</main>
        </div>
    </body>
</html>