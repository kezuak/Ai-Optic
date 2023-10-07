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
		if (! self::canUseFrontendEdition()) return;
		
		// load language file
		$lang	= \JFactory::getLanguage();
		$lang->load('com_pagebuilderck', JPATH_SITE . '/components/com_pagebuilderck', $lang->getTag(), false);

		global $pagebuilderckEditionLoaded;
		$pagebuilderckEditionLoaded = true;

		echo self::renderMenu();
		// loads the language files from the frontend
//		$lang	= JFactory::getLanguage();
//		$lang->load('com_pagebuilderck', JPATH_SITE . '/components/com_pagebuilderck', $lang->getTag(), false);
		$editor = \JFactory::getConfig()->get('editor') == 'jce' ? 'jce' : 'tinymce';
		$editor = \JEditor::getInstance($editor);
		$editor->display('ckeditor', $html = '', $width = '', $height = '200px', $col='', $row='', $buttons = true, $id = 'ckeditor');
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckeditor.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';
		include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/include.php');
		include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/menu.php');
		?>
		<link rel="stylesheet"  href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/frontedition.css" type="text/css" />
		<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/frontedition.js" type="text/javascript"></script>
		<?php

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
	public static function renderMenu() {;
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
		<a href="javascript:void(0);" class="ckheadermenuitem cksave" onclick="ckPagebuilderckFrontEditionSave()">
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


	/*public static function callTemplateEdition() {
		?>
		<link rel="stylesheet"  href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckframework.css" type="text/css" />
		<link rel="stylesheet"  href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/frontedition.css" type="text/css" />
		<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/frontedition.js" type="text/javascript"></script>
		<?php
		self::renderMenu();
		// loads the language files from the frontend
		$lang	= JFactory::getLanguage();
		$lang->load('com_pagebuilderck', JPATH_SITE . '/components/com_pagebuilderck', $lang->getTag(), false);
		$editor = JFactory::getConfig()->get('editor') == 'jce' ? 'jce' : 'tinymce';
		$editor = JEditor::getInstance($editor);
		$editor->display('ckeditor', $html = '', $width = '', $height = '200px', $col='', $row='', $buttons = true, $id = 'ckeditor');
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckeditor.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';
		include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/include.php');
		include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/menu.php');
	}*/
/*
	public static function renderMenu() {
		?>
		<div id="ckheader">
			<div class="ckheaderlogo"><a href="https://www.joomlack.fr" target="_blank"><img width="35" height="35" title="JoomlaCK" src="<?php echo CKUri::root(true) ?>/media/com_pagebuilderck/images/logo_ck.png" /></a></div>
			<div class="ckheadermenu">
				<a href="<?php //echo JRoute::_('index.php?option=com_templateck'); ?>" class="ckheadermenuitem ckcancel" >
					<span class="fa fa-times"></span>
					<span class="ckheadermenuitemtext"><?php echo CKText::_('CK_EXIT') ?></span>
				</a>
				<a href="javascript:void(0);" class="ckheadermenuitem cksave" onclick="ckTemplateEditionSave()">
					<span class="fa fa-check"></span>
					<span class="ckheadermenuitemtext"><?php echo CKText::_('CK_SAVE') ?></span>
				</a>
			</div>
		</div>
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
			z-index: 100000;
		}

		div.menuck > .inner {
			height: calc(100vh - 115px);
		}


		</style>
		<?php
	}
*/
	private static function getCurrentUri() {
		$uri = \JFactory::getURI();
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
			z-index: 100000;
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
		if ($authorised !== true)
		{
			if ($user->guest === 1)
			{
				$return = base64_encode(CKUri::getInstance());
				$login_url_with_return = \JRoute::_('index.php?option=com_users&return=' . $return);
				$app->enqueueMessage(CKText::_('JERROR_ALERTNOAUTHOR'), 'notice');
				$app->redirect($login_url_with_return, 403);
			}
			else
			{
				$app->enqueueMessage(CKText::_('JERROR_ALERTNOAUTHOR'), 'error');
				$app->setHeader('status', 403, true);
				return;
			}
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
}

// autoload the edition
//PBCK_FrontEdition::init();
