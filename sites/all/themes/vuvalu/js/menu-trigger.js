jQuery(document).ready(function($){

	// open menu
	$('.menu-trigger').on('click', function(event){
		event.preventDefault();
		$('#menubar').addClass('is-visible');
	});
	
	// close menu
	$('.close-menu').on('click', function(event){
		event.preventDefault();
		$('#menubar').removeClass('is-visible');
	});



});