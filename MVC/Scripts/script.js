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


	$('#TableIndex').after('<div id="nav"></div>'); // Ajout d'une div avec le tableau
	var rowsShown = 5; // Nombre de row a affiché
	var rowsTotal = $('#TableIndex tbody tr').length; // nombre total de row = taille du tableau
	var numPages = rowsTotal / rowsShown; // Nombre de page = division du deuxieme par le premier
	for (i = 0; i < numPages; i++) { // bouclier pour link les pages
		var pageNum = i + 1;
		$('#nav').append('<a href="#" class="btn btn-default btn-dark" rel="' + i + '">' + pageNum + '</a> ');
	}
	$('#TableIndex tbody tr').hide(); // Cache les tr 
	$('#TableIndex tbody tr').slice(0, rowsShown).show(); // Affiche les tr de 0 au nombre de vue desiré
	$('#nav a:first').addClass('active'); // Ajout d'une classe active au lien NAV
	$('#nav a').bind('click', function () { // sur click de NAV 

		$('#nav a').removeClass('active'); // on enleve la classe
		$(this).addClass('active'); // on l'ajoute a la suite 
		var currPage = $(this).attr('rel'); // on ajoute l'attribut rel a la plage 
		var startItem = currPage * rowsShown;
		var endItem = startItem + rowsShown;
		$('#TableIndex tbody tr').css('opacity', '0.0').hide().slice(startItem, endItem).
			css('display', 'table-row').animate({ opacity: 1 }, 300); // Animation du slice 
	});
});