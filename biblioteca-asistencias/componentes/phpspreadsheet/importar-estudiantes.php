<?php
// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
require_once '../../includes/auth.php';

require '../../conexion.php';

require 'vendor/autoload.php'; // Ajusta la ruta según tu configuración
use PhpOffice\PhpSpreadsheet\IOFactory;

// Procesar el archivo Excel
if (isset($_FILES['file']['name'])) {
	$archivo_excel = $_FILES['file']['tmp_name'];

	// Verificar si el tipo de archivo es un archivo Excel permitido
	$extensiones_permitidas = array('xlsx', 'xls');
	$extension = pathinfo($archivo_excel, PATHINFO_EXTENSION);

	// se quito la restriccion de archivo porque se accede de forma temporal con extension .tmp
	if (file_exists($archivo_excel)) {
		// El archivo existe, ahora puedes cargarlo con PhpSpreadsheet
		try {
			$spreadsheet = IOFactory::load($archivo_excel);
			$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

			// Iniciar contador de filas afectadas
			$registros_insertados = 0;
			$registros_repetidos = 0;
			$registros_actualizados = 0;

			// Iterar sobre las filas del archivo Excel a partir de la segunda fila
			foreach ($sheetData as $indice => $fila) {
				// Omitir la primera fila (encabezados)
				if ($indice === 1) {
					continue;
				}

				$identificador = strtoupper($fila['A']);
				$identificador = trim($identificador);
				$apellido_paterno = strtoupper($fila['B']);
				$apellido_materno = strtoupper($fila['C']);
				$nombre = strtoupper($fila['D']);
				$codigo_carrera = strtoupper($fila['E']);
				$semestre = intval($fila['F']);
				$genero = !empty($fila['G']) ? "'{$fila['G']}'" : "'F'";
				$genero = strtoupper($genero);

				// Validar semestre entre 0 y 12
				if ($semestre < 0 || $semestre > 12) {
					continue;
				}

				// Verificar si ya existe en estudiante
				$verificar_estudiante = mysqli_query($conexion, "SELECT 1 FROM estudiante WHERE numero_control = '$identificador'");
				if (mysqli_num_rows($verificar_estudiante) > 0) {
					$registros_repetidos += 1;
					continue; // Ya existe, no insertar
				}

				// Verificar si está en usuario
				$verificar_usuario = mysqli_query($conexion, "SELECT 1 FROM usuario WHERE numero_cuenta = '$identificador'");
				if (mysqli_num_rows($verificar_usuario) > 0) {
					// Estudiante registrado como usuario, lo elimina de la tabla usuario para colocarlo en estudiante
					mysqli_query($conexion, "DELETE FROM usuario WHERE numero_cuenta = '$identificador'");
					$registros_actualizados += 1;
				}

				// Insertar estudiante
				$sql = "INSERT INTO estudiante (numero_control, apellido_paterno, apellido_materno, nombre, codigo_carrera, semestre, genero) VALUES ('$identificador', '$apellido_paterno', '$apellido_materno', '$nombre', '$codigo_carrera', '$semestre', $genero)";
				mysqli_query($conexion, $sql);
				$registros_insertados += 1;
			}
			header("Location: ../../admin/importar-estudiantes.php?filas_afectadas=$registros_insertados&filas_repetidas=$registros_repetidos&filas_actualizadas=$registros_actualizados");
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	} else {
		// El archivo no existe, mostrar un mensaje de error
		echo "Error: El archivo no se ha subido correctamente.";
	}
} else {
	echo "Error: No se encontró el archivo";
}

mysqli_close($conexion);
