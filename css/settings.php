<?php
header("Content-Type: text/css");

session_start();

/* Estilo por defecto
$fondo = "#313a46";
$title = "#fff";
$texto = "#aaa";
$hover = "#eee"; */

$fondo = "#e95420";
$title = "#fff";
$texto = "#f5b199";
$hover = "#eee";


if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
	if (isset($_SESSION[$user]['bgcolor'])) {
		$fondo = $_SESSION[$user]['bgcolor'];
	}
	if (isset($_SESSION[$user]['ttlcolor'])) {
		$title = $_SESSION[$user]['ttlcolor'];
	}
	if (isset($_SESSION[$user]['txcolor'])) {
		$texto = $_SESSION[$user]['txcolor'];
	}
	if (isset($_SESSION[$user]['txhover'])) {
		$hover = $_SESSION[$user]['txhover'];
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

.logout-box {
	background-color: var(--bgcolor);
}

.logout-box__header,
.logout-box__sesion,
.logout-box__visitas {
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