<?php
	// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
	//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
	require_once '../../includes/auth.php';
	require '../../conexion.php';
	require '../../includes/fechas.php';
	
	require 'vendor/autoload.php'; // Asegúrate de que la ruta sea correcta según tu configuración

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

	// Obtiene las fechas desde la tbala reportes fechas
	
	$fecha_desde = $_GET['fecha_desde'] ?? null;
	$fecha_hasta = $_GET['fecha_hasta'] ?? null;

	$sql_2 = "SELECT * FROM asistencia WHERE DATE(fecha_hora) BETWEEN '$fecha_desde' AND '$fecha_hasta'";
	$result_2 = mysqli_query($conexion,$sql_2);

	// Verificar si la consulta fue exitosa
	if (!$result_2) {
		die("Error en la consulta: " . $conexion->error);
	}

	// Obtener los usuarios como un array asociativo
	$registros = $result_2->fetch_all(MYSQLI_ASSOC);

	// Crear un nuevo objeto Spreadsheet
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	// Añadir encabezados de columna
	$sheet->setCellValue('A1', 'identificador');
	$sheet->setCellValue('B1', 'nombre');
	$sheet->setCellValue('C1', 'carrera_area_dependencia');
	$sheet->setCellValue('D1', 'semestre');
	$sheet->setCellValue('E1', 'genero');
	$sheet->setCellValue('F1', 'fecha');
	$sheet->setCellValue('G1', 'hora');

	// Iterar sobre los usuarios y agregar los datos a las celdas
	foreach ($registros as $index => $registro) {
		$row = $index + 2; // Empezar desde la segunda fila
		$sheet->setCellValue('A' . $row, $registro['identificador']);
		$sheet->setCellValue('B' . $row, $registro['nombre_completo']);
		$sheet->setCellValue('C' . $row, $registro['carrera_area']);
		$sheet->setCellValue('D' . $row, $registro['semestre']);
		$sheet->setCellValue('E' . $row, $registro['genero']);

		// Fecha desde la base de datos en formato MySQL "2025-04-19 20:29:52"
		#$fecha_desde_db = $registro['fecha_hora'];
		$fecha = fecha_excel_campo_fecha($registro['fecha_hora']);
		$sheet->setCellValue('F' . $row, $fecha);

		// Fecha desde la base de datos en formato MySQL "2025-04-19 20:29:52"
		#$fecha_sql_hora = $registro['fecha_hora'];
		$hora = fecha_excel_campo_hora($registro['fecha_hora']);
		$sheet->setCellValue('G' . $row, $hora);
	}

	// Guardar el archivo Excel con nombre reporte-asistencias-dd-Mes-aaaa
	#date_default_timezone_set('America/Mexico_City');
	#$fecha_php = str_replace($meses_en_ingles, $meses_en_espanol, date("d-F-Y"));
	$fecha_archivo_excel = fecha_archivo_excel();
	$writer = new Xlsx($spreadsheet);
	$nombre_archivo = 'reporte-asistencias-'.$fecha_archivo_excel.'.xlsx';
	$writer->save($nombre_archivo);

	// Establecer las cabeceras para forzar la descarga del archivo
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($nombre_archivo));
	readfile($nombre_archivo);

	// Eliminar el archivo después de la descarga
	unlink($nombre_archivo);

	mysqli_close($conexion);
?>