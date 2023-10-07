<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Pagebuilderck\CKView;
use Pagebuilderck\CKfof;

/**
 * About View
 */
class PagebuilderckViewAbout extends CKView {

	/**
	 * About view display method
	 * @return void
	 * */
	function display($tpl = null) {
		require_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';

		$this->ckversion = PagebuilderckHelper::getCurrentVersion();

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar() {
		require_once JPATH_COMPONENT . '/helpers/pagebuilderck.php';

		// Load the left sidebar only for Joomla 3 and under.
		if (! PAGEBUILDERCK_ISJ4) {
			PagebuilderckHelper::addSubmenu('about');
		}

		JToolBarHelper::title(JText::_('COM_PAGEBUILDERCK') . ' - ' . JText::_('CK_ABOUT') , 'pagebuilderck');

	}

}
