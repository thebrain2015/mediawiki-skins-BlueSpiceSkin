<?php

class BlueSpiceSkinHooks {

	/**
	 * 
	 * @param SkinTemplate $sktemplate
	 * @param BaseTemplate $tpl
	 * @return boolean Always true to keep Hook running
	 */
	public static function onSkinTemplateOutputPageBeforeExec(&$sktemplate, &$tpl) {
		$aViews = array();
		wfRunHooks('BSBlueSpiceSkinAfterArticleContent', array(&$aViews, $sktemplate->getUser(), $sktemplate->getTitle()));
		self::mergeTemplateDataArray($tpl, 'bs_after_article_content', $aViews);

		return true;
	}

	protected static function mergeTemplateDataArray(&$tpl, $key, $array) {
		if (!isset($tpl->data[$key])) {
			$tpl->data[$key] = array();
		}
		$tpl->data[$key] += $array;
	}

	/**
	 * 
	 * @param type $oStatebar
	 * @param type $aTopViews
	 * @param type $oUser
	 * @param type $oTitle
	 * @return boolean Always true to keep Hook running
	 */
	public static function onBSStateBarBeforeTopViewAdd($oStatebar, &$aTopViews, User $oUser, $oTitle, $oSkinTemplate) {
		$aViews = array();
		wfRunHooks('BlueSpiceSkin:Widgets', array(&$aViews, $oUser, $oUser->getSkin()));
		$oTopView = new ViewStateBarTopElementTools();
		$oTopView->setOptions(
				array(
					'skinTemplate' => $oSkinTemplate,
					'views' => $aViews
				)
		);
		$aTopViews['statebartoptools'] = $oTopView;
		$oTopViewWatch = new ViewStateBarTopElementWatch();
		$aTopViews['statebartopwatch'] = $oTopViewWatch;
		return true;
	}

	/**
	 * 
	 * @param SkinTemplate $skin
	 * @param Title $title
	 * @param int $section
	 * @param string $tooltip
	 * @param string $result
	 * @param Language $lang
	 * @return boolean Always true to keep Hook running
	 */
	public static function onDoEditSectionLink($skin, $title, $section, $tooltip, &$result, $lang = false) {
		$result = "<span class='editsection'><a href='" . $title->getLocalURL(array('action' => 'edit', 'section' => $section)) . "' title='" . $tooltip . "'></a></span>";
		return true;
	}

	public static function onSkinBuildSidebar(Skin $skin, &$bar) {
		if (Title::makeTitle(NS_MEDIAWIKI, "Sidebar")->exists())
			return true;	
		$newBar = array();
		$aNavigation = array(
			'navigation' => array(
				Title::newMainPage(),
				SpecialPage::getTitleFor("allpages"),
				SpecialPage::getTitleFor("categories"),
				SpecialPage::getTitleFor("recentchanges")
			),
			'help' => array(
				array(
					'text' => 'Anleitungen',
					'href' => 'http://help.blue-spice.org/index.php/Wiki/de',
					'id' => 'manuals'
				),
				array(
					'text' => 'Support',
					'href' => 'http://www.blue-spice.org/de/service/support/business-support/',
					'id' => 'support'
				),
				array(
					'text' => 'Kontakt',
					'href' => 'http://www.blue-spice.org/de/ueber-uns/kontakt-anfahrt/',
					'id' => 'contact'),
			)
		);
		foreach ($aNavigation as $key => $aPages){
			foreach ($aPages as $oPage){
				if (is_object($oPage)){
					if ($oPage->isMainPage())
						$sId = "n-mainpage";
					else if ($oPage->isSpecialPage())
						$sId = "n-special-" . strtolower(SpecialPage::getPageByAlias($oPage->getText())->getName());
					else
						$sId = "n-" . $oPage->getDBkey();
					$newBar[$key][] = array(
						"text" => $oPage->getBaseText(),
						"href" => $oPage->getLocalURL(),
						"id"=> $sId,
						"active" => false
					);
				}
				else{
					$newBar[$key][] = array(
						"text" => $oPage['text'],
						"href" => $oPage['href'],
						"id"=> "n-external-" . $oPage['id'],
						"active" => false
					);
				}
			}
		}
		$bar = $newBar;
		$bar['TOOLBOX'] = array();
		return true;
	}
	public static function onBSGetLogo(&$sImg){
		$sLogoPath = BsConfig::get('MW::LogoPath');
		if (substr($sLogoPath,0,1) != '/' && substr($sLogoPath,0,4) != 'http')
				$sLogoPath = "/" . $sLogoPath;
		$sImg = "<img src='".$sLogoPath."' alt='' />";
		return true;
	}
}