<?php

/**
 * BlueSpiceSkin skin
 *
 * @file
 * @ingroup Skins
 * @author Radovan Kubani, Robert Vogel, Patric Wirth, et. al.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
if (!defined('MEDIAWIKI'))
	die("This is an extension to the MediaWiki package and cannot be run standalone.");

$wgSkipSkins = array( 'chick', 'cologneblue', 'common', 'modern', 'monobook', 'myskin', 'nostalgia', 'simple', 'standard', 'vector' );
$GLOBALS['wgAutoloadClasses']['Mobile_Detect'] = __DIR__ . '/includes/lib/Mobile_Detect.php';
global $oMobileDetect;
$oMobileDetect = new Mobile_Detect();

if ($oMobileDetect->isMobile()) {
	$wgExtensionCredits['skin'][] = array(
		'path' => __FILE__,
		'name' => 'BlueSpiceSkinMobile',
		'url' => 'http://www.blue-spice.org',
		'author' => 'Radovan Kubani, Robert Vogel, Patric Wirth, et. al.',
		'descriptionmsg' => 'bluespiceskinmobile-desc',
	);

	$wgValidSkinNames['bluespiceskinmobile'] = 'BlueSpiceSkinMobile';

	$wgAutoloadClasses['SkinBlueSpiceSkinMobile'] = __DIR__ . '/BlueSpiceSkinMobile.skin.php';
	$wgAutoloadClasses['BlueSpiceSkinMobileTemplate'] = __DIR__ . '/BlueSpiceSkinMobile.skin.php';

	$wgHooks['OutputPageBodyAttributes'][] = "BlueSpiceSkinMobileTemplate::onBSAddBodyAttribute";

	$wgResourceModules['ext.bluespice.bluespiceskinmobile.js'] = array(
		'scripts' => array(
			'BlueSpiceSkin/resources/bluespiceskinmobile.skin.core.js',
			'BlueSpiceSkin/resources/bluespiceskinmobile.skin.main.js'
		),
		'dependencies' => array(
			'ext.bluespice'
		),
		'position' => 'top',
		'localBasePath' => &$GLOBALS['wgStyleDirectory'],
		'remoteBasePath' => &$GLOBALS['wgStylePath']
	);
	$wgResourceModules['ext.bluespice.bluespiceskinmobile'] = array(
		'styles' => array(
			'BlueSpiceSkin/resources/bluespiceskin.fonts.css',
			'BlueSpiceSkin/resources/bluespiceskinmobile.skin.main.css' => array('media' => 'screen'),
		),
		'position' => 'top',
		'group' => 'site',
		'localBasePath' => &$GLOBALS['wgStyleDirectory'],
		'remoteBasePath' => &$GLOBALS['wgStylePath']
	);
	$wgExtensionMessagesFiles['BlueSpiceSkin'] = __DIR__ . '/BlueSpiceSkin.i18n.php';
	$wgDefaultSkin = "bluespiceskinmobile";
} else {
	$wgExtensionCredits['skin'][] = array(
		'path' => __FILE__,
		'name' => 'BlueSpiceSkin',
		'url' => 'http://www.blue-spice.org',
		'author' => 'Radovan Kubani, Robert Vogel, Patric Wirth, et. al.',
		'descriptionmsg' => 'bluespiceskin-desc',
	);

	$wgValidSkinNames['bluespiceskin'] = 'BlueSpiceSkin';

	$wgAutoloadClasses['SkinBlueSpiceSkin'] = __DIR__ . '/BlueSpiceSkin.skin.php';
	$wgAutoloadClasses['BlueSpiceSkinTemplate'] = __DIR__ . '/BlueSpiceSkin.skin.php';
	$wgAutoloadClasses['BlueSpiceSkinHooks'] = __DIR__ . '/includes/BlueSpiceSkinHooks.php';
	$wgAutoloadClasses['ViewStateBarTopElementTools'] = __DIR__ . '/views/view.StateBarTopElementTools.php';
	$wgAutoloadClasses['ViewStateBarTopElementWatch'] = __DIR__ . '/views/view.StateBarTopElementWatch.php';

	$wgExtensionMessagesFiles['BlueSpiceSkin'] = __DIR__ . '/BlueSpiceSkin.i18n.php';

	$wgHooks['SkinTemplateOutputPageBeforeExec'][] = "BlueSpiceSkinHooks::onSkinTemplateOutputPageBeforeExec";
	$wgHooks['BSStateBarBeforeTopViewAdd'][] = "BlueSpiceSkinHooks::onBSStateBarBeforeTopViewAdd";
	$wgHooks['DoEditSectionLink'][] = "BlueSpiceSkinHooks::onDoEditSectionLink";
	$wgHooks['SkinBuildSidebar'][] = 'BlueSpiceSkinHooks::onSkinBuildSidebar';
	$wgHooks['BSGetLogo'][] = "BlueSpiceSkinHooks::onBSGetLogo";
	$wgHooks['VisualEditorConfig'][] = "BlueSpiceSkinHooks::onVisualEditorConfig";

	$wgResourceModules['ext.bluespice.bluespiceskin.js'] = array(
		'scripts' => array(
			'BlueSpiceSkin/resources/bluespiceskin.main.js', //bottomscripts
			'BlueSpiceSkin/resources/bluespiceskin.skin.main.js'
		),
		'dependencies' => array(
			'ext.bluespice',
			'mediawiki.jqueryMsg',
			'ext.echo.overlay'
		),
		'messages' => array(
			'bs-top-bar-messages',
			'bs-top-bar-review'
		),
		'position' => 'top',
		'localBasePath' => &$GLOBALS['wgStyleDirectory'],
		'remoteBasePath' => &$GLOBALS['wgStylePath']
	);
	$wgResourceModules['ext.bluespice.bluespiceskin'] = array(
		'styles' => array(
			'BlueSpiceSkin/resources/bluespiceskin.ajaxwatchlist.css',
			'BlueSpiceSkin/resources/bluespiceskin.print.css' => array('media' => 'print'),
			'common/commonElements.css' => array('media' => 'screen'),
			'common/commonContent.css' => array('media' => 'screen'),
			'common/commonInterface.css' => array('media' => 'screen')
		),
		'position' => 'top',
		'localBasePath' => &$GLOBALS['wgStyleDirectory'],
		'remoteBasePath' => &$GLOBALS['wgStylePath']
	);
	$wgResourceModules['ext.bluespice.bluespiceskin.main'] = array(
		'styles' => array(
			//'BlueSpiceSkin/bluespiceskin/bluespiceskin.skin.main.css'
			'BlueSpiceSkin/resources/bluespiceskin.fonts.css',
			'BlueSpiceSkin/resources/bluespiceskin.links.css',
			'BlueSpiceSkin/resources/bluespiceskin.form.css',
			'BlueSpiceSkin/resources/bluespiceskin.skin.main.css'
		),
		'position' => 'top',
		'group' => 'site',
		'localBasePath' => &$GLOBALS['wgStyleDirectory'],
		'remoteBasePath' => &$GLOBALS['wgStylePath']
	);
	$wgResourceModules['ext.bluespice.bluespiceskin.content'] = array(
		'styles' => 'BlueSpiceSkin/resources/bluespiceskin.content.css',
		'position' => 'top',
		'localBasePath' => &$GLOBALS['wgStyleDirectory'],
		'remoteBasePath' => &$GLOBALS['wgStylePath']
	);

	$wgResourceModules['ext.bluespice.extensions'] = array(
		'styles' => 'BlueSpiceSkin/resources/bluespiceskin.notifications.css',
		'dependencies' => array(
			'ext.bluespice.bluespiceskin',
			'ext.bluespice.bluespiceskin.main',
		),
		'position' => 'bottom',
		'group' => 'site',
		'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
		'remoteBasePath' => &$GLOBALS['wgStylePath']
	);
	$wgResourceModules['ext.bluespice.bluespiceskin.icons'] = array(
		'styles' => 'BlueSpiceSkin/resources/bluespiceskin.icons.css',
		'position' => 'top',
		'group' => 'site',
		'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
		'remoteBasePath' => &$GLOBALS['wgStylePath']
	);
	$wgDefaultSkin = "bluespiceskin";
}