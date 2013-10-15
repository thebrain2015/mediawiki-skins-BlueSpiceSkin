<?php

/**
 * blue spice for MediaWiki
 * Authors: Radovan Kubani, Sebastian Ulbricht
 *
 * Copyright (C) 2010 Hallo Welt! – Medienwerkstatt GmbH, All rights reserved.
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
 *
 * Version information
 * $LastChangedDate: 2013-09-05 16:52:01 +0200 (Do, 05 Sep 2013) $
 * $LastChangedBy: tweichart $
 * $Rev: 10325 $
 * $Id: BlueForge.php 10325 2013-09-05 14:52:01Z tweichart $
 */
class SkinBlueSpiceSkinMobile extends SkinTemplate {

	var $skinname = 'bluespiceskinmobile';
	var $stylename = 'BlueSpiceSkinMobile';
	var $template = 'BlueSpiceSkinMobileTemplate';
	var $useHeadElement = true;

	/**
	 * @param $out OutputPage object
	 */
	function initPage(\OutputPage $out) {
		parent::initPage($out);
		$out->addModules('ext.bluespice.bluespiceskinmobile.js');
	}

	function setupSkinUserCss(OutputPage $out) {
		parent::setupSkinUserCss($out);
		$out->addModuleStyles('ext.bluespice.bluespiceskinmobile');
		$out->addHeadItem("viewport", '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" />');
	}

}

class BlueSpiceSkinMobileTemplate extends BaseTemplate {

	function __construct() {
		parent::__construct();
		global $wgTitle;
		$this->data['bs_title_actions'] = array(
			array(
				'id' => 'print',
				'href' => $wgTitle->getFullUrl(array('printable' => 'yes')),
				'title'
			)
		);
	}

	public static function onBSAddBodyAttribute($out, $sk, &$bodyAttrs) {
		$bodyAttrs['class'] .= ' skated-wptouch-bg';
		return true;
	}

	public function execute() {
		global $oMobileDetect;
		$this->skin = $this->data['skin'];
		wfSuppressWarnings();
		$this->html('headelement');
		?>

		<!-- New noscript check, we need js on now folks -->
		<noscript>
		<div id="noscript-wrap">
			<div id="noscript">
				<h2>Notice</h2>
				<p>JavaScript is currently turned off.</p>
				<p>Turn it on in <em>Settings</em><br /> to view this website.</p>
			</div>
		</div>
		</noscript>

		<!--#start The Login Overlay -->
		<div id="wptouch-login">
			<div id="wptouch-login-inner">
				<form name="loginform" id="loginform" action="/wordpress/wp-login.php" method="post">
					<label><input type="text" name="log" id="log" onfocus="if (this.value == 'username') {this.value = ''}" value="username" /></label>
					<label><input type="password" name="pwd" onfocus="if (this.value == 'password') {this.value = ''}" id="pwd" value="password" /></label>
					<input type="hidden" name="rememberme" value="forever" />
					<input type="hidden" id="logsub" name="submit" value="Login" tabindex="9" />
					<input type="hidden" name="redirect_to" value="/"/>
					<a href="javascript: return false;" onclick="bnc_jquery_login_toggle();"><img class="head-close" src="<?php echo $this->data['stylepath'] ?>/BlueSpiceSkin/resources/images/mobile/head-close.png" alt="close" /></a>
				</form>
			</div>
		</div>

		<!-- #start The Search Overlay -->
		<div id="wptouch-search"> 
			<div id="wptouch-search-inner">
				<form method="get" id="searchform" action="<?php $this->text('searchaction') ?>">
					<input name="search" type="text" value="Search..." onfocus="if (this.value == 'Search...') {this.value = ''}" name="s" id="s" /> 
					<input name="go" type="hidden" tabindex="5" value="Go" />
					<a href="javascript: return false;" onclick="bnc_jquery_search_toggle();"><img class="head-close" src="<?php echo $this->data['stylepath'] ?>/BlueSpiceSkin/resources/images/mobile/head-close.png" alt="close" /></a>
				</form>
			</div>
		</div>

		<div id="wptouch-menu" class="dropper"> 		
			<div id="wptouch-menu-inner">
				<div id="menu-head">
					<div id="tabnav">
						<a href="#head-navigation"><?php $this->msg('navigation') ?></a>
						<a href="#head-toolbox"><?php $this->msg('toolbox') ?></a>
						<a href="#head-personal"><?php $this->msg('personaltools') ?></a>
						<?php if ($oMobileDetect->isTablet()) { ?>
							<a href="#head-actions"><?php $this->msg('actions') ?></a>
						<?php } ?>
					</div>

					<ul id="head-navigation">
						<?php foreach ($this->data['sidebar'] as $bar => $cont) { ?>
							<?php foreach ($cont as $key => $val) { ?>
								<li id="<?php echo Sanitizer::escapeId($val['id']) ?>"<?php if ($val['active']) { ?> class="active" <?php }
								?>><a href="<?php echo htmlspecialchars($val['href']) ?>"<?php echo Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs($val['id'])) ?>><?php echo htmlspecialchars($val['text']) ?></a></li>
								<?php } ?>
							<?php } ?>
					</ul>

					<ul id="head-toolbox">
						<?php if ($this->data['notspecialpage']) { ?>
							<li id="t-whatlinkshere"><a href="<?php
				echo htmlspecialchars($this->data['nav_urls']['whatlinkshere']['href'])
							?>"<?php echo Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs('t-whatlinkshere')) ?>><?php $this->msg('whatlinkshere') ?></a></li>
								<?php if ($this->data['nav_urls']['recentchangeslinked']) { ?>
								<li id="t-recentchangeslinked"><a href="<?php
					echo htmlspecialchars($this->data['nav_urls']['recentchangeslinked']['href'])
									?>"<?php echo Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs('t-recentchangeslinked')) ?>><?php $this->msg('recentchangeslinked') ?></a></li>
									<?php
								}
							}
							if (isset($this->data['nav_urls']['trackbacklink'])) {
								?>
							<li id="t-trackbacklink"><a href="<?php
					echo htmlspecialchars($this->data['nav_urls']['trackbacklink']['href'])
								?>"<?php echo Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs('t-trackbacklink')) ?>><?php $this->msg('trackbacklink') ?></a></li>
								<?php
							}
							if ($this->data['feeds']) {
								?>
							<li id="feedlinks"><?php foreach ($this->data['feeds'] as $key => $feed) {
									?><span id="feed-<?php echo Sanitizer::escapeId($key) ?>"><a href="<?php echo htmlspecialchars($feed['href']) ?>"<?php echo Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs('feed-' . $key)) ?>><?php echo htmlspecialchars($feed['text']) ?></a
										>&nbsp;</span>
								<?php } ?></li><?php
				}

				foreach (array('contributions', 'log', 'blockip', 'emailuser', 'upload', 'specialpages') as $special) {
					if ($this->data['nav_urls'][$special]) {
									?>
								<li id="t-<?php echo $special ?>"><a href="<?php echo htmlspecialchars($this->data['nav_urls'][$special]['href'])
									?>"<?php echo Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs('t-' . $special)) ?>><?php $this->msg($special) ?></a></li>
									<?php
								}
							}
							if (!empty($this->data['nav_urls']['print']['href'])) {
								?>
							<li id="t-print"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['print']['href'])
								?>"<?php echo Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs('t-print')) ?>><?php $this->msg('printableversion') ?></a></li>
								<?php
							}
							if (!empty($this->data['nav_urls']['permalink']['href'])) {
								?>
							<li id="t-permalink"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['permalink']['href'])
								?>"<?php echo Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs('t-permalink')) ?>><?php $this->msg('permalink') ?></a></li>
							<?php } elseif ($this->data['nav_urls']['permalink']['href'] === '') {
								?>
							<li id="t-ispermalink"<?php echo $skin->tooltip('t-ispermalink') ?>><?php $this->msg('permalink') ?></li>
							<?php
						}

						wfRunHooks('pixeledTemplateToolboxEnd', array(&$this));
						?>
					</ul>

					<ul id="head-personal">
						<?php foreach ($this->data['personal_urls'] as $key => $item) { ?>
							<li id="pt-<?php echo Sanitizer::escapeId($key) ?>"<?php if ($item['active']) { ?> class="active"<?php } ?>><a href="<?php echo htmlspecialchars($item['href']) ?>"<?php echo Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs('pt-' . $key)) ?><?php if (!empty($item['class'])) { ?> class="<?php echo htmlspecialchars($item['class']) ?>"<?php } ?>><?php echo htmlspecialchars($item['text']) ?></a></li>
						<?php } ?>
					</ul>
					<?php if ($oMobileDetect->isTablet()) { ?>
						<ul id="head-actions">
							<?php foreach ($this->data['content_actions'] as $key => $item) { ?>
								<li id="pt-<?php echo Sanitizer::escapeId($key) ?>"<?php if ($item['active']) { ?> class="active"<?php } ?>><a href="<?php echo htmlspecialchars($item['href']) ?>"<?php echo Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs('ca-' . $key)) ?><?php if (!empty($item['class'])) { ?> class="<?php echo htmlspecialchars($item['class']) ?>"<?php } ?>><?php echo htmlspecialchars($item['text']) ?></a></li>
								<?php } ?>
						</ul>
													<?php } ?>
				</div>
			</div>
		</div>

		<div id="headerbar">
			<div id="headerbar-title">
				<img id="logo-icon" src="<?php echo $this->data['stylepath'] ?>/BlueSpiceSkin/resources/images/mobile/bsplauncher.png" alt="<?php $this->msg('sitetitle') ?>" />
				<a href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href']); ?>"><?php $this->msg('sitetitle') ?></a>
			</div>
			<div id="headerbar-menu">
				<a href="#" onclick="bnc_jquery_menu_drop(); return false;"></a>
			</div>
		</div>

		<div id="drop-fade">
			<a id="searchopen" class="top" href="#" onclick="bnc_jquery_search_toggle(); return false;">Search</a>
		</div>

		<div class="content">
			<div class="post">
				<h2><?php $this->data['displaytitle'] != "" ? $this->html('title') : $this->text('title') ?></h2>
				<hr>
				<div class="clearer"></div>
				<div class="mainentry">
					<p><?php $this->html('bodytext') ?></p>
				</div>
			</div>
		</div>

		<div class="cleared"></div>
		<div class="visualClear"></div>
		<div class='bs-autocomplete-field' style='display: none;'></div>
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