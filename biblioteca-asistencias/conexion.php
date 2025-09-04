<?php
	$servername = 'localhost';
	$username = 'biblioteca';
	$password = 'biblio';
	$database = 'biblioteca_asistencias';

	$conexion = mysqli_connect($servername, $username, $password, $database);

	// Verificar la conexión
	if (!$conexion) {
		die("Conexión fallida: " . mysqli_connect_error());
	}
?>