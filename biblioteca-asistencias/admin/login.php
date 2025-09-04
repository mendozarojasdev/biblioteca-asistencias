<?php
// Report all errors except E_NOTICE, E_WARNING, E_DEPRECATED
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

//cierra sesion si estaba abierta antes de volver a iniciar sesion
session_start();
session_destroy();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	include '../conexion.php';

	if (isset($_POST['usuario']) && isset($_POST['password']) && !empty($_POST['usuario']) && !empty($_POST['password'])) {
		$usuario = $_POST['usuario'];
		$password = $_POST['password'];

		// Obtiene la pass
		$sql = "SELECT * FROM administrador WHERE usuario = '$usuario'";
		$result = mysqli_query($conexion, $sql);
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$pass = $row['password_hash'];
			// Verifica pass
			$login_status = password_verify($password, $pass);
		} else {
			$login_status = false;
		}
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
	<script src="../js/font-awesome-6.7.2.min.js" crossorigin="anonymous"></script>
	<script src="../js/jquery.validate.min.js"></script>
</head>

<body class="d-flex flex-column h-100">
	<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
		<div class="container-fluid">
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<span class="navbar-brand d-none d-sm-block"><img src="../img/logos/logo-b.png" alt="" width="72px" height="36px"></span>
			<span class="navbar-brand mb-0 h1">Biblioteca ITSJR</span>
			<span class="navbar-brand"><img src="../img/logos/logo2-b.png" alt="" width="36px" height="36px"></span>
		</div>
		<div class="container-fluid">
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link" aria-current="page" href="../index.php">Inicio</a>
					</li>
				</ul>
				<ul class="navbar-nav">
					<?php if (!isset($_SESSION['admin'])) { ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								Usuarios
							</a>
							<ul class="dropdown-menu dropdown-menu-lg-end">
								<li><a class="dropdown-item" href="index.php">Administrador</a></li>
							</ul>
						</li>
					<?php } else { ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								Perfil
							</a>
							<ul class="dropdown-menu dropdown-menu-lg-end">
								<li><a class="dropdown-item" href="index.php">Administración</a></li>
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
		<div class="container-fluid">
			<div class="col-12 col-md-6 col-lg-4 col-xl-3 mx-auto py-3">
				<div class="card">
					<div class="card-body">
						<form id="formulario-admin" class="mb-0" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
							<div class="text-center mb-4">
								<img class="mb-4" src="../img/logos/logo2.png" alt="" width="72" height="72">
								<h3 class="mb-3 font-weight-normal">Administración</h3>
							</div>
							<?php if (isset($usuario) && isset($password)) { ?>
								<?php if (!$login_status) { ?>
									<div class="alert alert-danger alert-dismissible fade show" role="alert">
										<strong>Error:</strong> Datos incorrectos.
										<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>
								<?php } else {
									// Iniciando sesion
									session_start();
									$_SESSION['admin'] = true;
									$_SESSION['usuario'] = $row['usuario'];
									header("Location: index.php"); 
								} ?>
							<?php } ?>
							<div class="mb-3">
								<label for="usuario" class="form-label">Nombre de Usuario: <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="usuario" name="usuario" autofocus>
							</div>
							<div class="mb-3">
								<label for="password" class="form-label">Contraseña: <span class="text-danger">*</span></label>
								<input type="password" class="form-control" id="password" name="password">
							</div>
							<div class="d-flex justify-content-end">
								<button type="submit" class="btn btn-primary">Iniciar Sesión</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php include '../componentes/footer.html'; ?>
	<script src="../js/bootstrap-theme.js"></script>
	<script src="../js/jquery.validate.functions.js"></script>
</body>

</html>