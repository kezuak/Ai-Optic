<?php

/**
 * @name		Template Creator CK
 * @copyright	Copyright (C) since 2011. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
// No direct access
defined('_JEXEC') or die;

use Pagebuilderck\CKController;
use Pagebuilderck\CKFof;

class PagebuilderckControllerInterface extends CKController {

	protected $imagespath;

	function __construct() {
		parent::__construct();
	}

	/**
	 * Load the needed interface
	 * 
	 * @return void
	 */
	public function load() {
		// security check
		CKFof::checkAjaxToken();

		$layout = $this->input->get('layout', '', 'cmd');
		if (! $layout) return;

//		$this->interface = new CKInterface();
		$this->imagespath = PAGEBUILDERCK_MEDIA_URI . '/images/menustyles/';

		require_once(PAGEBUILDERCK_PATH . '/interfaces/' . $layout . '.php');
		exit;
	}
}