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
class PagebuilderckControllerPage extends CKController {

	function __construct() {
		parent::__construct();
	}

	public function apply() {
		return $this->save(0, 'apply');
	}

	public function ajaxSave($id = 0, $task = 'save') {
		$this->save($id, $task, true);
		exit;
	}

	/**
	 * Method to save a page
	 *
	 * @return	void
	 */
	public function save($id = 0, $task = 'save', $ajax = false) {
		// Check for request forgeries.
		if ($ajax === true) {
			CKFof::checkAjaxToken();
		} else {
			CKFof::checkToken();
		}

		$model = $this->getModel();

		// Initialise variables.
		$appendToUrl = $this->input->get('tmpl') ? '&tmpl=' . $this->input->get('tmpl') : '';
		$layout = $this->input->get('layout') == 'modal' ? '&layout=modal' : '&layout=edit';

		// Get the user data.
		$data = array();
		$data['id'] = $this->input->get('id', $id, 'int');
		$id = $data['id'];
		$data['title'] = $this->input->get('title', '', 'string');
		$data['alias'] = '';
		$data['ordering'] = 0;
		$data['state'] = 1;
		$data['created'] = null;
//		$data['created'] = '0000-00-00 00:00:00';
		$data['catid'] = '';
		$data['created_by'] = 0;
		$data['params'] = $this->input->get('params', '', 'string');
		$data['access'] = 1;
		$data['options'] = $this->input->get('options', array(), 'array');
		$data['htmlcode'] = $this->input->get('htmlcode', '', 'raw');
		$data['htmlcode'] = str_replace(JUri::root(true), "|URIROOT|", $data['htmlcode']);
		$data['categories'] = $this->input->get('categories', array(), 'array');

		// Check for errors.
		if ($data === false) {
			CKFof::enqueueMessage('ERROR : NO DATA SAVED', 'warning');
			// Redirect back to the edit screen.
			CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=page'.$layout.'&id=' . $id . $appendToUrl);
			return false;
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false) {
			// Redirect back to the edit screen.
			CKFof::enqueueMessage(JText::_('CK_ITEM_SAVED_FAILED'), 'warning');
			CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=page' . $layout . '&id=' . $id . $appendToUrl);
			return false;
		}

		// Redirect to the list screen.
		CKFof::enqueueMessage(JText::_('CK_ITEM_SAVED_SUCCESS'));

		$model->checkin($return);

		switch ($task)
		{
			case 'apply':
				// Redirect back to the edit screen.
				CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=page' . $layout . '&id=' . $return . $appendToUrl);
				break;
			default:
				// Redirect to the list screen.
				CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=pages', false);
				break;
		}
	}

	/**
	 * copy an existing page
	 * @return void
	 */
	function copy() {
		// security check
		CKFof::checkToken();

		$model = $this->getModel();
		$input = JFactory::getApplication()->input;
		$cid = $this->input->get('cid', '', 'array');
		$id = (int) $cid[0];
		if (!$model->copy($id)) {
			$msg = JText::_('CK_COPY_ERROR');
			$type = 'error';
		} else {
			$msg = JText::_('CK_COPY_SUCCESS');
			$type = 'message';
		}

		CKFof::redirect('index.php?option=com_pagebuilderck', $msg, $type);
	}

	function cancel() {
		$this->getModel()->checkin($this->input->get('id', 0, 'int'));

		//Redirect back to list
		CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=pages');
	}
}