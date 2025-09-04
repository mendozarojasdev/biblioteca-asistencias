<?php
// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
require_once '../includes/auth.php';
require '../conexion.php';

// Para la grafica de estudiantes
$query = "SELECT * FROM estudiante";
$result = mysqli_query($conexion, $query);
$num_rows_estudiantes = mysqli_num_rows($result);

$query2 = "SELECT * FROM estudiante WHERE codigo_carrera = 'II1'";
$result2 = mysqli_query($conexion, $query2);
$num_rows_estudiantes_ii = mysqli_num_rows($result2);

$query3 = "SELECT * FROM estudiante WHERE codigo_carrera = 'IGE'";
$result3 = mysqli_query($conexion, $query3);
$num_rows_estudiantes_ige = mysqli_num_rows($result3);

$query4 = "SELECT * FROM estudiante WHERE codigo_carrera = 'IE1'";
$result4 = mysqli_query($conexion, $query4);
$num_rows_estudiantes_ie = mysqli_num_rows($result4);

$query5 = "SELECT * FROM estudiante WHERE codigo_carrera = 'IS1'";
$result5 = mysqli_query($conexion, $query5);
$num_rows_estudiantes_isc = mysqli_num_rows($result5);

$query6 = "SELECT * FROM estudiante WHERE codigo_carrera = 'ITI'";
$result6 = mysqli_query($conexion, $query6);
$num_rows_estudiantes_itics = mysqli_num_rows($result6);

$query7 = "SELECT * FROM estudiante WHERE codigo_carrera = 'IEM'";
$result7 = mysqli_query($conexion, $query7);
$num_rows_estudiantes_iem = mysqli_num_rows($result7);

$query8 = "SELECT * FROM estudiante WHERE codigo_carrera = 'MCING'";
$result8 = mysqli_query($conexion, $query8);
$num_rows_estudiantes_mcing = mysqli_num_rows($result8);

$query14 = "SELECT * FROM estudiante WHERE codigo_carrera = 'MPIAD'";
$result14 = mysqli_query($conexion, $query14);
$num_rows_estudiantes_mpiad = mysqli_num_rows($result14);

# para la grafica de usuarios
$query9 = "SELECT * FROM usuario";
$result9 = mysqli_query($conexion, $query9);
$num_row_usuarios = mysqli_num_rows($result9);

$query10 = "SELECT * FROM usuario WHERE id_grupo = 2";
$result10 = mysqli_query($conexion, $query10);
$num_row_usuarios_docentes = mysqli_num_rows($result10);

$query11 = "SELECT * FROM usuario WHERE id_grupo = 3";
$result11 = mysqli_query($conexion, $query11);
$num_row_usuarios_admin = mysqli_num_rows($result11);

$query12 = "SELECT * FROM usuario WHERE id_grupo = 4";
$result12 = mysqli_query($conexion, $query12);
$num_row_usuarios_investigador = mysqli_num_rows($result12);

$query13 = "SELECT * FROM usuario WHERE id_grupo = 5";
$result13 = mysqli_query($conexion, $query13);
$num_row_usuarios_otros = mysqli_num_rows($result13);

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
	<script src="../js/chart.js"></script>
</head>

<body class="d-flex flex-column h-100">
	<?php require '../componentes/admin-navbar.html' ?>
	<?php require '../componentes/admin-sidebar.html' ?>
	<main class="flex-shrink-0">
		<div class="row">
			<div class="col-12 col-lg-6 mx-auto">
				<div class="card mb-3">
					<div class="card-header">
						<i class="fa-solid fa-chart-pie me-2"></i>Estudiantes Registrados:
						<?php echo $num_rows_estudiantes; ?>
					</div>
					<div class="card-body">
						<canvas id="graficaEstudiantes" width="100%" height="50"></canvas>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-6 mx-auto">
				<div class="card">
					<div class="card-header">
						<i class="fa-solid fa-chart-pie me-2"></i>Usuarios Registrados:
						<?php echo $num_row_usuarios; ?>
					</div>
					<div class="card-body">
						<canvas id="graficaUsuarios" width="100%" height="50"></canvas>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php require '../componentes/admin-footer.html'; ?>
	<script src="../js/admin-sidebar.js"></script>
	<script src="../js/bootstrap-theme.js"></script>
	<script>
		// Crear la gráfica
		const ctx = document.getElementById('graficaEstudiantes').getContext('2d');
		const graficaEstudiantes = new Chart(ctx, {
			type: 'line', // puedes cambiar a 'pie', 'doughnut', 'line', etc.
			data: {
				labels: ["II", "IGE", "IE", "ISC", "ITICS", "IEM", "MCING", "MPIAD"],
				datasets: [{
					label: 'Número de Estudiantes',
					data: [<?php echo $num_rows_estudiantes_ii; ?>, 
						<?php echo $num_rows_estudiantes_ige; ?>, 
						<?php echo $num_rows_estudiantes_ie; ?>, 
						<?php echo $num_rows_estudiantes_isc; ?>, 
						<?php echo $num_rows_estudiantes_itics; ?>, 
						<?php echo $num_rows_estudiantes_iem; ?>,
						<?php echo $num_rows_estudiantes_mcing; ?>,
						<?php echo $num_rows_estudiantes_mpiad; ?>],
					backgroundColor: [
						'#007bff',
						'#dc3545',
						'#ffc107',
						'#28a745',
						'#6f42c1',
						'#e83e8c',
						'#3ed7e8',
						'#e84f3e'
					],
					borderColor: '#4e73df',
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				scales: {
					y: {
						beginAtZero: true,
						title: {
							display: true,
							text: 'Cantidad'
						}
					},
					x: {
						title: {
							display: true,
							text: 'Carreras'
						}
					}
				},
				plugins: {
					legend: {
						display: false
					}
				}
			}
		});
	</script>
	<script>
		// Crear la gráfica
		const ctx2 = document.getElementById('graficaUsuarios').getContext('2d');
		const graficaUsuarios = new Chart(ctx2, {
			type: 'line', // puedes cambiar a 'pie', 'doughnut', 'line', etc.
			data: {
				labels: ["Docentes", "Administrativos", "Investigadores", "Otros"],
				datasets: [{
					label: 'Número de Usuarios',
					data: [<?php echo $num_row_usuarios_docentes; ?>, <?php echo $num_row_usuarios_admin; ?>, <?php echo $num_row_usuarios_investigador; ?>, <?php echo $num_row_usuarios_otros; ?>],
					backgroundColor: [
						'#007bff',
						'#dc3545',
						'#ffc107',
						'#28a745'
					], 
					borderColor: '#4e73df',
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				scales: {
					y: {
						beginAtZero: true,
						title: {
							display: true,
							text: 'Cantidad'
						}
					},
					x: {
						title: {
							display: true,
							text: 'Áreas'
						}
					}
				},
				plugins: {
					legend: {
						display: false
					}
				}
			}
		});
	</script>
</body>

</html>