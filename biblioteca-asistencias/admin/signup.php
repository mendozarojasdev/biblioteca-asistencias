<?php
// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
include '../includes/fechas.php';
$fecha_actual = fecha_actual();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	require '../conexion.php';

	if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['cpass']) && !empty($_POST['user']) && !empty($_POST['pass']) && !empty($_POST['cpass']) && $_POST['pass'] === $_POST['cpass']) {
		$admin1 = $_POST['user'];
		$pass1 = $_POST['pass'];
		// Cifrar pass
		$password = password_hash($pass1, PASSWORD_BCRYPT);

		// Comprueba si ya existe el nombre de usuario
		$sql = "SELECT * FROM administrador WHERE usuario='$admin1'";
		$result = mysqli_query($conexion, $sql);

		if (mysqli_num_rows($result) == 0) {
			// El valor no está registrado, realizar el INSERT
			$sql_2 = "INSERT INTO administrador (usuario, password_hash) VALUES('$admin1','$password')";
			mysqli_query($conexion, $sql_2);
			$affected_rows = mysqli_affected_rows($conexion);

			if ($affected_rows > 0) {
				$alert_class = "alert-success";
				$alert_message = "<strong>Éxito!</strong> El nuevo usuario <strong>$admin1</strong> ha sido creado correctamente!";
			} else {
				$alert_class = "alert-danger";
				$alert_message = "<strong>Error:</strong> Ocurrió un problema al insertar el registro.";
			}
		} else {
			$alert_class = "alert-danger";
			$alert_message = "<strong>Error:</strong> El usuario <strong>$admin1</strong> ya se encuentra registrado, por favor ingresa un nombre de usuario diferente.";
		}
	} else {
		// Limpia variables para que no entren en la validación
		unset($_POST['user']);
		unset($_POST['pass']);
		unset($_POST['cpass']);
	}
	mysqli_close($conexion);
}
?>
<html lang="es" class="h-100">

<head>
	<meta charset="UTF-8">
	<title>Biblioteca ITSJR</title>
	<?php include '../componentes/admin-meta-tags.html'; ?>
	<link rel="stylesheet" href="../css/bootstrap-tec.css" />
	<script src="../js/jquery-3.7.1.js"></script>
	<script src="../js/bootstrap.bundle.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
</head>

<body class="d-flex flex-column h-100">
	<nav class="navbar bg-primary" data-bs-theme="dark">
		<div class="container-fluid">
			<span class="navbar-brand d-none d-sm-block"><img src="../img/logos/logo-b.png" alt="" width="72px" height="36px"></span>
			<a class="navbar-brand mb-0 h1" href="../index.php">Biblioteca ITSJR</a>
			<span class="navbar-brand"><img src="../img/logos/logo2-b.png" alt="" width="36px" height="36px"></span>
		</div>
	</nav>
	<main class="flex-shrink-0">
		<div class="container-fluid">
			<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mx-auto">
				<form id="signup" class="mb-0 py-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
					<div class="text-center mb-4">
						<img class="mb-4" src="../img/logos/logo2.png" alt="" width="72" height="72">
						<h3 class="mb-3 font-weight-normal">Usuario Principal</h3>
					</div>
					<?php if (isset($_POST['user']) && isset($_POST['pass'])) { ?>
						<div class="alert <?php echo $alert_class; ?> alert-dismissible fade show" role="alert">
							<?php echo $alert_message; ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					<?php } ?>
					<div class="alert alert-primary" role="alert">
						Hora del servidor: <strong><?php echo $fecha_actual; ?></strong>
					</div>
					<div class="alert alert-warning" role="alert">
						¡<strong>Eliminar esta página</strong> después de crear los usuarios necesarios!
					</div>
					<div class="mb-3">
						<label for="user" class="form-label">Nombre de Usuario: <span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="user" name="user" placeholder="Nombre de Usuario">
					</div>
					<div class="mb-3">
						<label for="pass" class="form-label">Contraseña: <span class="text-danger">*</span></label>
						<input type="password" class="form-control" id="pass" name="pass" placeholder="Contraseña">
					</div>
					<div class="mb-3">
						<label for="cpass" class="form-label">Confirmar contraseña: <span class="text-danger">*</span></label>
						<input type="password" class="form-control" id="cpass" name="cpass" placeholder="Confirmar contraseña">
					</div>
					<div class="d-flex justify-content-end">
						<button type="submit" class="btn btn-primary">Crear Usuario</button>
					</div>
				</form>
			</div>
		</div>
	</main>
	<?php include '../componentes/footer.html'; ?>
	<script src="../js/bootstrap-theme.js"></script>
	<script src="../js/jquery.validate.functions.js"></script>
</body>

</html>