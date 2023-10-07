<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

use Pagebuilderck\CKView;
use Pagebuilderck\CKfof;

/**
 * View class for a list of Templateck.
 */
class PagebuilderckViewElements extends CKView {

	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		$this->items = $this->get('Items');

		if (\Pagebuilderck\CKFof::isAdmin()) $this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar() {
		PagebuilderckHelper::loadCkbox();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		// Load the left sidebar only for Joomla 3 and under.
		if (! PAGEBUILDERCK_ISJ4) {
			PagebuilderckHelper::addSubmenu('elements');
		}

		JToolBarHelper::title(JText::_('COM_PAGEBUILDERCK'));

		if (CKFof::userCan('core.create')) {
			// JToolBarHelper::addNew('element.add', 'JTOOLBAR_NEW');
		}

		if (CKFof::userCan('core.edit')) {
			JToolBarHelper::editList('element.edit', 'JTOOLBAR_EDIT');
			JToolBarHelper::custom('element.copy', 'copy', 'copy', 'CK_COPY');
		}

		if (CKFof::userCan('core.delete')) {
			JToolBarHelper::trash('element.trash');
		}

		if (CKFof::userCan('core.admin')) {
			JToolBarHelper::preferences('com_pagebuilderck');
		}
	}
}
