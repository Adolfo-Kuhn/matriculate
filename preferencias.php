<?php
require_once './data/funciones.inc.php';
require_once './data/data.php';
require_once './data/data_sql.php';
try {
	session_start();

	if (!isset($_SESSION['user'])) {
		header('location: index.php');
	} else {
		$user = $_SESSION['user'];
		if (isset($_REQUEST['bgform'])) {
			$fondo = $_POST['bgcolor'];
			$_SESSION[$user]['bgcolor'] = $fondo;
			setcookie($user . '_fondo', $fondo, time() + (60 * 60 * 24 * 90));
			$fondo = '#' . $fondo;
			$respuesta = getAlertElement('<strong>¡Entorno actualizado!</strong> Se modificó el color del escritorio.', 'warning');
		}
		if (isset($_REQUEST['txform'])) {
			$texto = $_POST['txcolor'];
			$hover = $_POST['txhover'];
			$_SESSION[$user]['txcolor'] = $texto;
			$_SESSION[$user]['txhover'] = $hover;
			setcookie($user . '_texto', $texto, time() + (60 * 60 * 24 * 90));
			setcookie($user . '_hover', $hover, time() + (60 * 60 * 24 * 90));
			$texto = '#' . $texto;
			$hover = '#' . $hover;
			$respuesta = getAlertElement('<strong>¡Entorno actualizado!</strong> Se modificó el color del texto.', 'warning');
		}
		if (isset($_REQUEST['visitas'])) {
			$_SESSION['visitas'] = 1;
			setcookie($user . '_visitas', 1, time() + (60 * 60 * 24 * 90));
			$respuesta = getAlertElement('<strong>¡Entorno actualizado!</strong> Se reinició el contador de visitas.', 'warning');
		}
		if (isset($_REQUEST['defecto'])) {
			$_SESSION['visitas'] = 1;
			setcookie($user . '_visitas', 1, time() + (60 * 60 * 24 * 90));
			unset($_SESSION[$user]['bgcolor']);
			unset($_SESSION[$user]['txcolor']);
			unset($_SESSION[$user]['txhover']);
			setcookie($user . '_fondo', '', time() - (60 * 60));
			setcookie($user . '_title', '', time() - (60 * 60));
			setcookie($user . '_texto', '', time() - (60 * 60));
			setcookie($user . '_hover', '', time() - (60 * 60));
			$respuesta = getAlertElement('<strong>¡Entorno actualizado!</strong> Se restablecieron los valores por defecto.', 'warning');
		}
		if (isset($_SESSION[$user]['bgcolor'])) {
			$fondo = '#' . $_SESSION[$user]['bgcolor'];
		}
		if (isset($_SESSION[$user]['txcolor'])) {
			$texto = '#' . $_SESSION[$user]['txcolor'];
		}
	}
} catch (Exception $e) {
	$exc = getAlertElement($e->getMessage(), 'alert');
}
?>
<!DOCTYPE html>
<html lang='es'>
	<head>
		<meta charset='UTF-8'>
		<meta name='autor' content='Adolfo Kuhn'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'>
		<link rel='icon' type='image/png' href='./img/logo.png'>
		<link rel='stylesheet' href='./css/bootstrap.min.css'>
		<link rel='stylesheet' href='./css/estilos.css'>
		<link rel='stylesheet' href='./css/pantalla.css'>
		<link rel='stylesheet' href='./css/prefs.css'>
		<link rel='stylesheet' type="text/css" href='./css/settings.css.php'>
		<script src='./js/codigo.js'></script>
		<script src='./js/prefs.js'></script>
		<title>MatricúlAte</title>
	</head>
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
							<li class="breadcrumb-item active">Preferencias</li>
						</ol>
					</header>
					<main class='container showcase'>
						<?php
						if (isset($respuesta)) {
							echo $respuesta;
						}
						?>
						<div class='preferencias'>							
							<form class='fondo' name='bgform' action='preferencias.php' method="POST">
								<fieldset class='bgcSet'>
									<legend class='h5'>Color de fondo</legend>
									<div class="form-check">
										<?php if (isset($fondo)): ?>
											<?php if (strcasecmp($fondo, '#313a46') === 0): ?>
												<input class="form-check-input" type="radio" name="bgcolor" value="313a46" checked>
											<?php else: ?>
												<input class="form-check-input" type="radio" name="bgcolor" value="313a46">
											<?php endif; ?>
										<?php else: ?>
											<input class="form-check-input" type="radio" name="bgcolor" value="313a46" checked>
										<?php endif; ?>
										<div class="form-group">
											<input type="text" class="form-control color" id="bgc1" readonly>
										</div>
									</div>
									<div class="form-check">
										<?php if (isset($fondo) && strcasecmp($fondo, '#4b5043') === 0): ?>
											<input class="form-check-input" type="radio" name="bgcolor" value="4b5043" checked>
										<?php else: ?>
											<input class="form-check-input" type="radio" name="bgcolor" value="4b5043">
										<?php endif; ?>
										<div class="form-group">
											<input type="text" class="form-control color" id="bgc2" readonly>
										</div>
									</div>
									<div class="form-check">
										<?php if (isset($fondo) && strcasecmp($fondo, '#614a3e') === 0): ?>
											<input class="form-check-input" type="radio" name="bgcolor" value="614a3e" checked>
										<?php else: ?>
											<input class="form-check-input" type="radio" name="bgcolor" value="614a3e">
										<?php endif; ?>
										<div class="form-group">
											<input type="text" class="form-control color" id="bgc3" readonly>
										</div>
									</div>
									<div class='form-group btn-submit'>
										<input type="submit" name="bgform" class="btn btn-dark" value='Aplicar'>
									</div>
								</fieldset>
							</form>
							<form class='texto' name='txform' action='preferencias.php' method="POST">
								<fieldset class='txtSet'>
									<legend class='h5'>Color de texto</legend>
									<div class="form-check">
										<?php if (isset($texto)): ?>
											<?php if (strcasecmp($texto, '#aaa') === 0): ?>
												<input class="form-check-input" type="radio" name="txcolor" value="aaa" checked>
											<?php else: ?>
												<input class="form-check-input" type="radio" name="txcolor" value="aaa">
											<?php endif; ?>
										<?php else: ?>
											<input class="form-check-input" type="radio" name="txcolor" value="aaa" checked>
										<?php endif; ?>
										<input type="hidden" name="txhover" value="eee">
										<div class="form-group">
											<input type="text" class="form-control color" id="txt1" readonly>
										</div>
									</div>
									<div class="form-check">
										<?php if (isset($texto) && strcasecmp($texto, '#add1bf') === 0): ?>
											<input class="form-check-input" type="radio" name="txcolor" value="add1bf" checked>
										<?php else: ?>
											<input class="form-check-input" type="radio" name="txcolor" value="add1bf">
										<?php endif; ?>
										<input type="hidden" name="txhover" value="d3ffe9">
										<div class="form-group">
											<input type="text" class="form-control color" id="txt2" readonly>
										</div>
									</div>
									<div class="form-check">
										<?php if (isset($texto) && strcasecmp($texto, '#adb9a5') === 0): ?>
											<input class="form-check-input" type="radio" name="txcolor" value="adb9a5" checked>
										<?php else: ?>
											<input class="form-check-input" type="radio" name="txcolor" value="adb9a5">
										<?php endif; ?>
										<input type="hidden" name="txhover" value="cfbfb0">
										<div class="form-group">
											<input type="text" class="form-control color" id="txt3" readonly>
										</div>
									</div>
									<div class='form-group btn-submit'>
										<input type="submit" name="txform" class="btn btn-dark" value='Aplicar'>
									</div>
								</fieldset>
							</form>
						</div>
						<div class='preferencias'>
							<form name='visitas' action='preferencias.php' method="POST">
								<div class="input-group mb-3" id='ig1'>
									<div class="input-group-prepend">
										<span class="input-group-text">Nº de visitas</span>
									</div>
									<input type="text" class="form-control" placeholder="<?= $_SESSION['visitas'] ?>">
									<div class="input-group-append">
										<button class="btn btn-dark" name="visitas" type="submit" id="button-addon1">Reiniciar</button>
									</div>
								</div>
							</form>
						</div>
						<div class='preferencias'>
							<form name='defecto' action="preferencias.php" method="POST">
								<div class="input-group mb-3" id='ig2'>
									<div class="input-group-prepend">
										<span class="input-group-text">Restablecer la configuración por defecto</span>
									</div>
									<div class="input-group-append">
										<button class="btn btn-dark" name="defecto" type="submit" id="button-addon2">Restablecer</button>
									</div>
								</div>								
							</form>
						</div>
					</main>
				</article>
			</main>
        </div>
    </body>
</html>