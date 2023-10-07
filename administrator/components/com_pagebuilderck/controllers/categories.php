<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// No direct access.
defined('_JEXEC') or die;

use Pagebuilderck\CKController;
use Pagebuilderck\CKFof;

/**
 * Pages list controller class.
 */
class PagebuilderckControllerCategories extends CKController {

	function __construct() {
		parent::__construct();
	}

	public function save($id = 0, $task = 'save', $ajax = false) {
		// Check for request forgeries.
		if ($ajax === true) {
			CKFof::checkAjaxToken();
		} else {
			CKFof::checkToken();
		}

		$model = $this->getModel();

		// Initialise variables.
		// $appendToUrl = $this->input->get('tmpl') ? '&tmpl=' . $this->input->get('tmpl') : '';
		// $layout = $this->input->get('layout') == 'modal' ? '&layout=modal' : '&layout=edit';

		// Get the user data.
		$data = array();
		$data['id'] = $this->input->get('id', $id, 'int');
		$id = $data['id'];
		$data['name'] = $this->input->get('name', '', 'string');
		$data['description'] = '';
		$data['ordering'] = 0;
		$data['state'] = 1;
		$data['created'] = null;
		$data['created_by'] = 0;
		$data['access'] = 1;

		// Check for errors.
		if ($data === false) {
			CKFof::enqueueMessage('ERROR : NO DATA SAVED', 'warning');
			// Redirect back to the edit screen.
			CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=categories');
			return false;
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false) {
			// Redirect back to the edit screen.
			CKFof::enqueueMessage(JText::_('CK_ITEM_SAVED_FAILED'), 'warning');
			CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=categories');
			return false;
		}

		// Redirect to the list screen.
		CKFof::enqueueMessage(JText::_('CK_ITEM_SAVED_SUCCESS'));

		$model->checkin($return);

		CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=categories');
	}
}