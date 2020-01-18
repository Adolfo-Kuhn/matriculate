<aside class="logout-box">
	<h6 class="logout-box__header">Info de Sesión</h6>
	<div class="logout-box__hello">¡Hola, <?= $_SESSION['user'] ?>!</div>
	<div class="logout-box__sesion">Sesión iniciada:</div>
	<div class="logout-box__time"><?= $_SESSION[$user]['inicio']['fecha'] ?></div>
	<div class="logout-box__sesion">a las:</div>
	<div class="logout-box__time"><?= $_SESSION[$user]['inicio']['hora'] ?></div> 
	<div class="logout-box__visits">Número de visitas: 
		<span class="logout-box__number">
			<?= $_SESSION[$user]['visitas'] ?>
		</span>
	</div>
	<form class="logout-box__form" name="logout" action="./logout.php" method="POST">
		<input class="logout-box__submit" name="salir" value="Desconexión" type="submit">
	</form>
</aside>