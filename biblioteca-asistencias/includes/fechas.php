<?php
function fecha_actual() {
	// Función para transformar fecha actual a 19-Abril-2025 08:57 PM
	// Establecer la zona horaria a la Ciudad de México
	date_default_timezone_set('America/Mexico_City');

	// Obtener la fecha y hora actual en inglés
	$fecha_actual_en_ingles = date("d/F/Y h:i A");

	// Array de nombres de meses en inglés y sus equivalentes en español
	$meses_en_ingles = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	$meses_en_espanol = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

	// Reemplazar el nombre del mes en inglés por su equivalente en español
	$fecha_actual = str_replace($meses_en_ingles, $meses_en_espanol, $fecha_actual_en_ingles);

	// Mostrar la fecha y hora actual en español
	return $fecha_actual; // 19/Abril/2025 02:57 PM
}

function fecha_asistencias($fecha_hora_db) {
	// Función para transformar fecha de MySQL 2025-04-19 01:12:34 a 19/Abril/2025 01:12 PM para la pantalla asistencias
	// Obtener la fecha y hora en inglés con el formato de la base de datos
	$fecha_en_ingles = date("d/F/Y h:i A", strtotime($fecha_hora_db));

	// Array de nombres de meses en inglés y sus equivalentes en español
	$meses_en_ingles = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	$meses_en_espanol = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

	// Reemplazar el nombre del mes en inglés por su equivalente en español
	$fecha_hora_formateada = str_replace($meses_en_ingles, $meses_en_espanol, $fecha_en_ingles);

	// Mostrar la fecha y hora actual en español
	return $fecha_hora_formateada; // 19/Abril/2025 02:57 PM
}

function fecha_excel_campo_fecha($fecha_hora_db) {
	// Función para transformar fecha de formato MySQL 2025-04-19 01:12:34 a 19/Abril/2025

	// Obtener la fecha y hora desde la base de datos en inglés
	$fecha_actual_en_ingles = date("d/F/Y", strtotime($fecha_hora_db));

	// Array de nombres de meses en inglés y sus equivalentes en español
	$meses_en_ingles = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	$meses_en_espanol = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

	// Reemplazar el nombre del mes en inglés por su equivalente en español
	$fecha_formateada = str_replace($meses_en_ingles, $meses_en_espanol, $fecha_actual_en_ingles);
	return $fecha_formateada;
}

function fecha_excel_campo_hora($fecha_hora_db) {
	// Función para transformar fecha de formato MySQL 2025-04-19 01:12:34 a 01:12 PM
	
	// Convertir formato de fecha "08:29 PM"
	if (!empty($fecha_hora_db)) {
		$hora_formateada = date("h:i A", strtotime($fecha_hora_db));
	} else {
		$hora_formateada = '';
	}

	return $hora_formateada;
}

function fecha_archivo_excel() {
	// Función para transformar fecha actual a 19-Abril-2025 para el nombre del archivo Excel
	$fecha_actual = fecha_actual(); // 19-Abril-2025 08:57 PM (string)

	// Divide la fecha y hora usando espacio como separador
	$partes = explode(' ', $fecha_actual);

	// Cambia los separadores de fecha de / a -
	$fecha_formateada = str_replace('/', '-', $partes[0]);
	return $fecha_formateada;
}
?>