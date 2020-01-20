function inicio() {
	let colors = document.querySelectorAll('div.form-check');
	colors.forEach(a => a.addEventListener('click', addChecked));
}

function addChecked() {
	let clase = `.${this.parentElement.className}`;
	delChecked(clase);
	this.querySelector('input[type=radio]').setAttribute('checked', true);
}

function delChecked(clase) {
	let elemento = document.querySelector(clase);
	elemento.querySelectorAll('div.form-check').forEach(a => a.querySelector(
		'input[type=radio]').removeAttribute('checked'));
}

window.addEventListener('load', inicio);