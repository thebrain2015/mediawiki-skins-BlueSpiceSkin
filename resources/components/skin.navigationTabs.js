$(document).ready(function(){
	mw.loader.using('jquery.ui.tabs', function() {
		$("#bs-nav-sections").tabs({
			cookie: {
				expires: 30
			},
			create: function() {
				$("#bs-nav-sections").show();
			}
		});
	});
});