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

/**
 * Page controller class.
 */
class PagebuilderckControllerContenttype extends CKController {

	function __construct() {
		parent::__construct();
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function save($key = null, $urlVar = null) {
		$app = JFactory::getApplication();
		if ($app->input->get('method','', 'cmd') == 'ajax') {
			// Check for request forgeries.
			JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
		} else {
			// Check for request forgeries.
			JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		}
		
		$task = $this->getTask();

		// Initialise variables.
		
		$model = $this->getModel('Contenttype', 'PagebuilderckModel');

		$appendToUrl = $app->input->get('tmpl') ? '&tmpl=' . $app->input->get('tmpl') : '';
		$layout = $app->input->get('layout') == 'modal' ? '&layout=modal' : '&layout=edit';

		// Get the user data.
//		$data = $app->input->getArray($_POST);
		$data = array();
		$data['type'] = $app->input->get('type', '', 'string');
		$data['htmlcode'] = $app->input->get('htmlcode', '', 'raw');
		$data['stylecode'] = $app->input->get('stylecode', '', 'raw');

		$type = $data['type'];
		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
//			$app->setUserState('com_pagebuilderck.edit.contenttype.data', $data);

			// Redirect back to the edit screen.
//			$id = (int) $app->getUserState('com_pagebuilderck.edit.contenttype.id');
			$app->enqueueMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect('index.php?option=com_pagebuilderck&view=contenttype'.$layout.'&type=' . $type . $appendToUrl, false);
			return false;
		}


		// Check in the profile.
//		if ($return) {
//			$model->checkin($return);
//		}

		// Clear the profile id from the session.
//		$app->setUserState('com_pagebuilderck.edit.contenttype.id', null);

		// Redirect to the list screen.
		$app->enqueueMessage(JText::_('Item saved successfully'));
		
		switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				// $this->holdEditId($context, $recordId);
				// $app->setUserState($context . '.data', null);
//				$model->checkout($return);

				// Redirect back to the edit screen.
				$this->setRedirect('index.php?option=com_pagebuilderck&view=contenttype'.$layout.'&type=' . $type . $appendToUrl, false);
				break;
			default:
				// Clear the record id and data from the session.
				// $this->releaseEditId($context, $recordId);
				// $app->setUserState($context . '.data', null);

				// Redirect to the list screen.
				$this->setRedirect('index.php?option=com_pagebuilderck&view=contenttypes', false);
				break;
		}
		

		// Flush the data from the session.
//		$app->setUserState('com_pagebuilderck.edit.contenttype.data', null);
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function cancel($key = NULL) {
		$this->setRedirect('index.php?option=com_pagebuilderck&view=contenttypes', false);
	}

	public function ajaxLoadFields() {
		// security check
		if (! PagebuilderckHelper::getAjaxToken()) {
			exit();
		}

		$app = JFactory::getApplication();
		$input = $app->input;
		$type = $input->get('type', '', 'string');

		$model = $this->getModel('Contenttype', '', array());
		$item = $model->getData($type);
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/simple_html_dom.php';
		$html = \Pagebuilderck\str_get_html($item->htmlcode);

		$identifiers = array();
		// find all types in the page
		foreach($html->find('div.ckcontenttype') as $e) {
			$identifier = $e->attr['data-type'];
			$identifiers[] = str_replace($type . '.', '', $identifier);
		}

		echo '{"status" : "1", "identifiers" : "' . implode('|', $identifiers) . '"}';
		exit;
	}

	public function ajaxAddField() {
		// security check
		if (! PagebuilderckHelper::getAjaxToken()) {
			exit();
		}

		$app = JFactory::getApplication();
		$input = $app->input;
		$type = $input->get('type', '', 'string');
		$identifier = $input->get('identifier', '', 'string');
		$blocid = $input->get('blocid', '', 'string');

		require_once(JPATH_SITE . '/plugins/pagebuilderck/' . $type . '/' . $type . 'helper.php');

		$className = 'plgPagebuilderck' . ucfirst($type) . 'Helper';
		$html = $className::getField($identifier);
		echo str_replace('|ID|', $blocid, $html);
		exit;
	}

	public function ajaxGetFieldPosition() {
		// security check
		if (! PagebuilderckHelper::getAjaxToken()) {
			exit();
		}

		$app = JFactory::getApplication();
		$input = $app->input;
		$type = $input->get('type', '', 'string');
		$identifier = $input->get('identifier', '', 'string');

		require_once(JPATH_SITE . '/plugins/pagebuilderck/' . $type . '/' . $type . 'helper.php');

		$className = 'plgPagebuilderck' . ucfirst($type) . 'Helper';
		$fieldsList = $className::getFieldsList();

		$arrayPosition = array_search($identifier, $fieldsList);
		if ($arrayPosition === false) {
			$position = -1;
		} else if ($arrayPosition === 0) {
			$position = 'first';
		} else if ($arrayPosition === (count($fieldsList) -1)) {
			$position = 'last';
		} else {
			$position = $fieldsList[$arrayPosition - 1];
		}

		echo '{"status" : "1", "position" : "' . $position . '"}';
		exit;
	}
}