<?php
// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
require_once '../includes/auth.php';
include '../includes/fechas.php';

// Recibe los datos para realizar la busqueda, mostrarlos, y exportarlos
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['fecha_desde']) && !empty($_POST['fecha_hasta'])) {
	require '../conexion.php';

	if (isset($_POST['fecha_desde']) && isset($_POST['fecha_hasta'])) {
		$fecha_desde = $_POST['fecha_desde'];
		$fecha_hasta = $_POST['fecha_hasta'];

		$sql = "SELECT * FROM asistencia WHERE DATE(fecha_hora) BETWEEN '$fecha_desde' AND '$fecha_hasta'";
		$resultado_asistencias = mysqli_query($conexion, $sql);

		$cantidad_registros = mysqli_num_rows($resultado_asistencias);
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
	<link rel="stylesheet" href="../css/dataTables.bootstrap5.css">
	<script src="../js/jquery-3.7.1.js"></script>
	<script src="../js/bootstrap.bundle.js"></script>
	<script src="../js/font-awesome-6.7.2.min.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
	<script src="../js/dataTables.js"></script>
	<script src="../js/dataTables.bootstrap5.js"></script>
	<script src="../js/dataTables-functions.js"></script>
</head>

<body class="d-flex flex-column h-100">
	<?php require '../componentes/admin-navbar.html' ?>
	<?php require '../componentes/admin-sidebar.html' ?>
	<main class="flex-shrink-0">
		<div class="col-12 mx-auto mb-3">
			<div class="card">
				<div class="card-header">
					<i class="fa-solid fa-calendar me-2"></i>Generar Reporte
				</div>
				<div class="card-body">
					<form id="reportes" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="mb-3">
							<div class="row g-2 align-items-center">
								<div class="col-auto">
									<label for="fecha_desde" class="col-form-label">Buscar desde: <span class="text-danger">*</span></label>
								</div>
								<div class="col-auto">
									<input type="date" class="form-control" name="fecha_desde">
								</div>
							</div>
						</div>
						<div class="mb-3">
							<div class="row g-2 align-items-center">
								<div class="col-auto">
									<label for="fecha_hasta" class="col-form-label">Buscar hasta: <span class="text-danger">*</span></label>
								</div>
								<div class="col-auto">
									<input type="date" class="form-control" name="fecha_hasta">
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-between">
							<?php if (!empty($_POST) && $cantidad_registros > 0) { ?>
								<a href="../componentes/phpspreadsheet/reporte-asistencias.php?fecha_desde=<?php echo $fecha_desde; ?>&fecha_hasta=<?php echo $fecha_hasta; ?>" class="btn btn-success">Exportar</a>
							<?php } ?>
							<button type="submit" class="btn btn-primary">Buscar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php if (!empty($_POST)) { ?>
			<div class="col-12 mx-auto">
				<div class="card">
					<div class="card-header"><i class="fa-solid fa-table me-2"></i>Registros</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="tabla_reportes" class="table">
								<thead>
									<tr>
										<th>No. Control</th>
										<th>Nombre</th>
										<th>Carrera/Área/Dependencia</th>
										<th>Semestre</th>
										<th>Género</th>
										<th>Fecha</th>
									</tr>
								</thead>
								<tbody class="table-group-divider">
									<?php
									if (mysqli_num_rows($resultado_asistencias) > 0) {
										while ($row = mysqli_fetch_assoc($resultado_asistencias)) {
											echo "<tr>";
											echo "<td>{$row['identificador']}</td>";
											echo "<td>{$row['nombre_completo']}</td>";
											echo "<td>{$row['carrera_area']}</td>";
											echo "<td class='text-center'>{$row['semestre']}</td>";

											# Transforma F/M a FEMENINO/MASCULINO
											$genero = $row['genero'];
											$genero_texto = ($genero === 'M') ? 'MASCULINO' : (($genero === 'F') ? 'FEMENINO' : '');
											echo "<td>$genero_texto</td>";
											# Transforma fecha
											$fecha_hora = fecha_asistencias($row['fecha_hora']);
											echo "<td>$fecha_hora</td>";
											echo "</tr>";
										}
									} else {
										echo "<tr><td colspan='6' class='text-center'>No se encontraron registros</td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</main>
	<?php require '../componentes/admin-footer.html'; ?>
	<script src="../js/admin-sidebar.js"></script>
	<script src="../js/bootstrap-theme.js"></script>
	<script src="../js/jquery.validate.functions.js"></script>
</body>

</html>