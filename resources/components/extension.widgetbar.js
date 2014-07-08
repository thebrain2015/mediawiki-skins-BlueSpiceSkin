$(document).ready(function() {
	var widgetFlyout = $('#bs-flyout');
	if( widgetFlyout.length === 0 ) {
		return;
	}
	
	widgetFlyout.width( $('#content').width() );

	var widgetBarToggler = $('<li><a href="#" class="icon-globe"></a></li>');
	widgetBarToggler.hide();
	widgetBarToggler.click(function( e ){
		widgetFlyout.toggle();
		e.stopPropagation();
	});
	
	$(document).on('click', function(){
		widgetFlyout.hide();
	});
	
	$('#p-cactions').find('ul').first().append( widgetBarToggler );
	widgetBarToggler.fadeIn();
});

