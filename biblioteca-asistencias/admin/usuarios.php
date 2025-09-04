<?php
// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
require_once '../includes/auth.php';
require '../conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Verificar si hay registros
	$sql = "SELECT * FROM usuario";
	$result = mysqli_query($conexion, $sql);

	if (mysqli_num_rows($result) > 0) {
		// Si hay por lo menos un registro, lo elimina
		$sql_2 = "DELETE FROM usuario";
		if (mysqli_query($conexion, $sql_2)) {
			$affected_rows = mysqli_affected_rows($conexion);
			$alert_class = "alert-success";
			$alert_message = "<strong>Éxito!</strong> Se eliminaron <strong>$affected_rows</strong> registros correctamente!";
		} else {
			$alert_class = "alert-danger";
			$alert_message = "<strong>Error:</strong> Ocurrió un problema al eliminar los usuario registrados.";
		}
	} else {
		$alert_class = "alert-info";
		$alert_message = "<strong>Información:</strong> No se encontraron registros en el sistema.";
	}
}

// Obtiene los datos de todos usuarios registrados
$sql = "SELECT usuario.numero_cuenta, usuario.nombre, grupo.nombre AS grupo, escuela.nombre AS escuela,
	CASE 
		WHEN usuario.genero = 'M' THEN 'MASCULINO'
		WHEN usuario.genero = 'F' THEN 'FEMENINO'
		ELSE ''
	END AS genero 
	FROM usuario
	INNER JOIN grupo ON usuario.id_grupo = grupo.id
	INNER JOIN escuela ON usuario.id_escuela = escuela.id";
$result_2 = mysqli_query($conexion, $sql);

mysqli_close($conexion);
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
	<link rel="stylesheet" href="../css/dataTables.bootstrap5.css">
	<script src="../js/jquery-3.7.1.js"></script>
	<script src="../js/bootstrap.bundle.js"></script>
	<script src="../js/font-awesome-6.7.2.min.js"></script>
	<script src="../js/dataTables.js"></script>
	<script src="../js/dataTables.bootstrap5.js"></script>
	<script src="../js/dataTables-functions.js"></script>
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
		<div class="col-12 mx-auto mb-3">
			<div class="card">
				<div class="card-header">
					<i class="fa-solid fa-circle-info me-2"></i>Administrar Información
				</div>
				<div class="card-body">
					<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarUsuarios">
						Eliminar Usuarios
					</button>
				</div>
			</div>
		</div>
		<div class="col-12 mx-auto">
			<div class="card">
				<div class="card-header"><i class="fa-solid fa-clipboard me-2"></i>Usuarios Registrados</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="usuarios" class="table table-hover">
							<thead>
								<tr>
									<th>No. Cuenta</th>
									<th>Nombre Completo</th>
									<th>Grupo</th>
									<th>Escuela</th>
									<th>Género</th>
									<th></th>
								</tr>
							</thead>
							<tbody class="table-group-divider">
								<?php
									if (mysqli_num_rows($result_2) > 0) {
										// Imprimir datos en la tabla HTML
										while ($fila = mysqli_fetch_assoc($result_2)) {
											echo "<tr>";
											echo "<td>" . $fila["numero_cuenta"] . "</td>";
											echo "<td>" . $fila["nombre"] . "</td>";
											echo "<td>" . $fila["grupo"] . "</td>";
											echo "<td>" . $fila["escuela"] . "</td>";
											echo "<td>" . $fila["genero"] . "</td>";
											echo "<td>
													<form action='actualizar-usuarios.php' method='POST'>
														<input type='hidden' name='ncuenta' value='" . $fila['numero_cuenta'] . "'>
														<button type='submit' class='btn btn-primary'><i class='fa-solid fa-pen'></i></button>
													</form>
												</td>";
											echo "</tr>";
										}
									} else {
										echo "<tr><td colspan='7' class='text-center'>No se econtraron usuarios registrados</td></tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</main>
	<!-- Modal -->
	<div class="modal fade" id="modalEliminarUsuarios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Usuarios</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<p>Esta acción eliminará a todos los usuarios a excepción de estudiantes, es recomedable realizar un respaldo de la información.</p>
						<p>¿Desea continuar?</p>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
							<button type="submit" class="btn btn-danger">Eliminar</button>
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