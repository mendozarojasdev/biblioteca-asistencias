// Al cargar la página, restaurar el tema guardado aun cuando se reinicia el ordenador
const savedTheme = localStorage.getItem('theme');
if (savedTheme) {
	document.documentElement.setAttribute('data-bs-theme', savedTheme);
}