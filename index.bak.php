<?php

require_once './data/funciones.inc.php';

$exc = $logged = null;
if (isset($_POST['entrar'])) {
	$primera = false;
	try {
		if (logUser($_POST['user'])) {
			$logged = true;
			session_start();
			if (isset($_SESSION['visitas'])) {
				$_SESSION['visitas']++;
				$nick = $_SESSION['nick'];
				$_SESSION['inicio'] = getdate();
			} else {
				$_SESSION['visitas'] = 1;
				$_SESSION['nick'] = $_POST['user'];
				$nick = $_POST['user'];
			}
			setcookie('nick', $_SESSION['nick']);
			$title = "<title>Logged - $nick</title>";
		}
	} catch (Exception $e) {
		$exc = getAlertElement($e, 'alert');
		$title = "<title>Login - MatricúlAte</title>";
	}
} else {
	$primera = true;
}
include './components/html_header.inc.php';
echo "<body><div class='wrapper'>";
if ($exc) {
	echo $exc;
}
if ($logged) {
	include './components/logout-box.inc.php';
	include './components/doc_user_header.inc.php';
	echo "<main class='main'>";
	if (strcmp($nick, 'admin') === 0) {
		include './components/nav_admin.inc.php';
	} else {
		include './components/nav_user.inc.php';
	}
	include './components/logo_user_block.inc.php';
	include './components/html_footer.inc.php';
} else {
	include './components/login-box.inc.php';
	include './components/doc_header.inc.php';
	echo "<main class='main'>";
	include './components/nav_index.inc.php';
	include './components/logo_block.inc.php';
	include './components/html_footer.inc.php';
}
?>
