<?php

/**
 * BlueSpiceSkin skin
 *
 * @file
 * @ingroup Skins
 * @author Radovan Kubani, Robert Vogel, Patric Wirth, Tobias Weichart et. al.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
if (!defined('MEDIAWIKI')) {
	die("This is an extension to the MediaWiki package and cannot be run standalone.");
}

wfLoadSkin( 'BlueSpiceSkin' );

$wgValidSkinNames['bluespiceskin'] = 'BlueSpiceSkin';

//TODO: Move to Foundation and use API instead.
$wgAjaxExportList[] = 'BlueSpiceSkinHooks::ajaxGetDiscussionCount';

$wgDefaultSkin = 'bluespiceskin';
$wgSkipSkins = array(
	'chick',
	'cologneblue',
	'common',
	'modern',
	'monobook',
	'myskin',
	'nostalgia',
	'simple',
	'standard'
);
