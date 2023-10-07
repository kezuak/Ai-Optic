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
use Joomla\CMS\Factory;

class PagebuilderckViewPage extends CKView {

	function display($tpl = null) {
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckeditor.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';

		JToolBarHelper::title(JText::_('COM_PAGEBUILDERCK'), 'home_pagebuilderck');

		// get instance of the editor to load the css / js in the page
		$this->ckeditor = PagebuilderckHelper::loadEditor();
		$this->item = $this->get('Item');
		$this->elements = $this->get('Elements');

		$user = CKFof::getUser();
		$authorised = ($user->authorise('core.create', 'com_pagebuilderck') || (count($user->getAuthorisedCategories('com_pagebuilderck', 'core.create'))));

		if ($authorised !== true)
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		$this->addToolbar();

		parent::display($tpl);
		// exit to make a full edition standalone page
		if ($this->input->get('display') === 'raw') {
			exit();
		}
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar() {

		$this->input->set('hidemainmenu', true);
		$user		= CKFof::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0 || $this->item->id == "");
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		JToolBarHelper::title(JText::_('COM_PAGEBUILDERCK'));

		// For new records, check the create permission.
		if ($isNew && $user->authorise('core.create', 'com_pagebuilderck'))
		{
			JToolbarHelper::apply('page.apply');
			JToolbarHelper::save('page.save');
			JToolbarHelper::cancel('page.cancel');
		} else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if (CKFof::userCan('core.edit') || (CKFof::userCan('core.edit.own') && $this->item->created_by == $userId))
				{
					JToolbarHelper::apply('page.apply');
					JToolbarHelper::save('page.save');
					JToolbarHelper::custom('page.restore', 'archive', 'archive', 'CK_RESTORE', false);
				}
			}

			JToolbarHelper::cancel('page.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
