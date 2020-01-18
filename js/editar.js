function inicio() {
	if (document.querySelector('select[name=alumno]')) {
		let alumno = document.querySelector('select[name=alumno]');
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
	if (document.querySelector('select[name=ciclo]')) {
		let ciclo = document.querySelector('select[name=ciclo]');
		ciclo.addEventListener('change', () => {
			document.querySelector('input[name=cicloTxt]').value = ciclo[ciclo.selectedIndex].textContent;
		});
	}
	if (document.querySelector('select[name=alumno_mat]')) {
		let alumno = document.querySelector('select[name=alumno_mat]');
		alumno.addEventListener('change', () => {
			document.querySelector('input[name=alumnoTxt]').value = alumno[alumno.selectedIndex].textContent;
		});
	}
	if (document.querySelector('select[name=profesor]')) {
		let profesor = document.querySelector('select[name=profesor]');
		profesor.addEventListener('change', () => {
			document.querySelector('input[name=profesorTxt]').value = profesor[profesor.selectedIndex].textContent;
		});
	}
}

window.addEventListener('load', inicio);