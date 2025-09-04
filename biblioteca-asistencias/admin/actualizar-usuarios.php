<?php
	// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
	//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
	require_once '../includes/auth.php';
	require '../conexion.php';

	// Verificar si se ha enviado el formulario
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Procesar los datos del formulario y realizar la actualización en la base de datos

		############# Comienza-update-ncuenta ###############
		if (isset($_POST['update_ncuenta']) && !empty($_POST['nncuenta'])) {
			// Obtiene los datos formulario
			$nncuenta = strtoupper($_POST['nncuenta']);
			$ancuenta = strtoupper($_POST['ancuenta']);
			$ancuenta = trim($ancuenta);
			$nncuenta = trim($nncuenta);

			// Verifica si el nuevo numero de cuenta ya esta registrado
			$sql_2 = "SELECT * FROM
			(
				SELECT numero_control AS identificador, '0' AS tipo_usuario FROM estudiante
				UNION
				SELECT numero_cuenta AS identificador, '1' AS tipo_usuario FROM usuario
			) AS resultado
			WHERE identificador = '$nncuenta'";
			$result_2 = mysqli_query($conexion, $sql_2);

			if (mysqli_num_rows($result_2) == 0) {
				// Si el nuevo numero de cuenta no esta registrado lo registra
				$sql_3 = "UPDATE usuario SET numero_cuenta='$nncuenta' WHERE numero_cuenta='$ancuenta'";
				if (mysqli_query($conexion, $sql_3)) {
					// Actualiza tambien el numero de cuenta en la tabla de asistencias
					$sql_5 = "UPDATE asistencia SET identificador='$nncuenta' WHERE identificador='$ancuenta'";
					mysqli_query($conexion, $sql_5);

					// Vuelve a obtener los datos del usuario para poder vusualizarlos despues de haber actualizado su numero de cuenta
					$sql = "SELECT * FROM usuario WHERE numero_cuenta = '$nncuenta'";
					$result = mysqli_query($conexion, $sql);

					// Verificar si se encontró el usuario
					if (mysqli_num_rows($result) == 1) {
						// Obtener los datos del usuario
						$usuario = mysqli_fetch_assoc($result);
					} else {
						// Si no se encuentra registrado el numero de cuenta lo regresa a los usuarios registrados
						header("location: usuarios.php");
					}
					
					$alert_class = "alert-success";
					$alert_message = "<strong>Éxito!</strong> Se actualizó el número de cuenta correctamente!";
				} else {
					$alert_class = "alert-danger";
					$alert_message = "<strong>Error:</strong> Ocurrió un problema al actualizar el número de cuenta del usuario.";
				}
			} else {
				// Vuelve a obtener los datos del usuario para poder vusualizarlos
				$sql = "SELECT * FROM usuario WHERE numero_cuenta = '$ancuenta'";
				$result = mysqli_query($conexion, $sql);
				// Verificar si se encontró el usuario
				if (mysqli_num_rows($result) == 1) {
					// Obtener los datos del usuario
					$usuario = mysqli_fetch_assoc($result);
				}

				$row_2 = mysqli_fetch_assoc($result_2);
				$tipo_usuario = intval($row_2['tipo_usuario']);

				if ($tipo_usuario == 0) {
					// El usuario ya se encuentra registrado como estudiante
					$alert_class = "alert-info";
					$alert_message = "<strong>Información:</strong> El número de cuenta <strong>$nncuenta</strong> ya se encuentra registrado como estudiante en el sistema.";
				}

				if ($tipo_usuario == 1) {
					// El usuario ya se encuentra registrado
					$alert_class = "alert-danger";
					$alert_message = "<strong>Error:</strong> El número de cuenta <strong>$nncuenta</strong> ya se encuentra registrado en el sistema.";
				}
			}
		} else {
			unset($_POST['update_ncuenta']);
		}
		############# Comienza-update-ncuenta ###############

		############### Actualizar Informacion ###############
		if (isset($_POST['actualizar_datos']) && !empty($_POST['ncuenta']) && !empty($_POST['nombre']) && !empty($_POST['id_grupo']) && !empty($_POST['id_escuela'])) {
			$ncuenta = strtoupper($_POST['ncuenta']);
			$nombre = strtoupper($_POST['nombre']);
			$id_grupo = $_POST['id_grupo'];
			$id_escuela = $_POST['id_escuela'];
			$genero = !empty($_POST['genero']) ? "'{$_POST['genero']}'" : "NULL";

			$sql = "UPDATE usuario SET nombre='$nombre',id_grupo='$id_grupo',id_escuela='$id_escuela',genero=$genero WHERE numero_cuenta='$ncuenta'";
			
			if (mysqli_query($conexion, $sql)) {
				$alert_class = "alert-success";
				$alert_message = "<strong>Éxito!</strong> Datos actualizados correctamente!";
			} else {
				$alert_class = "alert-danger";
				$alert_message = "<strong>Error!</strong> Ocurrió un problema al acutalizar los datos del usuario.";
			}

			// Vuelve a obtener los datos del usuario para poder vusualizarlos
			$sql = "SELECT * FROM usuario WHERE numero_cuenta = '$ncuenta'";
			$result = mysqli_query($conexion, $sql);
			// Verificar si se encontró el usuario
			if (mysqli_num_rows($result) == 1) {
				// Obtener los datos del usuario
				$usuario = mysqli_fetch_assoc($result);
			}
		} else {
			unset($_POST['actualizar_datos']);
		}
		############### Actualizar Informacion ###############
	}

	// Obtiene los datos del usuario por su numero de cuenta
	if (isset($_POST['ncuenta']) && !empty($_POST['ncuenta'])) {
		//obtiene el ncuenta del usuario
		$ncuenta = $_POST['ncuenta'];

		$sql = "SELECT * FROM usuario WHERE numero_cuenta = '$ncuenta'";
		$result = mysqli_query($conexion, $sql);

		// Verificar si se encontró el usuario
		if (mysqli_num_rows($result) == 1) {
			// Obtener los datos del usuario
			$usuario = mysqli_fetch_assoc($result);
		} else {
			// Si no se encuentra registrado el numero de cuenta lo regresa a los usuarios registrados
			header("location: usuarios.php");
		}
	}

	// Obtiene las areas para mostrarlas en el menu
	$sql_4 = "SELECT * FROM grupo WHERE id != 1 ORDER BY nombre ASC";
	$resultado_grupos = mysqli_query($conexion, $sql_4);

	$sql_5 = "SELECT * FROM escuela ORDER BY nombre ASC";
	$resultado_escuelas = mysqli_query($conexion, $sql_5);

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
		<?php if (isset($_POST['update_ncuenta'])) { ?>
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
					<i class="fa-solid fa-user me-2"></i>Actualizar Información del Usuario
				</div>
				<div class="card-body">
					<?php if (isset($usuario)): ?>
						<form id="update-usuarios" class="mb-0" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
							<div class="mb-3">
								<div class="row g-3 align-items-center">
									<div class="col-auto">
										<label for="ncuenta" class="col-form-label">Número de Cuenta: <span class="text-danger">*</span></label>
									</div>
									<div class="col-auto">
										<input type="text" class="form-control text-uppercase" name="ncuenta" value="<?php echo $usuario['numero_cuenta']; ?>" readonly>
									</div>
									<div class="col-auto">
										<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalActualizarNC">
											Actualizar Número de Cuenta
										</button>
									</div>
								</div>
							</div>
							<div class="mb-3">
								<label for="nombre" class="form-label">Nombre Completo: <span class="text-danger">*</span></label>
								<input type="text" class="form-control text-uppercase" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>">
							</div>
							<div class="mb-3">
								<label for="id_grupo" class="form-label">Grupo: <span class="text-danger">*</span></label>
								<select class="form-select" id="id_grupo" name="id_grupo">
									<option disabled>Seleccione una grupo</option>
									<?php 
										if (mysqli_num_rows($resultado_grupos) > 0) {
											while ($grupo = mysqli_fetch_assoc($resultado_grupos)) {
												$selected = ($grupo['id'] == $usuario['id_grupo']) ? 'selected' : '';
												echo "<option value='{$grupo['id']}' $selected>{$grupo['nombre']}</option>";
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
								<label for="id_escuela" class="form-label">Escuela: <span class="text-danger">*</span></label>
								<select class="form-select" id="id_escuela" name="id_escuela">
									<option disabled>Seleccione una escuela</option>
									<?php 
										if (mysqli_num_rows($resultado_escuelas) > 0) {
											while ($escuela = mysqli_fetch_assoc($resultado_escuelas)) {
												$selected = ($escuela['id'] == $usuario['id_escuela']) ? 'selected' : '';
												echo "<option value='{$escuela['id']}' $selected>{$escuela['nombre']}</option>";
											}
										} else {
											echo "<option disabled value='0'>No se encontraron registros</option>";
										}
									?>
								</select>
							</div>
							<div class="mb-3 validate">
								<div class="row g-3 align-items-center">
									<div class="col-auto">
										<label for="genero" class="col-form-label">Género: <span class="text-danger">*</span></label>
									</div>
									<div class="col-auto">
										<div class="form-check">
											<input class="form-check-input" type="radio" name="genero" id="option1" value="M" <?php echo ($usuario['genero'] == 'M') ? 'checked' : ''; ?>>
											<label class="form-check-label" for="option1">
												Masculino
											</label>
										</div>
									</div>
									<div class="col-auto">
										<div class="form-check">
											<input class="form-check-input" type="radio" name="genero" id="option2" value="F" <?php echo ($usuario['genero'] == 'F') ? 'checked' : ''; ?>>
											<label class="form-check-label" for="option2">
												Femenino
											</label>
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="actualizar_datos">
							<div class="d-flex justify-content-between">
								<a href="usuarios.php" class="btn btn-danger">Cancelar</a>
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
					<h1 class="modal-title fs-5" id="exampleModalLabel">Actualizar Número de Cuenta</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="update-usuarios-ncuenta" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
						<div class="mb-3">
							<label for="nncuenta" class="form-label">Nuevo número de cuenta: <span class="text-danger">*</span></label>
							<input type="text" class="form-control text-uppercase" name="nncuenta">
							<input type="hidden" name="ancuenta" value="<?php echo $usuario['numero_cuenta']; ?>">
							<input type="hidden" name="update_ncuenta">
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