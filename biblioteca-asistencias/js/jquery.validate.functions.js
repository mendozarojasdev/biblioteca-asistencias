$(document).ready(function () {
	$('#signup').validate({
		rules: {
			user: {
				required: true
			},
			pass: {
				required: true
			},
			cpass: {
				required: true,
				equalTo: "#pass"
			}
		},
		messages: {
			user: {
				required: "Por favor, ingrese un nombre de usuario"
			},
			pass: {
				required: "Por favor, ingrese una contraseña"
			},
			cpass: {
				required: "Por favor, confirme la contraseña",
				equalTo: "Las contraseñas no coinciden"
			}
		},
		errorElement: "div",
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			error.insertAfter(element);
		},
		highlight: function (element) {
			$(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function (element) {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	});
	$('#asistencias').validate({
		rules: {
			identificador: {
				required: true
			}
		},
		messages: {
			identificador: {
				required: "Por favor, ingresa tu número de control o identificador"
			}
		},
		errorElement: "div",
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			error.insertAfter(element);
		},
		highlight: function (element) {
			$(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function (element) {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	});
	$('#formulario-admin').validate({
		rules: {
			usuario: {
				required: true
			},
			password: {
				required: true
			}
		},
		messages: {
			usuario: {
				required: "Por favor, ingresa tu nombre de usuario"
			},
			password: {
				required: "Por favor, ingresa tu contraseña"
			}
		},
		errorElement: "div",
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			error.insertAfter(element);
		},
		highlight: function (element) {
			$(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function (element) {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	});
	$('#alta-estudiantes').validate({
		rules: {
			ncontrol: {
				required: true,
				minlength: 5,
				maxlength: 11
			},
			apellidop: {
				required: true
			},
			nombre: {
				required: true
			},
			carrera_codigo: {
				required: true
			},
			semestre: {
				required: true,
				number: true,
				min: 1,
				max: 12,
				digits: true
			},
			genero: {
				required: true
			}
		},
		messages: {
			ncontrol: {
				required: "Por favor, ingrese el número de control del estudiante",
				minlength: "El número de control debe contener mínimo 5 caracteres",
				maxlength: "El número de control solo puede contener hasta 11 caracteres"
			},
			apellidop: {
				required: "Por favor, ingrese el apellido paterno del estudiante"
			},
			nombre: {
				required: "Por favor, ingrese el nombre del estudiante"
			},
			carrera_codigo: {
				required: "Por favor, seleccione la carrera del estudiante"
			},
			semestre: {
				required: "Por favor, ingrese el semestre del estudiante",
				number: "El semestre debe ser numérico",
				min: "El semestre debe ser un número entre 1 y 12",
				max: "El semestre límite para el registro es 12",
				digits: "El semestre debe contener solo números enteros"
			},
			genero: {
				required: "Por favor, seleccione el género del estudiante"
			}
		},
		errorElement: "div",
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			if (element.attr("type") === "radio") {
				error.insertAfter(element.closest('.col-auto').parent());
			} else {
				error.insertAfter(element);
			}
		},
		highlight: function (element) {
			$(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function (element) {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	});
	$('#alta-usuarios').validate({
		rules: {
			ncuenta: {
				required: true,
				maxlength: 20
			},
			nombre: {
				required: true
			},
			grupo_id: {
				required: true
			},
			escuela_id: {
				required: true
			},
			genero: {
				required: true
			}
		},
		messages: {
			ncuenta: {
				required: "Por favor, ingrese el número de cuenta del usuario",
				maxlength: "El número de control contiene demasiados caracteres"
			},
			nombre: {
				required: "Por favor, ingrese el nombre completo del usuario"
			},
			grupo_id: {
				required: "Por favor, seleccione el grupo del usuario"
			},
			escuela_id: {
				required: "Por favor, seleccione la escuela del usuario"
			},
			genero: {
				required: "Por favor, seleccione el género del usuario"
			}
		},
		errorElement: "div",
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			if (element.attr("type") === "radio") {
				error.insertAfter(element.closest('.col-auto').parent());
			} else {
				error.insertAfter(element);
			}
		},
		highlight: function (element) {
			$(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function (element) {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	});
	$('#update-estudiantes').validate({
		rules: {
			ncontrol: {
				required: true,
				minlength: 5,
				maxlength: 11
			},
			apellidop: {
				required: true
			},
			nombre: {
				required: true
			},
			carrera_codigo: {
				required: true
			},
			semestre: {
				required: true,
				number: true,
				min: 1,
				max: 12,
				digits: true
			},
			genero: {
				required: true
			}
		},
		messages: {
			ncontrol: {
				required: "Por favor, ingrese el número de control del estudiante",
				minlength: "El número de control debe contener mínimo 5 caracteres",
				maxlength: "El número de control solo puede contener hasta 11 caracteres"
			},
			apellidop: {
				required: "Por favor, ingrese el apellido paterno del estudiante"
			},
			nombre: {
				required: "Por favor, ingrese el nombre del estudiante"
			},
			carrera_codigo: {
				required: "Por favor, seleccione la carrera del estudiante"
			},
			semestre: {
				required: "Por favor, ingrese el semestre del estudiante",
				number: "El semestre debe ser numérico",
				min: "El semestre debe ser un número entre 1 y 12",
				max: "El semestre límite para el registro es 12",
				digits: "El semestre debe contener solo números enteros"
			},
			genero: {
				required: "Por favor, seleccione el género del estudiante"
			}
		},
		errorElement: "div",
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			if (element.attr("type") === "radio") {
				error.insertAfter(element.closest('.col-auto').parent());
			} else {
				error.insertAfter(element);
			}
		},
		highlight: function (element) {
			$(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function (element) {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	});
	$('#update-estudiantes-ncontrol').validate({
		rules: {
			nuevo_numero_control: {
				required: true,
				minlength: 5,
				maxlength: 11
			}
		},
		messages: {
			nuevo_numero_control: {
				required: "Por favor, ingrese el nuevo número de control del estudiante",
				minlength: "El número de control debe contener mínimo 5 caracteres",
				maxlength: "El número de control solo puede contener hasta 11 caracteres"
			}
		},
		errorElement: "div",
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			error.insertAfter(element);
		},
		highlight: function (element) {
			$(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function (element) {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	});
	$('#update-usuarios').validate({
		rules: {
			ncuenta: {
				required: true,
				maxlength: 20
			},
			nombre: {
				required: true
			},
			grupo_id: {
				required: true
			},
			escuela_id: {
				required: true
			},
			genero: {
				required: true
			}
		},
		messages: {
			ncuenta: {
				required: "Por favor, ingrese el número de cuenta del usuario",
				maxlength: "El número de cuenta contiene demasiados caracteres"
			},
			nombre: {
				required: "Por favor, ingrese el nombre completo del usuario"
			},
			grupo_id: {
				required: "Por favor, seleccione el grupo del usuario"
			},
			escuela_id: {
				required: "Por favor, seleccione la escuela del usuario"
			},
			genero: {
				required: "Por favor, seleccione el género del usuario"
			}
		},
		errorElement: "div",
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			if (element.attr("type") === "radio") {
				error.insertAfter(element.closest('.col-auto').parent());
			} else {
				error.insertAfter(element);
			}
		},
		highlight: function (element) {
			$(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function (element) {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	});
	$('#update-usuarios-ncuenta').validate({
		rules: {
			nncuenta: {
				required: true,
				maxlength: 20
			}
		},
		messages: {
			nncuenta: {
				required: "Por favor, ingrese el nuevo número de cuenta del usuario",
				maxlength: "El nuevo número de cuenta contiene demasiados caracteres"
			}
		},
		errorElement: "div",
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			error.insertAfter(element);
		},
		highlight: function (element) {
			$(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function (element) {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	});
	$('#cargar-datos').validate({
		rules: {
			file: {
				required: true
			}
		},
		messages: {
			file: {
				required: "Por favor, seleccione un archivo"
			}
		},
		errorElement: "div",
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			error.insertAfter(element);
		},
		highlight: function (element) {
			$(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function (element) {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	});
	$('#updtpass').validate({
		rules: {
			npass: {
				required: true
			},
			cnpass: {
				required: true,
				equalTo: "#npass"
			}
		},
		messages: {
			npass: {
				required: "Por favor, ingrese una contraseña"
			},
			cnpass: {
				required: "Por favor, confirme la contraseña",
				equalTo: "Las contraseñas no coinciden"
			}
		},
		errorElement: "div",
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			error.insertAfter(element);
		},
		highlight: function (element) {
			$(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function (element) {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	});
	$('#reportes').validate({
		rules: {
			fecha_desde: {
				required: true
			},
			fecha_hasta: {
				required: true
			}
		},
		messages: {
			fecha_desde: {
				required: "Por favor, ingrese una fecha para realizar la busqueda"
			},
			fecha_hasta: {
				required: "Por favor, ingrese una fecha para realizar la busqueda"
			}
		},
		errorElement: "div",
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			error.insertAfter(element);
		},
		highlight: function (element) {
			$(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function (element) {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	});
});