<?php
// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
require 'conexion.php';
include 'includes/fechas.php';
session_start();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (isset($_POST['identificador']) && !empty($_POST['identificador'])) {
		$identificador = strtoupper($_POST['identificador']);
		$identificador = trim($identificador);
		// Verifica que el numero de cuenta se encuentre registrado en la tabla estudiante
		$sql_estudiante = "SELECT 
							CONCAT(estudiante.nombre,' ',estudiante.apellido_paterno,' ', estudiante.apellido_materno) AS nombre_completo,
							carrera.nombre AS carrera,
							estudiante.semestre,
							estudiante.genero
						FROM estudiante
						INNER JOIN carrera ON estudiante.codigo_carrera = carrera.codigo
						WHERE estudiante.numero_control = '$identificador'";
		$resultado_estudiante = mysqli_query($conexion, $sql_estudiante);

		if (mysqli_num_rows($resultado_estudiante) == 1) {
			// Identificador encontrado, es estudiante
			$existe_identificador = true;
			// Obtener los datos del usuario
			$row = mysqli_fetch_assoc($resultado_estudiante);
			$nombre = $row['nombre_completo'];
			$carrera_area = $row['carrera'];
			$semestre = $row['semestre'];
			#$genero = $row['genero'];
			// Coloca genero entre comillas dobles ya que en la consulta el campo genero no tiene comillas simples como el campo 'identificador' para permitir valores NULL
			$genero = !empty($row['genero']) ? "'{$row['genero']}'" : "NULL";
		} else {
			// Busca identificador en la tabla usuario
			$sql_usuario = "SELECT 
								usuario.nombre AS nombre_completo,
								grupo.nombre AS grupo,
								escuela.nombre AS escuela,
								usuario.genero
							FROM usuario
							INNER JOIN grupo ON usuario.id_grupo = grupo.id
							INNER JOIN escuela ON usuario.id_escuela = escuela.id
							WHERE usuario.numero_cuenta = '$identificador'";
			$resultado_usuario = mysqli_query($conexion, $sql_usuario);

			if (mysqli_num_rows($resultado_usuario) == 1) {
				// Identificador encontrado, es usuario
				$existe_identificador = true;
				// Obtener los datos del usuario
				$row = mysqli_fetch_assoc($resultado_usuario);
				$nombre = $row['nombre_completo'];
				$carrera_area = $row['grupo'] . ' [' . $row['escuela'] . '] ';
				$semestre = "NULL"; // Debe estar entre comillas dobles "" para que pueda realizar el INSERT
				$genero = !empty($row['genero']) ? "'{$row['genero']}'" : "NULL";
			} else {
				// Identificador no encontrado
				// Usuario no registrado
				$alert_class = "alert-warning";
				$alert_message = "<h4 class='alert-heading text-center'>¡Advertencia!</h4>
					<p class='text-center'>El número de control/identificador <strong>$identificador</strong> no se encuentra registrado en el sistema, favor de notificar al personal responsable para su registro.</p>";
				$existe_identificador = false;
			}
		}
		if ($existe_identificador) {
			$sql_insertar = "INSERT INTO asistencia (identificador, nombre_completo, carrera_area, semestre, genero) 
				VALUES ('$identificador', '$nombre', '$carrera_area', $semestre, $genero)";
			if (!mysqli_query($conexion, $sql_insertar)) {
				// Ocurrió un error al registrar la asistencia
				$alert_class = "alert-danger";
				$alert_message = "<h4 class='alert-heading text-center'>¡Advertencia!</h4>
					<p class='text-center'>Ocurrió un problema al registrar la asistencia, favor de notificar al personal responsable.</p>";
			}
		}
	}
}

// Obtiene los datos de la tabla asistencias para mostrarlos en la lista
$sql_asistencias = "SELECT * FROM asistencia ORDER BY id DESC LIMIT 10";
$resultado_asistencias = mysqli_query($conexion, $sql_asistencias);

mysqli_close($conexion);
?>
<html lang="es" class="h-100">

<head>
	<meta charset="UTF-8">
	<title>Biblioteca ITSJR</title>
	<?php include 'componentes/meta-tags.html'; ?>
	<link rel="stylesheet" href="css/bootstrap-tec.css" />
	<script src="js/jquery-3.7.1.js"></script>
	<script src="js/bootstrap.bundle.js"></script>
	<script src="js/font-awesome-6.7.2.min.js" crossorigin="anonymous"></script>
	<script src="js/jquery.validate.min.js"></script>
</head>

<body class="d-flex flex-column h-100">
	<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
		<div class="container-fluid">
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<span class="navbar-brand d-none d-sm-block"><img src="img/logos/logo-b.png" alt="" width="72px" height="36px"></span>
			<span class="navbar-brand mb-0 h1">Biblioteca ITSJR</span>
			<span class="navbar-brand"><img src="img/logos/logo2-b.png" alt="" width="36px" height="36px"></span>
		</div>
		<div class="container-fluid">
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="index.php">Inicio</a>
					</li>
				</ul>
				<ul class="navbar-nav">
					<?php if (!isset($_SESSION['admin'])) { ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								Usuarios
							</a>
							<ul class="dropdown-menu dropdown-menu-lg-end">
								<li><a class="dropdown-item" href="admin/" target="_blank">Administrador</a></li>
							</ul>
						</li>
					<?php } else { ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								Perfil
							</a>
							<ul class="dropdown-menu dropdown-menu-lg-end">
								<li><a class="dropdown-item" href="admin/" target="_blank">Administración</a></li>
								<li>
									<hr class="dropdown-divider">
								</li>
								<li><a class="dropdown-item" href="admin/logout.php">Cerrar Sesión</a></li>
							</ul>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</nav>
	<main class="flex-shrink-0">
		<?php if (isset($alert_message)) { ?>
			<div class="alert alert-warning alert-dismissible fade show" role="alert">
				<?php echo $alert_message; ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php } ?>
		<div class="container-fluid">
			<div class="col-12 col-lg-8 col-xl-6 mx-auto py-3">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title text-uppercase text-center">Registro de Asistencias</h5>
						<form id="asistencias" class="mb-0" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
							<div class="mb-3">
								<label for="identificador" class="form-label text-uppercase">Ingresa tu número de control para registrar tu asistencia: <span class="text-danger">*</span></label>
								<input type="text" class="form-control text-uppercase" id="identificador" name="identificador" placeholder="Número de control/Identificador" autofocus>
							</div>
							<div class="d-flex justify-content-end">
								<button type="submit" class="btn btn-primary">Registrar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-12 mx-auto mb-3">
				<div class="card">
					<div class="card-header"><i class="fa-solid fa-clipboard"></i> Lista de Asistencias</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table">
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
											# Formatea fecha
											$fecha_hora = fecha_asistencias($row['fecha_hora']);
											echo "<td>$fecha_hora</td>";
											echo "</tr>";
										}
									} else {
										echo "<tr><td colspan='6' class='text-center'>No se han encontrado registros</td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php include 'componentes/footer.html'; ?>
	<script src="js/bootstrap-theme.js"></script>
	<script src="js/jquery.validate.functions.js"></script>
</body>

</html>