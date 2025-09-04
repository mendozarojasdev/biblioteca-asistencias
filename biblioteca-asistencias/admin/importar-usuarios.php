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
			$filas_repetidas = intval($_GET['filas_repetidas']); ?>
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
					<i class="fa-solid fa-table me-2"></i>Importar Usuarios
				</div>
				<div class="card-body">
					<form id="cargar-datos" action="../componentes/phpspreadsheet/importar-usuarios.php" method="post" autocomplete="off" enctype="multipart/form-data">
						<div class="mb-3">
							<label for="file" class="form-label">Seleccionar archivo <span class="text-danger">*</span></label>
							<input class="form-control" type="file" id="file" name="file" accept=".xlsx, .xls">
							<div class="form-text">El archivo debe ser en formato Excel (.xlsx) el cual solo debe contener datos de docentes y otros usuarios, si contiene estudiantes, éstos no se agregan.</div>
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
				<div class="card-header"><i class="fa-solid fa-table me-2"></i>Formato Excel para cargar usuarios</div>
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
									<th>no_de_cuenta</th>
									<th>nombre</th>
									<th>grupo</th>
									<th>escuela</th>
									<th>puede</th>
									<th>contener</th>
									<th>otros campos</th>
								</tr>
								<tr>
									<th>2</th>
									<td>355</td>
									<td>AMEZQUITA UGALDE MARIA DEL CARMEN</td>
									<td>3</td>
									<td>5</td>
									<td>puede</td>
									<td>contener</td>
									<td>otros datos</td>
								</tr>
								<tr>
									<th>3</th>
									<td>ci14004</td>
									<td>ARIANA CORDERO CONTRERAS</td>
									<td>2</td>
									<td>5</td>
									<td>puede</td>
									<td>contener</td>
									<td>otros datos</td>
								</tr>
								<tr>
									<th>4</th>
									<td>CI050002</td>
									<td>BECERRA RODRIGUEZ MARIA BLANCA</td>
									<td>2</td>
									<td>1</td>
									<td>puede</td>
									<td>contener</td>
									<td>otros datos</td>
								</tr>
								<tr>
									<th>5</th>
									<td>200402</td>
									<td>ACEVEDO AGUSTIN JASMIN GRACIELA</td>
									<td>5</td>
									<td>7</td>
									<td>puede</td>
									<td>contener</td>
									<td>otros datos</td>
								</tr>
								<tr>
									<th>6</th>
									<td>CI2003EX16</td>
									<td>BARTOLO BADILLO MA. MACRINA</td>
									<td>3</td>
									<td>6</td>
									<td>puede</td>
									<td>contener</td>
									<td>otros datos</td>
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