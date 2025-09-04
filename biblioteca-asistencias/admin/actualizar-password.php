<?php
// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
require_once '../includes/auth.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['npass']) && isset($_POST['cnpass']) && !empty($_POST['npass']) && !empty($_POST['cnpass']) && $_POST['npass'] === $_POST['cnpass']) {
	require '../conexion.php';

	// Al precionar el boton de actualizar
	$password1 = $_POST['cnpass'];
	$admin = $_SESSION['usuario'];
	// Encriptar pass
	$npass = password_hash($password1, PASSWORD_BCRYPT);
	// Actualiza el usuario que al momento es el unico admin
	$sql = "UPDATE administrador SET password_hash='$npass' WHERE usuario='$admin'";
	if (mysqli_query($conexion, $sql)) {
		$alert_class = "alert-success";
		$alert_message = "<strong>Éxito!</strong> Se actualizó la contraseña correctamente!";
	} else {
		$alert_class = "alert-danger";
		$alert_message = "<strong>Error:</strong> Ocurrió un problema al actualizar la contraseña.";
	}

	mysqli_close($conexion);
} else {
	unset($_POST);
}
?>
<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
	<title>Biblioteca ITSJR</title>
	<?php include '../componentes/admin-meta-tags.html'; ?>
	<link rel="stylesheet" href="../css/bootstrap-tec.css">
	<link rel="stylesheet" href="../css/admin-sidebar.css">
	<script src="../js/jquery-3.7.1.js"></script>
	<script src="../js/bootstrap.bundle.js"></script>
	<script src="../js/font-awesome-6.7.2.min.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
</head>

<body class="d-flex flex-column h-100">
	<?php require '../componentes/admin-navbar.html' ?>
	<?php require '../componentes/admin-sidebar.html' ?>
	<main class="flex-shrink-0">
		<?php if (isset($_POST)) { ?>
			<div class="col-12 col-md-6 col-xl-4 mx-auto">
				<div class="alert <?php echo $alert_class; ?> alert-dismissible fade show" role="alert">
					<?php echo $alert_message; ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			</div>
		<?php } ?>
		<div class="col-12 col-md-6 col-xl-4 mx-auto">
			<div class="card">
				<div class="card-header">
					<i class="fa-solid fa-key me-2"></i>Actualizar Contraseña
				</div>
				<div class="card-body">
					<form id="updtpass" class="mb-0" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
						<p>Usuario: <?php echo $_SESSION['usuario']; ?></p>
						<div class="mb-3">
							<label for="npass" class="form-label">Nueva Contraseña: <span class="text-danger">*</span></label>
							<input type="password" class="form-control" id="npass" name="npass" autofocus>
						</div>
						<div class="mb-3">
							<label for="cnpass" class="form-label">Confirmar Nueva Contraseña: <span class="text-danger">*</span></label>
							<input type="password" class="form-control" id="cnpass" name="cnpass">
						</div>
						<div class="d-flex justify-content-between">
							<a href="index.php" class="btn btn-danger">Cancelar</a>
							<button type="submit" class="btn btn-primary">Actualizar</button>
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