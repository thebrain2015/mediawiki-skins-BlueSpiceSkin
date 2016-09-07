$( document ).on( 'click', '#bs-cexport-button > a', function( e ) {
	e.preventDefault();
	$( this ).toggleClass( "open" );
	$( "li#bs-cexport-button div.menu" ).fadeToggle( 200 );
	e.stopPropagation();
});