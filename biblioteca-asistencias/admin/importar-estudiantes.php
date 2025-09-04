<?php
// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
require_once '../includes/auth.php';
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
		<?php if (isset($_GET['filas_afectadas'])) { ?>
			<?php $filas_afectadas = intval($_GET['filas_afectadas']);
			$filas_repetidas = intval($_GET['filas_repetidas']);
			$filas_actualizadas = intval($_GET['filas_actualizadas']); ?>
			<?php if ($filas_afectadas > 0) { ?>
				<div class="col-12 mx-auto">
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<center><strong>Éxito!</strong> Se cargaron <strong><?php echo $filas_afectadas; ?></strong> registros correctamente al sistema.</center>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				</div>
				<?php if ($filas_repetidas > 0) { ?>
					<div class="col-12 mx-auto">
						<div class="alert alert-warning alert-dismissible fade show" role="alert">
							<center><strong>¡Advertencia!</strong> Se encontraron <strong><?php echo $filas_repetidas; ?></strong> registros repetidos.</center>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					</div>
				<?php } ?>
				<?php if ($filas_actualizadas > 0) { ?>
					<div class="col-12 mx-auto">
						<div class="alert alert-info alert-dismissible fade show" role="alert">
							<center><strong>Información:</strong> Se actualizaron <strong><?php echo $filas_actualizadas; ?></strong> registros.</center>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					</div>
				<?php } ?>
			<?php } else { ?>
				<?php if ($filas_repetidas > 0) { ?>
					<div class="col-12 mx-auto">
						<div class="alert alert-warning alert-dismissible fade show" role="alert">
							<center><strong>¡Advertencia!</strong> Se encontraron <strong><?php echo $filas_repetidas; ?></strong> registros repetidos.</center>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					</div>
				<?php } else { ?>
					<div class="col-12 mx-auto">
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<center><strong>Error:</strong> Ocurrió un problema al importar los datos.</center>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		<?php } ?>
		<div class="col-12 mx-auto mb-3">
			<div class="card">
				<div class="card-header">
					<i class="fa-solid fa-table me-2"></i>Importar Estudiantes
				</div>
				<div class="card-body">
					<form id="cargar-datos" action="../componentes/phpspreadsheet/importar-estudiantes.php" method="post" autocomplete="off" enctype="multipart/form-data">
						<div class="mb-3">
							<label for="file" class="form-label">Seleccionar archivo <span class="text-danger">*</span></label>
							<input class="form-control" type="file" id="file" name="file" accept=".xlsx, .xls">
							<div class="form-text">El archivo debe ser en formato Excel (.xlsx) el cual solo debe contener datos de estudiantes.</div>
						</div>
						<div class="d-flex justify-content-end">
							<button type="submit" class="btn btn-primary">Cargar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-12 mx-auto">
			<div class="card">
				<div class="card-header"><i class="fa-solid fa-table me-2"></i>Formato Excel para cargar estudiantes</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-sm table-bordered mb-0">
							<thead class="table-success">
								<tr>
									<th></th>
									<th>
										<center>A</center>
									</th>
									<th>
										<center>B</center>
									</th>
									<th>
										<center>C</center>
									</th>
									<th>
										<center>D</center>
									</th>
									<th>
										<center>E</center>
									</th>
									<th>
										<center>F</center>
									</th>
									<th>
										<center>G</center>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th>1</th>
									<th>no_de_control</th>
									<th>apellido_paterno</th>
									<th>apellido_materno</th>
									<th>nombre_alumno</th>
									<th>carrera</th>
									<th>semestre</th>
									<th>genero</th>
								</tr>
								<tr>
									<th>2</th>
									<td>25590425</td>
									<td>HERNANDEZ</td>
									<td>SANTIAGO</td>
									<td>MARIAN</td>
									<td>II1</td>
									<td>1</td>
									<td>F</td>
								</tr>
								<tr>
									<th>3</th>
									<td>24590033</td>
									<td>RODRIGUEZ</td>
									<td>HERNANDEZ</td>
									<td>BRENDA LIZBETH</td>
									<td>IGE</td>
									<td>2</td>
									<td>F</td>
								</tr>
								<tr>
									<th>4</th>
									<td>25590592</td>
									<td>MARTINEZ</td>
									<td>ZARRAGA</td>
									<td>LILIANA</td>
									<td>IS1</td>
									<td>1</td>
									<td>F</td>
								</tr>
								<tr>
									<th>5</th>
									<td>22590050</td>
									<td>GARCIA</td>
									<td>ALEGRIA</td>
									<td>ARMINDA MARIANA</td>
									<td>IEM</td>
									<td>4</td>
									<td>F</td>
								</tr>
								<tr>
									<th>6</th>
									<td>25590339</td>
									<td>MIRANDA</td>
									<td>RIVAS</td>
									<td>GEMMA HELI</td>
									<td>IE1</td>
									<td>1</td>
									<td>F</td>
								</tr>
								<tr>
									<th>7</th>
									<td>25590760</td>
									<td>URIBE</td>
									<td>HERNANDEZ</td>
									<td>JUAN PABLO</td>
									<td>ITI</td>
									<td>1</td>
									<td>M</td>
								</tr>
								<tr>
									<th>8</th>
									<td>M25590352</td>
									<td>GOMEZ</td>
									<td>SANTIAGO</td>
									<td>LINDA ELISAMAR</td>
									<td>MCING</td>
									<td>1</td>
									<td>F</td>
								</tr>
								<tr>
									<th>9</th>
									<td>M25590123</td>
									<td>HERNANDEZ</td>
									<td>LOPEZ</td>
									<td>JUAN</td>
									<td>MPIAD</td>
									<td>1</td>
									<td>M</td>
								</tr>
							</tbody>
						</table>
					</div>
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