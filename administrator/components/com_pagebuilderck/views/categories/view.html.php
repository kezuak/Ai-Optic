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
use Pagebuilderck\CKFof;

/**
 * View class for a list of Templateck.
 */
class PagebuilderckViewCategories extends CKView {

	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		// for the items published to be shown in the modal list
		$this->state = $this->model->getState();
		if ($this->input->get('layout') === 'modal') {
			$this->model->setState('filter_state', '1');
		} else {
			$this->model->setState('filter_state', '');
		}

		$this->items = $this->get('Items');

		if (CKFof::isAdmin()) $this->addToolbar();
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
			PagebuilderckHelper::addSubmenu('categories');
		}

		JToolBarHelper::title(JText::_('COM_PAGEBUILDERCK'));

		if (CKFof::userCan('create')) {
			// JToolBarHelper::addNew('page.add', 'JTOOLBAR_NEW');
			$importButton = '<button class="btn btn-small button-new btn btn-success" onclick="ckShowNewCategoryForm()">
									<span class="icon-new" aria-hidden="true"></span>
									' . JText::_('JTOOLBAR_NEW') . '
								</button>';
			$bar->appendButton('Custom', $importButton, 'import');
		}

		if (CKFof::userCan('edit')) {
			JToolBarHelper::custom('categories.copy', 'copy', 'copy', 'CK_COPY');
			JToolBarHelper::custom('categories.publish', 'publish', 'publish', 'JTOOLBAR_PUBLISH');
			JToolBarHelper::custom('categories.unpublish', 'unpublish', 'unpublish', 'JTOOLBAR_UNPUBLISH');
		}

		if (CKFof::userCan('core.delete')) {
			//If this component does not use state then show a direct delete button as we can not trash
			// JToolBarHelper::custom('page.trash', 'trash', 'trash', 'JTOOLBAR_DELETE', 'test');
			$deleteButton = '<joomla-toolbar-button id="toolbar-cktrash" list-selection=""><button class="btn btn-small ' . (PAGEBUILDERCK_ISJ4 ? 'btn-danger' : '') . '" onclick="if (document.adminForm.boxchecked.value == 0) { alert(Joomla.JText._(\'JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST\')); } else { if (confirm(\'' . JText::_('CK_CONFIRM_DELETE') . '\')) Joomla.submitbutton(\'categories.trash\'); }">
									<span class="icon-trash"></span>
									' . JText::_('CK_DELETE') . '
								</button></joomla-toolbar-button>';
			$bar->appendButton('Custom', $deleteButton, 'export');
		}

		if (CKFof::userCan('core.admin')) {
			JToolBarHelper::preferences('com_pagebuilderck');
		}
	}
}
