<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Pagebuilderck\CKView;
use Pagebuilderck\CKfof;

class PagebuilderckViewPixabay extends CKView {

	protected $name = 'pixabay';

	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		$user = CKFof::getUser();
		$authorised = ($user->authorise('core.create', 'com_pagebuilderck') || (count($user->getAuthorisedCategories('com_pagebuilderck', 'core.create'))));

		if ($authorised !== true)
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		parent::display($tpl);
	}
}
