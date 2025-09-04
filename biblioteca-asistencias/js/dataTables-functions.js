// Ordena tablas con datatables
$(document).ready(function(){
	$('#estudiantes').DataTable({
		"order":[[3,"asc"]],
		language: {
			url: '../componentes/datatables-idioma-mx.json',
			paginate: {
				first: '«',
				previous: '‹',
				next: '›',
				last: '»'
			}
		},
		columnDefs: [
			{ orderable: false, targets: 5 },
			{ orderable: false, targets: 4 }
		]
	});
	$('#usuarios').DataTable({
		"order":[[1,"asc"]],
		language: {
			url: '../componentes/datatables-idioma-mx.json',
			paginate: {
				first: '«',
				previous: '‹',
				next: '›',
				last: '»'
			}
		},
		columnDefs: [
			{ orderable: false, targets: 5 },
			{ orderable: false, targets: 4 }
		]
	});
	$('#tabla_reportes').DataTable({
		"order":[[5,"asc"]],
		language: {
			url: '../componentes/datatables-idioma-mx.json',
			paginate: {
				first: '«',
				previous: '‹',
				next: '›',
				last: '»'
			}
		},
		columnDefs: [
			{ orderable: false, targets: 4 }
		]
	});
});