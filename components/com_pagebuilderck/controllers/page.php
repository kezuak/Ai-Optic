<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

defined('_JEXEC') or die;

use Pagebuilderck\CKController;
use Pagebuilderck\CKFof;

/**
 * Controller for single page view
 *
 * @since  1.5.19
 */
class PagebuilderckControllerPage extends CKController
{
	function __construct() {
		parent::__construct();
	}

	public function apply() {
		return $this->save(0, 'apply');
	}

	/**
	 * Method to save data.
	 *
	 * @return	void
	 */
	public function save($task = null) {
		// Check for request forgeries.
		CKFof::checkToken();

		// Initialise variables.
		$model = $this->getModel();

		// Initialise variables.
		$appendToUrl = $this->input->get('tmpl') ? '&tmpl=' . $this->input->get('tmpl') : '';
		$layout = $this->input->get('layout') == 'modal' ? '&layout=modal' : '&layout=edit';

		// Get the user data.
		$data = $this->input->getArray($_POST);

		// Check for errors.
		if ($data === false) {
			CKFof::enqueueMessage('ERROR : NO DATA SAVED', 'warning');
			// Redirect back to the edit screen.
			CKFof::redirect(PAGEBUILDERCK_BASE_URL . '&view=page'.$layout.'&id=' . $id . $appendToUrl);
			return false;
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false) {
			// Redirect back to the edit screen.
			CKFof::enqueueMessage(JText::_('CK_ITEM_SAVED_FAILED'), 'warning');
			CKFof::redirect(PAGEBUILDERCK_BASE_URL . '&view=page' . $layout . '&id=' . $id . $appendToUrl);
			return false;
		}

		// Redirect to the list screen.
		CKFof::enqueueMessage(JText::_('CK_ITEM_SAVED_SUCCESS'));

		$model->checkin($return);

		switch ($task)
		{
			case 'apply':
				// Redirect back to the edit screen.
				CKFof::redirect(PAGEBUILDERCK_BASE_URL . '&view=page' . $layout . '&id=' . $return . $appendToUrl);
				break;
			default:
				// Redirect to the list screen.
				CKFof::redirect(PAGEBUILDERCK_BASE_URL . '&view=page&id=' . $return, false);
				break;
		}
	}

	function cancel() {
		$id = $this->input->get('id', 0, 'int');
		if (! $this->getModel()->checkin($id)) {
			CKFof::redirect('index.php?option=com_pagebuilderck&view=page&id=' . $id, JLIB_APPLICATION_ERROR_CHECKIN_FAILED, 'warning');
		}

		//Redirect back to list
		CKFof::redirect('index.php?option=com_pagebuilderck&view=page&id=' . $id);
	}
}
