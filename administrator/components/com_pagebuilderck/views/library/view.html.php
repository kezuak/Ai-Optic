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

class PagebuilderckViewLibrary extends CKView {

	function display($tpl = null) {
		$user = CKFof::getUser();
		$authorised = ($user->authorise('core.create', 'com_pagebuilderck') || (count($user->getAuthorisedCategories('com_pagebuilderck', 'core.create'))));

		if ($authorised !== true)
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		$url = 'https://media.joomlack.fr/api/pagebuilderck/pages';
		// for local test purpose only
//		if ($input->getInt('sandbox' == '1')) {
//			$url = 'https://localhost/media.joomlack.fr';
//		}
		// store the library in the session to avoid to much request to the external server
//		$session = JFactory::getSession();
		// if (! $this->itemsCats = $session->get('pagebuilderck_library_categories')) {
		// try {
				// $itemsCats = file_get_contents($url);
		// } catch (Exception $e) {
				// echo 'ERROR : Unable To connect to the library server. Check your internet connection and retry later. ',  $e->getMessage(), "\n";
			// exit();
		// }
			// $this->itemsCats = json_decode($itemsCats);
			// $session->set('pagebuilderck_library_categories', $this->itemsCats);
		// }

		parent::display($tpl);

		exit();
	}
}
