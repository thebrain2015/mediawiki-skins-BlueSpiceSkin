$(document).on( 'click', '*, .menuToggler', function(e){
		var $target = $(e.target);
		if( !$target.is( 'a' ) ) {
			$target = $target.find( 'a' );
		}
		$( '.menuToggler' ).each(function() {
			var $currentToggler = $(this);
			if( $target[0] !== $currentToggler[0] ) {
				$currentToggler
					.removeClass( 'open' )
					.next( '.menu' ).fadeOut( 200 );
			}
		});
	});