<?php
require_once './data/funciones.inc.php';
session_start();

$exc = $logged = null;
if (!isset($_SESSION['nick'])) {
	if (isset($_POST['entrar'])) {
		try {
			if (logUser($_POST['user'])) {
				$logged = true;
				if (isset($_SESSION['nick'])) {
					$_SESSION['visitas'] += 1;
				} else {
					$_SESSION['nick'] = $_POST['user'];
					$_SESSION['visitas'] = 1;
					$_SESSION['inicio'] = getDateTime();
				}
			}
		} catch (Exception $e) {
			$exc = getAlertElement($e, 'alert');
		}
	}
} else {
	$logged = true;
}
?>
<!DOCTYPE html>
<html>
	<?php include_once './components/html_head.inc.php'; ?>
	<body>
		<div class='wrapper'>
			<?php
			if ($exc) {
				echo $exc;
			}
			if ($logged) {
				include_once './components/logout-box.inc.php';
			} else {
				include_once './components/login-box.inc.php';
			}
			?>
			<header class='header'>
				<div class='header__logo'>Matricúl<mark class='logo-end'>Ate</mark></div>
				<div class='header__titulo'>
					<span class='header__titulo-txt'>IES Linus Torvalds</span>
					<?php if ($logged): ?>
						<span class='header__titulo-id__logged'><?= $_SESSION['nick'] ?></span>
					<?php else: ?>
						<span class='header__titulo-id'>Iniciar Sesión</span>
					<?php endif; ?>
				</div>
			</header>
			<main class='main'>
				<?php
				if ($logged) {
					if (strcmp($_SESSION['nick'], 'admin') === 0) {
						include_once './components/nav_admin.inc.php';
					} else {
						include_once './components/nav_user.inc.php';
					}
					include_once './components/logo_user_block.inc.php';
				} else {
					include_once './components/nav_index.inc.php';
					include_once './components/logo_block.inc.php';
				}
				?>
			</main>
		</div>
	</body>
</html>