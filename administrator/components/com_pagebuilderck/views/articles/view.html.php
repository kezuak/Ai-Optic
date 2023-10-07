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

class PagebuilderckViewArticles extends CKView {

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

		// Load the left sidebar only for Joomla 3 and under.
		if (! PAGEBUILDERCK_ISJ4) {
			PagebuilderckHelper::addSubmenu('articles');
		}

		JToolBarHelper::title(JText::_('COM_PAGEBUILDERCK'));

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		// Create the New button
		$newButton = '<a class="btn btn-small button-new btn-success" href="index.php?option=com_content&view=article&layout=edit&pbck=1">
										<span class="icon-new icon-white"></span>
										' . JText::_('CK_NEW') . '
									</a>';
		$bar->appendButton('Custom', $newButton, 'new');
	}
}
