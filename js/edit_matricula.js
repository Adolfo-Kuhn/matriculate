function inicio() {
	if (document.querySelector('select[name=alumno_mat]')) {
		let alumno = document.querySelector('select[name=alumno_mat]');
		alumno.addEventListener('change', () => {
			document.querySelector('input[name=alumnoTxt]').value = alumno[alumno.selectedIndex].textContent;
		});
	}
	if (document.querySelector('select[name=asignatura]')) {
		let asignatura = document.querySelector('select[name=asignatura]');
		asignatura.addEventListener('change', () => {
			document.querySelector(
				'input[name=asignaturaTxt]').value = asignatura[asignatura.selectedIndex].textContent;
		});
	}
}

window.addEventListener('load', inicio);