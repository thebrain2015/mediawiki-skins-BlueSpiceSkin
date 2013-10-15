$(document).ready(function(){
	if ($("#bs-nav-small").length != 0 && $("#bs-left-resize-btn").length != 0){
		$("#bs-nav-sections").append($("#bs-nav-small"));
		
		$("#bs-left-resize-btn").bind('click', function(){
			if ($('#bs-nav-small').css('display') == 'none'){
				$(this).fadeOut(300, function(){
					$(this).css('background-image', 'url( '+wgScriptPath+'/skins/BlueSpiceSkin/resources/images/desktop/navi-expand-btn.png)').fadeIn();

				});
				$("#bs-nav-sections > div").each(function(i, v){
					if ($(v).attr('id') != 'bs-nav-small')
						$(v).fadeOut();
				});
				$("#bs-nav-sections > ul").fadeOut(400, function(){
					$("#bs-nav-small").fadeIn();
				});
				$("#bs-left-column").animate({width: '60px'});
                                if ($("#bs-left-column").css('float') == 'right')
                                        $("#bs-content-column, #footer").animate({"margin-right": '90px'});
                                else
                                        $("#bs-content-column, #footer").animate({"margin-left": '90px'});
				$("#bs-tools-container").css('width', $("#bs-tools-container").width() + 110 + "px");
                                var margin = +$("#bs-tools-container").css('margin-left').replace('px', '');
				$("#bs-tools-container").css('margin-left', margin-110+"px");
			}
			else{
				$("#bs-nav-small").fadeOut(400, function(){
					$("#bs-nav-sections > ul").fadeIn();
					$("#bs-nav-sections > div").each(function(i, v){
							if ($(v).attr('id') != 'bs-nav-small')
								$(v).fadeIn();
					});
				});
				$(this).fadeOut(300, function(){
					$(this).css('background-image', 'url('+wgScriptPath+'/skins/BlueSpiceSkin/resources/images/desktop/navi-collapse-btn.png)').fadeIn();
				});
				var new_height = 0;
				$("#bs-nav-sections > div").each(function(i, v){
					if ($(v).attr('id') != 'bs-nav-small')
						new_height += $(v).height();
				});
				new_height += $("#bs-nav-sections > ul").height();
				$("#bs-left-column").animate({width: '170px'});
                                if ($("#bs-left-column").css('float') == 'right')
                                        $("#bs-content-column, #footer").animate({"margin-right": '200px'});
                                else
                                        $("#bs-content-column, #footer").animate({"margin-left": '200px'});
				$("#bs-tools-container").css('width', $("#bs-tools-container").width() - 110 + "px");
                                var margin = +$("#bs-tools-container").css('margin-left').replace('px', '');
				$("#bs-tools-container").css('margin-left', margin+110+"px");
			}
		});
	}
	$("#bs-statebar-viewtoggler-image").attr( 'src', wgScriptPath+'/skins/BlueSpiceSkin/resources/images/desktop/statusbar-btn_more.png');
	$("#bs-statebar-viewtoggler-image").css('display', 'block');
	$("#bs-beforearticlecontent").append($("#bs-statebar-viewtoggler"));
	if (typeof BsStateBar != 'undefined'){
		BsStateBar.imagePathActive = wgScriptPath+'/skins/BlueSpiceSkin/resources/images/desktop/statusbar-btn_less.png';
		BsStateBar.imagePathInactive = wgScriptPath+'/skins/BlueSpiceSkin/resources/images/desktop/statusbar-btn_more.png';
	}
	/*BlueSpice.Skin.initWidgets = function(){};
	BlueSpice.Skin.initMoreMenu = function(){};
	BlueSpice.Skin.initPersonalMenu = function(){};*/
	$('#bs-search-input').focus(function(e){
		if( $(this).val() == $(this).attr( 'defaultValue' ) ) $(this).val('');
		e.stopPropagation();
		return false;
	}).blur(function(e){
		if($(this).val() == '' || $(this).val() == $(this).attr( 'defaultValue' ) ) {
			$(this).val( $(this).attr( 'defaultValue' ) );
		}
		e.stopPropagation();
		return false;
	});
	var height = 0;
	$("#bs-flyout-content div.bs-widget").each(function(i, v){
		//$("#bs-tools-widgets").append($(v));
		height += $(v).height();
	});
	var column = 0;
	$("#bs-flyout-content div.bs-widget").each(function(i, v){
		
		$(v).find('.bs-widget-body').css('display', 'block');
		var sCookieKey = $(v).attr('id')+'-viewstate';
		$.cookie(sCookieKey, null, {path: '/'});
		$(v).find('.bs-widget-head').click(function(e){e.stopPropagation();});
		$("#bs-tools-widgets-column-" + column).append($(v));
		if (($("#bs-tools-widgets-column-" + column).height() + $(v).height()) > height/4)
			column++;
	});
	var biggest = 0;
	$('.bs-tools-widgets-column').each(function(i, v){
		if (biggest < $(v).height())
			biggest = $(v).height();
	});
	$('.bs-tools-widgets-column').each(function(i, v){
			$(v).height(biggest);
	});
	$("#bs-widget-container").detach();
	$("#bs-tools-container").css('display', 'none');
	$('#bs-tools').hover(function() {
		//$('#bs-flyout').stop(true,true).fadeIn();
		
		
		$("#bs-tools-img").css("background-position", "0px -18px");
		if ($('#bs-tools-container').css('visibility') == 'hidden')
			$('#bs-tools-container').css('visibility', 'visible');
		$('#bs-tools-container').fadeIn();
	}, function() {
		//$('#bs-flyout').stop(true,true).delay(200).fadeOut();
		
		$('#bs-tools-container').stop(true,true).delay(200).fadeOut(function(){
			$("#bs-tools-img").css("background-position", "0px 0px");
		});
	});
	$("#bs-afterarticlecontent").append($('#footer-info'));
	$("#mw-data-after-content >div > span").css('height', 'auto');
	
	var user_menu = $("#bs-user-container").clone();
	user_menu.attr('id', "bs-user-container-open");
	user_menu.find("div, ul, li").each(function(i, v){
		if ($(v).attr('id') != "")
			$(v).attr('id', $(v).attr('id') + "-open");
	});
	$("#bs-user-container").after(user_menu);
	$('#pt-notifications-open a').click(function(e){
		e.preventDefault();
		$('#pt-notifications a').click();
	});
	$("#pt-notifications-open a").text($("#pt-notifications-open a").text() + " " + mw.message('bs-top-bar-messages').plain());
	$('#bs-button-user').mouseenter( function(e){
		e.preventDefault();
		$("#bs-user-container-open").fadeIn();
	});
	$("#bs-user-container-open").mouseleave(function(){
		//$('#bs-user-container').append($('#pt-notifications'));
		$("#bs-user-container-open").fadeOut();
	});
	var count =$("#review-userbar-element-open").text().replace(/(\r\n|\n|\r)/gm,"").replace(/\s+/g, '');
	var oLink = $("#review-userbar-element-open a");
	oLink.html(mw.message('bs-top-bar-review', +count).text());
	$("#review-userbar-element-open").html(oLink);
});