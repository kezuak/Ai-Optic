<?php
/**
 * @name		Page Builder CK
 * @copyright	Copyright (C) since 2011. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
namespace Pagebuilderck;

// No direct access
defined('_JEXEC') or die;
if (! defined('CK_LOADED')) define('CK_LOADED', 1);

require_once 'ckinput.php';
require_once 'cktext.php';
// require_once 'ckpath.php';
require_once 'ckfile.php';
require_once 'ckfolder.php';
require_once 'ckfof.php';
//use Joomla\CMS\Language\Text as CKText;
use Joomla\CMS\Uri\Uri as CKUri;

use Pagebuilderck\CKFof;

/**
 * Front Edition helper
 */
class FrontEdition {

	public static function init() {
//		return; // TODO remove to enable front edition
		if (! self::canUseFrontendEdition()) return;

		$pagebuilderckParams = \JComponentHelper::getParams('com_pagebuilderck');
		// check if the front edition is enabled from the options
		$enableFrontedition = $pagebuilderckParams->get('frontedition', '1', 'int');
		if ($enableFrontedition == 0) return;

		// load language file
		$lang	= \JFactory::getLanguage();
		$lang->load('com_pagebuilderck', JPATH_SITE . '/components/com_pagebuilderck', $lang->getTag(), false);

		global $pagebuilderckEditionLoaded, $ckFrontEditionLoaded;
		$pagebuilderckEditionLoaded = true;

		$input = CKFof::getInput();
		$user = CKFof::getUser();
		if ($user->id && $input->get('tckedition', 0, 'int') === 1) {
			if (! $ckFrontEditionLoaded) echo self::renderMenu();

			$editor = \JFactory::getConfig()->get('editor') == 'jce' ? 'jce' : 'tinymce';
			$editor = \JEditor::getInstance($editor);
			$editor->display('ckeditor', $html = '', $width = '', $height = '200px', $col='', $row='', $buttons = true, $id = 'ckeditor');
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckeditor.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';
			include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/include.php');
			include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/menu.php');
			include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/toolbar.php');
			?>
			<link rel="stylesheet"  href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/frontedition.css" type="text/css" />
			<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/frontedition.js" type="text/javascript"></script>
			<?php
		} else {
			if ($user->id) {
				$buttons = self::getActivationButton();
				$html = self::getEmptyMenu($buttons);
				echo $html;
				?>
				<link rel="stylesheet"  href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckframework.css" type="text/css" />
				<link rel="stylesheet"  href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/frontedition.css" type="text/css" />
				<?php
			}
		}

		// check if modules manager ck is used and if the correct version is insalled
		self::checkModulesmanagerckCompatibility();

		$ckFrontEditionLoaded = true;
				/*$user = CKFof::getUser();
		$input = CKFof::getInput();
		// if user logged in
		if ($user->id && $input->get('tckedition', 0, 'int') === 1) {
//			self::renderMenu();
			// get current uri
			$uri = JFactory::getURI();
			$current_url = $uri->toString();
//			CKFof::redirect(JUri::root(true) . '/index.php?option=com_pagebuilderck&view=frontedition&url=' . urlencode($current_url));
		
//			$this->callTemplateEdition();
		}*/
//		self::callTemplateEdition();
	}

	public static function getEmptyMenu($buttons = '') {
		ob_start();
		?>
		<div id="ckheader">
			<div class="ckheaderlogo"><a href="https://www.joomlack.fr" target="_blank"><img width="35" height="35" title="JoomlaCK" src="https://media.joomlack.fr/images/logo_ck_white.png" /></a></div>
			<div class="ckheadermenu">
				<?php echo $buttons; ?>
			</div>
		</div>
		<?php
		echo self::getCssForEdition();
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/* load only if page builder ck is not already loaded */
	public static function renderMenu() {
		$current_url = self::getCurrentUri();
		ob_start();
		$mmckbuttons = '';
		$editionfilemmck = JPATH_SITE . '/components/com_modulesmanagerck/helpers/frontedition.php';
		if (file_exists($editionfilemmck)) {
			require_once $editionfilemmck;
			if (\Modulesmanagerck\FrontEdition::canUseFrontendEdition()) {
				$mmckbuttons = \Modulesmanagerck\FrontEdition::getMenuButtons();
				echo \Modulesmanagerck\FrontEdition::getCssForEdition();
			}
		}
		?>
		<a href="<?php echo str_replace('tckedition=1', '', $current_url); ?>" class="ckheadermenuitem ckcancel" >
			<span class="fa fa-times cktip" data-placement="bottom" title="<?php echo CKText::_('CK_EXIT'); ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo CKText::_('CK_EXIT') ?></span>
		</a>
		<a href="javascript:void(0);" class="ckheadermenuitem cksave" onclick="ckPagebuilderFrontEditionSave()">
			<span class="fa fa-check cktip" data-placement="bottom" title="<?php echo CKText::_('CK_SAVE'); ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo CKText::_('CK_SAVE') ?></span>
		</a>
		<?php
		echo self::getCssForEdition();
		$buttons = ob_get_contents();
		ob_end_clean();

		$html = self::getEmptyMenu($mmckbuttons . $buttons);

		return $html;
	}

	/*
	* Function that can be called by others like Page Builder CK to integrate the buttons in another toolbar
	*/
	public static function getActivationButton() {
		$link = (CKURI::getInstance()->getQuery() ? self::getCurrentUri() . '&tckedition=1' : self::getCurrentUri() . '?tckedition=1');

		ob_start();
		?>
		<a href="<?php echo $link ?>" class="ckheadermenuitem">
			<span class="fa fa-toggle-on cktip" title="<?php echo CKText::_('CK_ENABLE'); ?> <?php echo CKText::_('CK_FRONT_EDITION'); ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo CKText::_('CK_ENABLE'); ?> <?php echo CKText::_('CK_FRONT_EDITION'); ?></span>
		</a>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	private static function getCurrentUri() {
		// $uri = \JFactory::getURI();
		$uri = \Juri::getInstance();
		return $uri->toString();
	}

	public static function getCssForEdition() {
		ob_start();
		?>
		<style>
		.menuck {
			top: 65px !important;
		}

		#ckheader .ckheadermenu .ckheadermenuitem {
			font-size: 13px;
			line-height: 20px;
		}
		

		html {
			padding-top: 66px! important;
		}

		body.tck-edition-body #ckheader {
			z-index: 10000;
		}

		body.tck-edition-body .tab_fullscreen {
			top:65px;
		}

		div.menuck > .inner {
			height: calc(100vh - 115px);
		}
		</style>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public static function canUseFrontendEdition() {
		// check that the user has the rights to edit
		$user = \JFactory::getUser();
		$app = \JFactory::getApplication();
		$authorised = ($user->authorise('core.edit', 'com_pagebuilderck'));
		// check the ACL from the component config
		$canusefrontedition = $user->authorise('core.frontedition', 'com_pagebuilderck');
		if (! $canusefrontedition) return false;

		if ($authorised !== true)
		{
			return false;
//			if ($user->guest === 1)
//			{
//				$return = base64_encode(CKUri::getInstance());
//				$login_url_with_return = \JRoute::_('index.php?option=com_users&return=' . $return);
//				$app->enqueueMessage(CKText::_('JERROR_ALERTNOAUTHOR'), 'notice');
//				$app->redirect($login_url_with_return, 403);
//			}
//			else
//			{
//				$app->enqueueMessage(CKText::_('JERROR_ALERTNOAUTHOR'), 'error');
//				$app->setHeader('status', 403, true);
//				return;
//			}
		}

		// check that the template is compatible
		$app    = \JFactory::getApplication();
		$template = $app->getTemplate();

		// load xml file from the template
		$xml = simplexml_load_file(JPATH_SITE . '/templates/' . $template . '/templateDetails.xml');

		// check that the template is made with a compatible version of Template Creator CK
		if ($xml->generator != 'Template Creator CK') {
			// JError::raiseWarning(403, CKText::_('The template you are trying to edit has not been created with Template Creator CK, or not the latest version of if. You can download Template Creator CK on <a href="https://www.template-creator.com">https://www.template-creator.com</a>'));
			return false;
		}

		return true;
	}

	private static function checkModulesmanagerckCompatibility() {
		if (! file_exists(JPATH_ROOT . '/components/com_modulesmanagerck/modulesmanagerck.php')) return true;
		$xmlData = self::getXmlData(JPATH_ROOT . '/administrator/components/com_modulesmanagerck/modulesmanagerck.xml');
		$installedVersion = ((string)($xmlData->version));

		// if the installed version is the V1
		if(version_compare($installedVersion, '1.3.1', '<')) {
			// if the params is also installed
			if (\JPluginHelper::isEnabled('system', 'modulesmanagersystemck')) {
//				throw new \RuntimeException('Slideshow CK Light cannot be installed over Slideshow CK V1 + Params. Please install Slideshow CK Pro to get the same features as previously, else you may loose your existing settings. To downgrade, please first uninstall Slideshow CK Params. <a href="https://www.joomlack.fr/en/documentation/48-slideshow-ck/246-migration-from-slideshow-ck-version-1-to-version-2" target="_blank">Read more</a>');
				echo '<p style="color:red;font-size: 20px">WARNING : Modules Manager CK has been detected but its version is not up to date. You must set up your system correctly or it will not work.<br/> <a href="https://www.joomlack.fr/documentation/page-builder-ck/250-front-edtion" target="_blank">Please check the documentation</a></p>';
				return false;
			}
		}
		return true;
	}

	public static function getXmlData($file) {
		if ( ! is_file($file))
		{
			return '';
		}

		$xml = simplexml_load_file($file);

		if ( ! $xml || ! isset($xml['version']))
		{
			return '';
		}

		return $xml;
	}
}

// autoload the edition
//PBCK_FrontEdition::init();
