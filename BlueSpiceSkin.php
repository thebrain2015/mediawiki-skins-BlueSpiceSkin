<?php
/**
 * BlueSpiceSkin skin
 *
 * @file
 * @ingroup Skins
 * @author Radovan Kubani, Robert Vogel, Patric Wirth, et. al.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
 
 if( !defined( 'MEDIAWIKI' ) ) die( "This is an extension to the MediaWiki package and cannot be run standalone." );

$wgExtensionCredits['skin'][] = array(
	'path' => __FILE__,
	'name' => 'BlueSpiceSkin',
	'url' => 'http://www.blue-spice.org',
	'author' => 'Radovan Kubani, Robert Vogel, Patric Wirth, et. al.',
	'descriptionmsg' => 'bluespiceskin-desc',
);

$wgValidSkinNames['bluespiceskin'] = 'BlueSpiceSkin';
$wgAutoloadClasses['SkinBlueSpiceSkin'] = dirname(__FILE__).'/BlueSpiceSkin.skin.php';
$wgAutoloadClasses['BlueSpiceSkinTemplate'] = dirname(__FILE__).'/BlueSpiceSkin.skin.php';
$wgExtensionMessagesFiles['BlueSpiceSkin'] = dirname(__FILE__).'/BlueSpiceSkin.i18n.php';

$wgResourceModules['ext.bluespice.bluespiceskin'] = array(
	'scripts' => 'BlueSpiceSkin/bluespice/main.js', //bottomscripts
	'styles'  => array( 
		'BlueSpiceSkin/bluespice/general.css',
		'BlueSpiceSkin/bluespice/main.css',
		'BlueSpiceSkin/bluespice/ajaxwatchlist.css',
		'common/commonElements.css'  => array( 'media' => 'screen' ),
		'common/commonContent.css'   => array( 'media' => 'screen' ),
		'common/commonInterface.css' => array( 'media' => 'screen' ),
	),
	'position' => 'top',
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'remoteBasePath' => &$GLOBALS['wgStylePath']
);
