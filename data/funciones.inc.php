<?php

/* Fichero con los datos de configuración de acceso a la BBDD */
require_once './data/db.conf.php';

/**
 * Función manejadora de errores. Permite lanzar una excepción cuando se produce un error
 * con objeto de tener un mayor control sobre el programa.
 * @param int $errno número del error producido.
 * @param string $errstr cadena con el mensaje de error correspondiente.
 * @param string $errfile cadena con el nombre del fichero donde se produjo el error.
 * @param int $errline número de la línea en la que se produjo el error en el fichero.
 * @throws ErrorException excepción que se lanza en lugar del error.
 */
function manejadorEx($errno, $errstr, $errfile, $errline) {
	throw new ErrorException($errstr, $errno, $errno, $errfile, $errline);
}

/**
 * Permite asignar una función como manejadora de errores.
 */
set_error_handler('manejadorEx');

/**
 * 
 * @return \mysqli
 * @throws Exception
 */
function conectarBD() {
	restore_error_handler();
	@$conexion = new mysqli(HOST, USER, PASS, DDBB);
	$user = USER;
	$host = HOST;
	$db = DDBB;
	if ($conexion->connect_errno) {
		$errmsg = '';
		switch ($conexion->connect_errno) {
			case 1044:
				$errmsg = "<strong>Acceso denegado</strong> al usuario '$user'@'$host' a la base de datos '$db'";
				break;
			case 1045:
				$errmsg = "<strong>Acceso denegado</strong> al usuario '$user'@'$host'";
				break;
			case 2002:
				$errmsg = '<strong>Fallo en la conexión a BBDD:</strong> servidor desconocido';
				break;
		}
		set_error_handler('manejadorEx');
		throw new Exception($errmsg, $conexion->connect_errno);
	}
	set_error_handler('manejadorEx');
	$conexion->set_charset('utf8');
	return $conexion;
}

/**
 * 
 * @param type $user
 * @param type $pass
 * @return boolean
 * @throws 
 */
function logUser($user, $pass) {
	$msg = '<strong>¡Acceso no autorizado!</strong> Error de usuario y/o contraseña.';
	$conexion = conectarBD();
	$sql = "select usuario, pwd from usuarios where usuario = '$user'";
	if ($consulta = $conexion->query($sql)) {
		$datos = $consulta->fetch_assoc();
		if (password_verify($_POST['pass'], $datos['pwd']) and $conexion->affected_rows) {
			$consulta->free();
			$conexion->close();
			return true;
		}
	}
	throw new Exception($msg);
}

/**
 * Devuelve los datos que arroje la consulta que recibe por parámetro.
 * El método se encarga de abrir y cerrar la BBDD.
 * @param string $sql cadena de texto con la sentencia SQL para la consulta.
 * @return mix puede devolver un conjunto de filas como resultado de alguna consulta, o 
 * puede devolver un valor booleano como resulado de una sentencia que no devuelva datos.
 * @see conectar.
 */
function obtenerDatosConsulta($sql) {
	$conexion = conectarBD();
	$datos = $conexion->query($sql);
	$conexion->close();
	return $datos;
}

//esta función se ha sustituido por obtenerSelect();
//function obtenerSelAsignaturas($selected = '-') {
//	$resultado = obtenerDatosConsulta(SQL_LEER_ASIGNATURA_1);
//	$selector = '<div class="input-group col-5">'
//			. '<select class="custom-select" id="selec-matricula" name="asignatura">'
//			. '<option value="-">Seleccione asignatura...</option>';
//	while ($fila = $resultado->fetch_array()) {
//		$convert = ucwords(strtolower($fila[1]));
//		$selector .= "<option value='${fila[0]}'";
//		$selector .= ($fila[0] == $selected) ? (' selected>') : ('>');
//		$selector .= "$convert</option>";
//	}
//	$selector .= '</select><div class="input-group-append">'
//			. '<label class="input-group-text">Matrícula</label></div></div>';
//	return $selector;
//}

/**
 * 
 * @param type $name
 * @param type $label
 * @param type $sql
 * @param type $selected
 * @return string
 */
function obtenerLabeledSelect($name, $label, $sql, $selected = '-') {
	$resultado = obtenerDatosConsulta($sql);
	$selector = "<div class='input-group col-5'>"
			. "<select class='custom-select' name='$name'>"
			. "<option value='-'>Seleccione $name...</option>";
	while ($fila = $resultado->fetch_array()) {
		$convert = ucwords(strtolower($fila[1]));
		$selector .= "<option value='${fila[0]}'";
		$selector .= ($fila[0] == $selected) ? (' selected>') : ('>');
		$selector .= "$convert</option>";
	}
	$selector .= '</select><div class="input-group-append">'
			. "<label class='input-group-text'>$label</label></div></div>";
	return $selector;
}

/**
 * 
 * @param type $name
 * @param type $label
 * @param type $sql
 * @param type $selected
 * @return type
 */
function obtenerSelect($name, $label, $sql, $selected = '-') {
	$resultado = obtenerDatosConsulta($sql);
	$selector = "<div class='form-group col-5'>";
	$selector .= "<label for='$name'>$label</label>";
	$selector .= "<select class='custom-select' name='$name'>";
	$selector .= "<option value='-'>Seleccione $name...</option>";
	while ($fila = $resultado->fetch_array()) {
		$convert = ucwords(strtolower($fila[1]));
		$selector .= "<option value='${fila[0]}'";
		$selector .= ($fila[0] == $selected) ? (' selected>') : ('>');
		$selector .= "$convert</option>";
	}
	return $selector .= '</select></div>';
}

/**
 * 
 * @param type $titulo
 * @param type $tabla
 * @return type
 */
function obtenerBreadcrum($titulo, $tabla) {
	if ($tabla) {
		if (strcasecmp($tabla, 'seleccione registro...') !== 0) {
			$breadcrum = "<li class='breadcrumb-item'>$titulo</li>";
			$breadcrum .= "<li class='breadcrumb-item active'>$tabla</li>";
		} else {
			$breadcrum = "<li class='breadcrumb-item active'>$titulo</li>";
		}
	} else {
		$breadcrum = "<li class='breadcrumb-item active'>$titulo</li>";
	}
	return $breadcrum;
}

/**
 * Devuelve, en forma de cadena de texto, un elemento de fila 'tr' con tantos elementos
 * de celda de encabezado 'th' como posiciones tiene el array que recibe como parámetro.
 * @param Array $array matriz con los datos que se ubicarán en cada una de las celdas.
 * @return String cadena con estructura html de fila de cabecera con los datos.
 */
function crearFilaCabecera($array) {
	$fila = '<thead><tr>';
	for ($index = 0; $index < count($array); $index++) {
		$fila .= "<th class='sticky'>" . $array[$index] . '</th>';
	}
	return $fila . '</tr></thead>';
}

/**
 * Devuelve, en forma de cadena de texto, tantos elementos de fila de tabla 'tr' como tuplas
 * resultantes de la consulta SQL que recibe por parámetro. Cada fila contendrá tantas celdas
 * de datos 'td' como campos tiene la consulta.
 * @param string $sql cadena de texto con la consulta SQL para la obtención de datos
 * @return string cadena de texto con la construcción html de filas de tabla
 * @see obtenerDatosConsulta.
 */
function arrayAfilas($sql) {
	$respuesta = '<tbody>';
	$resultado = obtenerDatosConsulta($sql);
	// se recuperan los datos solamente como array posicional
	while ($fila = $resultado->fetch_row()) {
		$respuesta .= '<tr>';
		for ($index = 0; $index < count($fila); $index++) {
			if (strpos($fila[$index], 'http') === 0) {
				$respuesta .= "<td><img src='${fila[$index]}' /></td>";
			} else {
				if (is_numeric(substr($fila[$index], 0, 8))) {
					$respuesta .= '<td>' . $fila[$index] . '</td>';
				} else {
					$respuesta .= '<td>' . ucwords(strtolower($fila[$index])) . '</td>';
				}
			}
		}
		$respuesta .= '</tr>';
	}
	return $respuesta . '</tbody>';
}

/**
 * Presenta los datos de una consulta SQL en forma de tabla a partir de unos títulos de 
 * encabezado y la propia consulta SQL.
 * @param string $nombre valorque se asigna al atributo 'class' de la tabla.
 * @param array $filas matriz que contiene los nombres de encabezados de la tabla.
 * @param string $sql consulta SQL con la que se extraen los datos de la tabla.
 * @return string cadena de texto con la estructura html de la tabla y el contenido
 * extraído de la base de datos mediante la consulta.
 * @see crearFilaCabecera, arrayAFilas.
 */
function crearCuerpoTabla($filas, $sql) {
	$respuesta = "<table class='table'>";
	$respuesta .= crearFilaCabecera($filas);
	$respuesta .= arrayAfilas($sql);
	return $respuesta . '</table>';
}

/**
 * Método utilizado para la inserción de datos en la BBDD, concretamente para tablas que
 * permitan ingresar 4 valores.
 * @param string $sql consulta SQL a realizar contra la BBDD.
 * @param string $array matriz con los parámetros para enlazar a la consulta SQL.
 * @return string texto con el contenido html indicando el éxito o error en la consulta.
 */
function consultaInsertar_4($sql, $array) {
	$conexion = conectarBD();
	$consulta = $conexion->prepare($sql);
	$consulta->bind_param($array[0], $array[1], $array[2], $array[3], $array[4]);
	if ($consulta->execute()) {
		$respuesta = getAlertElement('<strong>Registro almacenado correctamente</strong>', 'success');
	} else {
		if ($consulta->errno) {
			$respuesta = getAlertElement("<strong>El registro no pudo almacenarse</strong> $consulta->errno - $consulta->error", 'warning');
		}
	}
	$consulta->close();
	$conexion->close();
	return $respuesta;
}

/**
 * Método utilizado para la inserción de datos en la BBDD, concretamente para tablas que
 * permitan ingresar 5 valores.
 * @param string $sql consulta SQL a realizar contra la BBDD.
 * @param string $array matriz con los parámetros para enlazar a la consulta SQL.
 * @return string texto con el contenido html indicando el éxito o error en la consulta.
 */
function consultaInsertar_5($sql, $array) {
	$conexion = conectarBD();
	$consulta = $conexion->prepare($sql);
	$consulta->bind_param($array[0], $array[1], $array[2], $array[3], $array[4], $array[5]);
	if ($consulta->execute()) {
		$respuesta = getAlertElement('<strong>Registro almacenado correctamente</strong>', 'success');
	} else {
		if ($consulta->errno) {
			$respuesta = getAlertElement("<strong>El registro no pudo almacenarse</strong> $consulta->errno - $consulta->error", 'warning');
		}
	}
	$consulta->close();
	$conexion->close();
	return $respuesta;
}

/**
 * Devuelve el valor siguiente al máximo utilizado como campo identificador clave en una 
 * tabla de una BBDD.
 * @param string $campo nombre del campo del que se quiere hallar el valor máximo almacenado.
 * @param string $tabla nombre de la tabla de la BBDD a la que se realiza la consulta.
 * @return int es el valor máximo de identificador clave almacenado en la tabla más una unidad,
 * con lo que será el nuevo id para la próxima inserción de datos.
 * @see obtenerDatosConsulta.
 */
function obtenerIdMaximo($campo, $tabla) {
    try {
        $sql = "select max($campo) from $tabla";
        $datos = obtenerDatosConsulta($sql);
        $fila = $datos->fetch_array();
        return $fila[0] + 1;
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

/**
 * 
 * @param type $e
 * @param type $tipo
 * @return type
 */
function getAlertElement($e, $tipo) {
	return <<<"ALERT"
<div class='alert alert-$tipo' role='alert'>$e
	<button type='button' class='close'>&times;</button>
</div>
ALERT;
}

/**
 * 
 * @return type
 */
function getDateTime() {
	$sem = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
	$date = getdate();
	$fecha = $sem[$date['wday']] . ' - ' . twoDigit($date['mday']) . '/'
			. twoDigit($date['mon']) . '/' . $date['year'];
	$hora = twoDigit($date['hours']) . ':' . twoDigit($date['minutes'])
			. ':' . twoDigit($date['seconds']);
	return ['fecha' => $fecha, 'hora' => $hora];
}

/**
 * 
 * @param type $number
 * @return type
 */
function twoDigit($number) {
	return ($number < 10) ? ('0' . $number) : ($number);
}
