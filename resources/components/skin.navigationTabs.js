$(document).ready(function(){
		$("#bs-nav-sections").tabs({
			cookie: {
				expires: 30,
				name: mw.config.get( 'wgCookiePrefix' ) + 'bs-skin-tab-navigationTabs'
			}
		});
});