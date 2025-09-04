<?php
// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
require_once '../includes/auth.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	require '../conexion.php';

	// Iniciar una transacción
	$reset_db = false;
	mysqli_autocommit($conexion, false);

	$sql = "DELETE FROM asistencia";
	if (mysqli_query($conexion, $sql)) {
		$sql_3 = "DELETE FROM estudiante";
		mysqli_query($conexion, $sql_3);
		$sql_4 = "DELETE FROM usuario";
		mysqli_query($conexion, $sql_4);
		mysqli_commit($conexion);
		$alert_class = "alert-success";
		$alert_message = "<strong>Éxito!</strong> La base de datos se reseteó correctamente!";
	} else {
		mysqli_rollback($conexion);
		$alert_class = "alert-danger";
		$alert_message = "<strong>Error:</strong> Ocurrió un problema al resetear la base de datos.";
	}
	mysqli_close($conexion);
}
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
	<script src="../js/bootstrap.bundle.js"></script>
	<script src="../js/font-awesome-6.7.2.min.js"></script>
</head>

<body class="d-flex flex-column h-100">
	<?php require '../componentes/admin-navbar.html' ?>
	<?php require '../componentes/admin-sidebar.html' ?>
	<main class="flex-shrink-0">
		<?php if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
			<div class="col-12 mx-auto">
				<div class="alert <?php echo $alert_class; ?> alert-dismissible fade show" role="alert">
					<?php echo $alert_message; ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			</div>
		<?php } ?>
		<div class="row">
			<div class="col-12 mx-auto">
				<div class="card">
					<div class="card-header">
						<i class="fa-solid fa-database me-2"></i>Administrar Base de Datos
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-8 mx-auto text-muted">
								<p class="form-text">Accede a phpMyAdmin para realizar un respaldo de la base de datos en un archivo en formato sql (<strong>biblioteca_asistencias > exportar > personalizado</strong>)</p>
								<p>Desmarcar las tablas: (opción de la primera columna)</p>
								<ul>
									<li>administrador</li>
									<li>carrera</li>
									<li>escuela</li>
									<li>grupo</li>
								</ul>
								<p>Mantener las tablas: (desmarcar opción de la columna <strong>Estructura</strong> y mantener la opción del campo <strong>Datos</strong>)</p>
								<ul>
									<li>asistencia</li>
									<li>estudiante</li>
									<li>usuario</li>
								</ul>
							</div>
							<div class="col-12 col-sm-12 col-md-4 mx-auto">
								<a href="/phpmyadmin" target="_blank" class="btn btn-primary">Respaldar Información</a>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-md-8 mx-auto">
								<p class="form-text text-muted">Accede a phpMyAdmin para realizar una restauración de la base de datos con un archivo en formato sql (<strong>biblioteca_asistencias > importar > seleccionar archivo</strong>). Nota: primero se debe <strong>resetear</strong> la base de datos (opción de abajo).</p>
							</div>
							<div class="col-12 col-md-4 mx-auto">
								<a href="/phpmyadmin" target="_blank" class="btn btn-primary">Restaurar Información</a>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-md-8 mx-auto">
								<p class="form-text text-muted">Resetea toda la información base de datos a excepción de los usuarios administradores, carreras, grupos y escuelas.</p>
							</div>
							<div class="col-12 col-md-4 mx-auto">
								<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalResetDB">
									Resetear Base de Datos
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<!-- Modal -->
	<div class="modal fade" id="modalResetDB" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Resetear Base de Datos</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<p>Esta acción borrará toda la información de la base de datos.</p>
						<p>¿Desea continuar?</p>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
							<button type="submit" class="btn btn-warning">Confirmar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php require '../componentes/admin-footer.html'; ?>
	<script src="../js/admin-sidebar.js"></script>
	<script src="../js/bootstrap-theme.js"></script>
</body>

</html>