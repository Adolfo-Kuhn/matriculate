function inicio() {
	if (document.querySelector('.header__titulo-id')) {
		document.querySelector('.header__titulo-id').addEventListener('click',
			mostrarLoginBox);
		document.querySelector('.logo-block__link').addEventListener('click',
			mostrarLoginBox);
	} else {
		document.querySelector('.header__titulo-id__logged').addEventListener(
			'click', mostrarLogoutBox);
	}
	if (document.querySelector('#selec-matricula')) {
		document.querySelector('#selec-matricula').addEventListener('change',
			fillInput);
	}
	if (document.querySelector('.alert')) {
		document.querySelector('.close').addEventListener('click', closeAlert);
		delayCloseAlert();
	}
}

function mostrarLoginBox() {
	document.querySelector('.login-box').style.display = 'block';
	document.querySelector('.login-box__user').focus();
	document.querySelector(
		'.header__titulo-id').style.background = "url('./img/arrow_up.png') right no-repeat";
	document.querySelector('.header__titulo-id').removeEventListener('click',
		mostrarLoginBox);
	document.querySelector('.header__titulo-id').addEventListener('click',
		ocultarLoginBox);
	if (document.querySelector('.logo-block__link')) {
		document.querySelector('.logo-block__link').removeEventListener(
			'click', mostrarLoginBox);
		document.querySelector('.logo-block__link').addEventListener('click',
			ocultarLoginBox);
	}
}

function ocultarLoginBox() {
	document.querySelector('.login-box').style.display = 'none';
	document.querySelector(
		'.header__titulo-id').style.background = "url('./img/arrow_dwn.png') right no-repeat";
	document.querySelector('.header__titulo-id').removeEventListener('click',
		ocultarLoginBox);
	document.querySelector('.header__titulo-id').addEventListener('click',
		mostrarLoginBox);
	if (document.querySelector('.logo-block__link')) {
		document.querySelector('.logo-block__link').removeEventListener(
			'click', ocultarLoginBox);
		document.querySelector('.logo-block__link').addEventListener('click',
			mostrarLoginBox);
	}
}

function mostrarLogoutBox() {
	document.querySelector('.logout-box').style.display = 'block';
	document.querySelector(
		'.header__titulo-id__logged').style.background = "url('./img/arrow_up_green.png') right no-repeat";
	document.querySelector('.header__titulo-id__logged').removeEventListener(
		'click', mostrarLogoutBox);
	document.querySelector('.header__titulo-id__logged').addEventListener(
		'click', ocultarLogoutBox);
}

function ocultarLogoutBox() {
	document.querySelector('.logout-box').style.display = 'none';
	document.querySelector(
		'.header__titulo-id__logged').style.background = "url('./img/arrow_dwn_green.png') right no-repeat";
	document.querySelector('.header__titulo-id__logged').removeEventListener(
		'click', ocultarLogoutBox);
	document.querySelector('.header__titulo-id__logged').addEventListener(
		'click', mostrarLogoutBox);
}

function fillInput() {
	let selec = document.querySelector('#selec-matricula');
	let index = selec.selectedIndex;
	document.querySelector('#txtAsig').setAttribute('value',
		selec[index].textContent);
}

function closeAlert() {
	let cajon = document.querySelector('.form-showcase');
	cajon.removeChild(cajon.firstElementChild);
}

function delayCloseAlert() {
	setTimeout(() => closeAlert(), 15000);
}

window.addEventListener('load', inicio);