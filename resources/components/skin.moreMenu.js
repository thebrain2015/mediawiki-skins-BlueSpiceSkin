$( document ).on( 'click', '#bs-cactions-button > a', function( e ) {
	e.preventDefault();
	$( this ).toggleClass( "open" );
	$( "li#bs-cactions-button div.menu" ).fadeToggle( 200 );
	e.stopPropagation();
});