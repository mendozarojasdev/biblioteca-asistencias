<?php
// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
require_once '../includes/auth.php';
require '../conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Verificar si hay registros
	$sql_select = "SELECT * FROM estudiante";
	$result_select = mysqli_query($conexion, $sql_select);
	if (mysqli_num_rows($result_select) > 0) {
		// Se encontraron registros, iniciar una transacción
		mysqli_autocommit($conexion, false);

		// Consulta para eliminar registros en estudiantes cuyo semestre sea 12 posteriormente incrementa para poder actualizar el semestre 11 a 12
		$sql_delete = "DELETE FROM estudiante WHERE semestre = 12";
		if (mysqli_query($conexion, $sql_delete)) {
			// Consulta realizada correctamente
			// Obtiene cantidad de registros eliminados
			$registros_eliminados_estudiantes = mysqli_affected_rows($conexion);
			if ($registros_eliminados_estudiantes > 0) {
				// Verifica que se hallan eliminado registros
				$alert_class = "alert-warning";
				$alert_message = "<strong>¡Advertencia!</strong> Se descartaron: <strong> $registros_eliminados_estudiantes</strong> estudiantes con semestre mayor a 12";
			}

			// Verificar nuevamente si hay registros para el caso en que solo había estudiantes de semestre 12 y fueron eliminados
			$sql_select = "SELECT * FROM estudiante";
			$result_select = mysqli_query($conexion, $sql_select);

			if (mysqli_num_rows($result_select) > 0) {
				// Aún se encontraron registros, consulta para incrementar el semestre en todos los registros excepto aquellos que ya sean semetre 12 ya que entraria en conflicto con CHECK (semestre BETWEEN 0 AND 12)
				$sql_update = "UPDATE estudiante SET semestre = semestre + 1 WHERE semestre < 12";
				mysqli_query($conexion, $sql_update);

				$alert_class_update = "alert-success";
				$alert_message_update = "<strong>Éxito!</strong> Se actualizó el semestre de los estudiantes correctamente!";
			} else {
				// No se encontraron más registros para actualizar el semestre
				$alert_class_update = "alert-info";
				$alert_message_update = "<strong>Información:</strong> No se encontraron más registros en el sistema.";
			}
			// Confirmar la transacción si todas las consultas fueron exitosas
			mysqli_commit($conexion);
		} else {
			// Revertir la transacción en caso de error
			mysqli_rollback($conexion);
			$alert_class = "alert-danger";
			$alert_message = "<strong>Error:</strong> Ocurrió un problema al actualizar el semestre de los estudiantes.";
		}
	} else {
		$alert_class = "alert-info";
		$alert_message = "<strong>Información:</strong> No se encontraron registros en el sistema.";
	}
}

//obtiene los datos de todos estudiantes registrados
$sql = "SELECT estudiante.numero_control, CONCAT(estudiante.nombre, ' ', estudiante.apellido_paterno, ' ', estudiante.apellido_materno) AS nombre_completo, carrera.nombre AS carrera, estudiante.semestre,
	CASE 
		WHEN estudiante.genero = 'M' THEN 'MASCULINO'
		WHEN estudiante.genero = 'F' THEN 'FEMENINO'
		ELSE ''
	END AS genero
	FROM estudiante
	INNER JOIN carrera ON estudiante.codigo_carrera = carrera.codigo";
$result = mysqli_query($conexion, $sql);

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
			<?php if (isset($alert_class) && isset($alert_message)) { ?>
				<div class="col-12 mx-auto">
					<div class="alert <?php echo $alert_class; ?> alert-dismissible fade show" role="alert">
						<?php echo $alert_message; ?>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				</div>
			<?php } ?>
			<?php if (isset($alert_class_update) && isset($alert_message_update)) { ?>
				<div class="col-12 mx-auto">
					<div class="alert <?php echo $alert_class_update; ?> alert-dismissible fade show" role="alert">
						<?php echo $alert_message_update; ?>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
		<div class="col-12 mx-auto mb-3">
			<div class="card">
				<div class="card-header">
					<i class="fa-solid fa-circle-info me-2"></i>Administrar Información
				</div>
				<div class="card-body">
					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalActualizarSemestre">
						Actualizar Semestre
					</button>
				</div>
			</div>
		</div>
		<div class="col-12 mx-auto">
			<div class="card">
				<div class="card-header"><i class="fa-solid fa-clipboard me-2"></i>Estudiantes</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="estudiantes" class="table table-hover">
							<thead>
								<tr>
									<th>No. Control</th>
									<th>Nombre</th>
									<th>Carrera</th>
									<th>Semestre</th>
									<th>Género</th>
									<th></th>
								</tr>
							</thead>
							<tbody class="table-group-divider">
								<?php
								if (mysqli_num_rows($result) > 0) {
									while ($row = mysqli_fetch_assoc($result)) {
										echo "<tr>";
										echo "<td>" . $row["numero_control"] . "</td>";
										echo "<td>" . $row["nombre_completo"] . "</td>";
										echo "<td>" . $row["carrera"] . "</td>";
										echo "<td class='text-center'>" . $row["semestre"] . "</td>";
										echo "<td>" . $row["genero"] . "</td>";
										echo "<td>
												<form action='actualizar-estudiantes.php' method='POST'>
													<input type='hidden' name='nctrl' value='" . $row['numero_control'] . "'>
													<button type='submit' class='btn btn-primary'><i class='fa-solid fa-pen'></i></button>
												</form>
											</td>";
										echo "</tr>";
									}
								} else {
									echo "<tr><td colspan='7' class='text-center'>No se encontraron estudiantes registrados</td></tr>";
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
	<div class="modal fade" id="modalActualizarSemestre" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Actualizar Semestre</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<p>Esta acción <strong>incrementará el semestre</strong> de todos los estudiantes y <strong>eliminará a los estudiantes de 12o semestre</strong> en adelante, es recomendable realizar un respaldo de la información.</p>
						<p>Acciones recomendadas:</p>
						<ul>
							<li>Realizar respaldo</li>
							<li>Actualizar semestre</li>
							<li>Importar estudiantes de primer semestre</li>
						</ul>
						<p>¿Desea continuar?</p>
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
</body>

</html>