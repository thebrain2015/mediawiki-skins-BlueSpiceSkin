$(document).on( 'mouseover', '#bs-button-user', function() {
	$('#bs-personal-menu').stop().fadeIn('fast');
});

$(document).on( 'mouseout', '#bs-button-user', function() {
	$('#bs-personal-menu').stop().fadeOut('fast');
});