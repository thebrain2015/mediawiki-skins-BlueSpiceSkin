/**
 * BlueSpiceSkin
 *
 * @author     Sebastian Ulbricht
 * @author     Robert Vogel <vogel@hallowelt.biz>
 * @author     Markus Glaser <glaser@hallowelt.biz>
 * @author     Stephan Muggli <muggli@hallowelt.biz>
 * @version    1.20.1
 * @version    $Id: main.js 9693 2013-06-12 11:45:38Z swidmann $
 * @copyright  Copyright (C) 2012 Hallo Welt! - Medienwerkstatt GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v2 or later
 * @filesource
 */

////Hints:
//http://forum.jquery.com/topic/hover-and-toggleclass-fail-at-speed

BlueSpiceSkin = {
	skinpath: '',
	init: function() {
		this.skinpath = stylepath + '/BlueSpiceSkin';
		this.initNavigationTabs();
		this.initMoreMenu();
		this.initSearchBox();
		this.initTables();
		this.initExceptionViews();
		this.initTooltips();
	},

	initNavigationTabs: function() {
		$("#bs-nav-sections").tabs({
			cookie: {
				expires: 30
			}
		});
	},

	initMoreMenu: function() {
		$('#ca-more').hover(function() {
			$('#ca-more-arrow').attr('src', BlueSpiceSkin.skinpath +'/bluespice/bs-moremenu-more.png');
			$('#ca-more-menu').stop(true,true).slideDown('fast');
		}, function() {
			$('#ca-more-arrow').attr('src', BlueSpiceSkin.skinpath +'/bluespice/bs-moremenu-less.png');
			$('#ca-more-menu').stop(true,true).delay(200).slideUp('fast');
		});
	},

	initSearchBox: function() {
		var searchInput = $('#bs-search-input');

		searchInput.attr( 'defaultValue', searchInput.val() );

		$('.bs-search-form').on( 'submit', function( e ) {
			if ( $( this ).attr( 'defaultValue' ) == $( this ).val() ) {
				e.preventDefault();
			}
		} );

		if ( typeof bsExtendedSearchSetFocus === "boolean" ) {
			//$(document).scrollTop(): prevent loosing last scroll position on history back
			if ( wgIsArticle === true && bsExtendedSearchSetFocus  === true && $(document).scrollTop() < 1) {
				if ( window.location.hash === '' ) {
					searchInput.val('').focus();
				}
			}
		}

		searchInput.keypress( function( e ) {
			if ( e.keyCode !== 13 ) {
				if ( $(this).val() == $(this).attr( 'defaultValue' ) ) {
					$(this).val('');
				}
			}
		}).focus( function() {
			if ( $(this).val() == $(this).attr( 'defaultValue' ) ) {
				$(this).val('');
			}

		}).blur( function() {
			if($(this).val() == '' || $(this).val() == $(this).attr( 'defaultValue' ) ) {
				$(this).val( $(this).attr( 'defaultValue' ) );
			}
		});
	},

	initTables: function() {
		$('.bs-table-zebra tr:nth-child(odd)').addClass('bs-zebra-table-row-odd');
		$('.bs-table-zebra tr:nth-child(even)').addClass('bs-zebra-table-row-even');
		$('.bs-table-highlight-rows tr').hover(function(){
			$(this).stop(true,true).toggleClass('bs-highlighted-row', 'fast');
		});
		$('.bs-table-highlight-cells td').hover(function(){
			$(this).stop(true,true).toggleClass('bs-highlighted-cell', 'fast');
		});
	},

	initExceptionViews: function() {
		$('.bs-exception-stacktrace').hide();
		$('.bs-exception-stacktrace-toggle').children().first().show();
		$('.bs-exception-stacktrace-toggle').toggle(
			function() {
				$(this).next().slideDown( 500 );
				$(this).children().first().hide();
				$(this).children().first().next().show();
			},
			function() {
				$(this).next().slideUp( 500 );
				$(this).children().first().next().hide();
				$(this).children().first().show();
			}
		);
	},

	disableEffects: function() {
		$.fx.off = false;
	},

	initTooltips: function() {
		$('.bs-tooltip-link').bstooltip();
	}
};

$(document).ready( function(){
	BlueSpiceSkin.init();
});