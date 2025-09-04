<?php
session_start();

if (!isset($_SESSION['admin'])) {
	// No hay sesión iniciada (de administrador), redirige al login
	header('Location: login.php');
	exit;
}