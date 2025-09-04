<?php
require_once '../includes/auth.php';
require '../conexion.php';

// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['ncontrol']) && !empty($_POST['nombre']) && !empty($_POST['apellidop']) && !empty($_POST['carrera_codigo']) && !empty($_POST['semestre']) && !empty($_POST['genero']) && is_numeric($_POST['semestre']) && $_POST['semestre'] >= 0 && $_POST['semestre'] <= 12) {
	// Obtiene los datos formulario
	$ncontrol = strtoupper($_POST['ncontrol']);
	$ncontrol = trim($ncontrol);
	$nombre = strtoupper($_POST['nombre']);
	$apellido_paterno = strtoupper($_POST['apellidop']);
	$apellido_materno = strtoupper($_POST['apellidom']);
	$carrera = $_POST['carrera_codigo'];
	$semestre = intval($_POST['semestre']);
	$genero = !empty($_POST['genero']) ? "'{$_POST['genero']}'" : "'F'";
	$genero = strtoupper($genero);

	// Verifica que el numero de control no se encuentre registrado en la tabla estudiante o usuario
	$sql_2 = "SELECT * FROM
		(
			SELECT numero_control AS identificador, '0' AS tipo_usuario FROM estudiante
			UNION
			SELECT numero_cuenta AS identificador, '1' AS tipo_usuario FROM usuario
		) AS resultado
		WHERE identificador = '$ncontrol'";

	$result_2 = mysqli_query($conexion, $sql_2);

	if (mysqli_num_rows($result_2) == 0) {
		// Usuario no registrado, inserta los datos en la tabla estudiante
		$sql_3 = "INSERT INTO estudiante VALUES('$ncontrol','$apellido_paterno','$apellido_materno','$nombre','$carrera','$semestre',$genero)";
		if (mysqli_query($conexion, $sql_3)) {
			// Usuario registrado correctamente
			$alert_class = "alert-success";
			$alert_message = "<strong>Éxito!</strong> Usuario <strong>$ncontrol</strong> registrado correctamente al sistema!";
		} else {
			$alert_class = "alert-danger";
			$alert_message = "<strong>Error:</strong> Ocurrió un problema al registrar al usuario.";
		}
	} else {
		// Comprueba si el numero de control pertenece a otro tipo de usuario
		$row_2 = mysqli_fetch_assoc($result_2);
		$tipo_usuario = intval($row_2['tipo_usuario']);

		if ($tipo_usuario == 0) {
			// El estudiante ya se encuentra registrado
			$alert_class = "alert-danger";
			$alert_message = "<strong>Error:</strong> el número de control <strong>$ncontrol</strong> ya se encuentra registrado en el sistema.";
		}

		if ($tipo_usuario == 1) {
			// Si estaba registrado como usuario lo actualiza a estudiante
			// Iniciar una transacción
			mysqli_autocommit($conexion, false);

			$sql = "DELETE FROM usuario WHERE numero_cuenta = '$ncontrol'";
			if (mysqli_query($conexion, $sql)) {
				// Insertar datos en la base de datos
				$sql = "INSERT INTO estudiante (numero_control, apellido_paterno, apellido_materno, nombre, codigo_carrera, semestre, genero) VALUES ('$ncontrol', '$apellido_paterno', '$apellido_materno', '$nombre', '$carrera', '$semestre', $genero)";

				// Ejecutar la consulta
				if (mysqli_query($conexion, $sql)) {
					// Estudiante removido de usuarios y almacenado como estudiante correctamente
					$alert_class = "alert-info";
					$alert_message = "<strong>Información:</strong> Se actualizó al usuario <strong>$ncontrol</strong> correctamente!";
				} else {
					$alert_class = "alert-danger";
					$alert_message = "<strong>Error:</strong> Ocurrió un problema al registrar los datos.";
				}

				// Confirmar la transacción si todas las consultas fueron exitosas
				mysqli_commit($conexion);
			} else {
				mysqli_rollback($conexion);
				$alert_class = "alert-danger";
				$alert_message = "<strong>Error:</strong> Ocurrió un problema al registrar los datos.";
			}
		}
	}
}

// Obtiene las carreras para mostrarlas en el menu
$sql = "SELECT * FROM carrera ORDER BY nombre ASC";
$result_carreras = mysqli_query($conexion, $sql);

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
	<title>Biblioteca ITSJR</title>
	<?php include '../componentes/admin-meta-tags.html'; ?>
	<link rel=" stylesheet" href="../css/bootstrap-tec.css">
	<link rel=" stylesheet" href="../css/admin-sidebar.css">
	<script src="../js/jquery-3.7.1.js"></script>
	<script src="../js/bootstrap.bundle.js"></script>
	<script src="../js/font-awesome-6.7.2.min.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
</head>

<body class="d-flex flex-column h-100">
	<?php require '../componentes/admin-navbar.html' ?>
	<?php require '../componentes/admin-sidebar.html' ?>
	<main class="flex-shrink-0">
		<?php if (isset($alert_class) && isset($alert_message)) { ?>
			<div class="col-12 col-xl-6 mx-auto">
				<div class="alert <?php echo $alert_class; ?> alert-dismissible fade show" role="alert">
					<?php echo $alert_message; ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			</div>
		<?php } ?>
		<div class="col-12 col-xl-6 mx-auto">
			<div class="card">
				<div class="card-header">
					<i class="fa-solid fa-user me-2"></i>Alta de Estudiantes
				</div>
				<div class="card-body">
					<form id="alta-estudiantes" class="mb-0" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
						<div class="mb-3">
							<label for="ncontrol" class="form-label">Número de Control: <span class="text-danger">*</span></label>
							<input type="text" class="form-control text-uppercase" name="ncontrol" autofocus>
						</div>
						<div class="mb-3">
							<label for="nombre" class="form-label">Nombre(s): <span class="text-danger">*</span></label>
							<input type="text" class="form-control text-uppercase" id="nombre" name="nombre">
						</div>
						<div class="mb-3">
							<label for="apellidop" class="form-label">Apellido Paterno: <span class="text-danger">*</span></label>
							<input type="text" class="form-control text-uppercase" id="apellidop" name="apellidop">
						</div>
						<div class="mb-3">
							<label for="apellidom" class="form-label">Apellido Materno:</label>
							<input type="text" class="form-control text-uppercase" id="apellidom" name="apellidom">
						</div>
						<div class="mb-3">
							<label for="carrera_codigo" class="form-label">Carrera: <span class="text-danger">*</span></label>
							<select class="form-select" id="carrera_codigo" name="carrera_codigo">
								<option selected disabled>Seleccione una carrera</option>
								<?php
								if (mysqli_num_rows($result_carreras) > 0) {
									while ($fila = mysqli_fetch_assoc($result_carreras)) {
										echo "<option value='" . $fila['codigo'] . "'>
													" . $fila['nombre'] . "
												</option>";
									}
								} else {
									echo "<option disabled value='0'>
												No se encontraron registros
											</option>";
								}
								?>
							</select>
						</div>
						<div class="mb-3">
							<label for="semestre" class="form-label">Semestre: <span class="text-danger">*</span></label>
							<input type="number" min="1" max="12" class="form-control text-uppercase" id="semestre" name="semestre">
						</div>
						<div class="mb-3 validate">
							<div class="row g-3 align-items-center">
								<div class="col-auto">
									<label for="genero" class="col-form-label">Género: <span class="text-danger">*</span></label>
								</div>
								<div class="col-auto">
									<div class="form-check">
										<input class="form-check-input" type="radio" name="genero" id="option1" value="M">
										<label class="form-check-label" for="option1">
											Masculino
										</label>
									</div>
								</div>
								<div class="col-auto">
									<div class="form-check">
										<input class="form-check-input" type="radio" name="genero" id="option2" value="F">
										<label class="form-check-label" for="option2">
											Femenino
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-end">
							<button type="submit" class="btn btn-primary">Guardar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</main>
	<?php require '../componentes/admin-footer.html'; ?>
	<script src="../js/admin-sidebar.js"></script>
	<script src="../js/bootstrap-theme.js"></script>
	<script src="../js/jquery.validate.functions.js"></script>
</body>

</html>