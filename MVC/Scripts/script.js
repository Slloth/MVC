$('.Delete').click(function (e) {
	var result = confirm("Etes-vous sûr ?");
	if (!result) {
		e.preventDefault();
	}
}); 