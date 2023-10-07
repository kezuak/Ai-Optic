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
class PagebuilderckViewPages extends CKView {

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
			PagebuilderckHelper::addSubmenu('pages');
		}

		JToolBarHelper::title(JText::_('COM_PAGEBUILDERCK'));

		if (CKFof::userCan('create')) {
			JToolBarHelper::addNew('page.add', 'JTOOLBAR_NEW');
		}

		if (CKFof::userCan('edit')) {
			JToolBarHelper::editList('page.edit', 'JTOOLBAR_EDIT');
			JToolBarHelper::custom('page.copy', 'copy', 'copy', 'CK_COPY');
			JToolBarHelper::custom('page.publish', 'publish', 'publish', 'JTOOLBAR_PUBLISH');
			JToolBarHelper::custom('page.unpublish', 'unpublish', 'unpublish', 'JTOOLBAR_UNPUBLISH');
			// if Params is installed
			if (PagebuilderckHelper::getParams()) {
				$importButton = '<button class="btn btn-small" onclick="CKBox.open({handler: \'inline\', content: \'ckImportModal\', fullscreen: false, size: {x: \'600px\', y: \'200px\'}});">
									<span class="icon-forward-2"></span>
									' . JText::_('CK_IMPORT') . '
								</button>';
				$bar->appendButton('Custom', $importButton, 'import');

				$exportButton = '<button class="btn btn-small" onclick="ckExportPage(document.adminForm);">
									<span class="icon-share"></span>
									' . JText::_('CK_EXPORT') . '
								</button>';
				$bar->appendButton('Custom', $exportButton, 'export');

				// if (document.adminForm.boxchecked.value==0){alert('Veuillez d\'abord effectuer une s�lection dans la liste.');}else{ Joomla.submitbutton('pages.export')}
				// JToolBarHelper::custom('pages.export', 'share', 'share', 'CK_EXPORT', true);
				if ($importClass = PagebuilderckHelper::getParams('import')) {
					$importClass->loadImportForm();
				}
				if ($exportClass = PagebuilderckHelper::getParams('export')) {
					$exportClass->loadExportForm();
				}
			} else {
				$importButton = '<button class="btn btn-small" onclick="CKBox.open({handler:\'inline\',content: \'pagebuilderckparamsmessage\', fullscreen: false, size: {x: \'600px\', y: \'150px\'}});">
									<span class="icon-forward-2"></span>
									' . JText::_('CK_IMPORT') . '
								</button>';
				$bar->appendButton('Custom', $importButton, 'import');
				$exportButton = '<button class="btn btn-small" onclick="CKBox.open({handler:\'inline\',content: \'pagebuilderckparamsmessage\', fullscreen: false, size: {x: \'600px\', y: \'150px\'}});">
									<span class="icon-share"></span>
									' . JText::_('CK_EXPORT') . '
								</button>';
				$bar->appendButton('Custom', $exportButton, 'export');
				echo PagebuilderckHelper::showParamsMessage(false);
			}
		}

		if (CKFof::userCan('core.delete')) {
			//If this component does not use state then show a direct delete button as we can not trash
			JToolBarHelper::trash('page.trash');
		}

		if (CKFof::userCan('core.admin')) {
			JToolBarHelper::preferences('com_pagebuilderck');
		}
	}
}
