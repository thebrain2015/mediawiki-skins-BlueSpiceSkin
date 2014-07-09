/*
 * WPtouch 1.9.x -The WPtouch Core JS File
 * This file holds all the default jQuery & Ajax functions for the theme
 * Copyright (c) 2008-2009 Duane Storey & Dale Mugford (BraveNewCode Inc.)
 * Licensed under GPL.
 *
 * Last Updated: December 24th, 2009
 */

/////// -- Get out of frames! -- ///////
if (top.location!= self.location) {top.location = self.location.href}


/////// -- Let's play nice in jQuery -- ///////
//jQuery = jQuery.noConflict();


/////// -- Switch Magic -- ///////
window.wptouch_switch_confirmation= function() {
if (document.cookie && document.cookie.indexOf("wptouch_switch_cookie") > -1) {
// just switch
	jQuery("a#switch-link").toggleClass("offimg");
	setTimeout('switch_delayer()', 1250); 
} else {
// ask first
	var answer = confirm("Switch to regular view? \n \n You can switch back to mobile view again in the footer.");
	if (answer){
	jQuery("a#switch-link").toggleClass("offimg");
	setTimeout('switch_delayer()', 1350); 
		}
	}
}

setTimeout(function() { jQuery('#prowl-success').fadeOut(400); }, 5250);
setTimeout(function() { jQuery('#prowl-fail').fadeOut(400); }, 5250);

//  function wptouch_toggle_text() {
//	  jQuery("p").toggleClass("fontsize");
//  }


/////// -- Menus -- ///////
// Creating a new function, fadeToggle()
jQuery.fn.fadeToggle = function(speed, easing, callback) { 
	return this.animate({opacity: 'toggle'}, speed, easing, callback); 
};
 
window.bnc_jquery_menu_drop= function() {
	jQuery('#wptouch-menu').fadeToggle(400);
	jQuery("#headerbar-menu a").toggleClass("open");
};

window.bnc_jquery_login_toggle= function() {
	jQuery('#wptouch-login').fadeToggle(400);
};

window.bnc_jquery_search_toggle= function() {
	jQuery('#wptouch-search').fadeToggle(400);
}

window.bnc_jquery_gigpress_toggle= function() {
	jQuery('#wptouch-gigpress').fadeToggle(400);
};


window.bnc_jquery_prowl_open= function() {
	jQuery('#prowl-message').fadeToggle(400);
};

window.bnc_jquery_wordtwit_open= function() {
	jQuery('#wptouch-wordtwit').fadeToggle(400);
};


/////// -- Ajax comments -- ///////
window.bnc_showhide_coms_toggle= function() {
	jQuery('#commentlist').fadeToggle(400);
	jQuery("img#com-arrow").toggleClass("com-arrow-down");
	jQuery("h3#com-head").toggleClass("comhead-open");
};

window.commentAdded= function() {
    if (jQuery('#errors')) {
        jQuery('#errors').hide();
	}
        
    if (jQuery('#nocomment')) {
        jQuery('#nocomment').hide();
    }
    
    if(jQuery('#hidelist')) {
        jQuery('#hidelist').hide();
    }

    jQuery("#commentform").hide();
    jQuery("#the-new-comment").fadeIn(400);
    jQuery("#refresher").fadeIn(400);
};


/////// --Single Post Page -- ///////

window.wptouch_toggle_twitter= function() {
	jQuery('#twitter-box').fadeToggle(400);
};

window.wptouch_toggle_bookmarks= function() {
	jQuery('#bookmark-box').fadeToggle(400);
};

/////// --jQuery Tabs-- ///////

jQuery(function () {
    var tabContainers = jQuery('#menu-head > ul');
    
    jQuery('#tabnav a').click(function () {
        tabContainers.hide().filter(this.hash).show();
        
        jQuery('#tabnav a').removeClass('selected');
        jQuery(this).addClass('selected');
        
        return false;
    }).filter(':first').click();
});

/////// -- Tweak jQuery Timer -- ///////
jQuery.timerId = setInterval(function(){
	var timers = jQuery.timers;
	for (var i = 0; i < timers.length; i++) {
		if (!timers[i]()) {
			timers.splice(i--, 1);
		}
	}
	if (!timers.length) {
		clearInterval(jQuery.timerId);
		jQuery.timerId = null;
	}
}, 83);

// End WPtouch jS