<?php

class BlueSpiceSkinHooks {

	/**
	 * Add BlueSpice skin to VisualEditor supported skins
	 */
	public static function setup() {
		global $wgVisualEditorSupportedSkins;
		if( is_array( $wgVisualEditorSupportedSkins ) ) {
			$wgVisualEditorSupportedSkins[] = 'bluespiceskin';
		}
	}

	/**
	 *
	 * @param StateBar $oStatebar
	 * @param array $aTopViews
	 * @param User $oUser
	 * @param Title $oTitle
	 * @return boolean Always true to keep Hook running
	 */
	public static function onBSStateBarBeforeTopViewAdd( $oStatebar, &$aTopViews, User $oUser, $oTitle, $oSkinTemplate ) {
		$aViews = array();
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
	 * Removes brackets and other markup from edit section links
	 * @param SkinTemplate $skin
	 * @param Title $title
	 * @param int $section
	 * @param string $tooltip
	 * @param string $result
	 * @param Language $lang
	 * @return boolean Always true to keep Hook running
	 */
	public static function onDoEditSectionLink($skin, $title, $section, $tooltip, &$result, $lang = false) {
		if ( $skin->getSkin() instanceof SkinBlueSpiceSkin ) {
			$result = Linker::link(
				$title,
				Html::element(
					'span',
					array(),
					wfMessage( 'editsection' )->inLanguage( $lang )->text()
				),
				array(
					'class' => 'mw-editsection icon-pencil',
					'title' => $tooltip
				),
				array(
					'action' => 'edit',
					'section' => $section
				)
			);
		}
		return true;
	}

	public static function onSkinBuildSidebar( Skin $skin, &$bar ) {
		if ( Title::makeTitle( NS_MEDIAWIKI, "Sidebar" )->exists() ) {
			return true;
		}

		$newBar = array();
		$aNavigation = array(
			'navigation' => array(
				Title::newMainPage(),
				SpecialPage::getTitleFor( "Allpages" ),
				SpecialPage::getTitleFor( "Categories" ),
				SpecialPage::getTitleFor( "Recentchanges" )
			),
			'help' => array(
				array(
					'text' => wfMessage( 'bs-navigation-instructions' )->plain(),
					'href' => wfMessage( 'bs-navigation-instructions-url' )->plain(),
					'id' => 'manuals'
				),
				array(
					'text' => wfMessage( 'bs-navigation-support' )->plain(),
					'href' => wfMessage( 'bs-navigation-support-url' )->plain(),
					'id' => 'support'
				),
				array(
					'text' => wfMessage( 'bs-navigation-contact' )->plain(),
					'href' => wfMessage( 'bs-navigation-contact-url' )->plain(),
					'id' => 'contact'
				)
			)
		);

		foreach ( $aNavigation as $key => $aPages ) {
			foreach ( $aPages as $oPage ){
				if ( $oPage instanceof Title) {
					if ( $oPage->isMainPage() ) {
						$sId = "n-mainpage";
					} elseif ( $oPage->isSpecialPage() ) {
						$oSpecialPage = SpecialPageFactory::getPage( $oPage->getText() );
						$sId = 'n-special-' . strtolower( $oSpecialPage->getName() );
					} else {
						$sId = 'n-' . $oPage->getDBkey();
					}

					$newBar[$key][] = array(
						"text" => ( isset( $oSpecialPage ) ) ? $oSpecialPage->getDescription() : $oPage->getText(),
						"href" => $oPage->getLocalURL(),
						"id"=> $sId,
						"active" => false
					);
				} else{
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

	public static function ajaxGetDiscussionCount() {
		$oResponse = BsCAResponse::newFromPermission('read');
		if( $oResponse->isSuccess() == false ) {
			return $oResponse;
		}

		$oContext = BsCAContext::newFromRequest();
		$iCount = BsArticleHelper::getInstance(
			$oContext->getTitle()
		)->getDiscussionAmount();

		$oResponse->setPayload( $iCount );
		$oResponse->setSuccess( true );

		return $oResponse;
	}

	public static function onSkinTemplateNavigationUniversal( &$sktemplate, &$links ) {
		if (isset($links['views']['view']))
			unset($links['views']['view']);
		if (isset($links['actions']['watch'])){
			$links['actions']['watch']['class'] = 'icon-star';
			$aTmp = $links['actions']['watch'];
			$links['views'] = array("watch" => $aTmp) + $links['views'];
			unset($links['actions']['watch']);
		}
		if (isset($links['actions']['unwatch'])){
			$links['actions']['unwatch']['class'] = 'icon-star3';
			$aTmp = $links['actions']['unwatch'];
			$links['views'] = array("unwatch" => $aTmp) + $links['views'];
			unset($links['actions']['unwatch']);
		}
		return true;
	}

	public static function onSkinTemplateOutputPageBeforeExec( &$sktemplate, &$tpl ){
		if ( !isset( $tpl->data['personal_urls']['notifications-alert'] )
				|| $tpl instanceof BsBaseTemplate != true ) {
			return true;
		}

		$tpl->data['bs_personal_info'][10] = array(
				'id' => 'pt-notifications-alert',
				'class' => 'mw-echo-notification-badge-nojs oo-ui-icon-bell oo-ui-widget-enabled mw-echo-notifications-badge',
			) + $tpl->data['personal_urls']['notifications-alert'];

		if( isset( $tpl->data['personal_urls']['notifications-alert']['text'] )
				&& $tpl->data['personal_urls']['notifications-alert']['text'] > 0 ) {
			$tpl->data['bs_personal_info'][10]['active'] = true;
		}

		unset( $tpl->data['personal_urls']['notifications-alert'] );


		if ( !isset( $tpl->data['personal_urls']['notifications-message'] )
				|| $tpl instanceof BsBaseTemplate != true ) {
			return true;
		}

		$tpl->data['bs_personal_info'][] = array(
				'id' => 'pt-notifications-message',
				'class' => 'mw-echo-notification-badge-nojs oo-ui-icon-speechBubbles oo-ui-widget-enabled mw-echo-notifications-badge',
			) + $tpl->data['personal_urls']['notifications-message'];

		if( isset( $tpl->data['personal_urls']['notifications-message']['text'] )
				&& $tpl->data['personal_urls']['notifications-message']['text'] > 0 ) {
			$tpl->data['bs_personal_info'][10]['active'] = true;
		}

		unset( $tpl->data['personal_urls']['notifications-message'] );

		return true;
	}
}
