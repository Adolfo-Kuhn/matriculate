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
	if (isset($_REQUEST['bgform'])) {
		$fondo = $_POST['bgcolor'];
	}
	if (isset($_REQUEST['txform'])) {
		$texto = $_POST['txcolor'];
	}
	if (isset($_REQUEST['visitas'])) {
		setcookie('visitas', 0, time() + (60 * 60 * 24 * 90));
	}
	if (isset($_REQUEST['defecto'])) {
		unset($_SESSION[$user]['visitas']);
		unset($_SESSION[$user]['bgColor']);
		unset($_SESSION[$user]['TxtColor']);
		unset($_SESSION[$user]['inicio']);		
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
		<script src='./js/codigo.js'></script>
		<script src='./js/prefs.js'></script>
		<title>MatricúlAte</title>
		<style>
			div.preferencias {
				display: flex;
				justify-content: space-around;
				margin-bottom: 1rem;
			}
			div.preferencias + div.preferencias {
				margin-left: 3.5rem;
				justify-content: flex-start;
			}
			div.preferencias:nth-child(2) {
				margin-top: 2.5rem;
			}
			.fondo, .texto {
				flex-basis: 40%;
			}
			input.color {
				border: none;
				border-radius: 0;
				cursor: default;
			}
			.btn-submit input[name=aplicarBgc],.btn-submit input[name=aplicarTxt] {
				margin-top: 1rem;
			}
			#bgc1 {
				background-color: #313a46;
			}
			#bgc2 {
				background-color: #e95420;
			}
			#bgc3 {
				background-color: #78c2ad;
			}
			#txt1 {
				background-color: #aaa;
			}
			#txt2 {
				background-color: #f5b199;
			}
			#txt3 {
				background-color: #adb9a5;
			}
			fieldset > legend {
				color: #8e939a;
			}
		</style>
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
						<div class='preferencias'>							
							<form class='fondo' name='bgform' action='preferencias.php' method="POST">
								<fieldset class='bgcSet'>
									<legend class='h5'>Color de fondo</legend>
									<div class="form-check">
										<input class="form-check-input" type="radio" name="bgcolor" value="#e95420" checked>
										<div class="form-group">
											<input type="text" class="form-control color" id="bgc1" readonly>
										</div>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="radio" name="bgcolor" value="#78c2ad">
										<div class="form-group">
											<input type="text" class="form-control color" id="bgc2" readonly>
										</div>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="radio" name="bgcolor" value="#008cba">
										<div class="form-group">
											<input type="text" class="form-control color" id="bgc3" readonly>
										</div>
									</div>
									<div class='form-group btn-submit'>
										<input type="submit" class="btn btn-dark" value='Aplicar'>
									</div>
								</fieldset>
							</form>
							<form class='texto'  name='txform' action='preferencias.php' method="POST">
								<fieldset class='txtSet'>
									<legend class='h5'>Color de texto</legend>
									<div class="form-check">
										<input class="form-check-input" type="radio" name="txcolor" value="#aaa" checked>
										<input type="hidden" name="txcolorHover1" value="#eee">
										<div class="form-group">
											<input type="text" class="form-control color" id="txt1" readonly>
										</div>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="radio" name="txcolor" value="#f5b199">
										<input type="hidden" name="txcolorHover2" value="#f9d0c2">
										<div class="form-group">
											<input type="text" class="form-control color" id="txt2" readonly>
										</div>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="radio" name="txcolor" value="#adb9a5">
										<input type="hidden" name="txcolorHover3" value="#f9d0c2">
										<div class="form-group">
											<input type="text" class="form-control color" id="txt3" readonly>
										</div>
									</div>
									<div class='form-group btn-submit'>
										<input type="submit" class="btn btn-dark" value='Aplicar'>
									</div>
								</fieldset>
							</form>
						</div>
						<div class='preferencias'>
							<form class='' name='visitas' action='preferencias.php' method="POST">
								<div class="input-group mb-3" id='ig1'>
									<div class="input-group-prepend">
										<span class="input-group-text">Nº de visitas</span>
									</div>
									<input type="text" class="form-control" placeholder="<?= $_SESSION[$user]['visitas'] ?>">
									<div class="input-group-append">
										<button class="btn btn-dark" type="button" id="button-addon1">Reiniciar</button>
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
										<button class="btn btn-dark" type="button" id="button-addon2">Restablecer</button>
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