// Funciones para resaltar los enlaces como activos
// Obtiene la URL de la página actual
//var current_path = window.location.pathname; // /ruta/del/servidor/pagina-1.php
var current_path = window.location.pathname.split("/").pop() || "index.php"; 
//console.log(current_path); // pagina-1.php o index.php

// Coloca active en subpáginas
if (current_path == "actualizar-estudiantes.php") {
	current_path = "estudiantes.php";
}
if (current_path == "actualizar-usuarios.php") {
	current_path = "usuarios.php";
}

// Obten todos los enlaces dentro del sidebar
var sidebar_links = document.querySelectorAll("aside a");

// Itera a través de los enlaces y agrega la clase "active" al enlace de la página actual
sidebar_links.forEach(function (link) {
	// Resalta automáticamente el enlace index.php incluso cuando la página se carga por primera vez
	// Extrae solo el pathname del href del link
	const linkHref = link.getAttribute("href");
	const linkPath = linkHref.split("/").pop(); // obtén solo el nombre del archivo

	if (current_path === linkPath) {
		link.classList.add("active");
	} else {
		link.classList.remove("active");
	}
});

// Funciones para abrir/cerrar sidebar/overlay
const toggle_button = document.querySelector(".navbar .btn");
const sidebar = document.querySelector(".sidebar");
const overlay = document.getElementById("sidebar-overlay");
const body = document.body;

toggle_button?.addEventListener("click", function () {
	sidebar.classList.add("show");
	overlay.classList.remove("d-none");
	overlay.classList.add("show");
	body.classList.add("sidebar-open");
});

overlay?.addEventListener("click", function () {
	sidebar.classList.remove("show");
	overlay.classList.remove("show");
	overlay.classList.add("d-none");
	overlay.classList.remove("show");
	body.classList.remove("sidebar-open");
});

// Guardar el tema al seleccionarlo
document.querySelectorAll('[data-theme]').forEach((item) => {
	item.addEventListener('click', (e) => {
		e.preventDefault();
		const theme = item.getAttribute('data-theme');
		document.documentElement.setAttribute('data-bs-theme', theme);
		localStorage.setItem('theme', theme);
	});
});