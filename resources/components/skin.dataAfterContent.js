$(document).ready(function(){
	mw.loader.using('jquery.ui.tabs', function() {
		$("#bs-data-after-content").tabs({
			cookie: {
				expires: 30,
				name: mw.config.get( 'wgCookiePrefix' ) + 'bs-skin-tab-dataAfterContent'
			}
		});
	});
	//Show always the first tab when there is no cookie
	var tabs = $("#bs-data-after-content").children('div.ui-tabs-panel');
	if( tabs.length > 0 ) {
		var active = false;
		$(tabs).each(function(i, v){
			if( $(v).hasClass("ui-tabs-hide") ) {
				return;
			}
			active = true;
			return;
		});
		if( !active ) {
			$("#bs-data-after-content-tabs").find('a').first().click();
		}
	}

	$(document).on("onBsShoutboxAfterUpdated", function(e, BsShoutbox){
		$(".bs-sb-archive").addClass("icon-cancel-circle");
	});
});