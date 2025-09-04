<?php
	// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
	//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
	require_once '../includes/auth.php';

	require '../conexion.php';

	if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['ncuenta']) && !empty($_POST['nombre']) && !empty($_POST['grupo_id']) && !empty($_POST['escuela_id'])) {
		// Obtiene los datos formulario
		$numero_cuenta = strtoupper($_POST['ncuenta']);
		$ncuenta = trim($numero_cuenta);
		$nombre = strtoupper($_POST['nombre']);
		$grupo_id = $_POST['grupo_id'];
		$escuela_id = $_POST['escuela_id'];
		$genero = !empty($_POST['genero']) ? "'{$_POST['genero']}'" : "NULL";

		// Verifica que el numero de cuenta no se encuentre registrado
		$sql = "SELECT * FROM
		(
			SELECT numero_control AS identificador, '0' AS tipo_usuario FROM estudiante
			UNION
			SELECT numero_cuenta AS identificador, '1' AS tipo_usuario FROM usuario
		) AS resultado
		WHERE identificador = '$ncuenta'";

		$result = mysqli_query($conexion, $sql);

		if (mysqli_num_rows($result) == 0) {
			// El valor no está registrado, realizar INSERT
			$sql_2 = "INSERT INTO usuario VALUES('$ncuenta','$nombre','$grupo_id','$escuela_id',$genero)";
			if (mysqli_query($conexion, $sql_2)) {
				// Usuario registrado correctamente
				$alert_class = "alert-success";
				$alert_message = "<strong>Éxito!</strong> Usuario <strong>$ncuenta</strong> registrado correctamente al sistema!";
			} else {
				$alert_class = "alert-danger";
				$alert_message = "<strong>Error:</strong> Ocurrió un problema al registrar al usuario.";
			}
		} else {
			// Comprueba si el numero de control pertenece a otro tipo de usuario
			$row = mysqli_fetch_assoc($result);
			$tipo_usuario = intval($row['tipo_usuario']);

			if ($tipo_usuario == 0) {
				// El usuario ya se encuentra registrado como estudiante
				$alert_class = "alert-info";
				$alert_message = "<strong>Información:</strong> El número de cuenta <strong>$ncuenta</strong> ya se encuentra registrado como estudiante en el sistema.";
			}

			if ($tipo_usuario == 1) {
				// El usuario ya se encuentra registrado
				$alert_class = "alert-danger";
				$alert_message = "<strong>Error:</strong> El número de cuenta <strong>$ncuenta</strong> ya se encuentra registrado en el sistema.";
			}
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
					<i class="fa-solid fa-user me-2"></i>Alta de Usuarios
				</div>
				<div class="card-body">
					<form id="alta-usuarios" class="mb-0" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
						<div class="mb-3">
							<label for="ncuenta" class="form-label">Número de Cuenta: <span class="text-danger">*</span></label>
							<input type="text" class="form-control text-uppercase" name="ncuenta" autofocus>
						</div>
						<div class="mb-3">
							<label for="nombre" class="form-label">Nombre Completo: <span class="text-danger">*</span></label>
							<input type="text" class="form-control text-uppercase" id="nombre" name="nombre">
						</div>
						<div class="mb-3">
							<label for="grupo_id" class="form-label">Grupo: <span class="text-danger">*</span></label>
							<select class="form-select" id="grupo_id" name="grupo_id">
								<option selected disabled>Seleccione un grupo</option>
								<?php
								if (mysqli_num_rows($resultado_grupos) > 0) {
									while ($fila = mysqli_fetch_assoc($resultado_grupos)) {
										echo "<option value='" . $fila['id'] . "'>
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
							<label for="escuela_id" class="form-label">Escuela: <span class="text-danger">*</span></label>
							<select class="form-select" id="escuela_id" name="escuela_id">
								<option selected disabled>Seleccione una escuela</option>
								<?php
								if (mysqli_num_rows($resultado_escuelas) > 0) {
									while ($fila = mysqli_fetch_assoc($resultado_escuelas)) {
										echo "<option value='" . $fila['id'] . "'>
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
						<div class="mb-3 validate">
							<div class="row g-3 align-items-center">
								<div class="col-auto">
									<label for="inputPassword6" class="col-form-label">Género: <span class="text-danger">*</span></label>
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