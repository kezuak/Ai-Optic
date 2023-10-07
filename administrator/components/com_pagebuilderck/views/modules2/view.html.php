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

class PagebuilderckViewModules2 extends CKView {

	/**
	 * Display the view
	 */
	public function display($tpl = null) {

		$this->items = $this->get('Items');

		$user = CKFof::getUser();
		$authorised = ($user->authorise('core.create', 'com_pagebuilderck') || (count($user->getAuthorisedCategories('com_pagebuilderck', 'core.create'))));

		if ($authorised !== true)
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

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

		// Load the left sidebar only for Joomla 3 and under.
		if (! PAGEBUILDERCK_ISJ4) {
			PagebuilderckHelper::addSubmenu('modules2');
		}

		JToolBarHelper::title(JText::_('COM_PAGEBUILDERCK'));
	}
}
