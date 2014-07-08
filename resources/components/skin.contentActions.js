$(document).on( 'mouseover', '#bs-cactions-button', function() {
	$(this).find('.menu').stop().fadeIn();
});

$(document).on( 'mouseout', '#bs-cactions-button', function() {
	$(this).find('.menu').stop().fadeOut();
});

(function(mw, $){
	$(function(){
		if ( wgIsArticle === true ) {
			$.getJSON(
				bs.util.getAjaxDispatcherUrl('BlueSpiceSkinHooks::ajaxGetDiscussionCount', [], true),
				function( data ) {
					if(data.success) {
						var discussAmount = $('<span> ('+data.payload+')</span>');
						discussAmount.hide();
						discussAmount.appendTo( $('#ca-talk > a').first() );
						discussAmount.css('display', 'inline').fadeIn();
					}
				}
			);
		}
	});
})( mediaWiki, jQuery );