$(document).ready(function() {
	if ($("#bs-nav-small").length != 0 && $("#bs-left-resize-btn").length != 0) {
		$("#bs-nav-sections").append($("#bs-nav-small"));

		$("#bs-left-resize-btn").bind('click', function() {
			if ($('#bs-nav-small').css('display') == 'none') {
				$(this).fadeOut(300, function() {
					//$(this).css('background-image', 'url( '+wgScriptPath+'/skins/BlueSpiceSkin/resources/images/desktop/navi-expand-btn.png)').fadeIn();
					$(this).removeClass("extended").addClass("narrowed").fadeIn();
				});

				$("#bs-nav-sections > div").each(function(i, v) {
					if ($(v).attr('id') != 'bs-nav-small')
						$(v).fadeOut();
				});

				$("#bs-nav-sections > ul").fadeOut(400, function() {
					$("#bs-nav-small").fadeIn();
				});

				$("#bs-left-column").animate({width: '60px'});

				if ($("#bs-left-column").css('float') == 'right') {
					$("#bs-content-column, #footer").animate({"margin-right": '90px'});
				}else {
					$("#bs-content-column, #footer").animate({"margin-left": '90px'});
				}

				var margin = parseInt($("#bs-tools-container").css('margin-left').replace('px', ''));
				$("#bs-tools-container").animate({
					'width': ($("#bs-tools-container").width() + 110) + "px",
					'margin-left': margin - 110 + "px"
				});
				var newWidth = parseInt($("#bs-tools-container").width() + 110 - $("#bs-tools-shadow-right").outerWidth() - $("#bs-tools-shadow-left").outerWidth());
				$("#bs-tools-shadow-bottom-middle").animate({'width' : (newWidth + 1) + 'px'});
				$("#bs-tools-widgets").animate({'width': (newWidth + 1) + "px"});
				$("#bs-tools-widgets > div").each(function(i, v){
					$(v).animate({'width' : (newWidth / 5)-1 + "px"});
					
				});
			} else {
				$("#bs-nav-small").fadeOut(400, function() {
					$("#bs-nav-sections > ul").fadeIn();
					$("#bs-nav-sections > div").each(function(i, v) {
						if ($(v).attr('id') != 'bs-nav-small')
							$(v).fadeIn();
					});
				});

				$(this).fadeOut(300, function() {
					//$(this).css('background-image', 'url('+wgScriptPath+'/skins/BlueSpiceSkin/resources/images/desktop/navi-collapse-btn.png)').fadeIn();
					$(this).removeClass("narrowed").addClass("extended").fadeIn();
				});

				var new_height = 0;
				$("#bs-nav-sections > div").each(function(i, v) {
					if ($(v).attr('id') != 'bs-nav-small')
						new_height += $(v).height();
				});
				new_height += $("#bs-nav-sections > ul").height();

				$("#bs-left-column").animate({width: '170px'});

				if ($("#bs-left-column").css('float') == 'right') {
					$("#bs-content-column, #footer").animate({"margin-right": '200px'});
				} else {
					$("#bs-content-column, #footer").animate({"margin-left": '200px'});
				}

				var margin = parseInt($("#bs-tools-container").css('margin-left').replace('px', ''));
				$("#bs-tools-container").animate({
					'width' : $("#bs-tools-container").width() - 110 + "px",
					'margin-left': margin + 110 + "px"
				});
				var newWidth = $("#bs-tools-container").width() - 110 - $("#bs-tools-shadow-right").outerWidth() - $("#bs-tools-shadow-left").outerWidth();
				$("#bs-tools-shadow-bottom-middle").animate({'width' : newWidth + 1});
				$("#bs-tools-widgets").animate({'width': newWidth + 1 + "px"});
				$("#bs-tools-widgets > div").each(function(i, v){
					$(v).animate({'width' : (newWidth / 5)-1 + 'px'});
					
				});
			}
		});
	}
	$("#bs-statebar-viewtoggler-image").attr('src', wgScriptPath + '/skins/BlueSpiceSkin/resources/images/desktop/statusbar-btn_more.png');
	$("#bs-statebar-viewtoggler-image").css('display', 'block');
	$("#bs-statebar-viewtoggler-image").css('margin', '0 auto');
	$("#bs-beforearticlecontent").append($("#bs-statebar-viewtoggler"));
	if (typeof BsStateBar != 'undefined') {
		BsStateBar.imagePathActive = wgScriptPath + '/skins/BlueSpiceSkin/resources/images/desktop/statusbar-btn_less.png';
		BsStateBar.imagePathInactive = wgScriptPath + '/skins/BlueSpiceSkin/resources/images/desktop/statusbar-btn_more.png';
	}
	$("#bs-statebar-viewtoggler-image").toggle(
		function() {
			$(this).css('margin-top', "0 !important");
			$(this).css('margin-right', "auto !important");
			$(this).css('margin-bottom', "0 !important");
			return true;
		}, 
		function() {
			$(this).css('margin-top', "0 !important");
			$(this).css('margin-right', "0 !important");
			$(this).css('margin-bottom', "0 !important");
			return true;
		}
	);
	/*BlueSpice.Skin.initWidgets = function(){};
	 BlueSpice.Skin.initMoreMenu = function(){};
	 BlueSpice.Skin.initPersonalMenu = function(){};*/
	$('#bs-search-input').focus(function(e) {
		if ($(this).val() == $(this).attr('defaultValue'))
			$(this).val('');
		e.stopPropagation();
		return false;
	}).blur(function(e) {
		if ($(this).val() == '' || $(this).val() == $(this).attr('defaultValue')) {
			$(this).val($(this).attr('defaultValue'));
		}
		e.stopPropagation();
		return false;
	});
	var height = 0;
	$("#bs-flyout-content div.bs-widget").each(function(i, v) {
		//$("#bs-tools-widgets").append($(v));
		$(v).find("h5, li, em").each(function(i2, v2) {
			if ($(v2).text().length > 25)
				height += 55;
			else
				height += 30;
		});
		//height += $(v).height();
	});
	var column = 0;
	$("#bs-flyout-content div.bs-widget").each(function(i, v) {

		$(v).find('.bs-widget-body').css('display', 'block');
		var sCookieKey = $(v).attr('id') + '-viewstate';
		$.cookie(sCookieKey, null, {path: '/'});
		$(v).find('.bs-widget-head').click(function(e) {
			e.stopPropagation();
		});
		if (($("#bs-tools-widgets-column-" + column).height() + $(v).height()) > height / 4)
			column++;
		$("#bs-tools-widgets-column-" + column).append($(v));
	});
	var biggest = 0;
	$('.bs-tools-widgets-column').each(function(i, v) {
		if (biggest < $(v).height())
			biggest = $(v).height();
	});
	/*$('.bs-tools-widgets-column').each(function(i, v){
	 $(v).height(biggest);
	 });*/
	$("#bs-widget-container").detach();
	$("#bs-tools-container").css('display', 'none');
	$('#bs-tools').click(function() {
		if ($("#bs-tools-container").css('visibility') === 'hidden') {
			$("#bs-tools-img").css("background-position", "0px -18px");

			if ($('#bs-tools-container').css('visibility') == 'hidden') {
				$('#bs-tools-container').css('visibility', 'visible');
			}

			$('#bs-tools-container').fadeIn();
			return;
		} else {
			$('#bs-tools-container').fadeOut(function() {
				$("#bs-tools-img").css("background-position", "0px 0px");
				$('#bs-tools-container').css('visibility', 'hidden');
			});
			return;
		}
	});

	$("#bs-tools-widgets").width($("#bs-beforearticlecontent").outerWidth());
	$("#bs-tools-container").width($("#bs-beforearticlecontent").outerWidth() + 30);
	$("#bs-tools-shadow-bottom-middle").width($("#bs-beforearticlecontent").outerWidth());
	$(".bs-tools-widgets-column").each(function(i, v) {
		$(v).css("min-height", biggest + "px");
		$(v).width($("#bs-tools-widgets").width() / 5);
	});
	$("#bs-tools-more").css("min-height", biggest + "px");
	$("#bs-tools-more").width($("#bs-tools-widgets").width() / 5 - 5);
	var elemWidth = 200;
	if ($(".bs-tools-widgets-column").width() !== null) {
		elemWidth = $(".bs-tools-widgets-column").width();
	}
	$("#bs-tools-container").css('margin-left', (($("#bs-beforearticlecontent").outerWidth() - 36) * -1) + "px");
	$("#bs-afterarticlecontent").append($('#footer-info'));

	$("#mw-data-after-content >div > span").css('height', 'auto');

	var user_menu = $("#bs-user-container").clone();
	user_menu.attr('id', "bs-user-container-open");
	user_menu.find("div, ul, li").each(function(i, v) {
		if ($(v).attr('id') != "")
			$(v).attr('id', $(v).attr('id') + "-open");
	});
	$("#bs-user-container").after(user_menu);
	$('#pt-notifications-open a').click(function(e) {
		e.preventDefault();
		$('#pt-notifications a').click();
	});
	$("#pt-notifications-open a").text(mw.message('bs-top-bar-messages', $("#pt-notifications-open a").text()).text());
	$('#bs-button-user, #bs-button-logout').mouseenter(function(e) {
		e.preventDefault();
		$("#bs-user-container-open").fadeIn();
		$("#pt-notifications").fadeOut();
	});
	$("#bs-user-container-open").mouseleave(function() {
		//$('#bs-user-container').append($('#pt-notifications'));
		$("#bs-user-container-open").fadeOut();
		$("#pt-notifications").fadeIn();
	});
        var count = $("#review-userbar-element-open").text().replace(/(\r\n|\n|\r)/gm, "").replace(/\s+/g, '').split('|');
        count = count[0];
	var oLink = $("#review-userbar-element-open a");
	oLink.html(mw.message('bs-top-bar-review', +count).text());
	$("#review-userbar-element-open").html(oLink);
	if ($("div.mw-badge-important").length > 0)
		$("#pt-notifications").css("background-position", "0px -10px");
	if ($("#bs-afterarticlecontent div.bs-readers").length > 0) {
		var authors = $("<div id='bs-authors-img-container'>");
		var readers = $("<div id='bs-readers-img-container'>");
		$("div.bs-authors legend").addClass("authors-legend");
		$("div.bs-readers legend").addClass("readers-legend");
		$("div.bs-authors legend").after($("div.bs-readers legend"));
		$("div.bs-authors div").each(function(i, v) {
			authors.append($(v));
		});
		$("div.bs-readers div").each(function(i, v) {
			readers.append($(v));
		});
		$("div.bs-authors fieldset").append(authors);
		$("div.bs-authors fieldset").append(readers);
		$("legend.authors-legend").addClass('active');
		$("div.bs-authors").addClass('tabbed');
		$("legend.readers-legend").bind('click', function() {
			$("#bs-authors-img-container").css('display', 'none');
			$("#bs-readers-img-container").css('display', 'block');
			$(this).addClass('active');
			$("legend.authors-legend").removeClass('active');
		});
		$("legend.authors-legend").bind('click', function() {
			$("#bs-readers-img-container").css('display', 'none');
			$("#bs-authors-img-container").css('display', 'block');
			$(this).addClass('active');
			$("legend.readers-legend").removeClass('active');
		});
		$("div.bs-readers").css('display', 'none');
	}
	$("#ca-more-top").hover(function() {
		$("#p-cactions-list-more").css('display', 'block');
	}, function() {
		$("#p-cactions-list-more").css('display', 'none');
	});
	$(document).ajaxSuccess(function(event, XMLHttpRequest, ajaxOptions) {
		if (typeof (ajaxOptions.data) === "undefined" || ajaxOptions.data === null || ajaxOptions.data.indexOf("watch") === -1)
			return;
		var response = $.parseJSON(XMLHttpRequest.responseText);
		if (typeof (response.watch.watched) !== "undefined")
			$("#ca-watch a").addClass("watched");
		else
			$("#ca-watch a").removeClass("watched");
	});
});