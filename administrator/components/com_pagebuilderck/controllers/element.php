<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.page-creator.com - https://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

use Pagebuilderck\CKController;
use Pagebuilderck\CKFof;

/**
 * Page controller class.
 */
class PagebuilderckControllerElement extends CKController {

	function __construct() {
		parent::__construct();
	}

	public function apply() {
		return $this->save(0, 'apply');
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function save($id = 0, $task = 'save') {
		// Check for request forgeries.
		CKFof::checkToken();

		$model = $this->getModel();

		// Initialise variables.
		$appendToUrl = $this->input->get('tmpl') ? '&tmpl=' . $this->input->get('tmpl') : '';
		$layout = $this->input->get('layout') == 'modal' ? '&layout=modal' : '&layout=edit';

		// Get the user data.
		$data = $this->input->getArray($_POST);
		$data['htmlcode'] = $this->input->get('htmlcode', '', 'raw');
		$data['htmlcode'] = str_replace(JUri::root(true), "|URIROOT|", $data['htmlcode']);

		// Check for errors.
		if ($data === false) {
			CKFof::enqueueMessage('ERROR : NO DATA SAVED', 'warning');
			// Redirect back to the edit screen.
			CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=element' . $layout . '&id=' . $id . $appendToUrl);
			return false;
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false) {
			// Redirect back to the edit screen.
			CKFof::enqueueMessage(JText::_('CK_ITEM_SAVED_FAILED'), 'warning');
			CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=element' . $layout . '&id=' . $id . $appendToUrl);
			return false;
		}

		// Redirect to the list screen.
		CKFof::enqueueMessage(JText::_('CK_ITEM_SAVED_SUCCESS'));

		switch ($task)
		{
			case 'apply':
				// Redirect back to the edit screen.
				CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=element' . $layout . '&id=' . $return . $appendToUrl);
				break;
			default:
				// Redirect to the list screen.
				CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=elements');
				break;
		}
	}

	/**
	 * copy an existing element
	 * @return void
	 */
	function copy() {
		// Check for request forgeries.
		CKFof::checkToken();

		$this->redirect = PAGEBUILDERCK_ADMIN_URL . '&view=elements';

		parent::copy();
	}

	function cancel() {
		//Redirect back to list
		CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=elements');
	}

	/**
	 * copy an existing element
	 * @return void
	 */
	function delete() {
		// Check for request forgeries.
		CKFof::checkToken();

		$this->redirect = PAGEBUILDERCK_ADMIN_URL . '&view=elements';

		parent::delete();
	}
}