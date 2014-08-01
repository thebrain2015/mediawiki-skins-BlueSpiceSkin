$(document).ready(function(){
	mw.loader.using('jquery.ui.tabs', function() {
		$("#bs-data-after-content").tabs({
			cookie: {
				expires: 30
			},
			selected: 0
		});
	});
});