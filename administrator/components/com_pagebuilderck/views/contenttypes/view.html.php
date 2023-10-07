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
class PagebuilderckViewContenttypes extends CKView {

	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null) {
//		require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/pagebuilderck.php';

		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		// Check for errors.
//		if (! empty($errors = $this->get('Errors'))) {
//			JError::raiseError(500, implode("\n", $errors));
//			return false;
//		}

		if (Pagebuilderck\CKFof::isAdmin()) $this->addToolbar();
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

		$state = $this->get('State');
		$canDo = PagebuilderckHelper::getActions($state->get('filter.category_id'));

		// Load the left sidebar.
		PagebuilderckHelper::addSubmenu('contenttypes');

		JToolBarHelper::title(JText::_('COM_PAGEBUILDERCK'));

		//Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/contenttype';
		if (file_exists($formPath)) {

			if ($canDo->get('core.create')) {
				// JToolBarHelper::addNew('contenttype.add', 'JTOOLBAR_NEW');
			}

			if ($canDo->get('core.edit')) {
				JToolBarHelper::editList('contenttype.edit', 'JTOOLBAR_EDIT');
				JToolBarHelper::custom('contenttype.copy', 'copy', 'copy', 'CK_COPY');
			}
		}

		if ($canDo->get('core.edit.state')) {

			if (isset($this->items[0]->state)) {
				JToolBarHelper::divider();
			} else {
				//If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::trash('contenttypes.delete');
			}



			if (isset($this->items[0]->state)) {
				JToolBarHelper::divider();
			}
		}

		//Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state)) {
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
				JToolBarHelper::divider();
				JToolBarHelper::trash('contenttypes.delete', 'CK_DELETE');
			} else if ($canDo->get('core.edit.state')) {
				JToolBarHelper::trash('contenttypes.trash', 'CK_DELETE');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_pagebuilderck');
		}
	}
}
