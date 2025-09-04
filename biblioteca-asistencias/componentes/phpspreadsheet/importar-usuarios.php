<?php
// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
session_start();

// Verifica si no es administrador lo regresa al login
if (!isset($_SESSION['admin'])) {
	session_destroy();
	header("location:../admin/login.php");
}

require '../../conexion.php';

require 'vendor/autoload.php'; // Ajusta la ruta según tu configuración
use PhpOffice\PhpSpreadsheet\IOFactory;

// Procesar el archivo Excel
if (isset($_FILES['file']['name'])) {
	$archivo_excel = $_FILES['file']['tmp_name'];

	// se quito la restriccion de archivo porque se accede de forma temporal con extension .tmp
	if (file_exists($archivo_excel)) {
		// El archivo existe, ahora puedes cargarlo con PhpSpreadsheet
		try {
			$spreadsheet = IOFactory::load($archivo_excel);
			$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

			// Iniciar contador de filas afectadas
			$registros_insertados = 0;
			$registros_repetidos = 0;
			//$filas_estudiantes = 0;
			
			// Iterar sobre las filas del archivo Excel a partir de la segunda fila
			foreach ($sheetData as $indice => $fila) {
				// Omitir la primera fila (encabezados)
				if ($indice === 1) {
					continue;
				}

				$numero_cuenta_excel = $fila['A'];
				$numero_cuenta_str = strtoupper($numero_cuenta_excel);
				// Eliminar espacios en blanco al principio y al final de la cadena
				$numero_cuenta = trim($numero_cuenta_str);
				$nombre = $fila['B'];
				$id_grupo = $fila['C'];
				$id_escuela = $fila['D'];

				/* Previene que se inserten numeros de control de estudiantes aunque si no se encuentra en la tabla de estudiantes al momento de realizar la carga, lo cargara como otro tipo de usuario, se tendria que registrarlo como estudiante manualmente o al momento de realizar la carga con datos de estudiantes lo eliminará de usuarios y lo asisganará a estudiantes */

				// Validar no sea estudiante (aunque puede haber estudiantes registrados como docente, investigador, etc)
				if ($id_grupo == 1) {
					continue;
				}

				// Verificar si el número de cuenta ya está registrado en la tabla usuario
				$verificar_usuario = mysqli_query($conexion, "SELECT 1 FROM usuario WHERE numero_cuenta = '$numero_cuenta'");
				if (mysqli_num_rows($verificar_usuario) > 0) {
					$registros_repetidos += 1;
					continue;
				}

				// Verificar si ya existe en estudiante
				$verificar_estudiante = mysqli_query($conexion, "SELECT 1 FROM estudiante WHERE numero_control = '$numero_cuenta'");
				if (mysqli_num_rows($verificar_estudiante) > 0) {
					$registros_repetidos += 1;
					continue; // Ya existe, no insertar
				}

				// Insertar datos en la base de datos
				$sql = "INSERT INTO usuario (numero_cuenta, nombre, id_grupo, id_escuela) VALUES ('$numero_cuenta', '$nombre', $id_grupo, $id_escuela)";
				if (mysqli_query($conexion, $sql)) {
					// Incrementar el contador de filas afectadas
					$registros_insertados += mysqli_affected_rows($conexion);
				} else {
					echo "Error al insertar: " . mysqli_error($conexion);
				}
			}
			header("Location: ../../admin/importar-usuarios.php?filas_afectadas=$registros_insertados&filas_repetidas=$registros_repetidos");
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	} else {
		// El archivo no existe, mostrar un mensaje de error
		echo "Error: El archivo no se ha subido correctamente.";
	}
} else {
	echo "No se encontro archivo";
}

mysqli_close($conexion);
?>