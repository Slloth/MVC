$(document).ready(function () {
	$('.Delete').click(function (e) {
		var result = confirm("Etes-vous sûr ?");
		if (!result) {
			e.preventDefault();
		}
	});
	$("#myInput").on("keyup", function () {
		var value = $(this).val().toLowerCase();
		$("#myTable tr").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
});