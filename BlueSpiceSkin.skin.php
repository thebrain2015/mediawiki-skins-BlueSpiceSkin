<?php

/**
 * BlueSpice for MediaWiki
 * Authors: Radovan Kubani, Sebastian Ulbricht
 *
 * Copyright (C) 2013 Hallo Welt! – Medienwerkstatt GmbH, All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * For further information visit http://www.blue-spice.org
 */
class SkinBlueSpiceSkin extends SkinTemplate {

	var $skinname = 'bluespiceskin';
	var $stylename = 'BlueSpiceSkin';
	var $template = 'BlueSpiceSkinTemplate';
	var $useHeadElement = true;

	/**
	 * @param $out OutputPage object
	 */
	function initPage( \OutputPage $out) {
		parent::initPage($out);
		$out->addModules('ext.bluespice.bluespiceskin.js');
	}

	function setupSkinUserCss(OutputPage $out) {
		parent::setupSkinUserCss($out);
		$out->addModuleStyles('ext.bluespice.bluespiceskin');
		$out->addModuleStyles('ext.bluespice.bluespiceskin.main');
		$out->addModuleStyles('ext.bluespice.bluespiceskin.content');
		$out->addModuleStyles('ext.bluespice.extensions');
		//$out->addHeadItem("font", "<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro|Muli:300,400' rel='stylesheet' type='text/css' />");
	}

}

class BlueSpiceSkinTemplate extends BaseTemplate {

	function __construct() {
		parent::__construct();
		global $wgTitle;
		$this->data['bs_title_actions'] = array(
			array(
				'id' => 'print',
				'href' => $wgTitle->getLocalURL( array( 'printable' => 'yes' ) ),
				'title'
			)
		);
	}

	protected function printViews( $aViews ) {
		foreach ( $aViews as $oView ) {
			if ( $oView !== null && $oView instanceof ViewBaseElement ) {
				echo $oView->execute();
			} else {
				wfDebugLog('BS::Skin', 'BlueSpiceSkinTemplate::printViews: Invalid view.');
			}
		}
	}

	protected function printBeforeArticleHeadline() {
		global $wgUser, $wgTitle;
		$aViews = array();
		wfRunHooks( 'BlueSpiceSkin:BeforeArticleHeadline', array( &$aViews, $wgUser, $wgTitle ) );
		if ( !empty( $aViews ) ) {
			echo '<div id="bs-beforearticleheadline">';
			$this->printViews( $aViews );
			echo '</div>';
		}
	}

	protected function printBeforeArticleContent() {
		global $wgUser, $wgTitle;
		$aViews = array();
		wfRunHooks( 'BSBlueSpiceSkinBeforeArticleHeadline', array( &$aViews, $wgUser, $wgTitle ) );
		//todo: reimplement skin
		wfRunHooks( 'BSBlueSpiceSkinBeforeArticleContent', array( &$aViews, $wgUser, $wgTitle, $this ) );
		if ( !empty( $aViews ) ) {
			echo '<div id="bs-beforearticlecontent">';
			$this->printViews( $aViews );
			echo '</div>';
		}
		if ( isset( $this->data['dataBeforeContent'] ) ) {
			echo '<div id="bs-dataaftercontent">';
			echo $this->html( 'dataBeforeContent' );
			echo '</div>';
		}
		echo "<div id='bs-statebar-extend'></div>";
	}

	protected function printAfterArticleContent() {
		if ( $this->data['bs_after_article_content'] ) {
			echo '<div id="bs-afterarticlecontent">';
			$this->printViews( $this->data['bs_after_article_content'] );
			echo '</div>';
		}

		if ( $this->data['dataAfterContent'] ) {
			echo '<div id="bs-dataaftercontent">';
			echo $this->html( 'dataAfterContent' );
			echo '</div>';
		}
	}

	protected function printContentActionsList(){
		wfRunHooks( 'SkinTemplateContentActions', array( &$this->data[ 'content_actions' ] ) );
		wfRunHooks( 'SkinTemplateTabs', array( $this->skin, &$this->data[ 'content_actions' ] ) ); // TODO RBV (12.12.11 12:46): Check for Cross-Version compat. Is there still a $this->skin in MW 1.18 ($this->getSkin()?)?
		$aActionsNotInMoreMenu = array( 'nstab-user', 'talk', 'edit', 'viewsource', 'history', 'addsection', 'watch', 'unwatch' );
		// Hook to add "not in more menu" and reorder content_actions
		wfRunHooks( 'BlueSpiceSkin:ReorderActionTabs', array( &$this->data[ 'content_actions' ], &$aActionsNotInMoreMenu ) );
		$aOut = array();
		$aMoreMenuOut = array();
		$aOut[] = "<ul id='p-cactions-list-left'>";
		foreach ($this->data['content_navigation']['namespaces'] as $sKey=>$aActionTab){
			$sCssClass = isset( $aActionTab[ 'class' ] ) ? ' class="' . htmlspecialchars( $aActionTab[ 'class' ] ) . '"' : '';
			$aOut[] = '<li id="ca-' . Sanitizer::escapeId( $sKey ) . '"' . $sCssClass . '>';
			$aOut[] = ' <span>';
			$aOut[] = '  <a href="' . htmlspecialchars( $aActionTab[ 'href' ] ) . '" ' . $aActionTab[ 'attributes' ] . ' title="' . $aActionTab[ 'text' ] . '">';
			$aOut[] = htmlspecialchars( $aActionTab[ 'text' ] );
			// Should be secure enough
			$aOut[] = $aActionTab[ 'textasrawhtml' ];
			if ( $sKey == 'talk' ) {
				$aOut[] = $this->getDiscussionAmount();
			}
			$aOut[] = '  </a>';
			$aOut[] = ' </span>';
			$aOut[] = '</li>';
		}
		$aOut[] = "</ul>";
		$aOut[] = "<ul id='p-cactions-list-right'>";
		foreach ($this->data['content_actions'] as $sKey=>$aActionTab){
			$bIsNamespaceActionTab = strstr( $sKey, 'nstab' ) ? true : false;
			if ( in_array( $sKey, $aActionsNotInMoreMenu ) || $bIsNamespaceActionTab ) {
				if ( ($sKey == "watch" || $sKey == "unwatch") && BsConfig::get( 'MW::StateBar::Show' ) === true ) continue;
				if ( $sKey == "talk" || $aActionTab['context'] == "subject") continue;
				$aOut[] = '<li id="ca-' . Sanitizer::escapeId( $sKey ) . '"' . $sCssClass . '>';
				$aOut[] = ' <span>';
				$aOut[] = '  <a href="' . htmlspecialchars( $aActionTab[ 'href' ] ) . '" ' . $aActionTab[ 'attributes' ] . ' title="' . $aActionTab[ 'text' ] . '">';
				$aOut[] = htmlspecialchars( $aActionTab[ 'text' ] );
				// Should be secure enough
				$aOut[] = $aActionTab[ 'textasrawhtml' ];
				if ( $sKey == 'talk' ) {
					$aOut[] = $this->getDiscussionAmount();
				}
				$aOut[] = '  </a>';
				$aOut[] = ' </span>';
				$aOut[] = '</li>';
			} else {
				$aMoreMenuOut[] = '<li id="ca-' . Sanitizer::escapeId( $sKey ) . '"' . $sCssClass . '>';
				$aMoreMenuOut[] = '  <a href="' . htmlspecialchars( $aActionTab[ 'href' ] ) . '" ' . $aActionTab[ 'attributes' ] . ' title="' . $aActionTab[ 'text' ] . '">';
				$aMoreMenuOut[] = htmlspecialchars( $aActionTab[ 'text' ] );
				$aMoreMenuOut[] = '  </a>';
				$aMoreMenuOut[] = '</li>';
			}
		}
		if (BsConfig::get( 'MW::StateBar::Show' ) === false){
			$aOut[] = "<li id='ca-more-top'>";
			$aOut[] = "<span>".wfMessage('bs-tools-button')->plain()."</span>";
			$aOut[] = "<ul id='p-cactions-list-more'>".implode("\n", $aMoreMenuOut)."</ul>";
			$aOut[] = "</li>";
		}
		$aOut[] = "</ul>";

		$this->data['more_menu'] = $aMoreMenuOut;

		echo implode( "\n", $aOut );
	}

	protected function printSiteNotice() {
		if ( $this->data['sitenotice'] ) {
			echo '<div id="siteNotice">';
			echo $this->html( 'sitenotice' );
			echo '</div>';
		}
	}

	protected function printArticleHeadline() {
		global $wgTitle;

		$aViews = array();
		if ( $wgTitle->equals( Title::newMainPage() ) === false ) {
			$aViews = array(
				'articletitleprefix' => '<h1 class="firstHeading">',
				'articletitle' => $this->data['title'],
				'articletitlesuffix' => '</h1>',
			);
		}

		wfRunHooks( 'BSBlueSpiceSkinBeforePrintArticleHeadline', array( $wgTitle, $this, &$aViews ) );

		foreach ( $aViews as $key => $sView ) echo $sView;
	}

	protected function printLanguageBox() {
		if ( $this->data['language_urls'] ) {
			$aOut = array();
			$aOut[] = '<div id="p-lang" class="portlet">';
			$aOut[] = '  <h5>' . wfMessage( 'otherlanguages' )->plain() . '</h5>';
			$aOut[] = '  <div class="pBody">';
			$aOut[] = '     <ul>';

			foreach ( $this->data['language_urls'] as $langlink ) {
				$aOut[] = '<li class="' . htmlspecialchars( $langlink['class'] ) . '">';
				$aOut[] = '  <a href="' . htmlspecialchars( $langlink['href'] ) . '">' . $langlink['text'] . '</a>';
				$aOut[] = '</li>';
			}

			$aOut[] = '    </ul>';
			$aOut[] = '  </div>';
			$aOut[] = '</div>';

			echo implode( "\n", $aOut );
		}
	}

	protected function getToolboxMarkUp( $bRenderHeading = true ) {
		// adding link to Allpages
		$oAllPagesSpecialPage = SpecialPageFactory::getPage( 'Allpages' );
		$this->data['nav_urls']['specialpageallpages']['href'] = $oAllPagesSpecialPage->getTitle()->getLinkURL();
		$this->data['nav_urls']['specialpageallpages']['text'] = $oAllPagesSpecialPage->getDescription();

		$aToolboxExcludeList = array( 'mainpage' );
		$aToolboxLinkList = array();
		foreach ( $this->data['nav_urls'] as $sKey => $aLink ) {
			if ( empty( $aLink['href'] ) || in_array( $sKey, $aToolboxExcludeList ) ) continue;

			$aLink['href'] = str_replace( '&', '&amp;', $aLink['href'] );
			$vTooltip = $this->tooltipAndAccesskeyAttribs( 't-' . $sKey );
			$sAttr = '';
			if ( is_array( $vTooltip ) ) {
				$sAttr = ' title="' . $vTooltip['title'] . '" accesskey="' . $vTooltip['accesskey'] . '"';
			} else {
				$sAttr = $vTooltip;
			}
			$aToolboxLinkList[] = '<li id="t-' . htmlspecialchars( $sKey ) . '">';
			$aToolboxLinkList[] = '  <a href="' . $aLink['href'] . '"' . $sAttr . '>';
			$aToolboxLinkList[] = empty( $aLink['text'] ) ? htmlspecialchars( $this->translator->translate( $sKey ) ) : $aLink['text'];
			$aToolboxLinkList[] = '  </a>';
			$aToolboxLinkList[] = '</li>';
		}

		$sToolboxLinkList = implode("\n", $aToolboxLinkList);

		ob_start();
		wfRunHooks('SkinTemplateToolboxEnd', array(&$this));
		$sToolboxEndLinkList = ob_get_contents();
		ob_end_clean();

		$aOut = array();
		$aOut[] = '<div class="portlet bs-nav-links" id="p-tb">';
		if ( $bRenderHeading === true ) {
			$aOut[] = '  <h5>' . $this->translator->translate( 'toolbox' ) . '</h5>';
		}
		$aOut[] = '  <div class="pBody">';
		$aOut[] = '    <ul>';
		$aOut[] = $sToolboxLinkList;
		$aOut[] = $sToolboxEndLinkList;
		$aOut[] = '    </ul>';
		$aOut[] = '  </div>';
		$aOut[] = '</div>';

		return implode( "\n", $aOut );
	}

	protected function printToolBox() {
		echo $this->getToolboxMarkUp();
	}

	public function getToolBoxWidget() {
		$oWidgetView = new ViewWidget();
		$oWidgetView->setId('bs-toolbox')
				->setTitle($this->translator->translate('toolbox')) // BsI18N::getInstance( )->msg('label');
				->setBody($this->getToolboxMarkUp(false))
				->setTooltip($this->translator->translate('toolbox'));

		return $oWidgetView;
	}

	public function onBSWidgetBarGetDefaultWidgets(&$aViews, $oUser, $oTitle) {
		if (!isset($this->data['sidebar']['TOOLBOX'])) {
			$aViews[] = $this->getToolBoxWidget();
		}
		return true;
	}

	public function onBSWidgetListHelperInitKeyWords(&$aKeywords, $oTitle) {
		$aKeywords['TOOLBOX'] = array($this, 'getToolBoxWidget');
		return true;
	}

	protected function printNavigationSidebar() {
		global $wgScriptPath;
		$aPortlets = array();

		foreach ($this->data['sidebar'] as $bar => $cont) {
			$sTitle = wfEmptyMsg($bar, wfMessage($bar)->plain()) ? $bar : wfMessage($bar)->plain();
			$aOut = array();

			if ($bar == 'TOOLBOX') {
				$aPortlets[$bar] = $this->getToolboxMarkUp();
				continue;
			}
			if ($cont) {
				$aOut[] = '<div id="p-' . Sanitizer::escapeId($bar) . '" class="bs-nav-links">';
				$aOut[] = '  <h5>' . $sTitle . '</h5>';
				$aOut[] = '  <ul>';
				foreach ($cont as $key => $val) {
					$sCssClass = (!isset($val['active']) ) ? ' class="active"' : '';
					$sTarget = ( isset($val['target']) ) ? ' target="' . $val['target'] . '"' : '';
					$sRel = ( isset($val['rel']) ) ? ' rel="' . $val['rel'] . '"' : '';
					$aOut[] = '<li id="' . Sanitizer::escapeId($val['id']) . '"' . $sCssClass . ' class="clearfix">';
					if (strpos($val['text'], "|") !== false) {
						$aVal = explode('|', $val['text']);
						$oFile = wfFindFile($aVal[1]);
						if ($oFile->exists()) {
							$aOut[] = '<div style="background-image:url(' . $oFile->getFullUrl() . '); width:24px; height:24px;" class="left_navigation_icon" ></div>';
							$aOutHidden[] = ' <li> <a href="' . htmlspecialchars($val['href']) . '" title="' . htmlspecialchars($aVal[0]) .'" ' . $sTarget . $sRel . '>' . '<div id="' . Sanitizer::escapeId($val['id']) . '-small" class="left_navigation_icon" style="background-image:url(' . $oFile->getFullUrl() . '); width:24px; height:24px;"></div>' . '</a> </li>';
						} else {
							//default
							$aOut[] = '<div class="left_navigation_icon"></div>';
							$aOutHidden[] = ' <li> <a href="' . htmlspecialchars($val['href']) . '" title="' . htmlspecialchars($aVal[0]) .'" ' . $sTarget . $sRel . '>' . '<div id="' . Sanitizer::escapeId($val['id']) . '-small" class="left_navigation_icon" ></div>' . '</a> </li>';
						}
						$aOut[] = '  <a href="' . htmlspecialchars($val['href']) . '" title="' . htmlspecialchars($aVal[0]) .'" ' . $sTarget . $sRel . '>' . htmlspecialchars($aVal[0]) . '</a>';
					} else {
						$aOut[] = '<div class="left_navigation_icon"></div>';
						$aOutHidden[] = ' <li> <a href="' . htmlspecialchars($val['href']) . '" title="' . htmlspecialchars($val['text']) .'" ' . $sTarget . $sRel . '>' . '<div id="' . Sanitizer::escapeId($val['id']) . '-small" class="left_navigation_icon" ></div>' . '</a> </li>';
						$aOut[] = '  <a href="' . htmlspecialchars($val['href']) . '" title="' . htmlspecialchars($val['text']) .'" ' . $sTarget . $sRel . '>' . htmlspecialchars($val['text']) . '</a>';
					}
					$aOut[] = '</li>';
				}
				$aOut[] = '  </ul>';
				$aOut[] = '</div>';
				$aPortlets[$bar] = implode("\n", $aOut);
			}
		}

		if ($this->data['language_urls']) {
			$aOut = array();
			$aOut[] = '<div title="' . wfMessage('otherlanguages')->plain() . '" id="p-lang" class="bs-widget portal">';
			$aOut[] = '  <div class="bs-widget-head">';
			$aOut[] = '    <h5 class="bs-widget-title" ' . $this->data['userlangattributes'] . '>' . wfMessage('otherlanguages')->plain() . '</h5>';
			$aOut[] = '  </div>';
			$aOut[] = '  <div class="bs-widget-body bs-nav-links">';
			$aOut[] = '    <ul>';
			foreach ($this->data['language_urls'] as $langlink) {
				$aOut[] = '      <li class="' . htmlspecialchars($langlink['class']) . '">';
				$aOut[] = '        <a href="' . htmlspecialchars($langlink['href']) . '">' . $langlink['text'] . '</a>';
				$aOut[] = '      </li>';
			}
			$aOut[] = '    </ul>';
			$aOut[] = '  </div>';
			$aOut[] = '</div>';
			$aPortlets['language_urls'] = implode("\n", $aOut);
		}

		wfRunHooks('BSBlueSpiceSkinNavigationSidebar', array($this, &$aPortlets));
		$aOut = array();
		foreach ($aPortlets as $sKey => $vPortlet) {
			if ($vPortlet instanceof ViewBaseElement) {
				$aOut[] = $vPortlet->execute();
			} else {
				$aOut[] = $vPortlet; //Check for string?
			}
		}
		$aOut[] = '<div id="bs-nav-small"><ul>';
		$aOut[] = implode("", $aOutHidden);
		$aOut[] = '</ul></div>';
		echo implode("\n", $aOut);
	}

	protected function getDiscussionAmount() {
		global $wgTitle;
		if ($wgTitle->getNamespace() < 0)
			return '';
		return ' (' . BsArticleHelper::getDiscussionAmountForTitle($wgTitle) . ')';
	}

	protected function printFocusSidebar() {
		global $wgUser;
		$aViews = array();

		//wfRunHooks( 'BSFocusSidebar', array( &$aViews, $wgUser, $this ) );
		wfRunHooks('BSBlueSpiceSkinFocusSidebar', array(&$aViews, $wgUser, $this));
		//wfRunHooks( 'BlueSpiceSkin:FocusSidebar', array( &$aViews, $wgUser, $this ) );
		$this->printViews($aViews);
	}

	protected function printAdminSidebar() {
		global $wgUser;
		$aViews = array();

		wfRunHooks( 'BSBlueSpiceSkinAdminSidebar', array( &$aViews, $wgUser, $this) ); // TODO RBV (29.10.10 08:49): For future use

		$oWikiAdminSpecialPageTitle = SpecialPage::getTitleFor( 'SpecialWikiAdmin' );

		$aOut = array();
		$aOut[] = '<div id="p-adminbar" class="bs-nav-links">';
		$aOut[] = '  <ul>';

		// CR RBV (27.06.11 14:46): Use hook or event
		if ( class_exists( 'WikiAdmin' ) ) {
			$aRegisteredModules = WikiAdmin::getRegisteredModules();

			foreach ( $aRegisteredModules as $sModuleKey => $aModulParams ) {
				$skeyLower = strtolower($sModuleKey);
				$sModulLabel = wfMessage( 'bs-' . $skeyLower . '-label' )->plain();
				$sUrl = $oWikiAdminSpecialPageTitle->getLocalURL('mode=' . $sModuleKey);
				$aPointsAdmin [$sModulLabel] = $sUrl;
			}
			ksort( $aPointsAdmin );
			foreach ( $aPointsAdmin as $sModuleLabel => $sUrl ) {
				$sUrl = str_replace('&', '&amp;', $sUrl);
				$aOut[] = '    <li><a href="' . $sUrl . '" title="' . $sModuleLabel . '">' . $sModuleLabel . '</a></li>';
			}
		}

		$aOut[] = '  </ul>';
		$aOut[] = '</div>';

		echo implode("\n", $aOut);
	}

	/**
	 * @global Title $wgTitle
	 * @global User $wgUser
	 * @global WebRequest $wgRequest
	 */
	protected function printUserBar() {
		global $wgTitle, $wgUser, $wgRequest;

		$aOut = array();

		if ( $wgUser->isLoggedIn() ) {
			$sButtonUserImage = 'account-icon.png';
			$sLoginSwitchTooltip = wfMessage( 'bs-userbar_loginswitch_logout', 'Logout' )->plain();

			$aOut[] = '<div id="bs-user-container">';
			$aOut[] = '<div id="bs-button-logout">';
			$aOut[] = '  <span>' . $sLoginSwitchTooltip . '</span>';
			$aOut[] = '  <a href="' . SpecialPage::getTitleFor( 'Userlogout' )->getLocalURL( array( 'returnto' => $wgRequest->getVal( 'title' ) ) ) . '" title="' . $sLoginSwitchTooltip . '">';
			$aOut[] = '    <img src="' . $this->data['stylepath'] . '/' . $this->data['stylename'] . '/resources/images/desktop/logout-icon.png" alt="' . $sLoginSwitchTooltip . '" />';
			$aOut[] = '  </a>';
			$aOut[] = '</div>';

			$aUserBarBeforeLogoutViews = array();

			$aOut[] = '<div id="bs-button-user">';
			$aOut[] = '  <span>' . wfMessage('bs-my-account')->plain() . '</span>';
			//$aOut[] = $wgUser->getSkin()->link($wgUser->getUserPage(), '    <img src="' . $this->data['stylepath'] . '/' . $this->data['stylename'] . '/resources/images/desktop/' . $sButtonUserImage . '" alt="' . $sUserDisplayName . '" />');
			//$aOut[] = $wgUser->getSkin()->link($wgUser->getUserPage(), '<div id="bs-button-user-img" style="background-image: url('.BsCore::getInstance()->getUserMiniProfile($wgUser, array("width"=>"19", "height" => "16"))->execute().');"></div>');
			$aOut[] = BsCore::getInstance()->getUserMiniProfile($wgUser, array("width"=>"19", "height" => "16"))->execute();
			$aOut[] = '  <ul id="bs-personal-menu">';

			$oTitle = Title::makeTitle( NS_USER, $wgUser->getName() );
			$sLink = BsLinkProvider::makeLink( $oTitle, wfMessage("bs-topbar-profile")->plain() );
			$aPersonalUrlsFilter = array('userpage', 'logout', 'anonlogin', 'notifications');
			$sUsername = $wgUser->getRealName() == "" ? $wgUser->getName() : $wgUser->getRealName();
			$aOut[] = "<li class='bs-top-box'><div>".$sUsername."</div></li>";
			$aOut[] = '<li id="pt-profile">';
			$aOut[] = $sLink;
			$aOut[] = '</li>';
			foreach ( $this->data['personal_urls'] as $sKey => $aItem ) {
				if ( in_array( $sKey, $aPersonalUrlsFilter ) ) continue;
				$sCssClass = $aItem['active'] ? ' class="active"' : '';
				$aOut[] = '<li id="pt-' . Sanitizer::escapeId($sKey) . '"' . $sCssClass . '>';
				$sCssClass = !empty($aItem['class']) ? ' class="' . htmlspecialchars( $aItem['class'] ) . '"' : '';
				$aOut[] = '  <a href="' . htmlspecialchars( $aItem['href'] ) . '"' . $sCssClass . '>';
				$aOut[] = htmlspecialchars( $aItem['text'] );
				$aOut[] = '  </a>';
				$aOut[] = '</li>';
			}
			$aOut[] = "<li class='bs-bottom-box'></li>";
			$aOut[] = '  </ul>';
			$aOut[] = '</div>';
			wfRunHooks( 'BSBlueSpiceSkinUserBarBeforeLogout', array( &$aUserBarBeforeLogoutViews, $wgUser, $this ) );
			foreach ($aUserBarBeforeLogoutViews as $oUserBarView) {
				$aOut[] = $oUserBarView->execute();
			}
			$sUsernameCSSClass = '';

			if ($wgTitle->equals($wgUser->getUserPage())) {
				$sUsernameCSSClass = ' class="active"';
			}

			/* $aOut[ ] = '<div id="bs-skin-username"' . $sUsernameCSSClass . '>';
			  $sUserDisplayName = BsAdapterMW::getUserDisplayName( $wgUser );
			  $aOut[ ] = $wgUser->getSkin()->link( $wgUser->getUserPage(), $sUserDisplayName );
			  $aOut[ ] = '</div>'; */
			$aOut[] = '</div>';
		} else {
			$sButtonUserImage = 'bs-icon-user-transp-50.png';
			$sLoginSwitchTooltip = wfMessage('bs-userbar_loginswitch_login', 'Login')->plain();
			$aOut[] = '<div id="bs-button-logout">';
			$aOut[] = '  <span>' . $sLoginSwitchTooltip . '</span>';
			$aOut[] = '  <a href="' . SpecialPage::getTitleFor( 'Userlogin' )->escapeLocalURL( array( 'returnto' => $wgRequest->getVal( 'title' ) ) ) . '" title="' . $sLoginSwitchTooltip . '">';
			$aOut[] = '    <img src="' . $this->data['stylepath'] . '/' . $this->data['stylename'] . '/resources/images/desktop/login-icon.png" alt="' . $sLoginSwitchTooltip . '" />';
			$aOut[] = '  </a>';
			$aOut[] = '</div>';
			$aUserBarBeforeLogoutViews = array();
			wfRunHooks('BSBlueSpiceSkinUserBarBeforeLogout', array(&$aUserBarBeforeLogoutViews, $wgUser, $this));
			foreach ($aUserBarBeforeLogoutViews as $oUserBarView) {
				$aOut[] = $oUserBarView->execute();
			}
		}

		echo implode("\n", $aOut);
	}

	protected function printSkyScraper() {
		global $wgTitle;
		$aViews = array();
		wfRunHooks('BSBlueSpiceSkinSkyScraper', array(&$aViews, $wgTitle));
		if (!empty($aViews)) {
			echo '<div id="bs-skyscraper">';
			$this->printViews($aViews);
			echo '</div>';
		}
	}

	protected function printWidgets() {
		global $wgUser;
		$aViews = array();
		wfRunHooks('BlueSpiceSkin:Widgets', array(&$aViews, $wgUser, $this));
		$this->printViews($aViews);
	}

	protected function printApplicationList() {
		global $wgUser;
		$aApplications = BsConfig::get('MW::Applications');
		$sCurrentApplicationContext = BsConfig::get('MW::ApplicationContext');
		$aOut = array();
		if (wfRunHooks('BSBlueSpiceSkin:ApplicationList', array(&$aApplications, &$sCurrentApplicationContext, $wgUser, &$aOut, $this))) {
			// TODO RBV (02.11.10 11:00): Encapsulate in view
			$aOut[] = '<div id="bs-apps">';
			$aOut[] = '  <ul>';
			foreach ($aApplications as $aApp) {
				$sClass = ( $aApp['name'] == $sCurrentApplicationContext ) ? ' class="active" ' : '';
				$aListItem = array();
				$aListItem[] = '  <li>';
				$aListItem[] = '    <a href="' . $aApp['url'] . '" title="' . $aApp['displaytitle'] . '"' . $sClass . '>' . $aApp['displaytitle'] . '</a>';
				$aListItem[] = '  </li>';
				$aOut[] = implode("\n", $aListItem);
			}
			$aOut[] = '  </ul>';
			$aOut[] = '</div>';
		}
		echo implode("\n", $aOut);
	}

	protected function printSearchBox() {
		$aSearchBoxKeyValues = array();
		wfRunHooks('FormDefaults', array($this, &$aSearchBoxKeyValues));

		$aOut = array();
		$aOut[] = '<div id="bs-searchbar">';
		/* $aOut[] = '  <form class="bs-search-form" action="' . $aSearchBoxKeyValues[ 'SearchDestination' ] . '" onsubmit="if (document.getElementById(\'bs-search-input\').value == \'' . $aSearchBoxKeyValues[ 'SearchTextFieldDefaultText' ] . '\') return false;" method="' . $aSearchBoxKeyValues[ 'method' ] . '">';
		  if ( isset( $aSearchBoxKeyValues[ 'HiddenFields' ] ) && is_array( $aSearchBoxKeyValues[ 'HiddenFields' ] ) ) {
		  foreach ( $aSearchBoxKeyValues[ 'HiddenFields' ] as $key => $value )
		  $aOut[] = "    <input type=\"hidden\" name=\"$key\" value=\"$value\" />";
		  }
		  $aOut[] = '    <input type="hidden" name="search_scope" value="' . $aSearchBoxKeyValues['DefaultKeyValuePair'][ 1 ] . '" id="bs-search-button-hidden" />';
		  //$aOut[] = '    <button type="button" id="bs-search-button" title="' . $aSearchBoxKeyValues[ 'SubmitButtonTitle' ] . '" ></button>';
		  $aOut[] = '    <div id="bs-search-right"></div>';
		  $aOut[] = '    <input name="' . $aSearchBoxKeyValues[ 'SearchTextFieldName' ] . '" type="text" title="' . $aSearchBoxKeyValues[ 'SearchTextFieldTitle' ] . '" id="bs-search-input" value="' . $aSearchBoxKeyValues[ 'SearchTextFieldDefaultText' ] . '" class="bs-unfocused-textfield bs-autocomplete-field" accesskey="f" />';
		  $aOut[] = '    <button type="button" id="bs-search-fulltext" title="' . $aSearchBoxKeyValues[ 'SubmitButtonFulltext' ] . '" ></button>';
		  //$aOut[] = ( isset( $aSearchBoxKeyValues[ 'IdBsSearchLeft' ] ) ) ? $aSearchBoxKeyValues[ 'IdBsSearchLeft' ] : '<div id="bs-search-left"></div>';
		  $aOut[] = '  </form>';
		  $aOut[] = '</div>';

		  $out = implode( "\n", $aOut );
		  echo $out; */


		$aOut[] = '    <form class="bs-search-form" action="' . $aSearchBoxKeyValues['SearchDestination'] . '" onsubmit="if (document.getElementById(\'bs-search-input\').value == \'' . $aSearchBoxKeyValues['SearchTextFieldDefaultText'] . '\') return false;" method="' . $aSearchBoxKeyValues['method'] . '">';

		if (isset($aSearchBoxKeyValues['HiddenFields']) && is_array($aSearchBoxKeyValues['HiddenFields'])) {
			foreach ($aSearchBoxKeyValues['HiddenFields'] as $key => $value) {
				$aOut[] = "        <input type=\"hidden\" name=\"$key\" value=\"$value\" />";
			}
		}

		$aOut[] = '        <input type="submit" id="bs-search-fulltext" title="' . $aSearchBoxKeyValues['SubmitButtonFulltext'] . '" name="search_scope" value="">';
		$aOut[] = '        <input name="' . $aSearchBoxKeyValues['SearchTextFieldName'] . '" type="text" title="' . $aSearchBoxKeyValues['SearchTextFieldTitle'] . '" id="bs-search-input" value="' . $aSearchBoxKeyValues['SearchTextFieldDefaultText'] . '" class="bs-unfocused-textfield bs-autocomplete-field" accesskey="f" />';

		wfRunHooks('BSExtendedSearchFormInputs', array(&$aOut));

		$aOut[] = '    </form>';
		$aOut[] = '</div>';

		echo implode("\n", $aOut);
	}

	protected function printNavigationTop() {
		$aViews = array();
		wfRunHooks( 'BlueSpiceSkin:NavigationTop', array( &$aViews, $this ) );
		$this->printViews($aViews);
	}

	protected function tooltipAndAccesskeyAttribs($sName) {
		return Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( $sName ) );
	}

	public function printTitleActions() {
		global $wgRequest, $wgTitle;
		$aOut = array();
		if ($wgRequest->getVal('action', 'view') != 'view')
			return true;
		$aContentActions = $this->data['bs_title_actions'];
		if (count($aContentActions) < 1 || $wgRequest->getVal('action', 'view') != 'view' || $wgTitle->isSpecialPage())
			return;
		foreach ($aContentActions as $aContentAction) {
			$aOut[] = "<li class='bs-actions-" . $aContentAction['id'] . "'>";
			$aOut[] = "  <a href='" . $aContentAction['href'] . "' title='" . $aContentAction['title'] . "'>" . $aContentAction['text'] . "</a>";
			$aOut[] = "</li>";
		}
		echo implode("\n", $aOut);
	}

	public function execute() {
		global $wgUser, $wgHooks;
		$this->skin = $this->data['skin'];
		$wgHooks['BSWidgetBarGetDefaultWidgets'][] = array(&$this, 'onBSWidgetBarGetDefaultWidgets');
		$wgHooks['BSWidgetListHelperInitKeyWords'][] = array(&$this, 'onBSWidgetListHelperInitKeyWords');

		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();
		$this->html('headelement');
		?>
		<div id="bs-wrapper">
			<div id="bs-menu-top" class="clearfix">
				<!--<div id="bs-menu-top-left">
		<?php $this->printApplicationList(); // TODO RBV (27.10.10 15:22): Make hookpoint  ?>
					</div>-->
				<div id="bs-logo">
					<a href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href']) ?>" <?php echo $this->tooltipAndAccesskeyAttribs('p-logo') ?>><?php $sImg = ""; wfRunHooks("BSGetLogo", array(&$sImg)); echo $sImg; ?></a>
				</div>
				<div id="bs-menu-top-left">
					<!-- Applications -->
					<?php $this->printApplicationList(); // TODO RBV (27.10.10 15:22): Make hookpoint ?>
				</div>
				<div id="bs-menu-top-right">
		<?php $this->printUserBar() // TODO RBV (27.10.10 15:22): Make hookpoint   ?>
		<?php $this->printSearchBox() ?>
				</div>
			</div>
			<div id="bs-application">
				<!-- #bs-left-column START -->
				<div id="bs-left-column" class="clearfix">
					<div id="bs-left-resize-container">
						<div id='bs-left-resize-btn'></div>
					</div>
					<div id="bs-nav-sections"> <?php // TODO RBV (02.11.10 11:36): encapsulate creation of left navigation. Maybe views?    ?>
						<ul id ="bs-nav-tabs">
							<li>
								<a href="#bs-nav-section-navigation"><?php echo wfMessage('bs-tab_navigation', 'Navigation')->plain(); ?></a>
							</li>
		<?php if ($wgUser->isLoggedIn()) { ?>
								<li>
									<a href="#bs-nav-section-focus"><?php echo wfMessage('bs-tab_focus', 'Focus')->plain(); ?></a>
								</li>
								<?php if ($wgUser->isAllowed('wikiadmin')) { ?>
									<li>
										<a href="#bs-nav-section-admin"><?php echo wfMessage('bs-tab_admin', 'Admin')->plain(); ?></a>
									</li>
								<?php }
							}
							?>
						</ul>
						<div id="bs-nav-section-navigation">
							<!-- Navigation-Code -->
		<?php $this->printNavigationSidebar(); ?>
						</div>
							<?php if ($wgUser->isLoggedIn()) { ?>
							<div id="bs-nav-section-focus">
								<!-- Focus-Code -->
							<?php $this->printFocusSidebar(); ?>
							</div>
								<?php if ($wgUser->isAllowed('wikiadmin') || $wgUser->isAllowed('useradmin') || $wgUser->isAllowed('editadmin')) { ?>
								<div id="bs-nav-section-admin">
									<!-- Admin-Code -->
								<?php $this->printAdminSidebar(); ?>
								</div>
								<?php }
							}
							?>
					</div>
				</div>
				<!-- #bs-left-column END -->

				<!-- #bs-content-column START -->
				<div id="bs-content-column">
					<div id="p-cactions">
						<ul id="p-cactions-list">
		<?php $this->printContentActionsList(); ?>
						</ul>
					</div>
		<?php $this->printBeforeArticleContent(); ?>
					<div id="content">
						<div id="mw-js-message" style="display:none;"<?php $this->html('userlangattributes') ?>></div>
		<?php $this->printBeforeArticleHeadline(); ?>
						<a name="top" id="top"></a>
						<?php $this->printSiteNotice(); ?>
						<?php $this->printArticleHeadline(); ?>
						<div id="bodyContent" class="clearfix">
							<h3 id="siteSub">    <?php $this->msg('tagline') ?>  </h3>
							<div id="contentSub"><?php $this->html('subtitle') ?></div>

		<?php if ($this->data['undelete']) { ?><div id="contentSub2"><?php $this->html('undelete') ?></div><?php } ?>
							<?php if ($this->data['newtalk']) { ?><div class="usermessage"><?php $this->html('newtalk') ?></div><?php } ?>
							<?php if ($this->data['showjumplinks']) { ?>
								<div id="jump-to-nav"><?php $this->msg('jumpto') ?>
									<a href="#column-one"><?php $this->msg('jumptonavigation') ?></a>,
									<a href="#searchInput"><?php $this->msg('jumptosearch') ?></a>
								</div>
		<?php } ?>
							<ul id="bs-title-actions">
							<?php $this->printTitleActions(); ?>
							</ul>
							<!-- start content -->
		<?php $this->html('bodytext') ?>
							<!-- end content -->
						</div>
		<?php $this->printAfterArticleContent(); ?>
					</div>
				</div>
				<!-- #bs-content-column END -->
				<!-- #bs-footer START -->
				<div id="footer" <?php $this->html('userlangattributes') ?>>
		<?php
		$aFooterIcons = $this->getFooterIcons("icononly");
		$aFooterLinks = $this->getFooterLinks();
		foreach ($aFooterLinks as $sCategory => $aLinks):
			?>
						<ul id="footer-<?php echo $sCategory ?>">
							<?php
							foreach ($aLinks as $sLink) {
								echo '<li id="footer-' . $sCategory . '-' . $sLink . '">' . $this->data[$sLink] . '</li>';
							}
							?>
						</ul>
					<?php endforeach; ?>
					<?php
					if (count($aFooterIcons) > 0):
						?>
						<ul id="footer-icons" class="noprint">
								<?php foreach ($aFooterIcons as $blockName => $aFooterIconBlock): ?>
								<li id="footer-<?php echo htmlspecialchars($blockName); ?>ico">
									<?php foreach ($aFooterIconBlock as $icon): ?>
										<?php echo $this->getSkin()->makeFooterIcon($icon); ?>
								<?php endforeach; ?>
								</li>
						<?php endforeach; ?>
						</ul>
		<?php endif; ?>
				</div>
				<!-- #bs-footer END -->
			</div>
		</div>
		<?php $this->printSkyScraper(); ?>
		<?php
		$aAdditionalHTML = array();
		wfRunHooks('BSBlueSpiceSkinAfterBsFloaterDiv', array($this, &$aAdditionalHTML));
		if (!empty($aAdditionalHTML)) {
			echo implode("\n", $aAdditionalHTML);
		}

		$this->printTrail();
		?>
		</body>
		</html><?php
		wfRestoreWarnings();
	}

// end of execute() method
}

// end of class