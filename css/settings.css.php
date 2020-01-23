<?php
header("Content-Type: text/css; charset=utf-8");
/* Se establece el tipo de contenido para que se codifique
 * como una hoja de estilos css */

session_start();

/* Estilo por defecto.
 * Si no hay ninguna otra modificación estos serán
 * los estilos aplicables */
$fondo = "#313a46";
$title = "#fff";
$texto = "#aaa";
$hover = "#eee";

if (isset($_SESSION['user'])) {
	$user = $_SESSION['user'];
	if (isset($_SESSION[$user]['bgcolor'])) {
		$fondo = '#' . $_SESSION[$user]['bgcolor'];
		switch ($fondo) {
			case '#313a46':
				$title = '#fff';
				break;
			case '#4b5043':
				$title = '#d3ffe9';
				break;
			case '#614a3e':
				$title = '#ccb096';
				break;
		}
	} else {
		if (isset($_COOKIE[$user . '_fondo'])) {
			$fondo = '#' . $_COOKIE[$user . '_fondo'];
			$_SESSION[$user]['bgcolor'] = $_COOKIE[$user . '_fondo'];
			switch ($fondo) {
				case '#313a46':
					$title = '#fff';
					break;
				case '#4b5043':
					$title = '#d3ffe9';
					break;
				case '#614a3e':
					$title = '#ccb096';
					break;
			}
		}
	}
	if (isset($_SESSION[$user]['txcolor'])) {
		$texto = '#' . $_SESSION[$user]['txcolor'];
		switch ($texto) {
			case '#aaa':
				$hover = '#eee';
				break;
			case '#add1bf':
				$hover = '#d3ffe9';
				break;
			case '#adb9a5':
				$hover = '#cfbfb0';
				break;
		}
	} else {
		if (isset($_COOKIE[$user . '_texto'])) {
			$texto = '#' . $_COOKIE[$user . '_texto'];
			$hover = '#' . $_COOKIE[$user . '_hover'];
			$_SESSION[$user]['txcolor'] = $_COOKIE[$user . '_texto'];
			$_SESSION[$user]['txhover'] = $_COOKIE[$user . '_hover'];
		}		
	}
}
?>

:root {
--bgcolor: <?= $fondo ?>;
--ttcolor: <?= $title ?>;
--txcolor: <?= $texto ?>;
--txhover: <?= $hover ?>;
}

.header__titulo {
background-color: var(--bgcolor);
color: var(--ttcolor);
}

.header__titulo-id {
color: var(--txcolor);
}

.header__titulo-id:hover {
color: var(--txhover);
}

.login-box {
background-color: var(--bgcolor);
}

.login-box__header {
color: var(--txcolor);
}

.logout-box {
background-color: var(--bgcolor);
}

.logout-box__header,
.logout-box__sesion,
.logout-box__visits {
color: var(--txcolor);
}

.nav {
background-color: var(--bgcolor);
}

.nav__item > a {
color: var(--txcolor);
}

.nav__item > a:hover {
color: var(--txhover);
}

.logo-user {
color: var(--bgcolor);
}