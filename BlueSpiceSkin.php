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

$wgExtensionCredits['skin'][] = array(
	'path' => __FILE__,
	'name' => 'BlueSpiceSkin',
	'url' => 'http://www.blue-spice.org',
	'author' => 'Radovan Kubani, Robert Vogel, Patric Wirth, Tobias Weichart et. al.',
	'descriptionmsg' => 'bluespiceskin-desc',
);

$wgValidSkinNames['bluespiceskin'] = 'BlueSpiceSkin';

$wgAutoloadClasses['SkinBlueSpiceSkin'] = __DIR__ . '/BlueSpiceSkin.skin.php';
$wgAutoloadClasses['BlueSpiceSkinTemplate'] = __DIR__ . '/BlueSpiceSkin.skin.php';
$wgAutoloadClasses['BlueSpiceSkinHooks'] = __DIR__ . '/includes/BlueSpiceSkinHooks.php';
$wgAutoloadClasses['ViewStateBarTopElementTools'] = __DIR__ . '/views/view.StateBarTopElementTools.php';
$wgAutoloadClasses['ViewStateBarTopElementWatch'] = __DIR__ . '/views/view.StateBarTopElementWatch.php';

$wgMessagesDirs['BlueSpiceSkin'] = __DIR__ . '/i18n';

$wgExtensionMessagesFiles['BlueSpiceSkin'] = __DIR__ . '/BlueSpiceSkin.i18n.php';

//$wgHooks['BSStateBarBeforeTopViewAdd'][] = "BlueSpiceSkinHooks::onBSStateBarBeforeTopViewAdd";
$wgHooks['DoEditSectionLink'][] = "BlueSpiceSkinHooks::onDoEditSectionLink";
$wgHooks['SkinBuildSidebar'][] = 'BlueSpiceSkinHooks::onSkinBuildSidebar';
$wgHooks['SkinTemplateNavigation::Universal'][] = 'BlueSpiceSkinHooks::onSkinTemplateNavigationUniversal';

$wgAjaxExportList[] = 'BlueSpiceSkinHooks::ajaxGetDiscussionCount';

$aResourceModuleTemplate = array(
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
	'remoteBasePath' => &$GLOBALS['wgStylePath']
);

$wgResourceModules['skins.bluespiceskin.scripts'] = array(
	'scripts' => array(
		'BlueSpiceSkin/resources/components/skin.navigationTabs.js',
		'BlueSpiceSkin/resources/components/skin.contentActions.js',
		'BlueSpiceSkin/resources/components/skin.menuTop.js',
		'BlueSpiceSkin/resources/components/skin.scrollToTop.js',
		'BlueSpiceSkin/resources/components/skin.dataAfterContent.js',
		'BlueSpiceSkin/resources/components/extension.widgetbar.js',
		'BlueSpiceSkin/resources/components/special.preferences.js'
	),
	'messages' => array(
		'bs-tools-button',
		'bs-to-top-desc'
	),
	'position' => 'top',
	'styles' => array(
		'BlueSpiceSkin/resources/components/skin.scrollToTop.less'
	),
	'dependencies' => array(
		'mediawiki.jqueryMsg',
		'jquery.ui.tabs',
		'jquery.cookie'
	),
) + $aResourceModuleTemplate;

$wgResourceModules['skins.bluespiceskin'] = array(
	'styles' => array(
		'BlueSpiceSkin/resources/screen.less',
		'BlueSpiceSkin/resources/print.less' => array( 'media' => 'print' ),
		'BlueSpiceSkin/resources/bs.icons.css',
		'BlueSpiceSkin/resources/fonts.css'
	)
) + $aResourceModuleTemplate;

if ( version_compare( $GLOBALS['wgVersion'], '1.23', '<' ) ) {
	$wgResourceModules['skins.bluespiceskin']['styles'] += array(
		'common/commonElements.css' => array( 'media' => 'screen' ),
		'common/commonContent.css' => array( 'media' => 'screen' ),
		'common/commonInterface.css' => array( 'media' => 'screen' ),
		'common/commonPrint.css' => array( 'media' => 'print' )
	);
}

unset( $aResourceModuleTemplate );

$wgDefaultSkin = 'bluespiceskin';
$wgSkipSkins = array( 'chick', 'cologneblue', 'common', 'modern', 'monobook',
	'myskin', 'nostalgia', 'simple', 'standard' );

// Set LESS global variables
$wgResourceLoaderLESSVars += array(
	'body-font-size' => '1em',
	'bs-color-primary' => '#3e5389', //blue
	'bs-color-secondary' => '#ffae00', //orange
	'bs-color-tertiary' => '#b73a3a', //red
	'bs-color-neutral' => '#929292', //grey
	'bs-color-neutral2' => '#ABABAB', //lighten(@bs-color-neutral1, 10%); - LESS / RL issue
	'bs-color-neutral3' => '#C4C4C4', //lighten(@bs-color-neutral1, 20%)',
	'bs-color-neutral4' => '#787878', //darken(@bs-color-neutral1, 10%)',
	'bs-color-dark-blue' => 'rgb(62, 83, 137)',
	'bs-color-bright-blue-a' => 'rgba(205, 223, 242, 0.6)',
	'bs-color-middle-blue-a' => 'rgba(62, 83, 137, 0.44)',
	'bs-color-middle-blue' => 'rgb(152, 167, 196)',
	'bs-color-light-grey' => 'rgb(211, 211, 211)',
	'bs-color-dark-grey' => 'rgb(186, 186, 186)',
	'bs-color-darker-grey' => '#494949',
	'bs-color-content-default' => '#252525',
	'bs-color-redlink' => '#ba0000',
	'bs-font-default' => '"Source Sans Pro", sans-serif',
	'bs-font-roboto' => '"RobotoSlab"',
	'bs-box-shadow' => '0px 4px 20px 0px rgba(9, 7, 9, 0.45)',
	'bs-width-page' => '1222px',
	'bs-width-navcol' => '276px',
	'bs-margin-left-content' => '302px'
);
