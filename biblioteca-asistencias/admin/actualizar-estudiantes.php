<?php
// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
require_once '../includes/auth.php';
require '../conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	############# Comienza-update-ncontrol ###############
	if (isset($_POST['update_ncontrol']) && !empty($_POST['nuevo_numero_control'])) {
		// Obtiene los datos formulario
		$nuevo_numero_control = strtoupper($_POST['nuevo_numero_control']);
		$anterior_numero_control = strtoupper($_POST['anterior_numero_control']);

		$anterior_numero_control = trim($anterior_numero_control);
		$nuevo_numero_control = trim($nuevo_numero_control);

		// Verifica si el nuevo numero de cuenta ya esta registrado
		$sql_2 = "SELECT * FROM
			(
				SELECT numero_control AS identificador FROM estudiante
				UNION
				SELECT numero_cuenta AS identificador FROM usuario
			) AS resultado
			WHERE identificador = '$nuevo_numero_control'";
		$result_2 = mysqli_query($conexion, $sql_2);

		if (mysqli_num_rows($result_2) == 0) {
			// Si el nuevo numero de control no esta registrado lo actualiza
			mysqli_autocommit($conexion, false);

			$sql_3 = "UPDATE estudiante SET numero_control='$nuevo_numero_control' WHERE numero_control='$anterior_numero_control'";
			if (mysqli_query($conexion, $sql_3)) {
				// Registro actualizado correctamente

				// Actualiza tambien el numero de control en la tabla de asistencias
				$sql_5 = "UPDATE asistencia SET identificador='$nuevo_numero_control' WHERE identificador='$anterior_numero_control'";
				mysqli_query($conexion, $sql_5);

				mysqli_commit($conexion);

				// Vuelve a obtener los datos del usuario para poder vusualizarlos despues de haber actualizado su numero de control
				$sql = "SELECT * FROM estudiante WHERE numero_control = '$nuevo_numero_control'";
				$result = mysqli_query($conexion, $sql);

				// Verificar si se encontró el usuario
				if (mysqli_num_rows($result) == 1) {
					// Obtener los datos del usuario
					$estudiante = mysqli_fetch_assoc($result);
				} else {
					// Si no se encuentra registrado el numero de control lo regresa a los usuarios registrados
					header("location: estudiantes.php");
				}

				$alert_class = "alert-success";
				$alert_message = "<strong>Éxito!</strong> Se actualizó el número de control correctamente!";
			} else {
				mysqli_rollback($conexion);
			}
		} else {
			$alert_class = "alert-danger";
			$alert_message = "<strong>Error:</strong> El número de control <strong>$nuevo_numero_control</strong> ya se encuentra registrado en el sistema.";

			// Vuelve a obtener los datos del usuario para poder vusualizarlos
			$sql = "SELECT * FROM estudiante WHERE numero_control = '$anterior_numero_control'";
			$result = mysqli_query($conexion, $sql);
			// Verificar si se encontró el usuario
			if (mysqli_num_rows($result) == 1) {
				// Obtener los datos del usuario
				$estudiante = mysqli_fetch_assoc($result);
			}
		}
	} else {
		unset($_POST['update_ncontrol']);
	}
	############# Comienza-update-ncontrol ###############

	############# Comienza-update-datos ###############
	if (isset($_POST['actualizar_datos']) && !empty($_POST['ncontrol']) && !empty($_POST['nombre']) && !empty($_POST['apellidop']) && !empty($_POST['carrera_codigo']) && !empty($_POST['semestre']) && !empty($_POST['genero']) && is_numeric($_POST['semestre']) && $_POST['semestre'] >= 0 && $_POST['semestre'] <= 12) {
		// Obtiene los datos formulario
		$ncontrol = strtoupper($_POST['ncontrol']);
		$nombre = strtoupper($_POST['nombre']);
		$apellidop = strtoupper($_POST['apellidop']);
		$apellidom = strtoupper($_POST['apellidom']);
		$codigo_carrera = $_POST['carrera_codigo'];
		$semestre = intval($_POST['semestre']);
		$genero = strtoupper($_POST['genero']);

		// update a los datos en la tabla de estudiantes
		$sql = "UPDATE estudiante
			SET apellido_paterno='$apellidop',apellido_materno='$apellidom',nombre='$nombre',codigo_carrera='$codigo_carrera',semestre='$semestre',genero='$genero' 
			WHERE numero_control='$ncontrol'";
		if (mysqli_query($conexion, $sql)) {
			$alert_class = "alert-success";
			$alert_message = "<strong>Éxito!</strong> Datos actualizados correctamente!";
		} else {
			$alert_class = "alert-danger";
			$alert_message = "<strong>Error:</strong> Ocurrió un problema al actualizar los datos del usuario.";
		}

		$sql_2 = "SELECT * FROM estudiante WHERE numero_control = '$ncontrol'";
		$result_2 = mysqli_query($conexion, $sql_2);
		// Verificar si se encontró el usuario
		if (mysqli_num_rows($result_2) == 1) {
			// Obtener los datos del usuario
			$estudiante = mysqli_fetch_assoc($result_2);
		}
	} else {
		unset($_POST['actualizar_datos']);
	}
	############# Comienza-update-datos ###############
}

// Obtiene los datos del estudiante por su numero de control
if (isset($_POST['nctrl']) && !empty($_POST['nctrl'])) {
	//obtiene el nctrl del usuario
	$nctrl = $_POST['nctrl'];

	$sql = "SELECT * FROM estudiante WHERE numero_control = '$nctrl'";
	$result = mysqli_query($conexion, $sql);
	// Verificar si se encontró el usuario
	if (mysqli_num_rows($result) == 1) {
		// Obtener los datos del usuario
		$estudiante = mysqli_fetch_assoc($result);
	} else {
		// Si no se encuentra registrado el numero de cuenta lo regresa a los usuarios registrados
		header("location: estudiantes.php");
	}
}

// Obtiene las carreras para mostrarlas en el menu
$sql_2 = "SELECT * FROM carrera ORDER BY nombre ASC";
$resultado_carreras = mysqli_query($conexion, $sql_2);

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
		<?php if (isset($_POST['update_ncontrol'])) { ?>
			<div class="col-12 col-xl-6 mx-auto">
				<div class="alert <?php echo $alert_class; ?> alert-dismissible fade show" role="alert">
					<?php echo $alert_message; ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			</div>
		<?php } ?>
		<?php if (isset($_POST['actualizar_datos'])) { ?>
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
					<i class="fa-solid fa-user me-2"></i>Actualizar Información
				</div>
				<div class="card-body">
					<?php if (isset($estudiante)): ?>
						<form id="update-estudiantes" class="mb-0" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
							<div class="mb-3">
								<div class="row g-3 align-items-center">
									<div class="col-auto">
										<label for="ncontrol" class="col-form-label">Número de Control: <span class="text-danger">*</span></label>
									</div>
									<div class="col-auto">
										<input type="text" class="form-control text-uppercase" name="ncontrol" value="<?php echo $estudiante['numero_control']; ?>" readonly>
									</div>
									<div class="col-auto">
										<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalActualizarNC">
											Actualizar Número de Control
										</button>
									</div>
								</div>
							</div>
							<div class="mb-3">
								<label for="nombre" class="form-label">Nombre(s): <span class="text-danger">*</span></label>
								<input type="text" class="form-control text-uppercase" id="nombre" name="nombre" value="<?php echo $estudiante['nombre']; ?>">
							</div>
							<div class="mb-3">
								<label for="apellidop" class="form-label">Apellido Paterno: <span class="text-danger">*</span></label>
								<input type="text" class="form-control text-uppercase" id="apellidop" name="apellidop" value="<?php echo $estudiante['apellido_paterno']; ?>">
							</div>
							<div class="mb-3">
								<label for="apellidom" class="form-label">Apellido Materno:</label>
								<input type="text" class="form-control text-uppercase" id="apellidom" name="apellidom" value="<?php echo $estudiante['apellido_materno']; ?>">
							</div>
							<div class="mb-3">
								<label for="carrera_codigo" class="form-label">Carrera: <span class="text-danger">*</span></label>
								<select class="form-select" id="carrera_codigo" name="carrera_codigo">
									<option disabled>Seleccione una carrera</option>
									<?php
									if (mysqli_num_rows($resultado_carreras) > 0) {
										while ($carrera = mysqli_fetch_assoc($resultado_carreras)) {
											$selected = ($carrera['codigo'] == $estudiante['codigo_carrera']) ? 'selected' : '';
											echo "<option value='{$carrera['codigo']}' $selected>{$carrera['nombre']}</option>";
										}
									} else {
										echo "<option disabled value='0'>No se encontraron registros</option>";
									}
									?>
								</select>
							</div>
							<div class="mb-3">
								<label for="semestre" class="form-label">Semestre: <span class="text-danger">*</span></label>
								<input type="number" min="0" max="12" class="form-control text-uppercase" id="semestre" name="semestre" value="<?php echo $estudiante['semestre']; ?>">
							</div>
							<div class="mb-3 validate">
								<div class="row g-3 align-items-center">
									<div class="col-auto">
										<label for="genero" class="col-form-label">Género: <span class="text-danger">*</span></label>
									</div>
									<div class="col-auto">
										<div class="form-check">
											<input class="form-check-input" type="radio" name="genero" id="option1" value="M" <?php echo ($estudiante['genero'] == 'M') ? 'checked' : ''; ?>>
											<label class="form-check-label" for="option1">
												Masculino
											</label>
										</div>
									</div>
									<div class="col-auto">
										<div class="form-check">
											<input class="form-check-input" type="radio" name="genero" id="option2" value="F" <?php echo ($estudiante['genero'] == 'F') ? 'checked' : ''; ?>>
											<label class="form-check-label" for="option2">
												Femenino
											</label>
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="actualizar_datos">
							<div class="d-flex justify-content-between">
								<a href="estudiantes.php" class="btn btn-danger">Cancelar</a>
								<button type="submit" class="btn btn-primary">Guardar</button>
							</div>
						</form>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</main>
	<!-- Modal -->
	<div class="modal fade" id="modalActualizarNC" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Actualizar Número de Control</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="update-estudiantes-ncontrol" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
						<div class="mb-3">
							<label for="nuevo_numero_control" class="form-label">Nuevo Número de Control: <span class="text-danger">*</span></label>
							<input type="text" class="form-control text-uppercase" name="nuevo_numero_control" autofocus>
							<input type="hidden" name="anterior_numero_control" value="<?php echo $estudiante['numero_control']; ?>">
							<input type="hidden" name="update_ncontrol">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
							<button type="submit" class="btn btn-primary">Actualizar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php require '../componentes/admin-footer.html'; ?>
	<script src="../js/admin-sidebar.js"></script>
	<script src="../js/bootstrap-theme.js"></script>
	<script src="../js/jquery.validate.functions.js"></script>
</body>

</html>