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
class PagebuilderckControllerPages extends CKController {

	function __construct() {
		parent::__construct();
	}

	public function import() {
		$app = JFactory::getApplication();
		if ($importClass = PagebuilderckHelper::getParams('import')) {
			$importClass->importFile();
		} else {
			$msg = JText::_('CK_PAGEBUILDERCK_PARAMS_NOT_FOUND');
			$app->redirect("index.php?option=com_pagebuilderck&view=pages", $msg, 'error');
			return false;
		}
	}

	public function export() {
		$app = JFactory::getApplication();
		if ($exportClass = PagebuilderckHelper::getParams('export')) {
			$exportClass->exportFile();
		} else {
			$msg = JText::_('CK_PAGEBUILDERCK_PARAMS_NOT_FOUND');
			$app->redirect("index.php?option=com_pagebuilderck&view=pages", $msg, 'error');
			return false;
		}
	}

	function checkin() {
		$this->getModel()->checkin($this->input->get('id', 0, 'int'));

		//Redirect back to list
		CKFof::redirect(PAGEBUILDERCK_ADMIN_URL . '&view=pages');
	}
}